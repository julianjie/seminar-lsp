<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AccountStatusController extends Controller
{
    public function form(): View
    {
        return view('auth.account-status');
    }

    public function check(Request $request): RedirectResponse
    {
        $validated = $request->validate(
            [
                'email' => [
                    'required',
                    'email',
                ],
            ],
            [
                'email.required' => 'Email wajib diisi.',
                'email.email' => 'Format email tidak valid.',
            ]
        );

        $user = User::where('email', $validated['email'])
            ->where('role', 'peserta')
            ->first();

        if (!$user) {
            return back()
                ->withInput()
                ->with(
                    'error',
                    'Data pendaftaran dengan email tersebut tidak ditemukan.'
                );
        }

        return back()
            ->withInput()
            ->with('account_status', [
                'name' => $user->name,
                'email' => $user->email,
                'status' => $user->status_akun,
            ]);
    }
}