<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Seminar LSP</title>

    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
</head>

<body class="bg-light">

    {{-- Navbar --}}
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm">
        <div class="container">
            <a class="navbar-brand fw-bold" href="{{ url('/') }}">
                Seminar LSP
            </a>

            <div class="ms-auto">
                @auth
                    <a href="{{ url('/home') }}" class="btn btn-light">
                        Dashboard
                    </a>
                @else
                    <a href="{{ route('login') }}" class="btn btn-outline-light me-2">
                        Login
                    </a>

                    <a href="{{ route('register') }}" class="btn btn-light">
                        Registrasi
                    </a>
                @endauth
            </div>
        </div>
    </nav>

    {{-- Hero --}}
    <section class="py-5">
        <div class="container">
            <div class="row align-items-center min-vh-75">

                <div class="col-lg-6 mb-4 mb-lg-0">
                    <span class="badge bg-primary mb-3">
                        Pendaftaran Seminar Online
                    </span>

                    <h1 class="display-4 fw-bold mb-3">
                        Tingkatkan Pengetahuan Anda Melalui Seminar Terbaik
                    </h1>

                    <p class="lead text-secondary mb-4">
                        Daftarkan akun, pilih seminar, lakukan konfirmasi
                        pembayaran, dan pantau status pendaftaran Anda secara
                        mudah melalui satu aplikasi.
                    </p>

                    @guest
                        <a href="{{ route('register') }}"
                           class="btn btn-primary btn-lg me-2">
                            Daftar Sekarang
                        </a>

                        <a href="{{ route('login') }}"
                           class="btn btn-outline-primary btn-lg">
                            Masuk
                        </a>
                    @else
                        <a href="{{ url('/home') }}"
                           class="btn btn-primary btn-lg">
                            Buka Dashboard
                        </a>
                    @endguest
                </div>

                <div class="col-lg-6 text-center">
                    <div class="card border-0 shadow-lg">
                        <div class="card-body p-5">

                            <div class="display-1 mb-3">
                                🎓
                            </div>

                            <h3 class="fw-bold">
                                Seminar LSP
                            </h3>

                            <p class="text-secondary">
                                Platform pendaftaran dan pengelolaan seminar
                                berbasis web.
                            </p>

                            <hr>

                            <div class="row text-center">
                                <div class="col-4">
                                    <h4 class="fw-bold text-primary">
                                        Mudah
                                    </h4>
                                    <small class="text-secondary">
                                        Registrasi
                                    </small>
                                </div>

                                <div class="col-4">
                                    <h4 class="fw-bold text-primary">
                                        Cepat
                                    </h4>
                                    <small class="text-secondary">
                                        Verifikasi
                                    </small>
                                </div>

                                <div class="col-4">
                                    <h4 class="fw-bold text-primary">
                                        Aman
                                    </h4>
                                    <small class="text-secondary">
                                        Pembayaran
                                    </small>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    {{-- Footer --}}
    <footer class="bg-dark text-white py-3">
        <div class="container text-center">
            <small>
                &copy; {{ date('Y') }} Seminar LSP.
                Aplikasi Pendaftaran Seminar.
            </small>
        </div>
    </footer>

</body>
</html>