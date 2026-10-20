<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Kelola 5 Section Landing Page</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script>
        function initializeTitleEditor(container) {
            const input = container.querySelector('[data-title-input]');
            const colorInput = container.querySelector('[data-title-color]');
            const applyButton = container.querySelector('[data-apply-title-color]');
            const clearButton = container.querySelector('[data-clear-title-color]');
            const hiddenFields = container.querySelector('[data-title-segments]');
            const preview = container.querySelector('[data-title-preview]');
            let segments = [{
                text: input.value,
                color: null
            }];
            let selectionStart = 0;
            let selectionEnd = 0;

            if (hiddenFields.value) {
                try {
                    const initialSegments = JSON.parse(hiddenFields.value);
                    if (Array.isArray(initialSegments) &&
                        initialSegments.every(segment => segment && typeof segment.text === 'string') &&
                        initialSegments.map(segment => segment.text).join('') === input.value) {
                        segments = initialSegments.map(segment => ({
                            text: segment.text,
                            color: /^#[0-9a-fA-F]{6}$/.test(segment.color || '') ? segment.color : null,
                        }));
                    }
                } catch (error) {
                    if (!(error instanceof SyntaxError)) {
                        throw error;
                    }
                }
            }

            function render() {
                hiddenFields.value = JSON.stringify(segments);
                preview.replaceChildren();

                segments.forEach(segment => {
                    const previewText = document.createElement('span');
                    previewText.textContent = segment.text;
                    if (/^#[0-9a-fA-F]{6}$/.test(segment.color || '')) {
                        previewText.style.color = segment.color;
                    }
                    preview.append(previewText);
                });
            }

            function setSegments(nextSegments) {
                const normalizedSegments = nextSegments.map(segment => ({
                    text: String(segment.text ?? ''),
                    color: /^#[0-9a-fA-F]{6}$/.test(segment.color || '') ? segment.color : null,
                }));

                if (normalizedSegments.map(segment => segment.text).join('') !== input.value) {
                    segments = [{
                        text: input.value,
                        color: null
                    }];
                } else {
                    segments = normalizedSegments;
                }

                render();
            }

            function rememberSelection() {
                selectionStart = input.selectionStart;
                selectionEnd = input.selectionEnd;
            }

            function applyColor(color = colorInput.value) {
                rememberSelection();
                if (selectionStart === selectionEnd) {
                    return;
                }

                const characters = input.value.split('');
                const colors = [];
                let offset = 0;

                segments.forEach(segment => {
                    for (let index = 0; index < segment.text.length; index++) {
                        colors[offset++] = segment.color;
                    }
                });

                for (let index = selectionStart; index < selectionEnd; index++) {
                    colors[index] = color;
                }

                const nextSegments = [];
                characters.forEach((character, index) => {
                    const color = colors[index] || null;
                    const previousSegment = nextSegments[nextSegments.length - 1];

                    if (previousSegment && previousSegment.color === color) {
                        previousSegment.text += character;
                    } else {
                        nextSegments.push({
                            text: character,
                            color
                        });
                    }
                });

                setSegments(nextSegments);
                input.focus();
                input.setSelectionRange(selectionStart, selectionEnd);
            }

            input.addEventListener('input', () => {
                setSegments([{
                    text: input.value,
                    color: null
                }]);
                rememberSelection();
            });
            input.addEventListener('select', rememberSelection);
            input.addEventListener('mouseup', rememberSelection);
            input.addEventListener('keyup', rememberSelection);
            colorInput.addEventListener('mousedown', rememberSelection);
            applyButton.addEventListener('mousedown', rememberSelection);
            clearButton.addEventListener('mousedown', rememberSelection);
            applyButton.addEventListener('click', () => applyColor());
            clearButton.addEventListener('click', () => applyColor(null));

            render();

            return {
                setSegments,
            };
        }
    </script>
</head>

