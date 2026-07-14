@extends('layouts.app')

@section('title', 'Status Pendaftaran Seminar')

@section('content')
<div class="container">

    <div class="d-flex flex-column flex-md-row
                justify-content-between align-items-md-center mb-4">

        <div>
            <h2 class="fw-bold mb-1">
                Status Pendaftaran Seminar
            </h2>

            <p class="text-secondary mb-0">
                Pantau proses verifikasi pendaftaran seminar Anda.
            </p>
        </div>

        <a href="{{ route('peserta.seminars.index') }}"
           class="btn btn-primary mt-3 mt-md-0">
            Cari Seminar
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
                            <th>Pelaksanaan</th>
                            <th>Biaya</th>
                            <th>Status</th>
                            <th>Catatan</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>

                    <tbody>

                        @forelse($registrations as $registration)

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
                                        {{ $registration
                                            ->seminar
                                            ->narasumber }}
                                    </div>
                                </td>

                                <td>
                                    <div>
                                        {{ $registration
                                            ->seminar
                                            ->tanggal
                                            ->format('d-m-Y') }}
                                    </div>

                                    <div class="small text-secondary">
                                        {{ substr(
                                            $registration
                                                ->seminar
                                                ->waktu_mulai,
                                            0,
                                            5
                                        ) }}
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
                                    @if(
                                        $registration->status_pendaftaran
                                        === 'diterima'
                                    )
                                        <span class="badge bg-success">
                                            Diterima
                                        </span>
                                    @elseif(
                                        $registration->status_pendaftaran
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
                                    {{ $registration->catatan_admin ?? '-' }}
                                </td>

                                <td class="text-center">

                                    <a href="{{ route(
                                            'peserta.seminars.show',
                                            $registration->seminar
                                        ) }}"
                                       class="btn btn-outline-primary btn-sm">
                                        Detail
                                    </a>

                                    @if(
                                        $registration->status_pendaftaran
                                        === 'pending'
                                    )
                                        <form action="{{ route(
                                                'peserta.registrations.destroy',
                                                $registration
                                            ) }}"
                                              method="POST"
                                              class="d-inline
                                                     swal-confirm-form"
                                              data-icon="warning"
                                              data-title="Batalkan pendaftaran?"
                                              data-text="Pendaftaran seminar ini akan dibatalkan."
                                              data-confirm-text="Ya, Batalkan"
                                              data-confirm-color="#dc3545">

                                            @csrf
                                            @method('DELETE')

                                            <button type="submit"
                                                    class="btn btn-danger btn-sm">
                                                Batalkan
                                            </button>
                                        </form>
                                    @endif

                                </td>
                            </tr>

                        @empty

                            <tr>
                                <td colspan="7"
                                    class="text-center text-secondary py-5">
                                    Anda belum mendaftar seminar.
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