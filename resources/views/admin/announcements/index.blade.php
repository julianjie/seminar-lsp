@extends('layouts.app')

@section('title', 'Kelola Pengumuman')

@section('content')
<div class="container">

    <div class="d-flex flex-column flex-md-row
                justify-content-between
                align-items-md-center mb-4">

        <div>
            <h2 class="fw-bold mb-1">
                Kelola Pengumuman
            </h2>

            <p class="text-secondary mb-0">
                Tambah, edit, dan hapus pengumuman.
            </p>
        </div>

        <a href="{{ route(
                'admin.announcements.create'
            ) }}"
           class="btn btn-primary mt-3 mt-md-0">
            + Tambah Pengumuman
        </a>
    </div>

    <div class="row g-3 mb-4">

        <div class="col-md-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body">

                    <p class="text-secondary mb-2">
                        Total Pengumuman
                    </p>

                    <h2 class="fw-bold mb-0">
                        {{ $totalPengumuman }}
                    </h2>

                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-0 shadow-sm">
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

        <div class="col-md-4">
            <div class="card border-0 shadow-sm">
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

    </div>

    <div class="card border-0 shadow-sm">

        <div class="card-header bg-white py-3">

            <form action="{{ route(
                    'admin.announcements.index'
                ) }}"
                  method="GET"
                  class="row g-2">

                <div class="col-md-6">
                    <input type="text"
                           name="search"
                           value="{{ $search }}"
                           class="form-control"
                           placeholder="Cari judul atau isi pengumuman">
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
                            @selected(
                                $status === 'dipublikasikan'
                            )>
                            Dipublikasikan
                        </option>

                    </select>
                </div>

                <div class="col-md-3 d-flex gap-2">

                    <button type="submit"
                            class="btn btn-primary flex-grow-1">
                        Filter
                    </button>

                    <a href="{{ route(
                            'admin.announcements.index'
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
                            <th>Gambar</th>
                            <th>Pengumuman</th>
                            <th>Publikasi</th>
                            <th>Status</th>
                            <th>Dibuat Oleh</th>
                            <th class="text-center">
                                Aksi
                            </th>
                        </tr>
                    </thead>

                    <tbody>

                        @forelse(
                            $announcements as $announcement
                        )

                            <tr>
                                <td>
                                    {{ $announcements->firstItem()
                                        + $loop->index }}
                                </td>

                                <td>
                                    @if($announcement->gambar)

                                        <img src="{{ Storage::url(
                                                $announcement->gambar
                                            ) }}"
                                             alt="{{ $announcement->judul }}"
                                             class="rounded border"
                                             style="
                                                width: 90px;
                                                height: 60px;
                                                object-fit: cover;
                                             ">

                                    @else

                                        <div class="bg-light border rounded
                                                    d-flex
                                                    align-items-center
                                                    justify-content-center"
                                             style="
                                                width: 90px;
                                                height: 60px;
                                             ">
                                            📢
                                        </div>

                                    @endif
                                </td>

                                <td>
                                    <strong>
                                        {{ $announcement->judul }}
                                    </strong>

                                    <div class="small text-secondary">
                                        {{ \Illuminate\Support\Str::limit(
                                            strip_tags($announcement->isi),
                                            80
                                        ) }}
                                    </div>
                                </td>

                                <td>
                                    {{ $announcement
                                        ->tanggal_publish
                                        ->format('d-m-Y H:i') }}
                                </td>

                                <td>
                                    @if(
                                        $announcement->status
                                        === 'dipublikasikan'
                                    )
                                        <span class="badge bg-success">
                                            Dipublikasikan
                                        </span>
                                    @else
                                        <span class="badge bg-secondary">
                                            Draft
                                        </span>
                                    @endif
                                </td>

                                <td>
                                    {{ $announcement
                                        ->creator
                                        ?->name ?? '-' }}
                                </td>

                                <td>
                                    <div class="d-flex flex-wrap
                                                justify-content-center
                                                gap-2">

                                        <a href="{{ route(
                                                'admin.announcements.edit',
                                                $announcement
                                            ) }}"
                                           class="btn btn-warning btn-sm">
                                            Edit
                                        </a>

                                        <form action="{{ route(
                                                'admin.announcements.destroy',
                                                $announcement
                                            ) }}"
                                              method="POST"
                                              class="swal-confirm-form"
                                              data-icon="warning"
                                              data-title="Hapus pengumuman?"
                                              data-text="Pengumuman {{ $announcement->judul }} akan dihapus permanen."
                                              data-confirm-text="Ya, Hapus"
                                              data-confirm-color="#dc3545">

                                            @csrf
                                            @method('DELETE')

                                            <button type="submit"
                                                    class="btn
                                                           btn-danger
                                                           btn-sm">
                                                Hapus
                                            </button>
                                        </form>

                                    </div>
                                </td>
                            </tr>

                        @empty

                            <tr>
                                <td colspan="7"
                                    class="text-center
                                           text-secondary py-5">
                                    Belum ada pengumuman.
                                </td>
                            </tr>

                        @endforelse

                    </tbody>
                </table>
            </div>

            @if($announcements->hasPages())
                <div class="mt-3">
                    {{ $announcements->links() }}
                </div>
            @endif

        </div>
    </div>

</div>
@endsection