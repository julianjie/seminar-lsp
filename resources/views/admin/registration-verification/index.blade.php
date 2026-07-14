@extends('layouts.app')

@section('title', 'Verifikasi Pendaftaran Seminar')

@section('content')
<div class="container">

    {{-- Header --}}
    <div class="d-flex flex-column flex-md-row
                justify-content-between align-items-md-center mb-4">

        <div>
            <h2 class="fw-bold mb-1">
                Verifikasi Pendaftaran Seminar
            </h2>

            <p class="text-secondary mb-0">
                Terima atau tolak pendaftaran seminar peserta.
            </p>
        </div>

        <a href="{{ route('admin.dashboard') }}"
           class="btn btn-outline-primary mt-3 mt-md-0">
            Kembali ke Dashboard
        </a>
    </div>

    {{-- Ringkasan status --}}
    <div class="row g-3 mb-4">

        <div class="col-md-4">
            <a href="{{ route(
                'admin.registration-verification.index',
                ['status' => 'pending']
            ) }}"
               class="text-decoration-none">

                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <p class="text-secondary mb-2">
                            Menunggu Verifikasi
                        </p>

                        <h2 class="fw-bold text-warning mb-0">
                            {{ $jumlahPending }}
                        </h2>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-md-4">
            <a href="{{ route(
                'admin.registration-verification.index',
                ['status' => 'diterima']
            ) }}"
               class="text-decoration-none">

                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <p class="text-secondary mb-2">
                            Pendaftaran Diterima
                        </p>

                        <h2 class="fw-bold text-success mb-0">
                            {{ $jumlahDiterima }}
                        </h2>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-md-4">
            <a href="{{ route(
                'admin.registration-verification.index',
                ['status' => 'ditolak']
            ) }}"
               class="text-decoration-none">

                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <p class="text-secondary mb-2">
                            Pendaftaran Ditolak
                        </p>

                        <h2 class="fw-bold text-danger mb-0">
                            {{ $jumlahDitolak }}
                        </h2>
                    </div>
                </div>
            </a>
        </div>

    </div>

    {{-- Tabel pendaftaran --}}
    <div class="card border-0 shadow-sm">

        <div class="card-header bg-white py-3">

            <form action="{{ route(
                'admin.registration-verification.index'
            ) }}"
                  method="GET"
                  class="row g-2">

                <div class="col-md-6">
                    <input type="text"
                           name="search"
                           class="form-control"
                           value="{{ $search }}"
                           placeholder="Cari peserta, email, atau seminar">
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
                        'admin.registration-verification.index'
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
                            <th>Pelaksanaan</th>
                            <th>Biaya</th>
                            <th>Status</th>
                            <th>Catatan</th>
                            <th class="text-center">
                                Aksi
                            </th>
                        </tr>
                    </thead>

                    <tbody>

                        @forelse($registrations as $item)

                            <tr>
                                <td>
                                    {{ $registrations->firstItem()
                                        + $loop->index }}
                                </td>

                                <td>
                                    <strong>
                                        {{ $item->user->name }}
                                    </strong>

                                    <div class="small text-secondary">
                                        {{ $item->user->email }}
                                    </div>

                                    <div class="small text-secondary">
                                        {{ $item->user->no_hp ?? '-' }}
                                    </div>
                                </td>

                                <td>
                                    <strong>
                                        {{ $item->seminar->judul }}
                                    </strong>

                                    <div class="small text-secondary">
                                        {{ $item->seminar->narasumber }}
                                    </div>

                                    <div class="small text-secondary">
                                        Daftar:
                                        {{ $item->created_at->format(
                                            'd-m-Y H:i'
                                        ) }}
                                    </div>
                                </td>

                                <td>
                                    <div>
                                        {{ $item->seminar
                                            ->tanggal
                                            ->format('d-m-Y') }}
                                    </div>

                                    <div class="small text-secondary">
                                        {{ substr(
                                            $item->seminar->waktu_mulai,
                                            0,
                                            5
                                        ) }}

                                        @if($item->seminar->waktu_selesai)
                                            -
                                            {{ substr(
                                                $item->seminar
                                                    ->waktu_selesai,
                                                0,
                                                5
                                            ) }}
                                        @endif
                                    </div>

                                    <div class="small text-secondary">
                                        {{ $item->seminar->lokasi }}
                                    </div>
                                </td>

                                <td>
                                    @if(
                                        (float) $item->seminar->harga === 0.0
                                    )
                                        <span class="text-success fw-bold">
                                            Gratis
                                        </span>
                                    @else
                                        Rp{{ number_format(
                                            (float) $item->seminar->harga,
                                            0,
                                            ',',
                                            '.'
                                        ) }}
                                    @endif
                                </td>

                                <td>
                                    @if(
                                        $item->status_pendaftaran
                                        === 'diterima'
                                    )
                                        <span class="badge bg-success">
                                            Diterima
                                        </span>

                                    @elseif(
                                        $item->status_pendaftaran
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
                                    @if($item->catatan_admin)

                                        <span title="{{ $item->catatan_admin }}">
                                            {{ \Illuminate\Support\Str::limit(
                                                $item->catatan_admin,
                                                50
                                            ) }}
                                        </span>

                                    @else
                                        -
                                    @endif
                                </td>

                                <td>
                                    <div class="d-flex flex-wrap
                                                justify-content-center gap-2">

                                        {{-- Terima --}}
                                        @if(
                                            $item->status_pendaftaran
                                            !== 'diterima'
                                        )
                                            <form action="{{ route(
                                                'admin.registration-verification.update',
                                                $item
                                            ) }}"
                                                  method="POST"
                                                  class="swal-confirm-form"
                                                  data-icon="question"
                                                  data-title="Terima pendaftaran?"
                                                  data-text="Pendaftaran {{ $item->user->name }} pada seminar {{ $item->seminar->judul }} akan diterima."
                                                  data-confirm-text="Ya, Terima"
                                                  data-confirm-color="#198754">

                                                @csrf
                                                @method('PATCH')

                                                <input type="hidden"
                                                       name="status_pendaftaran"
                                                       value="diterima">

                                                <button type="submit"
                                                        class="btn
                                                               btn-success
                                                               btn-sm">
                                                    Terima
                                                </button>
                                            </form>
                                        @endif

                                        {{-- Tolak --}}
                                        @if(
                                            $item->status_pendaftaran
                                            !== 'ditolak'
                                        )
                                            <button type="button"
                                                    class="btn
                                                           btn-danger
                                                           btn-sm"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#modalTolak{{ $item->id }}">
                                                Tolak
                                            </button>
                                        @endif

                                        {{-- Kembalikan ke pending --}}
                                        @if(
                                            $item->status_pendaftaran
                                            !== 'pending'
                                        )
                                            <form action="{{ route(
                                                'admin.registration-verification.update',
                                                $item
                                            ) }}"
                                                  method="POST"
                                                  class="swal-confirm-form"
                                                  data-icon="question"
                                                  data-title="Kembalikan ke pending?"
                                                  data-text="Pendaftaran {{ $item->user->name }} akan menunggu verifikasi kembali."
                                                  data-confirm-text="Ya, Ubah"
                                                  data-confirm-color="#6c757d">

                                                @csrf
                                                @method('PATCH')

                                                <input type="hidden"
                                                       name="status_pendaftaran"
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
                                    class="text-center
                                           text-secondary py-5">
                                    Belum ada pendaftaran seminar.
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