<body class="bg-slate-100 text-slate-800 font-sans">

    <div class="p-6 max-w-7xl mx-auto">
        <!-- Header & Top Navigation -->
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-2xl font-bold text-slate-900">Panel Kelola Landing Page</h1>
                <p class="text-xs text-slate-500">Kelola 5 section utama website dari 5 tab di bawah ini</p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('landing.public') }}" target="_blank"
                    class="bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2 rounded-lg text-sm font-semibold flex items-center gap-2 shadow">
                    <i class="fa-solid fa-globe"></i> Lihat Website
                </a>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit"
                        class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg text-sm font-semibold shadow">Logout</button>
                </form>
            </div>
        </div>

        <nav aria-label="Navigasi section landing page"
            class="flex gap-2 overflow-x-auto rounded-xl border border-slate-200 bg-white p-2 shadow-sm mb-6">
            @php
                $tabs = [
                    ['key' => 'banner', 'label' => 'Banner', 'icon' => 'fa-images'],
                    ['key' => 'partner', 'label' => 'Mitra', 'icon' => 'fa-handshake'],
                    ['key' => 'flyer', 'label' => 'Flyer', 'icon' => 'fa-file-image'],
                    ['key' => 'syllabus', 'label' => 'Silabus', 'icon' => 'fa-book-open'],
                    ['key' => 'history', 'label' => 'Histori Training', 'icon' => 'fa-clock-rotate-left'],
                ];
            @endphp
            @foreach ($tabs as $index => $tab)
                <a href="{{ route('admin.landing.index', ['tab' => $tab['key']]) }}"
                    @if ($activeTab === $tab['key']) aria-current="page" @endif
                    class="flex shrink-0 items-center gap-2 rounded-lg px-4 py-2.5 text-sm font-semibold transition
                        {{ $activeTab === $tab['key'] ? 'bg-blue-600 text-white shadow' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                    <span
                        class="flex h-6 w-6 items-center justify-center rounded-full text-xs {{ $activeTab === $tab['key'] ? 'bg-white/20 text-white' : 'bg-slate-100 text-slate-500' }}">
                        {{ $index + 1 }}
                    </span>
                    <i class="fa-solid {{ $tab['icon'] }}" aria-hidden="true"></i>
                    <span>{{ $tab['label'] }}</span>
                </a>
            @endforeach
        </nav>

        @if (session('success'))
            <div
                class="bg-green-100 border border-green-300 text-green-700 p-3 rounded-lg mb-6 shadow-sm flex items-center gap-2">
                <i class="fa-solid fa-circle-check"></i> {{ session('success') }}
            </div>
        @endif
        <!-- TAB 1: KELOLA BANNERS (ADMIN) -->
        @if ($activeTab == 'banner')
            <div class="space-y-6">

                <!-- DAFTAR BANNER YANG SUDAH DIUNGGAH -->
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200">
                    <h3 class="text-sm font-extrabold text-slate-900 mb-4">Daftar Banner Aktif ({{ $banners->count() }})
                    </h3>

                    <div class="space-y-3">
                        @if ($banners->count() > 0)
                            @foreach ($banners as $banner)
                                <div
                                    class="p-3 bg-slate-50 border border-slate-200 rounded-xl flex items-center justify-between gap-4">
                                    <div class="flex items-center gap-3 overflow-hidden">
                                        <img src="{{ asset('storage/' . $banner->image) }}"
                                            class="h-12 w-20 object-cover rounded-lg border shadow-sm flex-shrink-0 cursor-pointer"
                                            onclick="openImageModal('{{ asset('storage/' . $banner->image) }}')">
                                        <div class="truncate">
                                            <p class="text-xs font-bold text-slate-800 truncate">{{ $banner->title }}
                                            </p>
                                            <p class="text-[10px] text-slate-500 truncate">
                                                {{ $banner->subtitle ?? 'Tidak ada sub-judul' }}</p>
                                        </div>
                                    </div>

                                    <div class="flex items-center gap-2 flex-shrink-0">
                                        <!-- Tombol Lihat (Preview Gambar) -->
                                        <!-- Tombol Lihat (Memicu Modal Pop-up Rupa Banner) -->
                                        <button type="button"
                                            data-title="{{ $banner->title }}"
                                            data-subtitle="{{ $banner->subtitle ?? 'Tidak ada sub-judul' }}"
                                            data-image-url="{{ asset('storage/' . $banner->image) }}"
                                            data-title-segments="{{ json_encode($banner->title_segments_for_display) }}"
                                            onclick="openViewModal(this)"
                                            class="px-3 py-1.5 bg-slate-200 hover:bg-slate-300 text-slate-700 text-xs font-bold rounded-lg transition cursor-pointer flex items-center gap-1">
                                            <i class="fa-solid fa-eye"></i> Lihat
                                        </button>

                                        <!-- Tombol Edit (Pop-up Modal Edit) -->
                                        <button type="button"
                                            data-id="{{ $banner->id }}"
                                            data-title="{{ $banner->title }}"
                                            data-subtitle="{{ $banner->subtitle }}"
                                            data-image-url="{{ asset('storage/' . $banner->image) }}"
                                            data-title-segments="{{ json_encode($banner->title_segments_for_display) }}"
                                            data-wa-number="{{ $banner->wa_number }}"
                                            data-wa-button-text="{{ $banner->wa_button_text }}"
                                            onclick="openEditModal(this)"
                                            class="px-3 py-1.5 bg-amber-500 hover:bg-amber-600 text-white text-xs font-bold rounded-lg transition cursor-pointer">
                                            <i class="fa-solid fa-pen-to-square"></i> Edit
                                        </button>

                                        <!-- Tombol Hapus -->
                                        <form action="{{ route('admin.landing.destroy_banner', $banner->id) }}"
                                            method="POST"
                                            onsubmit="return confirm('Apakah Anda yakin ingin menghapus banner ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="px-3 py-1.5 bg-rose-500 hover:bg-rose-600 text-white text-xs font-bold rounded-lg transition cursor-pointer">
                                                <i class="fa-solid fa-trash"></i> Hapus
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <p class="text-xs text-slate-400 text-center py-4">Belum ada banner yang ditambahkan.</p>
                        @endif
                    </div>
                </div>

                <div class="flex justify-center">
                    <button id="openAddBannerButton" type="button" onclick="openAddBannerModal()"
                        class="inline-flex items-center gap-2 rounded-xl bg-blue-600 px-6 py-3 text-sm font-bold text-white shadow transition hover:bg-blue-700">
                        <i class="fa-solid fa-plus"></i>
                        Tambah Banner
                    </button>
                </div>

                <!-- MODAL TAMBAH BANNER -->
                <div id="addBannerModal"
                    class="fixed inset-0 {{ old('_banner_form') === 'create' ? 'flex' : 'hidden' }} items-center justify-center overflow-y-auto bg-slate-950/80 p-4 backdrop-blur-sm z-50"
                    role="dialog" aria-modal="true" aria-labelledby="addBannerModalTitle" tabindex="-1"
                    onclick="if (event.target === this) closeAddBannerModal()">
                    <div class="my-auto w-full max-w-3xl overflow-hidden rounded-2xl bg-white shadow-2xl">
                        <div class="flex items-center justify-between bg-slate-900 p-5 text-white">
                            <div>
                                <h2 id="addBannerModalTitle" class="text-lg font-extrabold">Tambah Banner Baru</h2>
                                <p class="mt-1 text-xs text-slate-300">Unggah gambar dan atur konten banner slider.</p>
                            </div>
                            <button type="button" onclick="closeAddBannerModal()"
                                class="rounded-lg p-2 text-slate-300 transition hover:bg-white/10 hover:text-white"
                                aria-label="Tutup form tambah banner">
                                <i class="fa-solid fa-xmark text-lg"></i>
                            </button>
                        </div>

                        <form action="{{ route('admin.landing.store_banner') }}" method="POST"
                            enctype="multipart/form-data" class="max-h-[80vh] space-y-5 overflow-y-auto p-6">
                            @csrf
                            <input type="hidden" name="_banner_form" value="create">

                            @if ($errors->any() && old('_banner_form') === 'create')
                                <div role="alert"
                                    class="rounded-xl border border-rose-200 bg-rose-50 p-4 text-sm text-rose-700">
                                    <p class="mb-2 font-bold">Banner belum tersimpan:</p>
                                    <ul class="list-inside list-disc space-y-1">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                                <div data-title-editor>
                                    <label class="mb-1.5 block text-xs font-bold text-slate-700">Judul Utama</label>
                                    <textarea name="title" rows="2" maxlength="255" required
                                        placeholder="Tingkatkan Keterampilan..." data-title-input
                                        class="w-full rounded-xl border border-slate-300 p-3 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('title') }}</textarea>
                                    <div class="mt-2 flex flex-wrap items-center gap-2">
                                        <input type="color" value="#fbbf24" aria-label="Pilih warna judul"
                                            data-title-color
                                            class="h-9 w-12 cursor-pointer rounded border border-slate-300 p-1">
                                        <button type="button" data-apply-title-color
                                            class="rounded-lg bg-slate-800 px-3 py-2 text-xs font-bold text-white transition hover:bg-slate-700">
                                            Warnai teks terpilih
                                        </button>
                                        <button type="button" data-clear-title-color
                                            class="rounded-lg border border-slate-300 px-3 py-2 text-xs font-bold text-slate-700 transition hover:bg-slate-100">
                                            Hapus warna
                                        </button>
                                    </div>
                                    <p class="mt-1 text-[11px] text-slate-500">Blok kata atau frasa di judul, pilih
                                        warna, lalu klik “Warnai teks terpilih”.</p>
                                    <input type="hidden" name="title_segments_json" data-title-segments>
                                    <p class="mt-2 text-xs font-semibold text-slate-600">Pratinjau:
                                        <span data-title-preview class="font-bold"></span>
                                    </p>
                                </div>

                                <div>
                                    <label class="mb-1.5 block text-xs font-bold text-slate-700">File Gambar
                                        Banner</label>
                                    <input type="file" name="image" accept="image/*" required
                                        class="w-full rounded-xl border border-slate-300 bg-slate-50 p-2 text-xs">
                                    <p class="mt-1 text-[11px] text-slate-500">Format JPG, PNG, atau WEBP; ukuran
                                        maksimal 2 MB.</p>
                                </div>
                            </div>

                            <div>
                                <label class="mb-1.5 block text-xs font-bold text-slate-700">Sub-Judul /
                                    Deskripsi</label>
                                <textarea name="subtitle" rows="2" placeholder="HR Manufaktur berkomitmen..."
                                    class="w-full rounded-xl border border-slate-300 p-3 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('subtitle') }}</textarea>
                            </div>

                            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                                <div>
                                    <label class="mb-1.5 block text-xs font-bold text-slate-700">Nomor WhatsApp</label>
                                    <input type="text" name="wa_number" value="{{ old('wa_number') }}"
                                        placeholder="628123456789"
                                        class="w-full rounded-xl border border-slate-300 p-3 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                                </div>
                                <div>
                                    <label class="mb-1.5 block text-xs font-bold text-slate-700">Teks Tombol
                                        WhatsApp</label>
                                    <input type="text" name="wa_button_text"
                                        value="{{ old('wa_button_text', 'Pesan training') }}"
                                        placeholder="Pesan training"
                                        class="w-full rounded-xl border border-slate-300 p-3 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                                </div>
                            </div>

                            <div class="flex justify-end gap-2 border-t border-slate-100 pt-4">
                                <button type="button" onclick="closeAddBannerModal()"
                                    class="rounded-xl bg-slate-100 px-5 py-2.5 text-sm font-bold text-slate-700 transition hover:bg-slate-200">
                                    Batal
                                </button>
                                <button type="submit"
                                    class="rounded-xl bg-blue-600 px-6 py-2.5 text-sm font-bold text-white shadow transition hover:bg-blue-700">
                                    <i class="fa-solid fa-plus mr-1"></i> Simpan Banner
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- MODAL POP-UP LIHAT BANNER -->
            <div id="viewBannerModal"
                class="fixed inset-0 bg-slate-950/80 backdrop-blur-sm hidden items-center justify-center p-4 z-50">
                <div class="bg-white max-w-xl w-full rounded-2xl overflow-hidden shadow-2xl relative">
                    <div class="p-4 bg-slate-900 text-white flex justify-between items-center">
                        <h3 class="text-sm font-bold">Pratonton Banner</h3>
                        <button type="button" onclick="closeViewModal()"
                            class="text-slate-400 hover:text-white cursor-pointer">
                            <i class="fa-solid fa-xmark text-lg"></i>
                        </button>
                    </div>

                    <div class="p-6 space-y-4">
                        <!-- Gambar Banner -->
                        <div class="rounded-xl overflow-hidden border shadow-sm bg-slate-950 flex justify-center">
                            <img id="view_image" src="" class="max-h-60 object-contain">
                        </div>

                        <!-- Judul Utama -->
                        <div>
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Judul
                                Utama</span>
                            <p id="view_title" class="text-sm font-extrabold text-slate-900 mt-0.5"></p>
                        </div>

                        <!-- Sub-Judul / Deskripsi -->
                        <div>
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Sub-Judul /
                                Deskripsi</span>
                            <p id="view_subtitle" class="text-xs text-slate-600 mt-0.5"></p>
                        </div>

                        <div class="flex justify-end pt-2">
                            <button type="button" onclick="closeViewModal()"
                                class="px-5 py-2 bg-slate-200 hover:bg-slate-300 text-slate-700 text-xs font-bold rounded-xl transition cursor-pointer">
                                Tutup
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- MODAL POP-UP EDIT BANNER -->
            <div id="editBannerModal"
                class="fixed inset-0 bg-slate-950/80 backdrop-blur-sm hidden items-center justify-center p-4 z-50">
                <div class="bg-white max-w-xl w-full rounded-2xl overflow-hidden shadow-2xl relative">
                    <div class="p-4 bg-slate-900 text-white flex justify-between items-center">
                        <h3 class="text-sm font-bold">Edit Banner</h3>
                        <button type="button" onclick="closeEditModal()"
                            class="text-slate-400 hover:text-white cursor-pointer">
                            <i class="fa-solid fa-xmark text-lg"></i>
                        </button>
                    </div>

                    <form id="editBannerForm" method="POST" enctype="multipart/form-data" class="p-6 space-y-4">
                        @csrf
                        @method('PUT')

                        <div data-title-editor>
                            <label class="block font-bold text-xs text-slate-700 mb-1.5">Judul Utama</label>
                            <textarea id="edit_title" name="title" rows="2" maxlength="255" required
                                data-title-input
                                class="w-full border border-slate-300 rounded-xl p-3 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
                            <div class="mt-2 flex items-center gap-2">
                                <input type="color" value="#fbbf24" aria-label="Pilih warna judul"
                                    data-title-color class="h-9 w-12 cursor-pointer rounded border border-slate-300 p-1">
                                <button type="button" data-apply-title-color
                                    class="rounded-lg bg-slate-800 px-3 py-2 text-xs font-bold text-white transition hover:bg-slate-700">
                                    Warnai teks terpilih
                                </button>
                                <button type="button" data-clear-title-color
                                    class="rounded-lg border border-slate-300 px-3 py-2 text-xs font-bold text-slate-700 transition hover:bg-slate-100">
                                    Hapus warna
                                </button>
                            </div>
                            <p class="mt-1 text-[11px] text-slate-500">Blok kata atau frasa di judul, pilih warna,
                                lalu klik “Warnai teks terpilih”.</p>
                            <input type="hidden" name="title_segments_json" data-title-segments>
                            <p class="mt-2 text-xs font-semibold text-slate-600">Pratinjau:
                                <span data-title-preview class="font-bold"></span>
                            </p>
                        </div>

                        <div>
                            <label class="block font-bold text-xs text-slate-700 mb-1.5">Gambar Banner Saat Ini</label>
                            <div class="mb-2">
                                <img id="edit_current_image" src=""
                                    class="h-20 w-32 object-cover rounded-lg border shadow-sm">
                            </div>
                            <label class="block font-bold text-xs text-slate-700 mb-1.5">Ganti Gambar
                                (Opsional)</label>
                            <input type="file" name="image" accept="image/*"
                                class="w-full border border-slate-300 rounded-xl p-2 text-xs bg-slate-50">
                        </div>

                        <div>
                            <label class="block font-bold text-xs text-slate-700 mb-1.5">Sub-Judul / Deskripsi</label>
                            <textarea id="edit_subtitle" name="subtitle" rows="3"
                                class="w-full border border-slate-300 rounded-xl p-3 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block font-bold text-xs text-slate-700 mb-1.5">Nomor WhatsApp</label>
                                <input type="text" id="edit_wa_number" name="wa_number"
                                    placeholder="628123456789"
                                    class="w-full border border-slate-300 rounded-xl p-3 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>
                            <div>
                                <label class="block font-bold text-xs text-slate-700 mb-1.5">Teks Tombol WhatsApp</label>
                                <input type="text" id="edit_wa_button_text" name="wa_button_text"
                                    placeholder="Pesan training"
                                    class="w-full border border-slate-300 rounded-xl p-3 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>
                        </div>

                        <div class="flex justify-end gap-2 pt-2">
                            <button type="button" onclick="closeEditModal()"
                                class="px-4 py-2 bg-slate-200 hover:bg-slate-300 text-slate-700 text-xs font-bold rounded-xl transition cursor-pointer">
                                Batal
                            </button>
                            <button type="submit"
                                class="px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold rounded-xl transition shadow cursor-pointer">
                                Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- JAVASCRIPT MODAL -->
            <script>
                initializeTitleEditor(document.querySelector('[data-title-editor]'));
                const editTitleEditor = initializeTitleEditor(
                    document.querySelector('#editBannerForm [data-title-editor]')
                );

                // Modal Lihat Banner
                function openAddBannerModal() {
                    const modal = document.getElementById('addBannerModal');
                    modal.classList.remove('hidden');
                    modal.classList.add('flex');
                    modal.querySelector('[data-title-input]').focus();
                }

                function closeAddBannerModal() {
                    const modal = document.getElementById('addBannerModal');
                    modal.classList.add('hidden');
                    modal.classList.remove('flex');
                    document.getElementById('openAddBannerButton').focus();
                }

                document.addEventListener('keydown', event => {
                    if (event.key === 'Escape' && !document.getElementById('addBannerModal').classList.contains('hidden')) {
                        closeAddBannerModal();
                    }
                });

                function openViewModal(button) {
                    const modal = document.getElementById('viewBannerModal');
                    const { title, subtitle, imageUrl, titleSegments } = button.dataset;

                    const titleElement = document.getElementById('view_title');
                    titleElement.replaceChildren();
                    JSON.parse(titleSegments).forEach(segment => {
                        const titlePart = document.createElement('span');
                        titlePart.textContent = segment.text;
                        if (/^#[0-9a-fA-F]{6}$/.test(segment.color || '')) {
                            titlePart.style.color = segment.color;
                        }
                        titleElement.append(titlePart);
                    });
                    document.getElementById('view_subtitle').innerText = subtitle;
                    document.getElementById('view_image').src = imageUrl;

                    modal.classList.remove('hidden');
                    modal.classList.add('flex');
                }

                function closeViewModal() {
                    const modal = document.getElementById('viewBannerModal');
                    modal.classList.add('hidden');
                    modal.classList.remove('flex');
                }

                function openEditModal(button) {
                    const modal = document.getElementById('editBannerModal');
                    const form = document.getElementById('editBannerForm');
                    const { id, title, subtitle, imageUrl, titleSegments, waNumber, waButtonText } = button.dataset;

                    // Sesuaikan URL route update controller Anda
                    form.action = `/admin/landing-page/banner/${id}`;

                    document.getElementById('edit_title').value = title;
                    editTitleEditor.setSegments(JSON.parse(titleSegments));
                    document.getElementById('edit_subtitle').value = (subtitle && subtitle !== 'null') ? subtitle : '';

                    document.getElementById('edit_wa_number').value = waNumber || '';
                    document.getElementById('edit_wa_button_text').value = waButtonText || 'Pesan training';

                    document.getElementById('edit_current_image').src = imageUrl;

                    modal.classList.remove('hidden');
                    modal.classList.add('flex');
                }

                function closeEditModal() {
                    const modal = document.getElementById('editBannerModal');
                    modal.classList.add('hidden');
                    modal.classList.remove('flex');
                }
            </script>
        @endif
        <!-- CONTENT TAB 2: PARTNER LOGO -->
        @if ($activeTab == 'partner')
            <div class="bg-white
                                p-6 rounded-xl shadow-sm">
                <h2 class="text-lg font-bold mb-4 border-b pb-2 text-slate-800">2. Kelola Logo Mitra
                </h2>
                <form action="{{ route('admin.landing.store_partner') }}" method="POST"
                    enctype="multipart/form-data"
                    class="flex flex-wrap gap-4 items-end bg-slate-50 p-4 rounded-lg border mb-6">
                    @csrf
                    <div>
                        <label class="block text-xs font-semibold mb-1">Nama Perusahaan
                            (Opsional)</label>
                        <input type="text" name="name" placeholder="PT Contoh Indonesia"
                            class="border rounded-lg p-2 text-sm bg-white">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold mb-1">File Logo Mitra</label>
                        <input type="file" name="logo" required
                            class="border rounded-lg p-1.5 text-xs bg-white">
                    </div>
                    <button type="submit"
                        class="bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2 rounded-lg font-bold text-sm shadow">+
                        Tambah Logo</button>
                </form>

                <div class="grid grid-cols-2 md:grid-cols-6 gap-4">
                    @foreach ($partners as $partner)
                        <div class="border rounded-lg p-3 bg-white text-center shadow-sm relative group">
                            <img src="{{ asset('storage/' . $partner->logo) }}"
                                class="h-12 mx-auto object-contain mb-2">
                            <p class="text-xs font-semibold truncate">{{ $partner->name ?? 'Mitra' }}
                            </p>
                            <form action="{{ route('admin.landing.destroy_partner', $partner->id) }}" method="POST"
                                onsubmit="return confirm('Hapus logo mitra ini?')" class="mt-2">
                                @csrf @method('DELETE')
                                <button class="bg-red-500 text-white px-2 py-1 rounded text-xs w-full">Hapus</button>
                            </form>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- CONTENT TAB 3: FLYER -->
        @if ($activeTab == 'flyer')
            @php
                $flyerTitleText = implode('', array_column($flyerTitleSegments, 'text'));
                $flyerDescriptionText = implode('', array_column($flyerDescriptionSegments, 'text'));
            @endphp
            <div class="bg-white p-6 rounded-xl shadow-sm">
                <h2 class="text-lg font-bold mb-4 border-b pb-2 text-slate-800">3. Kelola Flyer Training</h2>
                <form action="{{ route('admin.landing.update_flyer_section') }}" method="POST"
                    class="mb-6 space-y-5 rounded-lg border bg-slate-50 p-4">
                    @csrf
                    @method('PUT')
                    <h3 class="text-sm font-bold text-slate-800">Teks Section Flyer</h3>
                    @if ($errors->any())
                        <div role="alert" class="rounded-lg border border-rose-200 bg-rose-50 p-3 text-sm text-rose-700">
                            <ul class="list-inside list-disc space-y-1">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                        <div data-title-editor>
                            <label class="mb-1.5 block text-xs font-bold text-slate-700">Judul Utama</label>
                            <textarea name="flyer_section_title" rows="2" maxlength="255" required data-title-input
                                class="w-full rounded-xl border border-slate-300 bg-white p-3 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('flyer_section_title', $flyerTitleText) }}</textarea>
                            <div class="mt-2 flex flex-wrap items-center gap-2">
                                <input type="color" value="#fbbf24" aria-label="Pilih warna judul flyer"
                                    data-title-color class="h-9 w-12 cursor-pointer rounded border border-slate-300 p-1">
                                <button type="button" data-apply-title-color
                                    class="rounded-lg bg-slate-800 px-3 py-2 text-xs font-bold text-white transition hover:bg-slate-700">
                                    Warnai teks terpilih
                                </button>
                                <button type="button" data-clear-title-color
                                    class="rounded-lg border border-slate-300 px-3 py-2 text-xs font-bold text-slate-700 transition hover:bg-slate-100">
                                    Hapus warna
                                </button>
                            </div>
                            <input type="hidden" name="flyer_title_segments_json" data-title-segments
                                value="{{ old('flyer_title_segments_json', json_encode($flyerTitleSegments)) }}">
                            <p class="mt-2 text-xs font-semibold text-slate-600">Pratinjau:
                                <span data-title-preview class="font-bold"></span>
                            </p>
                        </div>
                        <div data-title-editor>
                            <label class="mb-1.5 block text-xs font-bold text-slate-700">Deskripsi</label>
                            <textarea name="flyer_section_description" rows="3" maxlength="2000" required data-title-input
                                class="w-full rounded-xl border border-slate-300 bg-white p-3 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('flyer_section_description', $flyerDescriptionText) }}</textarea>
                            <div class="mt-2 flex flex-wrap items-center gap-2">
                                <input type="color" value="#fbbf24" aria-label="Pilih warna deskripsi flyer"
                                    data-title-color class="h-9 w-12 cursor-pointer rounded border border-slate-300 p-1">
                                <button type="button" data-apply-title-color
                                    class="rounded-lg bg-slate-800 px-3 py-2 text-xs font-bold text-white transition hover:bg-slate-700">
                                    Warnai teks terpilih
                                </button>
                                <button type="button" data-clear-title-color
                                    class="rounded-lg border border-slate-300 px-3 py-2 text-xs font-bold text-slate-700 transition hover:bg-slate-100">
                                    Hapus warna
                                </button>
                            </div>
                            <input type="hidden" name="flyer_description_segments_json" data-title-segments
                                value="{{ old('flyer_description_segments_json', json_encode($flyerDescriptionSegments)) }}">
                            <p class="mt-2 text-xs font-semibold text-slate-600">Pratinjau:
                                <span data-title-preview class="font-bold"></span>
                            </p>
                        </div>
                    </div>
                    <div class="flex justify-end">
                        <button type="submit"
                            class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-bold text-white shadow transition hover:bg-blue-700">
                            Simpan Teks Flyer
                        </button>
                    </div>
                </form>

                <form action="{{ route('admin.landing.store_flyer') }}" method="POST" enctype="multipart/form-data"
                    class="grid grid-cols-1 md:grid-cols-3 gap-4 bg-slate-50 p-4 rounded-lg border mb-6">
                    @csrf
                    <div>
                        <label class="block text-xs font-semibold mb-1">Judul Flyer</label>
                        <input type="text" name="title" required
                            class="w-full border rounded-lg p-2 text-sm bg-white">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold mb-1">Gambar Flyer Berjalan (Thumbnail Slide)</label>
                        <input type="file" name="thumbnail_image" required
                            class="w-full border rounded-lg p-1.5 text-xs bg-white">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold mb-1">Gambar Flyer Full Resolution (Tampilan
                            Jelas)</label>
                        <input type="file" name="full_image" required
                            class="w-full border rounded-lg p-1.5 text-xs bg-white">
                    </div>
                    <div class="col-span-1 md:col-span-3">
                        <button type="submit"
                            class="bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2 rounded-lg font-bold text-sm shadow">+
                            Tambah Flyer</button>
                    </div>
                </form>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    @foreach ($flyers as $flyer)
                        <div class="border rounded-xl p-3 bg-white shadow-sm">
                            <img src="{{ asset('storage/' . $flyer->thumbnail_image) }}"
                                class="w-full h-40 object-cover rounded mb-2">
                            <h3 class="font-bold text-sm mb-1">{{ $flyer->title }}</h3>
                            <form action="{{ route('admin.landing.destroy_flyer', $flyer->id) }}" method="POST"
                                onsubmit="return confirm('Hapus flyer ini?')">
                                @csrf @method('DELETE')
                                <button
                                    class="bg-red-500 text-white px-3 py-1 rounded text-xs w-full font-semibold">Hapus
                                    Flyer</button>
                            </form>
                        </div>
                    @endforeach
                </div>
            </div>
            <script>
                document.querySelectorAll('[data-title-editor]').forEach(initializeTitleEditor);
            </script>
        @endif

        <!-- CONTENT TAB 4: SYLLABUS -->
        @if ($activeTab == 'syllabus')
            <div class="bg-white p-6 rounded-xl shadow-sm">
                <h2 class="text-lg font-bold mb-4 border-b pb-2 text-slate-800">4. Kelola Buku Silabus</h2>
                <form action="{{ route('admin.landing.store_syllabus') }}" method="POST"
                    enctype="multipart/form-data" class="bg-slate-50 p-4 rounded-lg border mb-6 space-y-3">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold mb-1">Judul Silabus</label>
                            <input type="text" name="title" required
                                class="w-full border rounded-lg p-2 text-sm bg-white">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold mb-1">Gambar Sampul Buku</label>
                            <input type="file" name="cover_image" required
                                class="w-full border rounded-lg p-1.5 text-xs bg-white">
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold mb-1">Teks Pendek (Ditampilkan saat kursor diarahkan
                            / Hover)</label>
                        <input type="text" name="short_description" required
                            class="w-full border rounded-lg p-2 text-sm bg-white">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold mb-1">Teks Panjang (Ringkasan Lengkap di Jendela
                            Pop-up Baru)</label>
                        <textarea name="long_description" rows="3" required class="w-full border rounded-lg p-2 text-sm bg-white"></textarea>
                    </div>
                    <button type="submit"
                        class="bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2 rounded-lg font-bold text-sm shadow">+
                        Simpan Buku Silabus</button>
                </form>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    @foreach ($syllabi as $item)
                        <div class="border rounded-xl p-4 bg-white shadow-sm flex flex-col justify-between">
                            <div>
                                <img src="{{ asset('storage/' . $item->cover_image) }}"
                                    class="w-full h-48 object-cover rounded mb-3">
                                <h3 class="font-bold text-base mb-1">{{ $item->title }}</h3>
                                <p class="text-xs text-slate-500 mb-2"><strong>Hover:</strong>
                                    {{ $item->short_description }}</p>
                                <p class="text-xs text-slate-600 bg-slate-50 p-2 rounded border">
                                    <strong>Ringkasan:</strong> {{ Str::limit($item->long_description, 100) }}
                                </p>
                            </div>
                            <form action="{{ route('admin.landing.destroy_syllabus', $item->id) }}" method="POST"
                                onsubmit="return confirm('Hapus silabus ini?')" class="mt-4">
                                @csrf @method('DELETE')
                                <button
                                    class="bg-red-500 text-white px-3 py-1 rounded text-xs w-full font-semibold">Hapus</button>
                            </form>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- CONTENT TAB 5: TRAINING HISTORY -->
        @if ($activeTab == 'history')
            <div class="bg-white p-6 rounded-xl shadow-sm">
                <h2 class="text-lg font-bold mb-4 border-b pb-2 text-slate-800">5. Kelola Histori Training & Artikel
                </h2>
                <form action="{{ route('admin.landing.store_history') }}" method="POST"
                    enctype="multipart/form-data" class="bg-slate-50 p-4 rounded-lg border mb-6 space-y-3">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="md:col-span-2">
                            <label class="block text-xs font-semibold mb-1">Judul Artikel / Kegiatan Training</label>
                            <input type="text" name="title" required
                                class="w-full border rounded-lg p-2 text-sm bg-white">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold mb-1">Foto Dokumentasi Kegiatan</label>
                            <input type="file" name="documentation_image" required
                                class="w-full border rounded-lg p-1.5 text-xs bg-white">
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold mb-1">Isi Artikel Process Training</label>
                        <textarea name="content" rows="4" required class="w-full border rounded-lg p-2 text-sm bg-white"></textarea>
                    </div>
                    <button type="submit"
                        class="bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2 rounded-lg font-bold text-sm shadow">+
                        Simpan Histori Training</button>
                </form>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach ($histories as $history)
                        <div class="border rounded-xl p-4 bg-white shadow-sm flex gap-4">
                            <img src="{{ asset('storage/' . $history->documentation_image) }}"
                                class="w-32 h-24 object-cover rounded">
                            <div class="flex-1">
                                <h3 class="font-bold text-sm mb-1">{{ $history->title }}</h3>
                                <p class="text-xs text-slate-500 mb-2 line-clamp-2">{{ $history->content }}</p>
                                <form action="{{ route('admin.landing.destroy_history', $history->id) }}"
                                    method="POST" onsubmit="return confirm('Hapus histori ini?')">
                                    @csrf @method('DELETE')
                                    <button
                                        class="bg-red-500 text-white px-2 py-1 rounded text-xs font-semibold">Hapus</button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

    </div>

</body>

</html>
