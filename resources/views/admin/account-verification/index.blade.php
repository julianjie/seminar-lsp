@extends('layouts.app')

@section('title', 'Verifikasi Akun Peserta')

@section('content')
<div class="container">

    {{-- Header --}}
    <div class="d-flex flex-column flex-md-row
                justify-content-between align-items-md-center mb-4">

        <div>
            <h2 class="fw-bold mb-1">
                Verifikasi Akun Peserta
            </h2>

            <p class="text-secondary mb-0">
                Terima atau tolak pendaftaran akun peserta.
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
                'admin.account-verification.index',
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
                'admin.account-verification.index',
                ['status' => 'diterima']
            ) }}"
               class="text-decoration-none">

                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">

                        <p class="text-secondary mb-2">
                            Akun Diterima
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
                'admin.account-verification.index',
                ['status' => 'ditolak']
            ) }}"
               class="text-decoration-none">

                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">

                        <p class="text-secondary mb-2">
                            Akun Ditolak
                        </p>

                        <h2 class="fw-bold text-danger mb-0">
                            {{ $jumlahDitolak }}
                        </h2>

                    </div>
                </div>
            </a>
        </div>

    </div>

    {{-- Daftar peserta --}}
    <div class="card border-0 shadow-sm">

        <div class="card-header bg-white py-3">

            <div class="d-flex flex-column flex-md-row
                        justify-content-between align-items-md-center">

                <h5 class="mb-2 mb-md-0">
                    Daftar Peserta
                </h5>

                {{-- Filter status --}}
                <form action="{{ route(
                    'admin.account-verification.index'
                ) }}"
                      method="GET"
                      class="d-flex gap-2">

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

                    <button type="submit"
                            class="btn btn-primary">
                        Filter
                    </button>

                    @if($status)
                        <a href="{{ route(
                            'admin.account-verification.index'
                        ) }}"
                           class="btn btn-outline-secondary">
                            Reset
                        </a>
                    @endif

                </form>
            </div>
        </div>

        <div class="card-body">

            <div class="table-responsive">

                <table class="table table-hover align-middle">

                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Peserta</th>
                            <th>Kontak</th>
                            <th>Alamat</th>
                            <th>Status</th>
                            <th class="text-center">
                                Aksi
                            </th>
                        </tr>
                    </thead>

                    <tbody>

                        @forelse($peserta as $item)

                            <tr>
                                <td>
                                    {{ $peserta->firstItem() + $loop->index }}
                                </td>

                                <td>
                                    <strong>
                                        {{ $item->name }}
                                    </strong>

                                    <div class="small text-secondary">
                                        Terdaftar:
                                        {{ $item->created_at->format(
                                            'd-m-Y H:i'
                                        ) }}
                                    </div>
                                </td>

                                <td>
                                    <div>
                                        {{ $item->email }}
                                    </div>

                                    <div class="small text-secondary">
                                        {{ $item->no_hp ?? '-' }}
                                    </div>
                                </td>

                                <td>
                                    {{ $item->alamat ?? '-' }}
                                </td>

                                <td>
                                    @if($item->status_akun === 'diterima')

                                        <span class="badge bg-success">
                                            Diterima
                                        </span>

                                    @elseif($item->status_akun === 'ditolak')

                                        <span class="badge bg-danger">
                                            Ditolak
                                        </span>

                                    @else

                                        <span class="badge bg-warning text-dark">
                                            Pending
                                        </span>

                                    @endif
                                </td>

                                <td>
                                    <div class="d-flex flex-wrap
                                                justify-content-center gap-2">

                                        {{-- Tombol terima --}}
                                        @if(
                                            $item->status_akun !== 'diterima'
                                        )
                                            <form action="{{ route(
                                                'admin.account-verification.update',
                                                $item
                                            ) }}"
                                                  method="POST"
                                                  class="swal-confirm-form"
                                                  data-icon="question"
                                                  data-title="Terima akun peserta?"
                                                  data-text="Akun {{ $item->name }} akan diterima dan dapat melakukan login."
                                                  data-confirm-text="Ya, Terima"
                                                  data-confirm-color="#198754">

                                                @csrf
                                                @method('PATCH')

                                                <input type="hidden"
                                                       name="status_akun"
                                                       value="diterima">

                                                <button type="submit"
                                                        class="btn btn-success btn-sm">
                                                    Terima
                                                </button>
                                            </form>
                                        @endif

                                        {{-- Tombol tolak --}}
                                        @if(
                                            $item->status_akun !== 'ditolak'
                                        )
                                            <form action="{{ route(
                                                'admin.account-verification.update',
                                                $item
                                            ) }}"
                                                  method="POST"
                                                  class="swal-confirm-form"
                                                  data-icon="warning"
                                                  data-title="Tolak akun peserta?"
                                                  data-text="Akun {{ $item->name }} akan ditolak dan tidak dapat melakukan login."
                                                  data-confirm-text="Ya, Tolak"
                                                  data-confirm-color="#dc3545">

                                                @csrf
                                                @method('PATCH')

                                                <input type="hidden"
                                                       name="status_akun"
                                                       value="ditolak">

                                                <button type="submit"
                                                        class="btn btn-danger btn-sm">
                                                    Tolak
                                                </button>
                                            </form>
                                        @endif

                                        {{-- Tombol pending --}}
                                        @if(
                                            $item->status_akun !== 'pending'
                                        )
                                            <form action="{{ route(
                                                'admin.account-verification.update',
                                                $item
                                            ) }}"
                                                  method="POST"
                                                  class="swal-confirm-form"
                                                  data-icon="question"
                                                  data-title="Kembalikan ke status pending?"
                                                  data-text="Akun {{ $item->name }} akan menunggu verifikasi kembali."
                                                  data-confirm-text="Ya, Ubah"
                                                  data-confirm-color="#6c757d">

                                                @csrf
                                                @method('PATCH')

                                                <input type="hidden"
                                                       name="status_akun"
                                                       value="pending">

                                                <button type="submit"
                                                        class="btn btn-outline-secondary btn-sm">
                                                    Pending
                                                </button>
                                            </form>
                                        @endif

                                    </div>
                                </td>
                            </tr>

                        @empty

                            <tr>
                                <td colspan="6"
                                    class="text-center text-secondary py-4">

                                    Tidak ada data peserta.

                                </td>
                            </tr>

                        @endforelse

                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            @if($peserta->hasPages())
                <div class="mt-3">
                    {{ $peserta->links() }}
                </div>
            @endif

        </div>
    </div>

</div>
@endsection