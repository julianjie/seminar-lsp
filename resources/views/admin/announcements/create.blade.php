@extends('layouts.app')

@section('title', 'Tambah Pengumuman')

@section('content')
<div class="container">

    <div class="mb-4">
        <h2 class="fw-bold mb-1">
            Tambah Pengumuman
        </h2>

        <p class="text-secondary mb-0">
            Buat informasi atau pemberitahuan untuk peserta.
        </p>
    </div>

    <div class="card border-0 shadow-sm">

        <div class="card-header bg-white py-3">
            <h5 class="mb-0">
                Form Pengumuman
            </h5>
        </div>

        <div class="card-body p-4">

            <form action="{{ route(
                    'admin.announcements.store'
                ) }}"
                  method="POST"
                  enctype="multipart/form-data">

                @csrf

                @include('admin.announcements._form')

            </form>

        </div>
    </div>

</div>
@endsection