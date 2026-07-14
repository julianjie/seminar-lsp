@extends('layouts.app')

@section('title', 'Daftar Seminar')

@section('content')
<div class="container">

    <div class="d-flex flex-column flex-md-row
                justify-content-between align-items-md-center mb-4">

        <div>
            <h2 class="fw-bold mb-1">
                Seminar Tersedia
            </h2>

            <p class="text-secondary mb-0">
                Pilih seminar yang ingin Anda ikuti.
            </p>
        </div>

        <a href="{{ route('peserta.registrations.index') }}"
           class="btn btn-outline-primary mt-3 mt-md-0">
            Status Pendaftaran
        </a>
    </div>

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">

            <form action="{{ route('peserta.seminars.index') }}"
                  method="GET"
                  class="row g-2">

                <div class="col-md-10">
                    <input type="text"
                           name="search"
                           class="form-control"
                           value="{{ $search }}"
                           placeholder="Cari judul, narasumber, atau lokasi">
                </div>

                <div class="col-md-2 d-flex gap-2">
                    <button type="submit"
                            class="btn btn-primary flex-grow-1">
                        Cari
                    </button>

                    @if($search)
                        <a href="{{ route(
                            'peserta.seminars.index'
                        ) }}"
                           class="btn btn-outline-secondary">
                            Reset
                        </a>
                    @endif
                </div>

            </form>
        </div>
    </div>

    <div class="row g-4">

        @forelse($seminars as $seminar)

            @php
                $status = $statusPendaftaran[$seminar->id] ?? null;

                $sisaKuota = max(
                    $seminar->kuota - $seminar->jumlah_pendaftar,
                    0
                );
            @endphp

            <div class="col-md-6 col-xl-4">

                <div class="card border-0 shadow-sm h-100">

                    @if($seminar->gambar)

                        <img src="{{ Storage::url($seminar->gambar) }}"
                             class="card-img-top"
                             alt="{{ $seminar->judul }}"
                             style="
                                height: 220px;
                                object-fit: cover;
                             ">

                    @else

                        <div class="bg-light d-flex
                                    justify-content-center
                                    align-items-center"
                             style="height: 220px;">

                            <span style="font-size: 70px;">
                                🎓
                            </span>

                        </div>

                    @endif

                    <div class="card-body d-flex flex-column">

                        <div class="mb-2">
                            <span class="badge bg-primary">
                                {{ $seminar->tanggal->format('d-m-Y') }}
                            </span>

                            @if((float) $seminar->harga === 0.0)
                                <span class="badge bg-success">
                                    Gratis
                                </span>
                            @else
                                <span class="badge bg-warning text-dark">
                                    Rp{{ number_format(
                                        (float) $seminar->harga,
                                        0,
                                        ',',
                                        '.'
                                    ) }}
                                </span>
                            @endif
                        </div>

                        <h5 class="fw-bold">
                            {{ $seminar->judul }}
                        </h5>

                        <p class="text-secondary mb-2">
                            Narasumber:
                            <strong>{{ $seminar->narasumber }}</strong>
                        </p>

                        <p class="text-secondary mb-2">
                            📍 {{ $seminar->lokasi }}
                        </p>

                        <p class="text-secondary mb-3">
                            Sisa kuota:
                            <strong>{{ $sisaKuota }}</strong>
                            dari {{ $seminar->kuota }}
                        </p>

                        <div class="mt-auto">

                            <a href="{{ route(
                                    'peserta.seminars.show',
                                    $seminar
                                ) }}"
                               class="btn btn-outline-primary w-100 mb-2">
                                Lihat Detail
                            </a>

                            @if($status)

                                @if($status === 'diterima')
                                    <button class="btn btn-success w-100"
                                            disabled>
                                        Pendaftaran Diterima
                                    </button>
                                @elseif($status === 'ditolak')
                                    <button class="btn btn-danger w-100"
                                            disabled>
                                        Pendaftaran Ditolak
                                    </button>
                                @else
                                    <button class="btn btn-warning w-100"
                                            disabled>
                                        Menunggu Verifikasi
                                    </button>
                                @endif

                            @elseif($sisaKuota > 0)

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
                                            class="btn btn-primary w-100">
                                        Daftar Seminar
                                    </button>
                                </form>

                            @else

                                <button class="btn btn-secondary w-100"
                                        disabled>
                                    Kuota Penuh
                                </button>

                            @endif

                        </div>
                    </div>
                </div>
            </div>

        @empty

            <div class="col-12">
                <div class="alert alert-info text-center">
                    Belum ada seminar yang tersedia.
                </div>
            </div>

        @endforelse

    </div>

    @if($seminars->hasPages())
        <div class="mt-4">
            {{ $seminars->links() }}
        </div>
    @endif

</div>
@endsection