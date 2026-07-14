@extends('layouts.app')

@section('title', 'Kelola Seminar')

@section('content')
<div class="container">

    <div class="d-flex flex-column flex-md-row
                justify-content-between align-items-md-center mb-4">

        <div>
            <h2 class="fw-bold mb-1">
                Kelola Seminar
            </h2>

            <p class="text-secondary mb-0">
                Tambah, edit, dan hapus data seminar.
            </p>
        </div>

        <a href="{{ route('admin.seminars.create') }}"
           class="btn btn-primary mt-3 mt-md-0">
            + Tambah Seminar
        </a>

    </div>

    <div class="row g-3 mb-4">

        <div class="col-md-6 col-xl-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <p class="text-secondary mb-2">
                        Total Seminar
                    </p>

                    <h2 class="fw-bold mb-0">
                        {{ $totalSeminar }}
                    </h2>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-xl-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <p class="text-secondary mb-2">
                        Draft
                    </p>

                    <h2 class="fw-bold text-secondary mb-0">
                        {{ $totalDraft }}
                    </h2>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-xl-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <p class="text-secondary mb-2">
                        Dipublikasikan
                    </p>

                    <h2 class="fw-bold text-success mb-0">
                        {{ $totalDipublikasikan }}
                    </h2>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-xl-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <p class="text-secondary mb-2">
                        Selesai
                    </p>

                    <h2 class="fw-bold text-primary mb-0">
                        {{ $totalSelesai }}
                    </h2>
                </div>
            </div>
        </div>

    </div>

    <div class="card border-0 shadow-sm">

        <div class="card-header bg-white py-3">

            <form action="{{ route('admin.seminars.index') }}"
                  method="GET"
                  class="row g-2">

                <div class="col-md-6">
                    <input type="text"
                           name="search"
                           class="form-control"
                           value="{{ $search }}"
                           placeholder="Cari judul, narasumber, atau lokasi">
                </div>

                <div class="col-md-3">
                    <select name="status"
                            class="form-select">

                        <option value="">
                            Semua Status
                        </option>

                        <option value="draft"
                            @selected($status === 'draft')>
                            Draft
                        </option>

                        <option value="dipublikasikan"
                            @selected($status === 'dipublikasikan')>
                            Dipublikasikan
                        </option>

                        <option value="selesai"
                            @selected($status === 'selesai')>
                            Selesai
                        </option>

                    </select>
                </div>

                <div class="col-md-3 d-flex gap-2">

                    <button type="submit"
                            class="btn btn-primary flex-grow-1">
                        Cari
                    </button>

                    <a href="{{ route('admin.seminars.index') }}"
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
                            <th>Poster</th>
                            <th>Seminar</th>
                            <th>Pelaksanaan</th>
                            <th>Kuota</th>
                            <th>Harga</th>
                            <th>Status</th>
                            <th class="text-center">
                                Aksi
                            </th>
                        </tr>
                    </thead>

                    <tbody>

                        @forelse($seminars as $item)

                            <tr>
                                <td>
                                    {{ $seminars->firstItem()
                                        + $loop->index }}
                                </td>

                                <td>
                                    @if($item->gambar)

                                        <img src="{{ Storage::url(
                                                $item->gambar
                                            ) }}"
                                             alt="{{ $item->judul }}"
                                             class="rounded"
                                             style="
                                                width: 90px;
                                                height: 60px;
                                                object-fit: cover;
                                             ">

                                    @else

                                        <div class="bg-light border rounded
                                                    d-flex align-items-center
                                                    justify-content-center"
                                             style="
                                                width: 90px;
                                                height: 60px;
                                             ">
                                            📚
                                        </div>

                                    @endif
                                </td>

                                <td>
                                    <strong>
                                        {{ $item->judul }}
                                    </strong>

                                    <div class="small text-secondary">
                                        Narasumber:
                                        {{ $item->narasumber }}
                                    </div>

                                    <div class="small text-secondary">
                                        {{ $item->lokasi }}
                                    </div>
                                </td>

                                <td>
                                    <div>
                                        {{ $item->tanggal->format('d-m-Y') }}
                                    </div>

                                    <div class="small text-secondary">
                                        {{ substr(
                                            $item->waktu_mulai,
                                            0,
                                            5
                                        ) }}

                                        @if($item->waktu_selesai)
                                            -
                                            {{ substr(
                                                $item->waktu_selesai,
                                                0,
                                                5
                                            ) }}
                                        @endif
                                    </div>
                                </td>

                                <td>
                                    {{ $item->kuota }}
                                </td>

                                <td>
                                    @if((float) $item->harga === 0.0)

                                        <span class="text-success fw-bold">
                                            Gratis
                                        </span>

                                    @else

                                        Rp{{ number_format(
                                            (float) $item->harga,
                                            0,
                                            ',',
                                            '.'
                                        ) }}

                                    @endif
                                </td>

                                <td>
                                    @if($item->status === 'dipublikasikan')

                                        <span class="badge bg-success">
                                            Dipublikasikan
                                        </span>

                                    @elseif($item->status === 'selesai')

                                        <span class="badge bg-primary">
                                            Selesai
                                        </span>

                                    @else

                                        <span class="badge bg-secondary">
                                            Draft
                                        </span>

                                    @endif
                                </td>

                                <td>
                                    <div class="d-flex justify-content-center
                                                flex-wrap gap-2">

                                        <a href="{{ route(
                                                'admin.seminars.edit',
                                                $item
                                            ) }}"
                                           class="btn btn-warning btn-sm">
                                            Edit
                                        </a>

                                        <form action="{{ route(
                                                'admin.seminars.destroy',
                                                $item
                                            ) }}"
                                              method="POST"
                                              class="swal-confirm-form"
                                              data-icon="warning"
                                              data-title="Hapus seminar?"
                                              data-text="Seminar {{ $item->judul }} akan dihapus permanen."
                                              data-confirm-text="Ya, Hapus"
                                              data-confirm-color="#dc3545">

                                            @csrf
                                            @method('DELETE')

                                            <button type="submit"
                                                    class="btn btn-danger btn-sm">
                                                Hapus
                                            </button>
                                        </form>

                                    </div>
                                </td>
                            </tr>

                        @empty

                            <tr>
                                <td colspan="8"
                                    class="text-center text-secondary py-5">

                                    Belum ada data seminar.

                                </td>
                            </tr>

                        @endforelse

                    </tbody>
                </table>
            </div>

            @if($seminars->hasPages())
                <div class="mt-3">
                    {{ $seminars->links() }}
                </div>
            @endif

        </div>
    </div>

</div>
@endsection