@extends('layouts.app')

@section('title', 'Display Monitor Antrean - Posko PPDB/SPMB')

@section('styles')
<style>
    body {
        background: #e9ecef; /* Light grey background */
        color: #333333;
        height: 100vh;
        display: flex;
        flex-direction: column;
        overflow: hidden;
        padding: 0;
        margin: 0;
        font-family: 'Outfit', sans-serif;
    }

    /* Header Styling */
    header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%); /* Premium Royal Blue to Ocean Blue Gradient */
        padding: 12px 30px;
        border-bottom: 4px solid #ffd100; /* Vibrant Gold border */
        box-shadow: 0 4px 15px rgba(30, 58, 138, 0.2);
    }

    .header-left {
        display: flex;
        align-items: center;
        gap: 20px;
    }

    .logo-container svg {
        width: 80px;
        height: 80px;
        filter: drop-shadow(0px 2px 4px rgba(0,0,0,0.15));
    }

    .header-text h1 {
        font-size: 1.7rem;
        font-weight: 800;
        color: #ffffff;
        margin: 0;
        letter-spacing: 0.5px;
        text-shadow: 0 1px 3px rgba(0,0,0,0.2);
    }

    .header-text h2 {
        font-size: 1.05rem;
        font-weight: 500;
        color: #ffffff;
        margin: 2px 0 0 0;
        opacity: 0.95;
    }

    .header-text p {
        font-size: 0.8rem;
        color: #e0f2f1;
        margin: 2px 0 0 0;
        opacity: 0.85;
    }

    .header-right {
        display: flex;
        flex-direction: column;
        align-items: flex-end;
        gap: 2px;
    }

    .clock-text {
        font-size: 1.7rem;
        font-weight: 800;
        color: #ffffff;
        letter-spacing: 1px;
        text-shadow: 0 1px 3px rgba(0,0,0,0.2);
    }

    .date-text {
        font-size: 0.9rem;
        font-weight: 600;
        color: #ffd100; /* Vibrant Gold clock date */
        opacity: 0.95;
    }

    /* Static Text Bar (Old Marquee Position) */
    .static-bar {
        background: #212121; /* Dark charcoal */
        padding: 10px 0;
        height: 44px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-bottom: 1px solid rgba(255,255,255,0.05);
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    }

    .static-container {
        width: 100%;
        text-align: center;
        padding: 0 20px;
    }

    .static-text {
        font-size: 1.2rem;
        font-weight: 700;
        color: #ffd100; /* Glowing Gold color */
        letter-spacing: 0.5px;
    }

    /* Marquee / Running Text Bar (Footer) */
    .marquee-bar {
        background: #212121; /* Dark charcoal */
        padding: 10px 0;
        height: 44px;
        display: flex;
        align-items: center;
        border-top: 1px solid rgba(255,255,255,0.05);
        box-shadow: 0 -2px 5px rgba(0,0,0,0.1);
        margin-top: auto;
    }

    .marquee-container {
        flex: 1;
        overflow: hidden;
        position: relative;
        height: 24px;
    }

    .marquee-text {
        position: absolute;
        white-space: nowrap;
        will-change: transform;
        font-size: 1.2rem;
        font-weight: 700;
        color: #ffffff;
        letter-spacing: 0.5px;
        padding-left: 100%;
        animation: marquee 25s linear infinite;
    }

    @keyframes marquee {
        0% { transform: translate3d(0, 0, 0); }
        100% { transform: translate3d(-100%, 0, 0); }
    }

    /* Main Grid Layout */
    .main-layout {
        flex: 1;
        display: flex;
        gap: 24px;
        padding: 24px;
        min-height: 0;
    }

    /* Left Column: Agenda Card */
    .agenda-card {
        width: 48%;
        display: flex;
        flex-direction: column;
        background: #ffffff;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 4px 15px rgba(0,0,0,0.08);
        border: 1px solid #dcdcdc;
    }

    .agenda-card .card-header {
        background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
        color: #ffffff;
        font-size: 1.6rem;
        font-weight: 700;
        text-align: center;
        padding: 14px;
        text-transform: uppercase;
        letter-spacing: 1px;
        border-bottom: 3px solid #ffd100; /* Yellow border */
    }

    .agenda-card .card-body {
        flex: 1;
        position: relative;
        background: #000000;
        min-height: 0;
    }

    /* Slideshow wrapper inside body */
    .slideshow-wrapper {
        width: 100%;
        height: 100%;
        position: relative;
    }

    .slide {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        opacity: 0;
        transition: opacity 1s ease-in-out;
        overflow: hidden;
        background: #000000;
    }

    .slide.active {
        opacity: 1;
    }

    .slide-blur-bg {
        position: absolute;
        top: -10%;
        left: -10%;
        width: 120%;
        height: 120%;
        background-size: cover;
        background-position: center;
        filter: blur(20px);
        opacity: 0.55;
    }

    .slide-fg {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-size: contain;
        background-position: center;
        background-repeat: no-repeat;
        z-index: 1;
    }

    /* Right Column: 2x2 grid of 4 Lokets */
    .loket-grid {
        width: 52%;
        display: grid;
        grid-template-columns: 1fr 1fr;
        grid-template-rows: 1fr 1fr;
        gap: 24px;
    }

    .mini-card {
        display: flex;
        flex-direction: column;
        background: #ffffff;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 4px 15px rgba(0,0,0,0.08);
        border: 1px solid #dcdcdc;
    }

    .mini-card-header {
        background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
        color: #ffffff;
        font-size: 1.5rem;
        font-weight: 700;
        text-align: center;
        padding: 12px;
        border-bottom: 3px solid #ffd100; /* Yellow border */
        letter-spacing: 0.5px;
    }

    .mini-card-body {
        flex: 1;
        display: flex;
        justify-content: center;
        align-items: center;
        background: #ffffff;
        font-size: 7rem;
        font-weight: 800;
        color: #333333; /* Dark grey for the numbers */
        line-height: 1;
        transition: background-color 0.3s ease;
    }

    .mini-card-body .prefix-digit {
        color: #6b7280; /* Subtle prefix */
    }

    .mini-card-body .main-digits {
        color: #111827;
    }

    /* Blink Animation when active/called */
    @keyframes callingBlink {
        0%, 100% { background: #ffffff; color: #111827; }
        50% { background: rgba(30, 58, 138, 0.2); color: #1e3c72; }
    }

    .calling-flash .mini-card-body {
        animation: callingBlink 0.8s ease-in-out infinite;
    }

    /* Start Overlay Modal */
    .start-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(15, 23, 42, 0.95); /* Deep slate */
        display: flex;
        justify-content: center;
        align-items: center;
        z-index: 9999;
        backdrop-filter: blur(10px);
    }

    .start-box {
        background: #ffffff;
        color: #0f172a;
        padding: 40px;
        border-radius: 16px;
        text-align: center;
        box-shadow: 0 10px 40px rgba(0,0,0,0.5);
        max-width: 500px;
        width: 90%;
        border: 1px solid rgba(255, 255, 255, 0.2);
    }

    .start-box h2 {
        font-size: 1.8rem;
        font-weight: 800;
        color: #002d62;
        margin-bottom: 10px;
    }

    .start-box p {
        color: #64748b;
        font-size: 0.95rem;
        line-height: 1.5;
        margin-bottom: 25px;
    }

    .start-btn {
        background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
        color: #ffffff;
        border: none;
        padding: 14px 28px;
        font-size: 1.1rem;
        font-weight: 700;
        border-radius: 8px;
        cursor: pointer;
        transition: var(--transition-smooth);
        box-shadow: 0 4px 12px rgba(30, 58, 138, 0.3);
        width: 100%;
    }

    .start-btn:hover {
        background: #ffd100;
        color: #1e3c72;
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(255, 209, 0, 0.45);
    }

    /* Slide Navigation Buttons */
    .slide-nav-btn {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        width: 46px;
        height: 46px;
        border-radius: 50%;
        background: rgba(0, 0, 0, 0.45);
        backdrop-filter: blur(4px);
        border: 1px solid rgba(255, 255, 255, 0.15);
        display: flex;
        justify-content: center;
        align-items: center;
        cursor: pointer;
        z-index: 10;
        transition: all 0.3s ease;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
    }

    .slide-nav-btn:hover {
        background: rgba(30, 58, 138, 0.85);
        border-color: rgba(255, 255, 255, 0.3);
        transform: translateY(-50%) scale(1.08);
        box-shadow: 0 6px 15px rgba(30, 58, 138, 0.45);
    }

    .slide-nav-btn svg {
        width: 28px;
        height: 28px;
        fill: #ffffff;
    }

    .prev-btn {
        left: 15px;
    }

    .next-btn {
        right: 15px;
    }
</style>
@endsection

@section('content')
<!-- Start Audio Overlay Kiosk -->
<div id="startOverlay" class="start-overlay">
    <div class="start-box">
        <div style="margin-bottom: 20px;">
            <!-- Simple DKI Jakarta Coat of Arms SVG inside box -->
            <svg viewBox="0 0 100 100" style="width: 80px; height: 80px; fill: #002d62;">
                <path d="M50,15 L78,25 L78,55 C78,75 50,85 50,85 C50,85 22,75 22,55 L22,25 L50,15 Z" fill="#002d62" />
                <path d="M50,18 L75,27 L75,55 C75,72 50,81 50,81 C50,81 25,72 25,55 L25,27 L50,18 Z" fill="#ffffff" />
                <rect x="47" y="35" width="6" height="30" fill="#fbc02d" />
                <path d="M44,35 L56,35 L50,25 Z" fill="#fbc02d" />
                <circle cx="50" cy="65" r="5" fill="#00875a" />
            </svg>
        </div>
        <h2>Buka Layar Monitor TV</h2>
        <p>Klik tombol di bawah ini untuk memulai pemutar video informasi dan mengaktifkan suara bel panggilan otomatis.</p>
        <button id="btnStart" class="start-btn">Mulai Tampilan Antrean</button>
    </div>
</div>

<!-- Header -->
<header>
    <div class="header-left">
        <div class="logo-container">
            @if(!empty($settings['header_logo']))
                <img src="{{ asset($settings['header_logo']) }}" style="height: 80px; width: auto; max-width: 300px; object-fit: contain; filter: drop-shadow(0px 2px 4px rgba(0,0,0,0.15)); border-radius: 6px;">
            @else
                <!-- Custom DKI Jakarta Logo SVG -->
                <svg viewBox="0 0 100 110" xmlns="http://www.w3.org/2000/svg">
                    <path d="M50,5 L85,17 L85,60 C85,85 50,100 50,100 C50,100 15,85 15,60 L15,17 L50,5 Z" fill="#fbc02d" />
                    <path d="M50,10 L80,21 L80,58 C80,80 50,94 50,94 C50,94 20,80 20,58 L20,21 L50,10 Z" fill="#002d62" />
                    <path d="M50,13 L77,23 L77,56 C77,77 50,90 50,90 C50,90 23,77 23,56 L23,23 L50,13 Z" fill="#ffffff" />
                    <path d="M38,72 L62,72 L58,63 L42,63 Z" fill="#e0e0e0" stroke="#002d62" stroke-width="1.5" />
                    <rect x="47" y="38" width="6" height="25" fill="#ffffff" stroke="#002d62" stroke-width="1.5" />
                    <path d="M50,24 C53,24 55,28 50,38 C45,28 47,24 50,24 Z" fill="#fbc02d" />
                    <path d="M25,78 C35,83 45,78 50,81 C55,78 65,83 75,78" stroke="#00875a" stroke-width="3" fill="none" />
                </svg>
            @endif
        </div>
        <div class="header-text">
            <h1 id="titleHeader">{{ $settings['header_title'] ?? 'POSKO PPDB / SPMB' }}</h1>
            <h2 id="subtitleHeader">{{ $settings['header_subtitle'] ?? 'SUDIN PENDIDIKAN WILAYAH 1 JAKARTA PUSAT' }}</h2>
            <p id="addressHeader">{{ $settings['header_address'] ?? 'Kantor Walikota Jakarta Pusat' }}</p>
        </div>
    </div>
    <div class="header-right">
        <div id="clockDisplay" class="clock-text"></div>
        <div id="dateDisplay" class="date-text"></div>
    </div>
</header>

<!-- Static Bar (Directly below header) -->
<div class="static-bar">
    <div class="static-container">
        <div id="staticDisplay" class="static-text">{{ $settings['static_text'] ?? 'Informasi: Silakan mengambil nomor antrean pada mesin cetak tiket.' }}</div>
    </div>
</div>

<!-- Main Layout Grid -->
<div class="main-layout">
    <!-- Left Column: Agenda Card (Media Player) -->
    <div class="agenda-card">
        <div class="card-header">Agenda</div>
        <div class="card-body">
            <div id="videoPlayer" style="display: {{ ($settings['media_type'] ?? 'video') === 'video' ? 'block' : 'none' }}; width: 100%; height: 100%;">
                <iframe id="mediaIframe" src="" allow="autoplay; encrypted-media" style="width: 100%; height: 100%; border: none;"></iframe>
            </div>
            <div id="slideshowPlayer" class="slideshow-wrapper" style="display: {{ ($settings['media_type'] ?? 'video') === 'slideshow' ? 'block' : 'none' }};">
                <div id="slideshowSlides" style="width: 100%; height: 100%; position: relative;"></div>
                <!-- Navigation Arrows -->
                <button id="btnPrevSlide" class="slide-nav-btn prev-btn" aria-label="Previous Slide">
                    <svg viewBox="0 0 24 24"><path d="M15.41,16.58L10.83,12L15.41,7.41L14,6L8,12L14,18L15.41,16.58Z"/></svg>
                </button>
                <button id="btnNextSlide" class="slide-nav-btn next-btn" aria-label="Next Slide">
                    <svg viewBox="0 0 24 24"><path d="M8.59,16.58L13.17,12L8.59,7.41L10,6L16,12L10,18L8.59,16.58Z"/></svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Right Column: 2x2 Grid of 4 Lokets -->
    <div class="loket-grid">
        @for($i = 0; $i < 4; $i++)
            @php
                $c = $counters->get($i);
            @endphp
            <div class="mini-card" id="cardCounter{{ $i + 1 }}" data-counter-id="{{ $c ? $c->id : '' }}">
                <div class="mini-card-header">{{ $c ? $c->name : 'LOKET ' . ($i + 1) }}</div>
                <div class="mini-card-body" id="counterNumber{{ $i + 1 }}">
                    @if($c && $c->current_call_number)
                        @php
                            $cNumStr = str_pad($c->current_call_number, 3, '0', STR_PAD_LEFT);
                        @endphp
                        <span class="prefix-digit">{{ substr($cNumStr, 0, 1) }}</span><span class="main-digits">{{ substr($cNumStr, 1) }}</span>
                    @else
                        <span class="prefix-digit">-</span><span class="main-digits">--</span>
                    @endif
                </div>
            </div>
        @endfor
    </div>
</div>
<!-- Marquee Bar (At the bottom) -->
<div class="marquee-bar">
    <div class="marquee-container">
        <div id="marqueeDisplay" class="marquee-text">{{ $settings['marquee_text'] ?? 'Selamat datang...' }}</div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    window.appSettings = {
        state_url: "{{ route('display.state') }}",
        media_type: "{{ $settings['media_type'] ?? 'video' }}",
        video_url: "{{ $settings['video_url'] ?? '' }}",
        slideshow_images: JSON.parse('{!! $settings['slideshow_images'] ?? '[]' !!}'),
        speech_rate: parseFloat("{{ $settings['speech_rate'] ?? '1.0' }}"),
        speech_pitch: parseFloat("{{ $settings['speech_pitch'] ?? '1.0' }}"),
        asset_base_url: "{{ asset('') }}"
    };
    window.lastCallId = parseInt("{{ $last_call ? $last_call->id : 0 }}");
</script>
<script src="{{ asset('js/display.js') }}?v={{ time() }}"></script>
@endsection
