@extends('layouts.app')

@section('content')
@php
    use Carbon\Carbon;
    use Illuminate\Support\Str;
    $infos = \App\Models\Information::orderBy('date', 'desc')->limit(6)->get();
@endphp

<div class="container py-5">

    {{-- HEADER SEKOLAH --}}
    <div class="text-center mb-5">
        <img src="{{ asset('img/logo-sekolah.png') }}" alt="Logo Sekolah" style="max-height: 100px;" class="mb-3">
        <h2 class="fw-bold text-primary">SMP Negeri 3 Jati Agung</h2>
        <p class="text-muted mb-1">Lampung Selatan</p>
        <p class="text-secondary">
            Selamat datang di <strong>Sistem Absensi QR Digital</strong> — solusi modern, cepat, dan aman dalam mencatat kehadiran siswa secara real-time.
        </p>
            {{-- TOMBOL APLIKASI --}}
    <div class="text-center mt-5 mb-4">
        @auth
            <a href="{{ route('dashboard') }}" class="btn btn-primary btn-lg px-4 me-2 shadow-sm">
                Masuk ke Dashboard
            </a>
        @else
            <a href="{{ route('login') }}" class="btn btn-success btn-lg px-4 me-2 shadow-sm">Login</a>
            <a href="{{ route('register') }}" class="btn btn-outline-primary btn-lg px-4 shadow-sm">Daftar</a>
        @endauth
    </div>
    </div>

{{-- CAROUSEL --}}
@php
    $carouselInfos = \App\Models\Information::whereNotNull('image')->orderBy('date', 'desc')->limit(5)->get();
@endphp

@if($carouselInfos->count())
    <div class="mb-5 d-flex justify-content-center">
        <div id="welcomeCarousel" class="carousel slide shadow-sm rounded" style="max-width: 720px;" data-bs-ride="carousel">
            <div class="carousel-inner rounded">
                @foreach ($carouselInfos as $info)
                    <div class="carousel-item {{ $loop->first ? 'active' : '' }}">
                        <img src="{{ asset('storage/' . $info->image) }}" class="d-block w-100 rounded-top" alt="{{ $info->title }}" style="max-height: 400px; object-fit: cover;">
                        <div class="bg-light text-dark p-3 rounded-bottom border-top">
                            <h5 class="mb-1">{{ $info->title }}</h5>
                            <small class="text-muted">
                                {{ \Carbon\Carbon::parse($info->date)->translatedFormat('d F Y') }}
                            </small>
                            <p class="mt-2" style="font-size: 14px;">
                                {{ Str::limit(strip_tags($info->content), 100) }}
                            </p>
                            <a href="{{ route('information.show', $info->id) }}" class="btn btn-sm btn-outline-primary mt-2">
                                Baca Selengkapnya
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>

            <button class="carousel-control-prev" type="button" data-bs-target="#welcomeCarousel" data-bs-slide="prev">
                <span class="carousel-control-prev-icon"></span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#welcomeCarousel" data-bs-slide="next">
                <span class="carousel-control-next-icon"></span>
            </button>
        </div>
    </div>
@endif


{{-- CAROUSEL INFORMASI DARI DATABASE --}}
@php
    $carouselInfos = \App\Models\Information::whereNotNull('image')->orderBy('date', 'desc')->limit(5)->get();
@endphp

@if($carouselInfos->count())
    <div class="mb-5 d-flex justify-content-center">
        <div id="welcomeCarousel" class="carousel slide carousel-fade shadow rounded overflow-hidden" style="max-width: 720px;" data-bs-ride="carousel">
            
                @foreach ($carouselInfos as $info)
                    <div class="carousel-item {{ $loop->first ? 'active' : '' }}">
                        <div class="position-relative">
                            <img src="{{ asset('storage/' . $info->image) }}" class="d-block w-100" alt="{{ $info->title }}" style="max-height: 400px; object-fit: cover; filter: brightness(90%); transition: 0.5s;">
                            <div class="position-absolute bottom-0 start-0 w-100 bg-white bg-opacity-75 text-dark p-3" style="backdrop-filter: blur(4px);">
                                <h5 class="fw-bold mb-1">{{ $info->title }}</h5>
                                <small class="text-muted">
                                    {{ \Carbon\Carbon::parse($info->date)->translatedFormat('d F Y') }}
                                </small>
                                <p class="mt-2 mb-0" style="font-size: 14px;">
                                    {{ Str::limit(strip_tags($info->content), 100) }}
                                </p>
                                <a href="{{ route('information.show', $info->id) }}" class="btn btn-sm btn-outline-primary mt-2 transition-all">
                                    Baca Selengkapnya →
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            

            <button class="carousel-control-prev" type="button" data-bs-target="#welcomeCarousel" data-bs-slide="prev">
                <span class="carousel-control-prev-icon"></span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#welcomeCarousel" data-bs-slide="next">
                <span class="carousel-control-next-icon"></span>
            </button>
        </div>
    </div>
@endif




    {{-- KONTAK DAN LOKASI --}}
    <div class="row justify-content-center mb-5">
        <div class="col-md-6 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <h5 class="card-title text-primary">📞 Kontak Sekolah</h5>
                    <p><strong>Alamat:</strong> Jl. Raya Jati Agung No.123, Lampung Selatan</p>
                    <p><strong>Email:</strong> smpn3jatiagung@gmail.com</p>
                    <p><strong>WhatsApp:</strong> <a href="https://wa.me/6281234567890" target="_blank">+62 812-3456-7890</a></p>
                    <p><strong>Jam Operasional:</strong> Senin - Jumat, 07.00 - 15.00 WIB</p>
                </div>
            </div>
        </div>
        <div class="col-md-6 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-0">
                    <iframe
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3979.883940320612!2d105.25481911434794!3d-5.334543396102692!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e40d0b5d8b11ebd%3A0xabcdef1234567890!2sSMP%20Negeri%203%20Jati%20Agung!5e0!3m2!1sid!2sid!4v1700000000000"
                        width="100%" height="250" style="border:0;" allowfullscreen="" loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade">
                    </iframe>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
