<?php

namespace App\Http\Controllers\Peserta;

use App\Http\Controllers\Controller;
use App\Models\Seminar;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SeminarController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->query('search');

        $seminars = Seminar::query()
            ->where('status', 'dipublikasikan')
            ->whereDate('tanggal', '>=', today())
            ->when($search, function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    $query->where('judul', 'like', "%{$search}%")
                        ->orWhere(
                            'narasumber',
                            'like',
                            "%{$search}%"
                        )
                        ->orWhere(
                            'lokasi',
                            'like',
                            "%{$search}%"
                        );
                });
            })
            ->withCount([
                'registrations as jumlah_pendaftar' => function ($query) {
                    $query->whereIn(
                        'status_pendaftaran',
                        ['pending', 'diterima']
                    );
                },
            ])
            ->orderBy('tanggal')
            ->paginate(9)
            ->withQueryString();

        $statusPendaftaran = $request->user()
            ->seminarRegistrations()
            ->pluck('status_pendaftaran', 'seminar_id');

        return view('peserta.seminars.index', compact(
            'seminars',
            'search',
            'statusPendaftaran'
        ));
    }

    public function show(
        Request $request,
        Seminar $seminar
    ): View {
        abort_unless(
            $seminar->status === 'dipublikasikan',
            404
        );

        $jumlahPendaftar = $seminar
            ->registrations()
            ->whereIn(
                'status_pendaftaran',
                ['pending', 'diterima']
            )
            ->count();

        $pendaftaran = $request->user()
            ->seminarRegistrations()
            ->where('seminar_id', $seminar->id)
            ->first();

        return view('peserta.seminars.show', compact(
            'seminar',
            'jumlahPendaftar',
            'pendaftaran'
        ));
    }
}