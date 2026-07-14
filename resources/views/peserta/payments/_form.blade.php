<div class="alert alert-info">

    <strong>{{ $registration->seminar->judul }}</strong>

    <div class="mt-1">
        Jumlah yang harus dibayar:
        <strong>
            Rp{{ number_format(
                (float) $registration->seminar->harga,
                0,
                ',',
                '.'
            ) }}
        </strong>
    </div>

</div>

<div class="row g-3">

    <div class="col-md-6">
        <label for="nama_pengirim"
               class="form-label">
            Nama Pengirim
        </label>

        <input type="text"
               id="nama_pengirim"
               name="nama_pengirim"
               class="form-control
                      @error('nama_pengirim') is-invalid @enderror"
               value="{{ old(
                   'nama_pengirim',
                   $payment->nama_pengirim ?? auth()->user()->name
               ) }}"
               required>

        @error('nama_pengirim')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror
    </div>

    <div class="col-md-6">
        <label for="bank_pengirim"
               class="form-label">
            Bank atau Metode Pembayaran
        </label>

        <input type="text"
               id="bank_pengirim"
               name="bank_pengirim"
               class="form-control
                      @error('bank_pengirim') is-invalid @enderror"
               value="{{ old(
                   'bank_pengirim',
                   $payment->bank_pengirim ?? ''
               ) }}"
               placeholder="Contoh: BCA, BRI, DANA"
               required>

        @error('bank_pengirim')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror
    </div>

    <div class="col-md-6">
    <label for="jumlah_bayar_display"
           class="form-label">
        Jumlah Pembayaran
    </label>

    <div class="input-group">
        <span class="input-group-text">
            Rp
        </span>

        <input type="text"
               id="jumlah_bayar_display"
               class="form-control"
               value="{{ number_format(
                   (float) old(
                       'jumlah_bayar',
                       $payment->jumlah_bayar
                           ?? $registration->seminar->harga
                   ),
                   0,
                   ',',
                   '.'
               ) }}"
               readonly>

        <input type="hidden"
               name="jumlah_bayar"
               value="{{ old(
                   'jumlah_bayar',
                   (int) (
                       $payment->jumlah_bayar
                           ?? $registration->seminar->harga
                   )
               ) }}">
    </div>

    @error('jumlah_bayar')
        <div class="text-danger small mt-1">
            {{ $message }}
        </div>
    @enderror

    <small class="text-secondary">
        Nominal pembayaran otomatis mengikuti harga seminar.
    </small>
</div>

    <div class="col-md-6">
        <label for="tanggal_bayar"
               class="form-label">
            Tanggal Pembayaran
        </label>

        <input type="date"
               id="tanggal_bayar"
               name="tanggal_bayar"
               max="{{ date('Y-m-d') }}"
               class="form-control
                      @error('tanggal_bayar') is-invalid @enderror"
               value="{{ old(
                   'tanggal_bayar',
                   isset($payment)
                       ? $payment->tanggal_bayar->format('Y-m-d')
                       : date('Y-m-d')
               ) }}"
               required>

        @error('tanggal_bayar')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror
    </div>

    <div class="col-12">
        <label for="bukti_pembayaran"
               class="form-label">
            Bukti Pembayaran
        </label>

        <input type="file"
               id="bukti_pembayaran"
               name="bukti_pembayaran"
               accept=".jpg,.jpeg,.png,.webp"
               class="form-control
                      @error('bukti_pembayaran') is-invalid @enderror"
               @required(!isset($payment))>

        @error('bukti_pembayaran')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror

        <small class="text-secondary">
            Format JPG, JPEG, PNG, atau WEBP. Maksimal 2 MB.
        </small>
    </div>

    @if(isset($payment) && $payment->bukti_pembayaran)
        <div class="col-12">

            <p class="mb-2">
                Bukti pembayaran saat ini:
            </p>

            <a href="{{ Storage::url(
                    $payment->bukti_pembayaran
                ) }}"
               target="_blank">

                <img src="{{ Storage::url(
                        $payment->bukti_pembayaran
                    ) }}"
                     alt="Bukti pembayaran"
                     class="img-thumbnail"
                     style="
                        width: 220px;
                        height: 150px;
                        object-fit: cover;
                     ">

            </a>
        </div>
    @endif

</div>

<hr class="my-4">

<div class="d-flex justify-content-end gap-2">

    <a href="{{ route('peserta.payments.index') }}"
       class="btn btn-outline-secondary">
        Batal
    </a>

    <button type="submit"
            class="btn btn-primary">
        {{ isset($payment)
            ? 'Kirim Ulang Pembayaran'
            : 'Kirim Konfirmasi' }}
    </button>

</div>