{{-- Modal penolakan --}}
@foreach($registrations as $item)

    @if($item->status_pendaftaran !== 'ditolak')

        <div class="modal fade"
             id="modalTolak{{ $item->id }}"
             tabindex="-1"
             aria-hidden="true">

            <div class="modal-dialog modal-dialog-centered">

                <div class="modal-content">

                    <form action="{{ route(
                        'admin.registration-verification.update',
                        $item
                    ) }}"
                          method="POST">

                        @csrf
                        @method('PATCH')

                        <input type="hidden"
                               name="status_pendaftaran"
                               value="ditolak">

                        <div class="modal-header">

                            <h5 class="modal-title">
                                Tolak Pendaftaran
                            </h5>

                            <button type="button"
                                    class="btn-close"
                                    data-bs-dismiss="modal">
                            </button>

                        </div>

                        <div class="modal-body">

                            <p>
                                Anda akan menolak pendaftaran:
                            </p>

                            <div class="alert alert-light border">

                                <strong>
                                    {{ $item->user->name }}
                                </strong>

                                <div>
                                    {{ $item->seminar->judul }}
                                </div>

                            </div>

                            <label for="catatan_admin_{{ $item->id }}"
                                   class="form-label">
                                Alasan Penolakan
                            </label>

                            <textarea id="catatan_admin_{{ $item->id }}"
                                      name="catatan_admin"
                                      rows="4"
                                      class="form-control"
                                      placeholder="Tuliskan alasan penolakan"
                                      required></textarea>

                            <small class="text-secondary">
                                Catatan ini akan terlihat oleh peserta.
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
                                Tolak Pendaftaran
                            </button>

                        </div>

                    </form>

                </div>
            </div>
        </div>

    @endif

@endforeach
@endsection