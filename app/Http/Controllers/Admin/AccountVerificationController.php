<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AccountVerificationController extends Controller
{
    public function index(Request $request): View
    {
        $status = $request->query('status');

        $peserta = User::query()
            ->where('role', 'peserta')
            ->when(
                in_array($status, ['pending', 'diterima', 'ditolak'], true),
                function ($query) use ($status) {
                    $query->where('status_akun', $status);
                }
            )
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $jumlahPending = User::where('role', 'peserta')
            ->where('status_akun', 'pending')
            ->count();

        $jumlahDiterima = User::where('role', 'peserta')
            ->where('status_akun', 'diterima')
            ->count();

        $jumlahDitolak = User::where('role', 'peserta')
            ->where('status_akun', 'ditolak')
            ->count();

        return view('admin.account-verification.index', compact(
            'peserta',
            'status',
            'jumlahPending',
            'jumlahDiterima',
            'jumlahDitolak'
        ));
    }

    public function update(
        Request $request,
        User $user
    ): RedirectResponse {
        if ($user->role !== 'peserta') {
            abort(404);
        }

        $validated = $request->validate(
            [
                'status_akun' => [
                    'required',
                    'in:pending,diterima,ditolak',
                ],
            ],
            [
                'status_akun.required' => 'Status akun wajib dipilih.',
                'status_akun.in' => 'Status akun tidak valid.',
            ]
        );

        $user->update([
            'status_akun' => $validated['status_akun'],
        ]);

        $pesan = match ($validated['status_akun']) {
            'diterima' => "Akun {$user->name} berhasil diterima.",
            'ditolak' => "Akun {$user->name} berhasil ditolak.",
            default => "Akun {$user->name} dikembalikan ke status pending.",
        };

        return back()->with('success', $pesan);
    }
}