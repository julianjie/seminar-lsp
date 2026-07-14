<div class="row g-3">

    <div class="col-md-8">
        <label for="judul"
               class="form-label">
            Judul Pengumuman
        </label>

        <input type="text"
               id="judul"
               name="judul"
               class="form-control
                      @error('judul') is-invalid @enderror"
               value="{{ old(
                   'judul',
                   $announcement->judul ?? ''
               ) }}"
               placeholder="Masukkan judul pengumuman"
               required>

        @error('judul')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror
    </div>

    <div class="col-md-4">
        <label for="status"
               class="form-label">
            Status
        </label>

        <select id="status"
                name="status"
                class="form-select
                       @error('status') is-invalid @enderror"
                required>

            <option value="">
                Pilih Status
            </option>

            <option value="draft"
                @selected(
                    old(
                        'status',
                        $announcement->status ?? ''
                    ) === 'draft'
                )>
                Draft
            </option>

            <option value="dipublikasikan"
                @selected(
                    old(
                        'status',
                        $announcement->status ?? ''
                    ) === 'dipublikasikan'
                )>
                Dipublikasikan
            </option>

        </select>

        @error('status')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror
    </div>

    <div class="col-md-6">
        <label for="tanggal_publish"
               class="form-label">
            Tanggal dan Waktu Publikasi
        </label>

        <input type="datetime-local"
               id="tanggal_publish"
               name="tanggal_publish"
               class="form-control
                      @error('tanggal_publish') is-invalid @enderror"
               value="{{ old(
                   'tanggal_publish',
                   isset($announcement)
                       ? $announcement
                           ->tanggal_publish
                           ->format('Y-m-d\TH:i')
                       : now()->format('Y-m-d\TH:i')
               ) }}"
               required>

        @error('tanggal_publish')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror

        <small class="text-secondary">
            Pengumuman hanya tampil setelah waktu ini.
        </small>
    </div>

    <div class="col-md-6">
        <label for="gambar"
               class="form-label">
            Gambar Pengumuman
        </label>

        <input type="file"
               id="gambar"
               name="gambar"
               accept=".jpg,.jpeg,.png,.webp"
               class="form-control
                      @error('gambar') is-invalid @enderror">

        @error('gambar')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror

        <small class="text-secondary">
            Maksimal 2 MB.
        </small>
    </div>

    <div class="col-12">
        <label for="isi"
               class="form-label">
            Isi Pengumuman
        </label>

        <textarea id="isi"
                  name="isi"
                  rows="8"
                  class="form-control
                         @error('isi') is-invalid @enderror"
                  placeholder="Tuliskan isi pengumuman"
                  required>{{ old(
                      'isi',
                      $announcement->isi ?? ''
                  ) }}</textarea>

        @error('isi')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror
    </div>

    @if(
        isset($announcement) &&
        $announcement->gambar
    )
        <div class="col-12">

            <p class="mb-2">
                Gambar saat ini:
            </p>

            <img src="{{ Storage::url(
                    $announcement->gambar
                ) }}"
                 alt="{{ $announcement->judul }}"
                 class="img-thumbnail"
                 style="
                    width: 220px;
                    height: 150px;
                    object-fit: cover;
                 ">
        </div>
    @endif

</div>

<hr class="my-4">

<div class="d-flex justify-content-end gap-2">

    <a href="{{ route(
            'admin.announcements.index'
        ) }}"
       class="btn btn-outline-secondary">
        Batal
    </a>

    <button type="submit"
            class="btn btn-primary">

        {{ isset($announcement)
            ? 'Simpan Perubahan'
            : 'Tambah Pengumuman' }}

    </button>

</div>