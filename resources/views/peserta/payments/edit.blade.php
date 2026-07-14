@extends('layouts.app')

@section('title', 'Perbarui Pembayaran')

@section('content')
<div class="container">

    <div class="mb-4">
        <h2 class="fw-bold mb-1">
            Perbarui Konfirmasi Pembayaran
        </h2>

        <p class="text-secondary mb-0">
            Perbaiki data pembayaran sesuai catatan administrator.
        </p>
    </div>

    @if($payment->catatan_admin)
        <div class="alert alert-danger">
            <strong>Catatan administrator:</strong>
            <div class="mt-1">
                {{ $payment->catatan_admin }}
            </div>
        </div>
    @endif

    <div class="card border-0 shadow-sm">

        <div class="card-body p-4">

            <form action="{{ route(
                    'peserta.payments.update',
                    $payment
                ) }}"
                  method="POST"
                  enctype="multipart/form-data">

                @csrf
                @method('PUT')

                @include('peserta.payments._form')

            </form>

        </div>
    </div>

</div>
@endsection