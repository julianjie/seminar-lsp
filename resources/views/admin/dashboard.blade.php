@extends('layouts.app')

@section('title', 'Dashboard Admin')

@section('content')
<div class="container">

    <div class="d-flex justify-content-between
                align-items-center mb-4">

        <div>
            <h2 class="fw-bold mb-1">
                Dashboard Admin
            </h2>

            <p class="text-secondary mb-0">
                Selamat datang, {{ auth()->user()->name }}.
            </p>
        </div>

        <div class="d-flex align-items-center gap-2">

    <a href="{{ route(
            'admin.account-verification.index'
        ) }}"
       class="btn btn-primary">
        Verifikasi Akun
    </a>

    <span class="badge bg-primary fs-6">
        Administrator
    </span>

</div>

    </div>

    <div class="row g-3 mb-4">

        <div class="col-md-6 col-xl-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <p class="text-secondary mb-2">
                        Total Peserta
                    </p>

                    <h2 class="fw-bold mb-0">
                        {{ $totalPeserta }}
                    </h2>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-xl-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <p class="text-secondary mb-2">
                        Menunggu Verifikasi
                    </p>

                    <h2 class="fw-bold text-warning mb-0">
                        {{ $pesertaPending }}
                    </h2>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-xl-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <p class="text-secondary mb-2">
                        Akun Diterima
                    </p>

                    <h2 class="fw-bold text-success mb-0">
                        {{ $pesertaDiterima }}
                    </h2>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-xl-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <p class="text-secondary mb-2">
                        Akun Ditolak
                    </p>

                    <h2 class="fw-bold text-danger mb-0">
                        {{ $pesertaDitolak }}
                    </h2>
                </div>
            </div>
        </div>

    </div>

    <div class="card border-0 shadow-sm">

        <div class="card-header bg-white">
            <h5 class="mb-0">
                Peserta Terbaru
            </h5>
        </div>

        <div class="card-body">

            <div class="table-responsive">

                <table class="table table-hover align-middle">

                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>Nomor HP</th>
                            <th>Status</th>
                        </tr>
                    </thead>

                    <tbody>

                        @forelse($pesertaTerbaru as $peserta)

                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $peserta->name }}</td>
                                <td>{{ $peserta->email }}</td>
                                <td>{{ $peserta->no_hp ?? '-' }}</td>
                                <td>

                                    @if($peserta->status_akun === 'diterima')
                                        <span class="badge bg-success">
                                            Diterima
                                        </span>
                                    @elseif($peserta->status_akun === 'ditolak')
                                        <span class="badge bg-danger">
                                            Ditolak
                                        </span>
                                    @else
                                        <span class="badge bg-warning text-dark">
                                            Pending
                                        </span>
                                    @endif

                                </td>
                            </tr>

                        @empty

                            <tr>
                                <td colspan="5"
                                    class="text-center text-secondary">
                                    Belum ada peserta terdaftar.
                                </td>
                            </tr>

                        @endforelse

                    </tbody>
                </table>

            </div>
        </div>
    </div>

</div>
@endsection