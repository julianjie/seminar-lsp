<?php

namespace App\Http\Controllers\Peserta;

use App\Http\Controllers\Controller;
use App\Models\Seminar;
use App\Models\SeminarRegistration;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class SeminarRegistrationController extends Controller
{
    public function index(Request $request): View
    {
        $registrations = $request->user()
            ->seminarRegistrations()
            ->with('seminar')
            ->latest()
            ->paginate(10);

        return view(
            'peserta.registrations.index',
            compact('registrations')
        );
    }

    public function store(
        Request $request,
        Seminar $seminar
    ): RedirectResponse {
        if ($seminar->status !== 'dipublikasikan') {
            return back()->with(
                'error',
                'Seminar tersebut belum dipublikasikan.'
            );
        }

        if ($seminar->tanggal->lt(today())) {
            return back()->with(
                'error',
                'Seminar tersebut sudah berakhir.'
            );
        }

        $sudahTerdaftar = SeminarRegistration::where(
            'user_id',
            $request->user()->id
        )
            ->where('seminar_id', $seminar->id)
            ->exists();

        if ($sudahTerdaftar) {
            return back()->with(
                'error',
                'Anda sudah terdaftar pada seminar ini.'
            );
        }

        DB::transaction(function () use ($request, $seminar) {
            $seminarTerkunci = Seminar::query()
                ->lockForUpdate()
                ->findOrFail($seminar->id);

            $jumlahPendaftar = SeminarRegistration::where(
                'seminar_id',
                $seminarTerkunci->id
            )
                ->whereIn(
                    'status_pendaftaran',
                    ['pending', 'diterima']
                )
                ->count();

            if ($jumlahPendaftar >= $seminarTerkunci->kuota) {
                throw ValidationException::withMessages([
                    'seminar' => 'Kuota seminar sudah penuh.',
                ]);
            }

            SeminarRegistration::create([
                'user_id' => $request->user()->id,
                'seminar_id' => $seminarTerkunci->id,
                'status_pendaftaran' => 'pending',
            ]);
        });

        return redirect()
            ->route('peserta.registrations.index')
            ->with(
                'success',
                'Pendaftaran seminar berhasil. Silakan menunggu verifikasi administrator.'
            );
    }

    public function destroy(
        Request $request,
        SeminarRegistration $registration
    ): RedirectResponse {
        if ($registration->user_id !== $request->user()->id) {
            abort(403);
        }

        if ($registration->status_pendaftaran !== 'pending') {
            return back()->with(
                'error',
                'Pendaftaran yang sudah diverifikasi tidak dapat dibatalkan.'
            );
        }

        $judulSeminar = $registration->seminar->judul;

        $registration->delete();

        return back()->with(
            'success',
            "Pendaftaran seminar {$judulSeminar} berhasil dibatalkan."
        );
    }
}