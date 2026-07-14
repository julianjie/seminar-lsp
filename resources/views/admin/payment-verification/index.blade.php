@extends('layouts.app')

@section('title', 'Verifikasi Pembayaran')

@section('content')
<div class="container">

    <div class="d-flex flex-column flex-md-row
                justify-content-between align-items-md-center mb-4">

        <div>
            <h2 class="fw-bold mb-1">
                Verifikasi Pembayaran
            </h2>

            <p class="text-secondary mb-0">
                Periksa bukti pembayaran peserta.
            </p>
        </div>

        <a href="{{ route('admin.dashboard') }}"
           class="btn btn-outline-primary mt-3 mt-md-0">
            Kembali ke Dashboard
        </a>
    </div>

    <div class="row g-3 mb-4">

        <div class="col-md-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <p class="text-secondary mb-2">
                        Menunggu Verifikasi
                    </p>

                    <h2 class="fw-bold text-warning mb-0">
                        {{ $jumlahPending }}
                    </h2>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <p class="text-secondary mb-2">
                        Pembayaran Diterima
                    </p>

                    <h2 class="fw-bold text-success mb-0">
                        {{ $jumlahDiterima }}
                    </h2>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <p class="text-secondary mb-2">
                        Pembayaran Ditolak
                    </p>

                    <h2 class="fw-bold text-danger mb-0">
                        {{ $jumlahDitolak }}
                    </h2>
                </div>
            </div>
        </div>

    </div>

    <div class="card border-0 shadow-sm">

        <div class="card-header bg-white py-3">

            <form action="{{ route(
                    'admin.payment-verification.index'
                ) }}"
                  method="GET"
                  class="row g-2">

                <div class="col-md-6">
                    <input type="text"
                           name="search"
                           value="{{ $search }}"
                           class="form-control"
                           placeholder="Cari peserta atau seminar">
                </div>

                <div class="col-md-3">
                    <select name="status"
                            class="form-select">

                        <option value="">
                            Semua Status
                        </option>

                        <option value="pending"
                            @selected($status === 'pending')>
                            Pending
                        </option>

                        <option value="diterima"
                            @selected($status === 'diterima')>
                            Diterima
                        </option>

                        <option value="ditolak"
                            @selected($status === 'ditolak')>
                            Ditolak
                        </option>

                    </select>
                </div>

                <div class="col-md-3 d-flex gap-2">

                    <button type="submit"
                            class="btn btn-primary flex-grow-1">
                        Filter
                    </button>

                    <a href="{{ route(
                            'admin.payment-verification.index'
                        ) }}"
                       class="btn btn-outline-secondary">
                        Reset
                    </a>

                </div>
            </form>
        </div>

        <div class="card-body">

            <div class="table-responsive">

                <table class="table table-hover align-middle">

                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Peserta</th>
                            <th>Seminar</th>
                            <th>Pembayaran</th>
                            <th>Bukti</th>
                            <th>Status</th>
                            <th>Catatan</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>

                    <tbody>

                        @forelse($payments as $payment)

                            <tr>
                                <td>
                                    {{ $payments->firstItem()
                                        + $loop->index }}
                                </td>

                                <td>
                                    <strong>
                                        {{ $payment->registration
                                            ->user
                                            ->name }}
                                    </strong>

                                    <div class="small text-secondary">
                                        {{ $payment->registration
                                            ->user
                                            ->email }}
                                    </div>
                                </td>

                                <td>
                                    <strong>
                                        {{ $payment->registration
                                            ->seminar
                                            ->judul }}
                                    </strong>

                                    <div class="small text-secondary">
                                        Harga:
                                        Rp{{ number_format(
                                            (float) $payment
                                                ->registration
                                                ->seminar
                                                ->harga,
                                            0,
                                            ',',
                                            '.'
                                        ) }}
                                    </div>
                                </td>

                                <td>
                                    <div>
                                        {{ $payment->nama_pengirim }}
                                    </div>

                                    <div class="small text-secondary">
                                        {{ $payment->bank_pengirim }}
                                    </div>

                                    <div class="fw-bold">
                                        Rp{{ number_format(
                                            (float) $payment
                                                ->jumlah_bayar,
                                            0,
                                            ',',
                                            '.'
                                        ) }}
                                    </div>

                                    <div class="small text-secondary">
                                        {{ $payment->tanggal_bayar
                                            ->format('d-m-Y') }}
                                    </div>
                                </td>

                                <td>
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
                                                width: 90px;
                                                height: 65px;
                                                object-fit: cover;
                                             ">
                                    </a>
                                </td>

                                <td>
                                    @if(
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
                                            Pending
                                        </span>
                                    @endif
                                </td>

                                <td>
                                    {{ $payment->catatan_admin ?? '-' }}
                                </td>

                                <td>
                                    <div class="d-flex flex-wrap
                                                justify-content-center gap-2">

                                        @if(
                                            $payment->status_pembayaran
                                            !== 'diterima'
                                        )
                                            <form action="{{ route(
                                                    'admin.payment-verification.update',
                                                    $payment
                                                ) }}"
                                                  method="POST"
                                                  class="swal-confirm-form"
                                                  data-icon="question"
                                                  data-title="Terima pembayaran?"
                                                  data-text="Pembayaran peserta akan dinyatakan valid."
                                                  data-confirm-text="Ya, Terima"
                                                  data-confirm-color="#198754">

                                                @csrf
                                                @method('PATCH')

                                                <input type="hidden"
                                                       name="status_pembayaran"
                                                       value="diterima">

                                                <button type="submit"
                                                        class="btn
                                                               btn-success
                                                               btn-sm">
                                                    Terima
                                                </button>
                                            </form>
                                        @endif

                                        @if(
                                            $payment->status_pembayaran
                                            !== 'ditolak'
                                        )
                                            <button type="button"
                                                    class="btn
                                                           btn-danger
                                                           btn-sm"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#modalTolakPembayaran{{ $payment->id }}">
                                                Tolak
                                            </button>
                                        @endif

                                        @if(
                                            $payment->status_pembayaran
                                            !== 'pending'
                                        )
                                            <form action="{{ route(
                                                    'admin.payment-verification.update',
                                                    $payment
                                                ) }}"
                                                  method="POST"
                                                  class="swal-confirm-form"
                                                  data-icon="question"
                                                  data-title="Kembalikan ke pending?"
                                                  data-text="Pembayaran akan diperiksa kembali."
                                                  data-confirm-text="Ya, Ubah"
                                                  data-confirm-color="#6c757d">

                                                @csrf
                                                @method('PATCH')

                                                <input type="hidden"
                                                       name="status_pembayaran"
                                                       value="pending">

                                                <button type="submit"
                                                        class="btn
                                                               btn-outline-secondary
                                                               btn-sm">
                                                    Pending
                                                </button>
                                            </form>
                                        @endif

                                    </div>
                                </td>
                            </tr>

                        @empty

                            <tr>
                                <td colspan="8"
                                    class="text-center text-secondary py-5">
                                    Belum ada konfirmasi pembayaran.
                                </td>
                            </tr>

                        @endforelse

                    </tbody>
                </table>
            </div>

            @if($payments->hasPages())
                <div class="mt-3">
                    {{ $payments->links() }}
                </div>
            @endif

        </div>
    </div>

