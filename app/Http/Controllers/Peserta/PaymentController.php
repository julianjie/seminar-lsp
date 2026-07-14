<?php

namespace App\Http\Controllers\Peserta;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\SeminarRegistration;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class PaymentController extends Controller
{
    public function index(Request $request): View
    {
        $registrations = $request->user()
            ->seminarRegistrations()
            ->where('status_pendaftaran', 'diterima')
            ->with([
                'seminar',
                'payment',
            ])
            ->latest()
            ->paginate(10);

        return view(
            'peserta.payments.index',
            compact('registrations')
        );
    }

    public function create(
        Request $request,
        SeminarRegistration $registration
    ): View|RedirectResponse {
        $this->authorizeRegistration($request, $registration);

        $registration->load([
            'seminar',
            'payment',
        ]);

        if ($registration->status_pendaftaran !== 'diterima') {
            return redirect()
                ->route('peserta.payments.index')
                ->with(
                    'error',
                    'Pendaftaran seminar belum diterima oleh administrator.'
                );
        }

        if ((float) $registration->seminar->harga === 0.0) {
            return redirect()
                ->route('peserta.payments.index')
                ->with(
                    'error',
                    'Seminar gratis tidak memerlukan pembayaran.'
                );
        }

        if ($registration->payment) {
            return redirect()->route(
                'peserta.payments.edit',
                $registration->payment
            );
        }

        return view(
            'peserta.payments.create',
            compact('registration')
        );
    }

    public function store(
        Request $request,
        SeminarRegistration $registration
    ): RedirectResponse {
        $this->authorizeRegistration($request, $registration);

        $registration->load([
            'seminar',
            'payment',
        ]);

        if ($registration->status_pendaftaran !== 'diterima') {
            return back()->with(
                'error',
                'Pendaftaran seminar belum diterima.'
            );
        }

        if ((float) $registration->seminar->harga === 0.0) {
            return back()->with(
                'error',
                'Seminar gratis tidak memerlukan pembayaran.'
            );
        }

        if ($registration->payment) {
            return redirect()
                ->route(
                    'peserta.payments.edit',
                    $registration->payment
                )
                ->with(
                    'error',
                    'Konfirmasi pembayaran sudah pernah dikirim.'
                );
        }

        $validated = $this->validatePayment($request, false);

        if (
            round((float) $validated['jumlah_bayar'], 2)
            !== round((float) $registration->seminar->harga, 2)
        ) {
            return back()
                ->withInput()
                ->withErrors([
                    'jumlah_bayar' =>
                        'Jumlah pembayaran harus sesuai dengan harga seminar.',
                ]);
        }

        $validated['bukti_pembayaran'] = $request
            ->file('bukti_pembayaran')
            ->store('payments', 'public');

        Payment::create([
            'seminar_registration_id' => $registration->id,
            'nama_pengirim' => $validated['nama_pengirim'],
            'bank_pengirim' => $validated['bank_pengirim'],
            'jumlah_bayar' => $validated['jumlah_bayar'],
            'tanggal_bayar' => $validated['tanggal_bayar'],
            'bukti_pembayaran' => $validated['bukti_pembayaran'],
            'status_pembayaran' => 'pending',
        ]);

        return redirect()
            ->route('peserta.payments.index')
            ->with(
                'success',
                'Konfirmasi pembayaran berhasil dikirim dan sedang menunggu verifikasi.'
            );
    }

    public function edit(
        Request $request,
        Payment $payment
    ): View|RedirectResponse {
        $payment->load([
            'registration.seminar',
        ]);

        $this->authorizePayment($request, $payment);

        if ($payment->status_pembayaran === 'diterima') {
            return redirect()
                ->route('peserta.payments.index')
                ->with(
                    'error',
                    'Pembayaran yang sudah diterima tidak dapat diubah.'
                );
        }

        $registration = $payment->registration;

        return view(
            'peserta.payments.edit',
            compact('registration', 'payment')
        );
    }

