@extends('layouts.app')

@section('title', 'Konfirmasi Pembayaran')

@section('content')
<div class="container">

    <div class="mb-4">
        <h2 class="fw-bold mb-1">
            Konfirmasi Pembayaran
        </h2>

        <p class="text-secondary mb-0">
            Lengkapi data pembayaran dan unggah bukti transfer.
        </p>
    </div>

    <div class="card border-0 shadow-sm">

        <div class="card-header bg-white py-3">
            <h5 class="mb-0">
                Form Konfirmasi Pembayaran
            </h5>
        </div>

        <div class="card-body p-4">

            <form action="{{ route(
                    'peserta.payments.store',
                    $registration
                ) }}"
                  method="POST"
                  enctype="multipart/form-data">

                @csrf

                @include('peserta.payments._form')

            </form>

        </div>
    </div>

</div>
@endsection