@extends('layouts.app')

@section('title', $announcement->judul)

@section('content')
<div class="container">

    <div class="mb-4">

        <a href="{{ route(
                'peserta.announcements.index'
            ) }}"
           class="btn btn-outline-secondary">
            Kembali
        </a>

    </div>

    <div class="row justify-content-center">

        <div class="col-lg-9">

            <article class="card border-0 shadow-sm">

                @if($announcement->gambar)

                    <img src="{{ Storage::url(
                            $announcement->gambar
                        ) }}"
                         alt="{{ $announcement->judul }}"
                         class="card-img-top"
                         style="
                            max-height: 450px;
                            object-fit: cover;
                         ">

                @endif

                <div class="card-body p-4 p-lg-5">

                    <span class="badge bg-primary mb-3">
                        Pengumuman
                    </span>

                    <h1 class="fw-bold mb-3">
                        {{ $announcement->judul }}
                    </h1>

                    <p class="text-secondary">
                        Dipublikasikan pada
                        {{ $announcement
                            ->tanggal_publish
                            ->format('d-m-Y H:i') }}
                    </p>

                    <hr class="my-4">

                    <div style="
                        white-space: pre-line;
                        line-height: 1.8;
                    ">{{ $announcement->isi }}</div>

                </div>
            </article>

        </div>
    </div>

</div>
@endsection