<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HR Manufaktur Indonesia</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- PASTIKAN CSS SWIPER INI ADA DI <head> -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
</head>

<body class="bg-slate-50 text-slate-800 font-sans">
    <main class="mx-auto w-full max-w-7xl">
        <!-- SECTION 1: BANNER UTAMA (SLIDER SWIPER.JS) -->
        <section class="relative bg-slate-950 h-[460px] md:h-[520px] w-full overflow-hidden">

            @if (isset($banners) && count($banners) > 0)
                <!-- Container Swiper dengan tinggi pasti (h-full) -->
                <div class="swiper bannerSwiper w-full h-full">
                    <div class="swiper-wrapper w-full h-full">

                        <!-- Loop Setiap Banner dari Database -->
                        @foreach ($banners as $banner)
                            <div class="swiper-slide relative w-full h-full flex items-center overflow-hidden">

                                <!-- 1. Gambar Latar Belakang -->
                                @if ($banner->image)
                                    <img src="{{ asset('storage/' . $banner->image) }}" alt="Banner HR Manufaktur"
                                        class="absolute inset-0 w-full h-full object-cover object-right z-0">
                                @endif

                                <!-- 2. Overlay Gradasi Biru Gelap -->
                                <div
                                    class="absolute inset-0 bg-gradient-to-r from-[#0d2137] via-[#0d2137]/80 to-transparent w-full md:w-3/5 z-10 pointer-events-none">
                                </div>

                                <!-- 3. Teks & Konten Utama -->
                                <div class="relative max-w-7xl mx-auto px-6 py-8 md:py-10 w-full z-20">
                                    <div class="max-w-2xl">

                                        <!-- Garis Hiasan Kuning -->
                                        <div class="w-12 h-1 bg-amber-400 mb-4 rounded-full"></div>

                                        @if ($banner->title)
                                            <h1
                                                class="banner-title mb-3 text-white drop-shadow-sm">
                                                @foreach ($banner->title_segments_for_display as $titleSegment)
                                                    @if ($titleSegment['color'])
                                                        <span
                                                            style="color: {{ $titleSegment['color'] }}">{{ $titleSegment['text'] }}</span>
                                                    @else
                                                        {{ $titleSegment['text'] }}
                                                    @endif
                                                @endforeach
                                            </h1>
                                        @endif

                                        @if ($banner->subtitle)
                                            <p
                                                class="banner-subtitle mb-6 text-slate-200 font-normal">
                                                {{ $banner->subtitle }}
                                            </p>
                                        @endif

                                        @if ($banner->wa_number || $banner->wa_button_text)
                                            <div
                                                class="bg-white/95 backdrop-blur-md p-2 rounded-2xl inline-flex flex-col sm:flex-row items-center gap-2 w-fit max-w-full shadow-xl">

                                                <div class="flex flex-wrap items-center gap-3">
                                                    @if ($banner->wa_number)
                                                        <div
                                                            class="banner-contact inline-flex min-h-14 items-center justify-center gap-2 text-center text-slate-800 font-bold bg-white/90 backdrop-blur px-5 py-3 rounded-xl shadow-sm border border-slate-200">
                                                            <i class="fa-solid fa-phone text-emerald-600"></i>
                                                            <span class="break-all">{{ $banner->wa_number }}</span>
                                                        </div>
                                                    @endif

                                                    <a href="https://wa.me/{{ $banner->wa_number }}?text={{ rawurlencode('Halo, saya ingin bertanya mengenai program training.') }}"
                                                        target="_blank"
                                                        class="banner-contact inline-flex min-h-14 max-w-full items-center justify-center gap-3 text-center bg-amber-500 hover:bg-amber-600 text-white font-bold px-7 py-3 rounded-xl shadow-md transition">
                                                        <i class="fa-brands fa-whatsapp text-xl"></i>
                                                        <span
                                                            class="break-words">{{ $banner->wa_button_text ?? 'Pesan training' }}</span>
                                                    </a>
                                                </div>
                                            </div>
                                        @endif

                                    </div>
                                </div>

                            </div>
                        @endforeach

                    </div>

                    <!-- Titik Indikator Navigasi (Pagination Dots) -->
                    <div class="swiper-pagination"></div>
                </div>
            @endif
        </section>

        <!-- Custom Styling untuk Swiper & Titik Indikator Warna Kuning -->
        <style>
            .bannerSwiper {
                width: 100%;
                height: 100%;
            }

            .bannerSwiper .swiper-slide,
            .bannerSwiper .banner-contact {
                font-family: "Montserrat", sans-serif;
            }

            .bannerSwiper .banner-title {
                font-size: clamp(2rem, 3.75vw, 3rem);
                font-family: "Montserrat", sans-serif;
                font-weight: 700;
                line-height: 1.12;
                letter-spacing: -0.025em;
            }

            .bannerSwiper .banner-subtitle {
                max-width: 38rem;
                font-size: clamp(1rem, 1.9vw, 1.5rem);
                line-height: 1.35;
            }

            .bannerSwiper .banner-contact {
                font-size: clamp(0.95rem, 1.35vw, 1.125rem);
                line-height: 1.25;
            }

            .swiper-slide {
                position: relative;
                display: flex;
                align-items: center;
            }

            .bannerSwiper .swiper-pagination {
                right: 1.5rem !important;
                bottom: 1rem !important;
                left: auto !important;
                display: flex !important;
                width: fit-content !important;
                align-items: center;
                gap: 0.5rem;
                padding: 0.5rem 0.75rem;
                border-radius: 9999px;
                background: rgb(2 6 23 / 35%);
                backdrop-filter: blur(4px);
                z-index: 30;
            }

            .bannerSwiper .swiper-pagination-bullet {
                margin: 0 !important;
            }

            .swiper-pagination-bullet-active {
                position: relative;
                width: 30px !important;
                border-radius: 9999px !important;
                background-color: rgb(255 255 255 / 40%) !important;
                overflow: hidden;
            }

            .swiper-pagination-bullet:not(.swiper-pagination-bullet-active) {
                background-color: #f59e0b !important;
                opacity: 0.8 !important;
            }

            .swiper-pagination-bullet-active::after {
                content: "";
                position: absolute;
                inset: 0;
                border-radius: inherit;
                background-color: #f59e0b;
                transform: scaleX(var(--banner-progress, 0));
                transform-origin: left;
            }
        </style>
        <!-- 2. SECTION LOGO MITRA -->
        @php
            $partnerLogoRows = $partners->values()->partition(fn ($partner, $index) => $index % 2 === 0);
            $partnerLogoRows = $partnerLogoRows->map(fn ($row) => $row->isEmpty() ? $partners : $row);
        @endphp
        <section class="partner-section border-b bg-white py-10 md:py-12">
            <div class="mx-auto grid max-w-7xl grid-cols-1 items-center gap-8 px-6 md:px-8 lg:grid-cols-5 lg:gap-0">
                <div class="flex items-center justify-center gap-5 text-left md:justify-start lg:col-span-2 lg:border-r lg:border-slate-200 lg:pr-8">
                    <i class="fa-solid fa-people-group shrink-0 text-[45px] text-blue-700 md:text-[54px]"
                        aria-hidden="true"></i>
                    <div>
                        <h2 class="text-2xl font-extrabold leading-tight text-slate-900">
                            50+ Perusahaan Mitra
                        </h2>
                        <p class="mt-1 max-w-sm text-2xl font-extrabold leading-tight text-slate-900">
                            <span class="block">Tumbuh bersama</span>
                            <span class="block whitespace-nowrap text-orange-500">HR Manufaktur Indonesia</span>
                        </p>
                    </div>
                </div>

                <div class="min-w-0 space-y-4 lg:col-span-3 lg:pl-8">
                    @if ($partners->isNotEmpty())
                        @foreach ($partnerLogoRows as $rowIndex => $rowPartners)
                        <div class="partner-marquee" role="group"
                            aria-label="Logo perusahaan mitra, baris {{ $rowIndex + 1 }}">
                            <div
                                class="partner-marquee-track {{ $rowIndex === 0 ? 'partner-marquee-right' : 'partner-marquee-left' }}">
                                @foreach ([false, true] as $isDuplicate)
                                    <div class="partner-marquee-group" @if ($isDuplicate) aria-hidden="true" @endif>
                                        @foreach ($rowPartners as $partner)
                                            <div class="partner-logo-item">
                                                <img src="{{ asset('storage/' . $partner->logo) }}"
                                                    alt="{{ $isDuplicate ? '' : $partner->name }}"
                                                    title="{{ $isDuplicate ? '' : $partner->name }}"
                                                    loading="lazy">
                                            </div>
                                        @endforeach
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        @endforeach
                    @else
                        <p class="py-8 text-center text-sm text-slate-400 md:text-left">Belum ada logo mitra.</p>
                    @endif
                </div>
            </div>
        </section>

        <style>
            .partner-marquee {
                overflow: hidden;
                mask-image: linear-gradient(to right, transparent, #000 5%, #000 95%, transparent);
            }

            .partner-marquee-track,
            .partner-marquee-group {
                display: flex;
                width: max-content;
                align-items: center;
            }

            .partner-marquee-track {
                will-change: transform;
            }

            .partner-marquee-group {
                flex: none;
                gap: 1rem;
                padding-right: 1rem;
            }

            .partner-marquee-right {
                animation: partner-marquee-right 32s linear infinite;
            }

            .partner-marquee-left {
                animation: partner-marquee-left 32s linear infinite;
            }

            .partner-marquee:hover .partner-marquee-track {
                animation-play-state: paused;
            }

            .partner-logo-item {
                display: flex;
                width: 8rem;
                height: 3.5rem;
                flex: none;
                align-items: center;
                justify-content: center;
                border-right: 1px solid rgb(226 232 240);
                padding-right: 1rem;
            }

            .partner-logo-item img {
                max-width: 100%;
                max-height: 2.5rem;
                object-fit: contain;
            }

            @keyframes partner-marquee-right {
                from {
                    transform: translateX(-50%);
                }

                to {
                    transform: translateX(0);
                }
            }

            @keyframes partner-marquee-left {
                from {
                    transform: translateX(0);
                }

                to {
                    transform: translateX(-50%);
                }
            }

            @media (prefers-reduced-motion: reduce) {
                .partner-marquee {
                    mask-image: none;
                }

                .partner-marquee-track {
                    animation: none;
                }

                .partner-marquee-group[aria-hidden="true"] {
                    display: none;
                }
            }
        </style>

        <!-- 3. SECTION FLYER TRAINING -->
        <section class="flyer-section bg-sky-50/70">
            <div
                class="mx-auto grid max-w-7xl grid-cols-1 items-center gap-8 px-6 pt-12 pb-0 md:px-8 lg:grid-cols-[48fr_52fr] lg:gap-0">
                <div class="lg:pr-6">
                    <h2 class="flyer-section-title whitespace-pre-line text-[2rem] font-extrabold leading-[1.15] tracking-tight text-slate-900 md:text-4xl">
                        @foreach ($flyerTitleSegments as $segment)
                            @if (preg_match('/\A#[0-9a-fA-F]{6}\z/', $segment['color'] ?? ''))
                                <span style="color: {{ $segment['color'] }}">{{ $segment['text'] }}</span>
                            @else
                                {{ $segment['text'] }}
                            @endif
                        @endforeach
                    </h2>
                    <p class="flyer-section-description mt-3 whitespace-pre-line text-lg leading-relaxed text-slate-600 md:text-xl">
                        @foreach ($flyerDescriptionSegments as $segment)
                            @if (preg_match('/\A#[0-9a-fA-F]{6}\z/', $segment['color'] ?? ''))
                                <span style="color: {{ $segment['color'] }}">{{ $segment['text'] }}</span>
                            @else
                                {{ $segment['text'] }}
                            @endif
                        @endforeach
                    </p>

                </div>

                <div class="min-w-0 lg:pl-8">
                    @if ($flyers->isNotEmpty())
                        <div class="swiper flyerSwiper overflow-hidden">
                            <div class="swiper-wrapper">
                                @foreach ($flyers as $flyer)
                                    <div class="swiper-slide">
                                        <button type="button"
                                            data-flyer-title="{{ $flyer->title ?? 'Flyer training' }}"
                                            data-flyer-image="{{ asset('storage/' . $flyer->full_image) }}"
                                            aria-label="Lihat flyer: {{ $flyer->title ?? 'Flyer training' }}"
                                            onclick="openFlyerModal(this)"
                                            class="group relative block w-full cursor-zoom-in overflow-hidden text-left focus:outline-none focus-visible:ring-2 focus-visible:ring-amber-400 focus-visible:ring-offset-4">
                                            <img src="{{ asset('storage/' . $flyer->thumbnail_image) }}"
                                                alt="{{ $flyer->title ?? 'Flyer training' }}"
                                                class="h-[460px] w-full object-contain md:h-[520px]" loading="lazy">
                                            <span
                                                class="pointer-events-none absolute left-1/2 top-1/2 flex max-w-[85%] -translate-x-1/2 -translate-y-1/2 items-center justify-center gap-1.5 rounded-full bg-slate-950/80 px-3 py-2 text-center text-xs font-semibold text-white opacity-0 shadow-lg transition-opacity duration-200 group-hover:opacity-100 group-focus-visible:opacity-100">
                                                <i class="fa-solid fa-magnifying-glass-plus" aria-hidden="true"></i>
                                                {{ $flyer->title ?? 'Lihat flyer' }}
                                            </span>
                                        </button>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        @if ($flyers->count() > 1)
                            <div class="mt-4 flex items-center justify-center gap-3">
                                <button type="button"
                                    class="flyer-button-prev flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-white text-slate-700 shadow transition hover:bg-slate-100"
                                    aria-label="Flyer sebelumnya">
                                    <i class="fa-solid fa-chevron-left" aria-hidden="true"></i>
                                </button>
                                <div class="flyer-progress-track h-2 w-11 overflow-hidden rounded-full bg-amber-100"
                                    role="progressbar" aria-label="Waktu menuju flyer berikutnya" aria-valuemin="0"
                                    aria-valuemax="100" aria-valuenow="0">
                                    <div class="flyer-progress-bar h-full w-full origin-left scale-x-0 rounded-full bg-amber-400"></div>
                                </div>
                                <button type="button"
                                    class="flyer-button-next flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-amber-400 text-white shadow transition hover:bg-amber-500"
                                    aria-label="Flyer berikutnya">
                                    <i class="fa-solid fa-chevron-right" aria-hidden="true"></i>
                                </button>
                            </div>
                        @endif
                    @else
                        <p class="rounded-xl border border-sky-100 bg-white/70 p-8 text-center text-sm text-slate-500">
                            Flyer training akan segera ditampilkan.
                        </p>
                    @endif
                </div>
            </div>
        </section>

        <style>
            .flyer-section-title,
            .flyer-section-description {
                font-family: "Montserrat", sans-serif;
            }

            .flyerSwiper .swiper-slide {
                width: 70%;
            }

            .flyerSwiper .swiper-slide button {
                transform: scale(0.88);
                opacity: 0.78;
                transition: transform 500ms ease, opacity 500ms ease;
            }

            .flyerSwiper .swiper-slide-active button {
                transform: scale(1);
                opacity: 1;
            }

            @media (min-width: 640px) {
                .flyerSwiper .swiper-slide {
                    width: 58%;
                }
            }

            @media (min-width: 1024px) {
                .flyerSwiper .swiper-slide {
                    width: 52%;
                }
            }

            .syllabus-pagination .swiper-pagination-bullet {
                width: 0.75rem;
                height: 0.375rem;
                margin: 0 !important;
                border-radius: 9999px;
                background: #cbd5e1;
                opacity: 1;
                transition: width 200ms ease, background-color 200ms ease;
            }

            .syllabus-pagination .swiper-pagination-bullet-active {
                width: 1.5rem;
                background: #f59e0b;
            }

            .flyer-hover-dismissed > span {
                opacity: 0 !important;
                transform: translateY(0.5rem) !important;
            }
        </style>

        <!-- 4. SECTION BUKU SILABUS -->
        <section class="bg-white pt-4 pb-4">
            <div class="max-w-7xl mx-auto px-4 md:px-8">
                <div class="mb-3 flex flex-col gap-4 md:flex-row md:items-center md:justify-between md:gap-8">
                    <div class="shrink-0 md:max-w-[43%]">
                        <div class="mb-2 h-1 w-10 rounded-full bg-amber-400"></div>
                        <h2 class="text-3xl font-extrabold leading-tight text-slate-900 md:text-4xl">
                            Silabus Training HR<br class="hidden md:block"> Manufaktur Indonesia
                        </h2>
                    </div>
                    <div class="flex flex-col gap-4 sm:flex-row sm:items-center md:max-w-[55%] md:border-l md:border-slate-200 md:pl-8">
                        <p class="text-sm leading-relaxed text-slate-600 md:text-base">
                            Buku-buku acuan untuk training-training HR Manufaktur Indonesia.
                        </p>
                        @if ($banners->isNotEmpty() && $banners->first()->wa_number)
                            <a href="https://wa.me/{{ $banners->first()->wa_number }}?text={{ rawurlencode('Halo, saya ingin bertanya mengenai program training.') }}"
                                target="_blank" rel="noopener noreferrer"
                                class="inline-flex shrink-0 items-center justify-center gap-2 rounded-full bg-blue-800 px-5 py-3 text-sm font-bold text-white transition hover:bg-blue-900">
                                Request Training
                                <i class="fa-solid fa-arrow-right" aria-hidden="true"></i>
                            </a>
                        @endif
                    </div>
                </div>
                @if ($syllabi->isNotEmpty())
                    <div class="swiper syllabusSwiper overflow-hidden py-0">
                        <div class="swiper-wrapper items-center {{ $syllabi->count() === 1 ? 'justify-center' : '' }}">
                            @foreach ($syllabi as $syllabus)
                                <div class="swiper-slide !h-24 !w-40 sm:!h-28 sm:!w-[181px]">
                                    <button type="button"
                                        class="mx-auto flex h-full w-full justify-center focus:outline-none focus-visible:ring-2 focus-visible:ring-amber-400 focus-visible:ring-offset-4"
                                        data-syllabus-title="{{ $syllabus->title }}"
                                        data-syllabus-image="{{ asset('storage/' . $syllabus->cover_image) }}"
                                        data-syllabus-description="{{ $syllabus->long_description }}"
                                        aria-label="Baca silabus: {{ $syllabus->title }}"
                                        onclick="openSyllabusModal(this)">
                                        <span class="relative block h-full w-full overflow-hidden">
                                        <img src="{{ asset('storage/' . $syllabus->cover_image) }}"
                                            alt="{{ $syllabus->title }}"
                                            class="absolute left-1/2 top-0 h-56 w-auto max-w-[75vw] -translate-x-1/2 -translate-y-[28%] object-contain sm:h-64"
                                            loading="lazy">
                                        </span>
                                    </button>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    @if ($syllabi->count() > 1)
                        <div class="mt-2 flex items-center justify-center gap-4">
                            <button type="button"
                                class="syllabus-button-prev flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-white text-slate-700 shadow transition hover:bg-slate-100"
                                aria-label="Silabus sebelumnya">
                                <i class="fa-solid fa-chevron-left" aria-hidden="true"></i>
                            </button>
                            <div class="syllabus-pagination flex items-center justify-center gap-2"></div>
                            <button type="button"
                                class="syllabus-button-next flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-amber-400 text-white shadow transition hover:bg-amber-500"
                                aria-label="Silabus berikutnya">
                                <i class="fa-solid fa-chevron-right" aria-hidden="true"></i>
                            </button>
                        </div>
                    @endif
                @endif
            </div>
        </section>

        <!-- 5. SECTION HISTORI TRAINING -->
        <section class="py-16 max-w-7xl mx-auto px-4">
            <h2 class="text-2xl font-bold mb-6">Histori & Dokumentasi Training</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                @foreach ($histories as $history)
                    <div class="bg-white rounded-xl shadow p-5 border flex flex-col md:flex-row gap-4">
                        <img src="{{ asset('storage/' . $history->documentation_image) }}"
                            class="w-full md:w-44 h-32 object-cover rounded-lg">
                        <div>
                            <span class="text-xs text-slate-400">{{ $history->date }}</span>
                            <h3 class="font-bold text-lg mb-2">{{ $history->title }}</h3>
                            <p class="text-xs text-slate-600 leading-relaxed">{{ $history->content }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </section>
    </main>

    <div id="flyerModal"
        class="fixed inset-0 z-50 hidden items-center justify-center bg-sky-100/90 p-3 backdrop-blur-sm sm:p-4"
        role="dialog" aria-modal="true" aria-label="Pratinjau flyer" tabindex="-1"
        onclick="if (event.target === this) closeFlyerModal(event)">
        <div class="relative flex max-h-[calc(100dvh-1.5rem)] w-fit max-w-full flex-col overflow-hidden rounded-2xl bg-white shadow-2xl sm:max-h-[calc(100dvh-2rem)]">
            <div class="flex shrink-0 items-center justify-center gap-3 border-b border-sky-100 px-4 py-3 text-slate-800 sm:gap-4 sm:px-6">
                <button type="button" onclick="closeFlyerModal(event)"
                    class="inline-flex items-center gap-2 rounded-full bg-amber-400 px-3 py-2 text-xs font-semibold text-white transition hover:bg-amber-500 sm:px-4 sm:text-sm"
                    aria-label="Kembali ke landing page">
                    <i class="fa-solid fa-arrow-left" aria-hidden="true"></i>
                    <span>Kembali</span>
                </button>
            </div>
            <div class="flex min-h-0 items-center justify-center p-2 sm:p-3">
                <img id="flyerModalImage" src="" alt=""
                    class="block h-auto max-h-[calc(100dvh-6rem)] w-auto max-w-[calc(100vw-2rem)] object-contain">
            </div>
        </div>
    </div>

    <div id="syllabusModal"
        class="fixed inset-0 z-50 hidden items-center justify-center bg-sky-100/90 p-4 backdrop-blur-sm"
        role="dialog" aria-modal="true" aria-label="Detail buku silabus" tabindex="-1"
        onclick="if (event.target === this) closeSyllabusModal(event)">
        <div class="relative flex max-h-[calc(100dvh-2rem)] w-full max-w-3xl flex-col overflow-hidden rounded-2xl bg-white shadow-2xl sm:flex-row">
            <div class="flex shrink-0 items-center justify-end border-b border-sky-100 p-3 sm:absolute sm:right-0 sm:top-0 sm:z-10 sm:border-0">
                <button type="button" onclick="closeSyllabusModal(event)"
                    class="inline-flex items-center gap-2 rounded-full bg-amber-400 px-4 py-2 text-sm font-semibold text-white transition hover:bg-amber-500"
                    aria-label="Kembali ke daftar silabus">
                    <i class="fa-solid fa-arrow-left" aria-hidden="true"></i>
                    <span>Kembali</span>
                </button>
            </div>
            <div class="flex min-h-0 items-center justify-center bg-sky-50 p-5 sm:w-2/5 sm:p-8">
                <img id="syllabusModalImage" src="" alt=""
                    class="max-h-[35vh] max-w-full object-contain sm:max-h-[calc(100dvh-8rem)]">
            </div>
            <div class="min-h-0 overflow-y-auto p-5 sm:w-3/5 sm:p-8 sm:pt-16">
                <h3 id="syllabusModalTitle" class="mb-3 pr-20 text-xl font-extrabold text-slate-900 sm:text-2xl"></h3>
                <p id="syllabusModalDescription" class="whitespace-pre-line text-sm leading-relaxed text-slate-600 sm:text-base"></p>
            </div>
        </div>
    </div>

    <!-- CDN Swiper JS -->
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

    <!-- Script Custom -->
    <script>
        let activeSyllabusTrigger = null;

        function openSyllabusModal(button) {
            activeSyllabusTrigger = button;
            document.getElementById('syllabusModalTitle').textContent = button.dataset.syllabusTitle;
            document.getElementById('syllabusModalImage').src = button.dataset.syllabusImage;
            document.getElementById('syllabusModalImage').alt = button.dataset.syllabusTitle;
            document.getElementById('syllabusModalDescription').textContent = button.dataset.syllabusDescription;
            document.getElementById('syllabusModal').classList.remove('hidden');
            document.getElementById('syllabusModal').classList.add('flex');
            document.body.classList.add('overflow-hidden');
            document.querySelector('#syllabusModal [aria-label="Kembali ke daftar silabus"]').focus();
        }

        function closeSyllabusModal(event = null) {
            const modal = document.getElementById('syllabusModal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            document.getElementById('syllabusModalImage').src = '';
            document.body.classList.remove('overflow-hidden');

            if (event?.type === 'click' && event.detail > 0) {
                activeSyllabusTrigger?.blur();
            } else {
                activeSyllabusTrigger?.focus();
            }

            activeSyllabusTrigger = null;
        }

        document.addEventListener('keydown', event => {
            if (event.key === 'Escape' && !document.getElementById('syllabusModal').classList.contains('hidden')) {
                closeSyllabusModal(event);
            }
        });

        let activeFlyerTrigger = null;

        function clearFlyerHoverDismissed(eventOrTrigger) {
            const trigger = eventOrTrigger.currentTarget || eventOrTrigger;

            trigger.classList.remove('flyer-hover-dismissed');
            trigger.removeEventListener('pointerenter', clearFlyerHoverDismissed);
            trigger.removeEventListener('pointerleave', clearFlyerHoverDismissed);
            trigger.removeEventListener('blur', clearFlyerHoverDismissed);
        }

        function openFlyerModal(button) {
            const modal = document.getElementById('flyerModal');
            const image = document.getElementById('flyerModalImage');
            const title = button.dataset.flyerTitle;

            activeFlyerTrigger = button;
            clearFlyerHoverDismissed(button);
            image.src = button.dataset.flyerImage;
            image.alt = title;
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            document.body.classList.add('overflow-hidden');
            modal.querySelector('button[aria-label="Kembali ke landing page"]').focus();
        }

        function closeFlyerModal(event = null) {
            const modal = document.getElementById('flyerModal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            document.getElementById('flyerModalImage').src = '';
            document.body.classList.remove('overflow-hidden');
            const wasClosedByPointer = event?.type === 'click' && event.detail > 0;

            if (activeFlyerTrigger) {
                const trigger = activeFlyerTrigger;

                trigger.classList.add('flyer-hover-dismissed');
                trigger.addEventListener('pointerenter', clearFlyerHoverDismissed);
                trigger.addEventListener('pointerleave', clearFlyerHoverDismissed);
                trigger.addEventListener('blur', clearFlyerHoverDismissed);

                if (wasClosedByPointer) {
                    trigger.blur();
                } else {
                    trigger.focus();
                }
            }

            activeFlyerTrigger = null;
        }

        document.addEventListener('keydown', event => {
            if (event.key === 'Escape' && !document.getElementById('flyerModal').classList.contains('hidden')) {
                closeFlyerModal(event);
            }
        });

        document.addEventListener('DOMContentLoaded', function() {
            var swiper = new Swiper(".bannerSwiper", {
                loop: true,
                autoplay: {
                    delay: 5000, // Coba set 5 detik dulu supaya cepat terlihat
                    disableOnInteraction: false,
                },
                pagination: {
                    el: ".swiper-pagination",
                    clickable: true,
                },
                on: {
                    autoplayTimeLeft(swiper, timeLeft, percentage) {
                        const activeBullet = swiper.pagination.el.querySelector(
                            ".swiper-pagination-bullet-active"
                        );

                        if (activeBullet) {
                            activeBullet.style.setProperty("--banner-progress", 1 - percentage);
                        }
                    },
                },
                speed: 800,
            });

            const flyerSwiperElement = document.querySelector('.flyerSwiper');
            if (flyerSwiperElement) {
                new Swiper(flyerSwiperElement, {
                    loop: {{ $flyers->count() > 1 ? 'true' : 'false' }},
                    slidesPerView: 'auto',
                    centeredSlides: true,
                    spaceBetween: 12,
                    autoplay: {{ $flyers->count() > 1 ? '{ delay: 10000, disableOnInteraction: false, pauseOnMouseEnter: true }' : 'false' }},
                    navigation: {
                        nextEl: '.flyer-button-next',
                        prevEl: '.flyer-button-prev',
                    },
                    on: {
                        autoplayTimeLeft(swiper, timeLeft, percentage) {
                            const progress = flyerSwiperElement.parentElement.querySelector('.flyer-progress-bar');
                            const progressTrack = flyerSwiperElement.parentElement.querySelector('.flyer-progress-track');
                            const elapsed = Math.round((1 - percentage) * 100);

                            if (progress && progressTrack) {
                                progress.style.transform = `scaleX(${1 - percentage})`;
                                progressTrack.setAttribute('aria-valuenow', String(elapsed));
                            }
                        },
                    },
                    speed: 700,
                });
            }

            const syllabusSwiperElement = document.querySelector('.syllabusSwiper');
            if (syllabusSwiperElement && {{ $syllabi->count() > 1 ? 'true' : 'false' }}) {
                new Swiper(syllabusSwiperElement, {
                    loop: true,
                    slidesPerView: 'auto',
                    spaceBetween: 20,
                    navigation: {
                        nextEl: '.syllabus-button-next',
                        prevEl: '.syllabus-button-prev',
                    },
                    pagination: {
                        el: '.syllabus-pagination',
                        clickable: true,
                    },
                    speed: 600,
                });
            }
        });
    </script>
</body>

</html>
