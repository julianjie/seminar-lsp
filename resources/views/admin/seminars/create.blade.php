@extends('layouts.app')

@section('title', 'Tambah Seminar')

@section('content')
<div class="container">

    <div class="mb-4">
        <h2 class="fw-bold mb-1">
            Tambah Seminar
        </h2>

        <p class="text-secondary mb-0">
            Masukkan informasi seminar yang akan diselenggarakan.
        </p>
    </div>

    <div class="card border-0 shadow-sm">

        <div class="card-header bg-white py-3">
            <h5 class="mb-0">
                Form Data Seminar
            </h5>
        </div>

        <div class="card-body p-4">

            <form action="{{ route('admin.seminars.store') }}"
                  method="POST"
                  enctype="multipart/form-data">

                @csrf

                @include('admin.seminars._form')

            </form>

        </div>
    </div>

</div>
@endsection