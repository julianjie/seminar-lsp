<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Seminar;
use App\Models\SeminarRegistration;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class SeminarRegistrationVerificationController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->query('search');
        $status = $request->query('status');

        $registrations = SeminarRegistration::query()
            ->with([
                'user',
                'seminar',
            ])
            ->when($search, function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    $query
                        ->whereHas('user', function ($userQuery) use ($search) {
                            $userQuery
                                ->where('name', 'like', "%{$search}%")
                                ->orWhere('email', 'like', "%{$search}%");
                        })
                        ->orWhereHas(
                            'seminar',
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
                    $query->where('status_pendaftaran', $status);
                }
            )
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $jumlahPending = SeminarRegistration::where(
            'status_pendaftaran',
            'pending'
        )->count();

        $jumlahDiterima = SeminarRegistration::where(
            'status_pendaftaran',
            'diterima'
        )->count();

        $jumlahDitolak = SeminarRegistration::where(
            'status_pendaftaran',
            'ditolak'
        )->count();

        return view(
            'admin.registration-verification.index',
            compact(
                'registrations',
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
        SeminarRegistration $registration
    ): RedirectResponse {
        $validated = $request->validate(
            [
                'status_pendaftaran' => [
                    'required',
                    'in:pending,diterima,ditolak',
                ],
                'catatan_admin' => [
                    'nullable',
                    'required_if:status_pendaftaran,ditolak',
                    'string',
                    'max:1000',
                ],
            ],
            [
                'status_pendaftaran.required' =>
                    'Status pendaftaran wajib dipilih.',
                'status_pendaftaran.in' =>
                    'Status pendaftaran tidak valid.',
                'catatan_admin.required_if' =>
                    'Alasan penolakan wajib diisi.',
                'catatan_admin.max' =>
                    'Catatan admin maksimal 1000 karakter.',
            ]
        );

        DB::transaction(function () use ($validated, $registration) {
            $registrationTerkunci = SeminarRegistration::query()
                ->lockForUpdate()
                ->findOrFail($registration->id);

            $seminar = Seminar::query()
                ->lockForUpdate()
                ->findOrFail($registrationTerkunci->seminar_id);

            /*
             * Mencegah jumlah pendaftaran aktif melebihi kuota.
             * Pemeriksaan ini penting jika pendaftaran yang sebelumnya
             * ditolak diubah menjadi diterima.
             */
            if ($validated['status_pendaftaran'] === 'diterima') {
                $jumlahAktifLainnya = SeminarRegistration::query()
                    ->where('seminar_id', $seminar->id)
                    ->where('id', '!=', $registrationTerkunci->id)
                    ->whereIn(
                        'status_pendaftaran',
                        ['pending', 'diterima']
                    )
                    ->count();

                if ($jumlahAktifLainnya >= $seminar->kuota) {
                    throw ValidationException::withMessages([
                        'status_pendaftaran' =>
                            'Pendaftaran tidak dapat diterima karena kuota seminar sudah penuh.',
                    ]);
                }
            }

            $statusBaru = $validated['status_pendaftaran'];

            $registrationTerkunci->update([
                'status_pendaftaran' => $statusBaru,

                'catatan_admin' => $statusBaru === 'pending'
                    ? null
                    : ($validated['catatan_admin'] ?? null),

                'tanggal_verifikasi' => $statusBaru === 'pending'
                    ? null
                    : now(),
            ]);
        });

        $registration->load([
            'user',
            'seminar',
        ]);

        $pesan = match ($validated['status_pendaftaran']) {
            'diterima' =>
                "Pendaftaran {$registration->user->name} pada seminar {$registration->seminar->judul} berhasil diterima.",

            'ditolak' =>
                "Pendaftaran {$registration->user->name} berhasil ditolak.",

            default =>
                "Pendaftaran {$registration->user->name} dikembalikan ke status pending.",
        };

        return back()->with('success', $pesan);
    }
}