</div>

@foreach($payments as $payment)

    @if($payment->status_pembayaran !== 'ditolak')

        <div class="modal fade"
             id="modalTolakPembayaran{{ $payment->id }}"
             tabindex="-1"
             aria-hidden="true">

            <div class="modal-dialog modal-dialog-centered">

                <div class="modal-content">

                    <form action="{{ route(
                            'admin.payment-verification.update',
                            $payment
                        ) }}"
                          method="POST">

                        @csrf
                        @method('PATCH')

                        <input type="hidden"
                               name="status_pembayaran"
                               value="ditolak">

                        <div class="modal-header">

                            <h5 class="modal-title">
                                Tolak Pembayaran
                            </h5>

                            <button type="button"
                                    class="btn-close"
                                    data-bs-dismiss="modal">
                            </button>

                        </div>

                        <div class="modal-body">

                            <p>
                                Tuliskan alasan penolakan pembayaran.
                            </p>

                            <textarea name="catatan_admin"
                                      rows="4"
                                      class="form-control"
                                      placeholder="Contoh: Bukti pembayaran tidak jelas."
                                      required></textarea>

                            <small class="text-secondary">
                                Catatan akan terlihat oleh peserta.
                            </small>

                        </div>

                        <div class="modal-footer">

                            <button type="button"
                                    class="btn btn-outline-secondary"
                                    data-bs-dismiss="modal">
                                Batal
                            </button>

                            <button type="submit"
                                    class="btn btn-danger">
                                Tolak Pembayaran
                            </button>

                        </div>

                    </form>
                </div>
            </div>
        </div>

    @endif

@endforeach
@endsection