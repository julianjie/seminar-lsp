@extends('layouts.app')

@section('title', 'Edit Pengumuman')

@section('content')
<div class="container">

    <div class="mb-4">
        <h2 class="fw-bold mb-1">
            Edit Pengumuman
        </h2>

        <p class="text-secondary mb-0">
            Perbarui informasi pengumuman.
        </p>
    </div>

    <div class="card border-0 shadow-sm">

        <div class="card-header bg-white py-3">
            <h5 class="mb-0">
                Form Edit Pengumuman
            </h5>
        </div>

        <div class="card-body p-4">

            <form action="{{ route(
                    'admin.announcements.update',
                    $announcement
                ) }}"
                  method="POST"
                  enctype="multipart/form-data">

                @csrf
                @method('PUT')

                @include('admin.announcements._form')

            </form>

        </div>
    </div>

</div>
@endsection