    public function update(
        Request $request,
        Payment $payment
    ): RedirectResponse {
        $payment->load([
            'registration.seminar',
        ]);

        $this->authorizePayment($request, $payment);

        if ($payment->status_pembayaran === 'diterima') {
            return redirect()
                ->route('peserta.payments.index')
                ->with(
                    'error',
                    'Pembayaran yang sudah diterima tidak dapat diubah.'
                );
        }

        $validated = $this->validatePayment($request, true);

        if (
            round((float) $validated['jumlah_bayar'], 2)
            !== round(
                (float) $payment->registration->seminar->harga,
                2
            )
        ) {
            return back()
                ->withInput()
                ->withErrors([
                    'jumlah_bayar' =>
                        'Jumlah pembayaran harus sesuai dengan harga seminar.',
                ]);
        }

        if ($request->hasFile('bukti_pembayaran')) {
            if (
                Storage::disk('public')->exists(
                    $payment->bukti_pembayaran
                )
            ) {
                Storage::disk('public')->delete(
                    $payment->bukti_pembayaran
                );
            }

            $validated['bukti_pembayaran'] = $request
                ->file('bukti_pembayaran')
                ->store('payments', 'public');
        } else {
            unset($validated['bukti_pembayaran']);
        }

        $validated['status_pembayaran'] = 'pending';
        $validated['catatan_admin'] = null;
        $validated['tanggal_verifikasi'] = null;

        $payment->update($validated);

        return redirect()
            ->route('peserta.payments.index')
            ->with(
                'success',
                'Konfirmasi pembayaran berhasil diperbarui dan dikirim kembali.'
            );
    }

    private function validatePayment(
        Request $request,
        bool $isUpdate
    ): array {
        return $request->validate(
            [
                'nama_pengirim' => [
                    'required',
                    'string',
                    'max:255',
                ],
                'bank_pengirim' => [
                    'required',
                    'string',
                    'max:100',
                ],
                'jumlah_bayar' => [
                    'required',
                    'numeric',
                    'min:1',
                ],
                'tanggal_bayar' => [
                    'required',
                    'date',
                    'before_or_equal:today',
                ],
                'bukti_pembayaran' => [
                    $isUpdate ? 'nullable' : 'required',
                    'image',
                    'mimes:jpg,jpeg,png,webp',
                    'max:2048',
                ],
            ],
            [
                'nama_pengirim.required' =>
                    'Nama pengirim wajib diisi.',
                'bank_pengirim.required' =>
                    'Bank atau metode pembayaran wajib diisi.',
                'jumlah_bayar.required' =>
                    'Jumlah pembayaran wajib diisi.',
                'jumlah_bayar.min' =>
                    'Jumlah pembayaran tidak valid.',
                'tanggal_bayar.required' =>
                    'Tanggal pembayaran wajib diisi.',
                'tanggal_bayar.before_or_equal' =>
                    'Tanggal pembayaran tidak boleh melebihi hari ini.',
                'bukti_pembayaran.required' =>
                    'Bukti pembayaran wajib diunggah.',
                'bukti_pembayaran.image' =>
                    'Bukti pembayaran harus berupa gambar.',
                'bukti_pembayaran.mimes' =>
                    'Format bukti pembayaran harus JPG, JPEG, PNG, atau WEBP.',
                'bukti_pembayaran.max' =>
                    'Ukuran bukti pembayaran maksimal 2 MB.',
            ]
        );
    }

    private function authorizeRegistration(
        Request $request,
        SeminarRegistration $registration
    ): void {
        abort_unless(
            $registration->user_id === $request->user()->id,
            403
        );
    }

    private function authorizePayment(
        Request $request,
        Payment $payment
    ): void {
        abort_unless(
            $payment->registration->user_id
                === $request->user()->id,
            403
        );
    }
}