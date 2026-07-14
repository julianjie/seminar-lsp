<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $totalPeserta = User::where('role', 'peserta')->count();

        $pesertaPending = User::where('role', 'peserta')
            ->where('status_akun', 'pending')
            ->count();

        $pesertaDiterima = User::where('role', 'peserta')
            ->where('status_akun', 'diterima')
            ->count();

        $pesertaDitolak = User::where('role', 'peserta')
            ->where('status_akun', 'ditolak')
            ->count();

        $pesertaTerbaru = User::where('role', 'peserta')
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalPeserta',
            'pesertaPending',
            'pesertaDiterima',
            'pesertaDitolak',
            'pesertaTerbaru'
        ));
    }
}