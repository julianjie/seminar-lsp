@extends('layouts.app')

@section('title', 'Pengumuman')

@section('content')
<div class="container">

    <div class="d-flex flex-column flex-md-row
                justify-content-between
                align-items-md-center mb-4">

        <div>
            <h2 class="fw-bold mb-1">
                Pengumuman
            </h2>

            <p class="text-secondary mb-0">
                Informasi terbaru mengenai kegiatan seminar.
            </p>
        </div>

        <a href="{{ route('peserta.dashboard') }}"
           class="btn btn-outline-primary mt-3 mt-md-0">
            Kembali ke Dashboard
        </a>
    </div>

    <div class="card border-0 shadow-sm mb-4">

        <div class="card-body">

            <form action="{{ route(
                    'peserta.announcements.index'
                ) }}"
                  method="GET"
                  class="row g-2">

                <div class="col-md-10">
                    <input type="text"
                           name="search"
                           class="form-control"
                           value="{{ $search }}"
                           placeholder="Cari pengumuman">
                </div>

                <div class="col-md-2 d-flex gap-2">

                    <button type="submit"
                            class="btn btn-primary flex-grow-1">
                        Cari
                    </button>

                    @if($search)
                        <a href="{{ route(
                                'peserta.announcements.index'
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

        @forelse($announcements as $announcement)

            <div class="col-md-6 col-xl-4">

                <div class="card border-0 shadow-sm h-100">

                    @if($announcement->gambar)

                        <img src="{{ Storage::url(
                                $announcement->gambar
                            ) }}"
                             class="card-img-top"
                             alt="{{ $announcement->judul }}"
                             style="
                                height: 220px;
                                object-fit: cover;
                             ">

                    @else

                        <div class="bg-light
                                    d-flex
                                    justify-content-center
                                    align-items-center"
                             style="height: 220px;">

                            <span style="font-size: 75px;">
                                📢
                            </span>

                        </div>

                    @endif

                    <div class="card-body d-flex flex-column">

                        <div class="small text-secondary mb-2">

                            {{ $announcement
                                ->tanggal_publish
                                ->format('d-m-Y H:i') }}

                        </div>

                        <h5 class="fw-bold">
                            {{ $announcement->judul }}
                        </h5>

                        <p class="text-secondary">
                            {{ \Illuminate\Support\Str::limit(
                                strip_tags($announcement->isi),
                                130
                            ) }}
                        </p>

                        <div class="mt-auto">

                            <a href="{{ route(
                                    'peserta.announcements.show',
                                    $announcement
                                ) }}"
                               class="btn btn-primary w-100">
                                Baca Selengkapnya
                            </a>

                        </div>
                    </div>
                </div>
            </div>

        @empty

            <div class="col-12">

                <div class="alert alert-info text-center">
                    Belum ada pengumuman yang dipublikasikan.
                </div>

            </div>

        @endforelse

    </div>

    @if($announcements->hasPages())
        <div class="mt-4">
            {{ $announcements->links() }}
        </div>
    @endif

</div>
@endsection