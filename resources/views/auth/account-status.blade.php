@extends('layouts.app')

@section('title', 'Cek Status Akun')

@section('content')
<div class="container">

    <div class="row justify-content-center">

        <div class="col-md-7 col-lg-6">

            <div class="card border-0 shadow-sm">

                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        Cek Status Pendaftaran Akun
                    </h5>
                </div>

                <div class="card-body p-4">

                    <p class="text-secondary">
                        Masukkan email yang digunakan ketika registrasi.
                    </p>

                    <form action="{{ route('account.status.check') }}"
                          method="POST">

                        @csrf

                        <div class="mb-3">
                            <label for="email"
                                   class="form-label">
                                Alamat Email
                            </label>

                            <input type="email"
                                   id="email"
                                   name="email"
                                   class="form-control"
                                   value="{{ old('email') }}"
                                   placeholder="nama@email.com"
                                   required>
                        </div>

                        <button type="submit"
                                class="btn btn-primary w-100">
                            Periksa Status
                        </button>

                    </form>

                    @if(session('account_status'))

                        @php
                            $account = session('account_status');

                            $badge = match($account['status']) {
                                'diterima' => 'success',
                                'ditolak' => 'danger',
                                default => 'warning',
                            };

                            $label = match($account['status']) {
                                'diterima' => 'Diterima',
                                'ditolak' => 'Ditolak',
                                default => 'Menunggu Verifikasi',
                            };
                        @endphp

                        <hr>

                        <div class="card bg-light border-0">
                            <div class="card-body">

                                <p class="mb-2">
                                    <strong>Nama:</strong>
                                    {{ $account['name'] }}
                                </p>

                                <p class="mb-2">
                                    <strong>Email:</strong>
                                    {{ $account['email'] }}
                                </p>

                                <p class="mb-0">
                                    <strong>Status:</strong>

                                    <span class="badge bg-{{ $badge }}">
                                        {{ $label }}
                                    </span>
                                </p>

                            </div>
                        </div>

                    @endif

                </div>
            </div>

        </div>
    </div>
</div>
@endsection