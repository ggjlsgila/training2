<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $flyer->title ?? 'Flyer Training' }} - HR Manufaktur Indonesia</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body class="min-h-screen bg-slate-950 text-white">
    <header class="sticky top-0 z-10 flex items-center justify-between gap-4 border-b border-white/10 bg-slate-950/95 px-4 py-3 backdrop-blur sm:px-6">
        <a href="{{ route('landing.public') }}"
            class="inline-flex items-center gap-2 rounded-lg bg-white/10 px-4 py-2 text-sm font-bold transition hover:bg-white/20">
            <i class="fa-solid fa-arrow-left" aria-hidden="true"></i>
            Kembali ke Landing Page
        </a>
        <h1 class="truncate text-right text-sm font-semibold text-slate-200 sm:text-base">
            {{ $flyer->title ?? 'Flyer Training' }}
        </h1>
    </header>

    <main class="flex min-h-[calc(100vh-4rem)] items-center justify-center p-4 sm:p-8">
        <img src="{{ asset('storage/' . $flyer->full_image) }}"
            alt="{{ $flyer->title ?? 'Flyer training' }}"
            class="max-h-[calc(100vh-7rem)] max-w-full object-contain">
    </main>
</body>

</html>
