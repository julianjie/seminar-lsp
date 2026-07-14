<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PaymentVerificationController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->query('search');
        $status = $request->query('status');

        $payments = Payment::query()
            ->with([
                'registration.user',
                'registration.seminar',
            ])
            ->when($search, function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    $query
                        ->where(
                            'nama_pengirim',
                            'like',
                            "%{$search}%"
                        )
                        ->orWhereHas(
                            'registration.user',
                            function ($userQuery) use ($search) {
                                $userQuery
                                    ->where(
                                        'name',
                                        'like',
                                        "%{$search}%"
                                    )
                                    ->orWhere(
                                        'email',
                                        'like',
                                        "%{$search}%"
                                    );
                            }
                        )
                        ->orWhereHas(
                            'registration.seminar',
                            function ($seminarQuery) use ($search) {
                                $seminarQuery->where(
                                    'judul',
                                    'like',
                                    "%{$search}%"
                                );
                            }
                        );
                });
            })
            ->when(
                in_array(
                    $status,
                    ['pending', 'diterima', 'ditolak'],
                    true
                ),
                function ($query) use ($status) {
                    $query->where(
                        'status_pembayaran',
                        $status
                    );
                }
            )
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $jumlahPending = Payment::where(
            'status_pembayaran',
            'pending'
        )->count();

        $jumlahDiterima = Payment::where(
            'status_pembayaran',
            'diterima'
        )->count();

        $jumlahDitolak = Payment::where(
            'status_pembayaran',
            'ditolak'
        )->count();

        return view(
            'admin.payment-verification.index',
            compact(
                'payments',
                'search',
                'status',
                'jumlahPending',
                'jumlahDiterima',
                'jumlahDitolak'
            )
        );
    }

    public function update(
        Request $request,
        Payment $payment
    ): RedirectResponse {
        $validated = $request->validate(
            [
                'status_pembayaran' => [
                    'required',
                    'in:pending,diterima,ditolak',
                ],
                'catatan_admin' => [
                    'nullable',
                    'required_if:status_pembayaran,ditolak',
                    'string',
                    'max:1000',
                ],
            ],
            [
                'status_pembayaran.required' =>
                    'Status pembayaran wajib dipilih.',
                'status_pembayaran.in' =>
                    'Status pembayaran tidak valid.',
                'catatan_admin.required_if' =>
                    'Alasan penolakan wajib diisi.',
                'catatan_admin.max' =>
                    'Catatan maksimal 1000 karakter.',
            ]
        );

        $statusBaru = $validated['status_pembayaran'];

        $payment->update([
            'status_pembayaran' => $statusBaru,

            'catatan_admin' => $statusBaru === 'pending'
                ? null
                : ($validated['catatan_admin'] ?? null),

            'tanggal_verifikasi' => $statusBaru === 'pending'
                ? null
                : now(),
        ]);

        $payment->load([
            'registration.user',
            'registration.seminar',
        ]);

        $pesan = match ($statusBaru) {
            'diterima' =>
                "Pembayaran {$payment->registration->user->name} berhasil diterima.",

            'ditolak' =>
                "Pembayaran {$payment->registration->user->name} berhasil ditolak.",

            default =>
                "Pembayaran dikembalikan ke status pending.",
        };

        return back()->with('success', $pesan);
    }
}