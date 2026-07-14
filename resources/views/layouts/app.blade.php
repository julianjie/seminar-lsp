<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <meta name="csrf-token"
          content="{{ csrf_token() }}">

    <title>
        @yield('title', 'Seminar LSP')
    </title>

    @vite([
        'resources/sass/app.scss',
        'resources/js/app.js'
    ])
</head>

<body class="bg-light">

<nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm">
    <div class="container">

        <a class="navbar-brand fw-bold"
           href="{{ route('welcome') }}">
            Seminar LSP
        </a>

        <button class="navbar-toggler"
                type="button"
                data-bs-toggle="collapse"
                data-bs-target="#navbarUtama">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse"
             id="navbarUtama">

            <ul class="navbar-nav me-auto">

                @auth
                    @if(auth()->user()->role === 'admin')
                        <li class="nav-item">
                            <a class="nav-link"
                               href="{{ route('admin.dashboard') }}">
                                Dashboard Admin
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link"
                            href="{{ route(
                                'admin.account-verification.index'
                                ) }}">
                                Verifikasi Akun
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link"
                            href="{{ route('admin.seminars.index') }}">
                                Kelola Seminar
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link"
                            href="{{ route(
                                'admin.registration-verification.index'
                            ) }}">
                                Verifikasi Pendaftaran
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link"
                            href="{{ route(
                                'admin.payment-verification.index'
                            ) }}">
                                Verifikasi Pembayaran
                            </a>
                        </li>
                    @elseif(auth()->user()->role === 'peserta')
                        <li class="nav-item">
                            <a class="nav-link"
                            href="{{ route('peserta.dashboard') }}">
                                Dashboard
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link"
                            href="{{ route('peserta.seminars.index') }}">
                                Daftar Seminar
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link"
                            href="{{ route('peserta.registrations.index') }}">
                                Status Pendaftaran
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link"
                            href="{{ route('peserta.payments.index') }}">
                                Pembayaran
                            </a>
                        </li>
                    @endif
                @endauth

            </ul>

            <ul class="navbar-nav ms-auto">

                @guest
                    <li class="nav-item">
                        <a class="nav-link"
                           href="{{ route('account.status.form') }}">
                            Cek Status Akun
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link"
                           href="{{ route('login') }}">
                            Login
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link"
                           href="{{ route('register') }}">
                            Registrasi
                        </a>
                    </li>
                @else
                    <li class="nav-item dropdown">

                        <a class="nav-link dropdown-toggle"
                           href="#"
                           role="button"
                           data-bs-toggle="dropdown">
                            {{ auth()->user()->name }}
                        </a>

                        <ul class="dropdown-menu dropdown-menu-end">

                            <li>
                                <span class="dropdown-item-text">
                                    {{ ucfirst(auth()->user()->role) }}
                                </span>
                            </li>

                            <li>
                                <hr class="dropdown-divider">
                            </li>

                            <li>
                                <form action="{{ route('logout') }}"
                                      method="POST">
                                    @csrf

                                    <button type="submit"
                                            class="dropdown-item">
                                        Logout
                                    </button>
                                </form>
                            </li>

                        </ul>
                    </li>
                @endguest

            </ul>
        </div>
    </div>
</nav>

<main class="py-4">

    <div class="container">

@if(session('success'))
    <div id="flash-success"
         data-message="{{ session('success') }}"
         hidden>
    </div>
@endif

@if(session('error'))
    <div id="flash-error"
         data-message="{{ session('error') }}"
         hidden>
    </div>
@endif

        @if($errors->any())
            <div class="alert alert-danger">
                <strong>Terjadi kesalahan:</strong>

                <ul class="mb-0 mt-2">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

    </div>

    @yield('content')

</main>

<footer class="bg-dark text-white py-3 mt-5">
    <div class="container text-center">
        <small>
            &copy; {{ date('Y') }} Seminar LSP.
            Aplikasi Pendaftaran Seminar.
        </small>
    </div>
</footer>

</body>
</html>