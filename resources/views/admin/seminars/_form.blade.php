<div class="row g-3">

    <div class="col-md-8">
        <label for="judul" class="form-label">
            Judul Seminar
        </label>

        <input type="text"
               id="judul"
               name="judul"
               class="form-control @error('judul') is-invalid @enderror"
               value="{{ old('judul', $seminar->judul ?? '') }}"
               placeholder="Masukkan judul seminar"
               required>

        @error('judul')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror
    </div>

    <div class="col-md-4">
        <label for="status" class="form-label">
            Status
        </label>

        <select id="status"
                name="status"
                class="form-select @error('status') is-invalid @enderror"
                required>

            <option value="">
                Pilih Status
            </option>

            <option value="draft"
                @selected(
                    old('status', $seminar->status ?? '') === 'draft'
                )>
                Draft
            </option>

            <option value="dipublikasikan"
                @selected(
                    old(
                        'status',
                        $seminar->status ?? ''
                    ) === 'dipublikasikan'
                )>
                Dipublikasikan
            </option>

            <option value="selesai"
                @selected(
                    old('status', $seminar->status ?? '') === 'selesai'
                )>
                Selesai
            </option>

        </select>

        @error('status')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror
    </div>

    <div class="col-md-6">
        <label for="narasumber" class="form-label">
            Narasumber
        </label>

        <input type="text"
               id="narasumber"
               name="narasumber"
               class="form-control @error('narasumber') is-invalid @enderror"
               value="{{ old(
                   'narasumber',
                   $seminar->narasumber ?? ''
               ) }}"
               placeholder="Nama narasumber"
               required>

        @error('narasumber')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror
    </div>

    <div class="col-md-6">
        <label for="lokasi" class="form-label">
            Lokasi
        </label>

        <input type="text"
               id="lokasi"
               name="lokasi"
               class="form-control @error('lokasi') is-invalid @enderror"
               value="{{ old('lokasi', $seminar->lokasi ?? '') }}"
               placeholder="Contoh: Aula UMDP atau Zoom Meeting"
               required>

        @error('lokasi')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror
    </div>

    <div class="col-md-4">
        <label for="tanggal" class="form-label">
            Tanggal
        </label>

        <input type="date"
               id="tanggal"
               name="tanggal"
               class="form-control @error('tanggal') is-invalid @enderror"
               value="{{ old(
                   'tanggal',
                   isset($seminar)
                       ? $seminar->tanggal->format('Y-m-d')
                       : ''
               ) }}"
               required>

        @error('tanggal')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror
    </div>

    <div class="col-md-4">
        <label for="waktu_mulai" class="form-label">
            Waktu Mulai
        </label>

        <input type="time"
               id="waktu_mulai"
               name="waktu_mulai"
               class="form-control @error('waktu_mulai') is-invalid @enderror"
               value="{{ old(
                   'waktu_mulai',
                   isset($seminar)
                       ? substr($seminar->waktu_mulai, 0, 5)
                       : ''
               ) }}"
               required>

        @error('waktu_mulai')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror
    </div>

    <div class="col-md-4">
        <label for="waktu_selesai" class="form-label">
            Waktu Selesai
        </label>

        <input type="time"
               id="waktu_selesai"
               name="waktu_selesai"
               class="form-control @error('waktu_selesai') is-invalid @enderror"
               value="{{ old(
                   'waktu_selesai',
                   isset($seminar) && $seminar->waktu_selesai
                       ? substr($seminar->waktu_selesai, 0, 5)
                       : ''
               ) }}">

        @error('waktu_selesai')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror
    </div>

    <div class="col-md-6">
        <label for="kuota" class="form-label">
            Kuota Peserta
        </label>

        <input type="number"
               id="kuota"
               name="kuota"
               min="1"
               class="form-control @error('kuota') is-invalid @enderror"
               value="{{ old('kuota', $seminar->kuota ?? 1) }}"
               required>

        @error('kuota')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror
    </div>

    <div class="col-md-6">
        <label for="harga" class="form-label">
            Harga Pendaftaran
        </label>

        <div class="input-group">
            <span class="input-group-text">
                Rp
            </span>

            <input type="number"
                   id="harga"
                   name="harga"
                   min="0"
                   step="1000"
                   class="form-control @error('harga') is-invalid @enderror"
                   value="{{ old('harga', $seminar->harga ?? 0) }}"
                   required>

            @error('harga')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>

        <small class="text-secondary">
            Isi 0 apabila seminar gratis.
        </small>
    </div>

    <div class="col-12">
        <label for="deskripsi" class="form-label">
            Deskripsi
        </label>

        <textarea id="deskripsi"
                  name="deskripsi"
                  rows="5"
                  class="form-control @error('deskripsi') is-invalid @enderror"
                  placeholder="Jelaskan informasi seminar"
                  required>{{ old(
                      'deskripsi',
                      $seminar->deskripsi ?? ''
                  ) }}</textarea>

        @error('deskripsi')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror
    </div>

    <div class="col-12">
        <label for="gambar" class="form-label">
            Poster Seminar
        </label>

        <input type="file"
               id="gambar"
               name="gambar"
               accept=".jpg,.jpeg,.png,.webp"
               class="form-control @error('gambar') is-invalid @enderror">

        @error('gambar')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror

        <small class="text-secondary">
            Format JPG, JPEG, PNG, atau WEBP. Maksimal 2 MB.
        </small>
    </div>

    @if(isset($seminar) && $seminar->gambar)
        <div class="col-12">
            <p class="mb-2">
                Poster saat ini:
            </p>

            <img src="{{ Storage::url($seminar->gambar) }}"
                 alt="{{ $seminar->judul }}"
                 class="img-thumbnail"
                 style="width: 180px; height: 120px; object-fit: cover;">
        </div>
    @endif

</div>

<hr class="my-4">

<div class="d-flex justify-content-end gap-2">

    <a href="{{ route('admin.seminars.index') }}"
       class="btn btn-outline-secondary">
        Batal
    </a>

    <button type="submit"
            class="btn btn-primary">
        {{ isset($seminar) ? 'Simpan Perubahan' : 'Tambah Seminar' }}
    </button>

</div>