@extends('layouts.app')

@section('title', 'Detail Seminar')

@section('content')
<div class="container">

    <div class="mb-4">
        <a href="{{ route('peserta.seminars.index') }}"
           class="btn btn-outline-secondary">
            Kembali
        </a>
    </div>

    <div class="card border-0 shadow-sm">

        <div class="row g-0">

            <div class="col-lg-5">

                @if($seminar->gambar)

                    <img src="{{ Storage::url($seminar->gambar) }}"
                         alt="{{ $seminar->judul }}"
                         class="img-fluid rounded-start w-100"
                         style="
                            height: 100%;
                            min-height: 400px;
                            object-fit: cover;
                         ">

                @else

                    <div class="bg-light d-flex
                                justify-content-center
                                align-items-center h-100"
                         style="min-height: 400px;">

                        <span style="font-size: 100px;">
                            🎓
                        </span>

                    </div>

                @endif

            </div>

            <div class="col-lg-7">
                <div class="card-body p-4 p-lg-5">

                    <span class="badge bg-success mb-3">
                        Dipublikasikan
                    </span>

                    <h2 class="fw-bold mb-3">
                        {{ $seminar->judul }}
                    </h2>

                    <p class="lead text-secondary">
                        {{ $seminar->deskripsi }}
                    </p>

                    <hr>

                    <div class="row g-3 mb-4">

                        <div class="col-md-6">
                            <strong>Narasumber</strong>
                            <div class="text-secondary">
                                {{ $seminar->narasumber }}
                            </div>
                        </div>

                        <div class="col-md-6">
                            <strong>Lokasi</strong>
                            <div class="text-secondary">
                                {{ $seminar->lokasi }}
                            </div>
                        </div>

                        <div class="col-md-6">
                            <strong>Tanggal</strong>
                            <div class="text-secondary">
                                {{ $seminar->tanggal->format('d-m-Y') }}
                            </div>
                        </div>

                        <div class="col-md-6">
                            <strong>Waktu</strong>
                            <div class="text-secondary">
                                {{ substr(
                                    $seminar->waktu_mulai,
                                    0,
                                    5
                                ) }}

                                @if($seminar->waktu_selesai)
                                    -
                                    {{ substr(
                                        $seminar->waktu_selesai,
                                        0,
                                        5
                                    ) }}
                                @endif
                            </div>
                        </div>

                        <div class="col-md-6">
                            <strong>Kuota</strong>
                            <div class="text-secondary">
                                {{ $jumlahPendaftar }}
                                dari {{ $seminar->kuota }} terisi
                            </div>
                        </div>

                        <div class="col-md-6">
                            <strong>Biaya</strong>
                            <div class="text-secondary">

                                @if((float) $seminar->harga === 0.0)
                                    Gratis
                                @else
                                    Rp{{ number_format(
                                        (float) $seminar->harga,
                                        0,
                                        ',',
                                        '.'
                                    ) }}
                                @endif

                            </div>
                        </div>

                    </div>

                    @if($pendaftaran)

                        @if(
                            $pendaftaran->status_pendaftaran
                            === 'diterima'
                        )
                            <div class="alert alert-success">
                                Pendaftaran seminar Anda sudah diterima.
                            </div>
                        @elseif(
                            $pendaftaran->status_pendaftaran
                            === 'ditolak'
                        )
                            <div class="alert alert-danger">
                                Pendaftaran seminar Anda ditolak.

                                @if($pendaftaran->catatan_admin)
                                    <hr>
                                    {{ $pendaftaran->catatan_admin }}
                                @endif
                            </div>
                        @else
                            <div class="alert alert-warning">
                                Pendaftaran Anda sedang menunggu
                                verifikasi administrator.
                            </div>
                        @endif

                    @elseif($jumlahPendaftar >= $seminar->kuota)

                        <button class="btn btn-secondary btn-lg w-100"
                                disabled>
                            Kuota Seminar Penuh
                        </button>

                    @else

                        <form action="{{ route(
                                'peserta.registrations.store',
                                $seminar
                            ) }}"
                              method="POST"
                              class="swal-confirm-form"
                              data-icon="question"
                              data-title="Daftar seminar?"
                              data-text="Anda akan mendaftar seminar {{ $seminar->judul }}."
                              data-confirm-text="Ya, Daftar"
                              data-confirm-color="#0d6efd">

                            @csrf

                            <button type="submit"
                                    class="btn btn-primary btn-lg w-100">
                                Daftar Seminar
                            </button>
                        </form>

                    @endif

                </div>
            </div>

        </div>
    </div>

</div>
@endsection