@extends('layouts.app')

@section('title', 'Dashboard Peserta')

@section('content')
<div class="container">

    <div class="row justify-content-center">

        <div class="col-lg-9">

            <div class="card border-0 shadow-sm mb-4">

                <div class="card-body p-4">

                    <div class="d-flex justify-content-between
                                align-items-start">

                        <div>
                            <h2 class="fw-bold">
                                Selamat Datang,
                                {{ $user->name }}
                            </h2>

                            <p class="text-secondary mb-0">
                                Kelola pendaftaran seminar Anda melalui
                                dashboard ini.
                            </p>
                        </div>

                        <span class="badge bg-success fs-6">
                            Akun Diterima
                        </span>

                    </div>

                </div>
            </div>

            <div class="row g-3">

                <div class="col-md-4">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body text-center p-4">
                            <div class="fs-1 mb-3">📚</div>

                            <h5 class="fw-bold">
                                Pilih Seminar
                            </h5>

                            <p class="text-secondary">
                                Lihat seminar yang tersedia dan lakukan
                                pendaftaran.
                            </p>

                            <button class="btn btn-primary"
                                    disabled>
                                Segera Tersedia
                            </button>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body text-center p-4">
                            <div class="fs-1 mb-3">📝</div>

                            <h5 class="fw-bold">
                                Status Pendaftaran
                            </h5>

                            <p class="text-secondary">
                                Pantau proses verifikasi pendaftaran seminar.
                            </p>

                            <button class="btn btn-outline-primary"
                                    disabled>
                                Segera Tersedia
                            </button>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body text-center p-4">
                            <div class="fs-1 mb-3">💳</div>

                            <h5 class="fw-bold">
                                Pembayaran
                            </h5>

                            <p class="text-secondary">
                                Unggah bukti pembayaran dan lihat statusnya.
                            </p>

                            <button class="btn btn-outline-primary"
                                    disabled>
                                Segera Tersedia
                            </button>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </div>
</div>
@endsection