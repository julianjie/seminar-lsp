@extends('layouts.app')

@section('title', 'Pembayaran Seminar')

@section('content')
<div class="container">

    <div class="d-flex flex-column flex-md-row
                justify-content-between align-items-md-center mb-4">

        <div>
            <h2 class="fw-bold mb-1">
                Pembayaran Seminar
            </h2>

            <p class="text-secondary mb-0">
                Unggah bukti pembayaran dan pantau status verifikasi.
            </p>
        </div>

        <a href="{{ route('peserta.registrations.index') }}"
           class="btn btn-outline-primary mt-3 mt-md-0">
            Status Pendaftaran
        </a>
    </div>

    <div class="card border-0 shadow-sm">

        <div class="card-body">

            <div class="table-responsive">

                <table class="table table-hover align-middle">

                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Seminar</th>
                            <th>Biaya</th>
                            <th>Bukti</th>
                            <th>Status Pembayaran</th>
                            <th>Catatan</th>
                            <th class="text-center">
                                Aksi
                            </th>
                        </tr>
                    </thead>

                    <tbody>

                        @forelse($registrations as $registration)

                            @php
                                $payment = $registration->payment;
                            @endphp

                            <tr>
                                <td>
                                    {{ $registrations->firstItem()
                                        + $loop->index }}
                                </td>

                                <td>
                                    <strong>
                                        {{ $registration->seminar->judul }}
                                    </strong>

                                    <div class="small text-secondary">
                                        {{ $registration->seminar
                                            ->tanggal
                                            ->format('d-m-Y') }}
                                    </div>
                                </td>

                                <td>
                                    @if(
                                        (float) $registration
                                            ->seminar
                                            ->harga === 0.0
                                    )
                                        <span class="text-success fw-bold">
                                            Gratis
                                        </span>
                                    @else
                                        Rp{{ number_format(
                                            (float) $registration
                                                ->seminar
                                                ->harga,
                                            0,
                                            ',',
                                            '.'
                                        ) }}
                                    @endif
                                </td>

                                <td>
                                    @if($payment)

                                        <a href="{{ Storage::url(
                                                $payment->bukti_pembayaran
                                            ) }}"
                                           target="_blank">

                                            <img src="{{ Storage::url(
                                                    $payment
                                                        ->bukti_pembayaran
                                                ) }}"
                                                 alt="Bukti pembayaran"
                                                 class="rounded border"
                                                 style="
                                                    width: 80px;
                                                    height: 55px;
                                                    object-fit: cover;
                                                 ">
                                        </a>

                                    @else
                                        -
                                    @endif
                                </td>

                                <td>
                                    @if(
                                        (float) $registration
                                            ->seminar
                                            ->harga === 0.0
                                    )
                                        <span class="badge bg-success">
                                            Tidak Perlu Pembayaran
                                        </span>

                                    @elseif(!$payment)
                                        <span class="badge bg-secondary">
                                            Belum Dikirim
                                        </span>

                                    @elseif(
                                        $payment->status_pembayaran
                                        === 'diterima'
                                    )
                                        <span class="badge bg-success">
                                            Diterima
                                        </span>

                                    @elseif(
                                        $payment->status_pembayaran
                                        === 'ditolak'
                                    )
                                        <span class="badge bg-danger">
                                            Ditolak
                                        </span>

                                    @else
                                        <span class="badge
                                                     bg-warning text-dark">
                                            Menunggu Verifikasi
                                        </span>
                                    @endif
                                </td>

                                <td>
                                    {{ $payment?->catatan_admin ?? '-' }}
                                </td>

                                <td class="text-center">

                                    @if(
                                        (float) $registration
                                            ->seminar
                                            ->harga === 0.0
                                    )
                                        <span class="text-secondary">
                                            Seminar gratis
                                        </span>

                                    @elseif(!$payment)

                                        <a href="{{ route(
                                                'peserta.payments.create',
                                                $registration
                                            ) }}"
                                           class="btn btn-primary btn-sm">
                                            Konfirmasi
                                        </a>

                                    @elseif(
                                        $payment->status_pembayaran
                                        !== 'diterima'
                                    )

                                        <a href="{{ route(
                                                'peserta.payments.edit',
                                                $payment
                                            ) }}"
                                           class="btn btn-warning btn-sm">
                                            Perbarui
                                        </a>

                                    @else
                                        <span class="text-success fw-bold">
                                            Selesai
                                        </span>
                                    @endif

                                </td>
                            </tr>

                        @empty

                            <tr>
                                <td colspan="7"
                                    class="text-center text-secondary py-5">

                                    Belum ada pendaftaran seminar yang
                                    diterima.

                                </td>
                            </tr>

                        @endforelse

                    </tbody>
                </table>
            </div>

            @if($registrations->hasPages())
                <div class="mt-3">
                    {{ $registrations->links() }}
                </div>
            @endif

        </div>
    </div>

</div>
@endsection