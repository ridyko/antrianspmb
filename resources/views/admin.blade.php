@extends('layouts.app')

@section('title', 'Panel Admin - Sistem Antrean PPDB/SPMB')

@section('styles')
<style>
    body {
        background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
        color: #f8fafc;
        min-height: 100vh;
        display: flex;
        flex-direction: column;
    }

    /* Darken glass panels on this page for high contrast */
    .glass-panel {
        background: rgba(30, 41, 59, 0.85) !important;
        border: 1px solid rgba(255, 255, 255, 0.15) !important;
        box-shadow: 0 12px 40px rgba(0, 0, 0, 0.3) !important;
    }

    /* Navbar */
    .navbar {
        background: rgba(30, 41, 59, 0.7);
        backdrop-filter: blur(12px);
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        padding: 15px 30px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        box-shadow: 0 4px 20px rgba(0,0,0,0.15);
    }

    .nav-brand {
        display: flex;
        align-items: center;
        gap: 12px;
        font-weight: 800;
        font-size: 1.25rem;
        color: #ffffff;
    }

    .nav-brand svg {
        width: 35px;
        height: 38px;
    }

    .btn-nav-home {
        background: rgba(255, 255, 255, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.2);
        color: #ffffff;
        padding: 8px 16px;
        border-radius: 6px;
        font-size: 0.85rem;
        font-weight: 600;
        cursor: pointer;
        text-decoration: none;
        transition: var(--transition-smooth);
    }

    .btn-nav-home:hover {
        background: rgba(255, 255, 255, 0.2);
    }

    /* Admin Container */
    .admin-container {
        flex: 1;
        max-width: 1100px;
        width: 100%;
        margin: 40px auto;
        padding: 0 20px;
    }

    /* Tabs Header */
    .tabs-header {
        display: flex;
        gap: 10px;
        margin-bottom: 24px;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        padding-bottom: 10px;
    }

    .tab-btn {
        background: none;
        border: none;
        color: #94a3b8;
        padding: 10px 20px;
        font-size: 1rem;
        font-weight: 600;
        cursor: pointer;
        border-radius: 6px;
        transition: var(--transition-smooth);
    }

    .tab-btn:hover {
        color: #ffffff;
        background: rgba(255, 255, 255, 0.05);
    }

    .tab-btn.active {
        color: #ffffff;
        background: #00875a;
        box-shadow: 0 4px 12px rgba(0, 135, 90, 0.25);
    }

    /* Tab Content Panel */
    .tab-content {
        display: none;
        padding: 30px;
    }

    .tab-content.active {
        display: block;
    }

    /* Form Fields */
    .form-group {
        margin-bottom: 20px;
        display: flex;
        flex-direction: column;
        gap: 8px;
    }

    .form-group label {
        font-size: 0.95rem;
        font-weight: 700;
        color: #f1f5f9;
        letter-spacing: 0.5px;
    }

    .form-control {
        background: rgba(15, 23, 42, 0.6);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 8px;
        padding: 12px 16px;
        color: #ffffff;
        font-family: var(--font-outfit);
        font-size: 0.95rem;
        width: 100%;
        transition: var(--transition-smooth);
    }

    .form-control:focus {
        border-color: #00875a;
        outline: none;
        box-shadow: 0 0 0 3px rgba(0, 135, 90, 0.2);
    }

    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
    }

    .btn-submit {
        background: #00875a;
        color: #ffffff;
        border: none;
        padding: 14px 28px;
        font-size: 1rem;
        font-weight: 700;
        border-radius: 8px;
        cursor: pointer;
        transition: var(--transition-smooth);
        box-shadow: 0 4px 12px rgba(0, 135, 90, 0.2);
    }

    .btn-submit:hover {
        background: #00a86b;
        transform: translateY(-1px);
        box-shadow: 0 6px 16px rgba(0, 168, 107, 0.3);
    }

    /* Table Styles */
    .counter-table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 25px;
        text-align: left;
    }

    .counter-table th {
        background: rgba(15, 23, 42, 0.4);
        padding: 12px 16px;
        font-size: 0.85rem;
        font-weight: 700;
        text-transform: uppercase;
        color: #94a3b8;
        border-bottom: 2px solid rgba(255, 255, 255, 0.1);
    }

    .counter-table td {
        padding: 14px 16px;
        border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        font-size: 0.95rem;
    }

    .btn-danger {
        background: #ef4444;
        color: #ffffff;
        border: none;
        padding: 8px 16px;
        border-radius: 6px;
        font-weight: 600;
        cursor: pointer;
        transition: var(--transition-smooth);
    }

    .btn-danger:hover {
        background: #f87171;
    }

    /* Warning reset panel */
    .danger-zone-card {
        border: 1px solid rgba(239, 68, 68, 0.3);
        background: rgba(239, 68, 68, 0.05);
        border-radius: 12px;
        padding: 24px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .danger-zone-text h4 {
        color: #ef4444;
        font-size: 1.1rem;
        font-weight: 700;
        margin-bottom: 4px;
    }

    .danger-zone-text p {
        color: #94a3b8;
        font-size: 0.85rem;
    }

    /* Slideshow Upload Gallery Styling */
    .slideshow-gallery {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(160px, 1fr));
        gap: 15px;
        margin-top: 10px;
    }
    
    .slide-item {
        position: relative;
        border-radius: 8px;
        overflow: hidden;
        aspect-ratio: 16/9;
        border: 1px solid rgba(255, 255, 255, 0.15);
        background: rgba(0, 0, 0, 0.3);
    }
    
    .slide-item img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    
    .btn-delete-slide {
        position: absolute;
        top: 5px;
        right: 5px;
        background: #ef4444;
        color: #ffffff;
        border: none;
        width: 22px;
        height: 22px;
        border-radius: 50%;
        font-size: 1.1rem;
        font-weight: 700;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.3);
        transition: var(--transition-smooth);
        line-height: 1;
    }
    
    .btn-delete-slide:hover {
        background: #f87171;
        transform: scale(1.1);
    }
    
    .upload-trigger-card {
        border: 2px dashed rgba(255, 255, 255, 0.2);
        border-radius: 8px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        aspect-ratio: 16/9;
        cursor: pointer;
        transition: var(--transition-smooth);
        gap: 5px;
        color: #94a3b8;
        font-weight: 600;
        font-size: 0.8rem;
    }
    
    .upload-trigger-card:hover {
        border-color: #00875a;
        color: #ffffff;
        background: rgba(0, 135, 90, 0.05);
    }
    
    .upload-trigger-card svg {
        width: 28px;
        height: 28px;
        fill: currentColor;
    }
</style>
@endsection

@section('content')
<!-- Navbar -->
<div class="navbar">
    <div class="nav-brand">
        @if(!empty($settings['header_logo']))
            <img src="{{ asset($settings['header_logo']) }}" style="height: 40px; width: auto; object-fit: contain; border-radius: 4px;">
        @else
            <!-- Minimalist DKI SVG -->
            <svg viewBox="0 0 100 110" xmlns="http://www.w3.org/2000/svg">
                <path d="M50,5 L85,17 L85,60 C85,85 50,100 50,100 C50,100 15,85 15,60 L15,17 L50,5 Z" fill="#fbc02d" />
                <path d="M50,10 L80,21 L80,58 C80,80 50,94 50,94 C50,94 20,80 20,58 L20,21 L50,10 Z" fill="#002d62" />
                <path d="M50,13 L77,23 L77,56 C77,77 50,90 50,90 C50,90 23,77 23,56 L23,23 L50,13 Z" fill="#ffffff" />
                <path d="M38,72 L62,72 L58,63 L42,63 Z" fill="#e0e0e0" stroke="#002d62" stroke-width="1.5" />
                <rect x="47" y="38" width="6" height="25" fill="#ffffff" stroke="#002d62" stroke-width="1.5" />
                <path d="M50,24 C53,24 55,28 50,38 C45,28 47,24 50,24 Z" fill="#fbc02d" />
            </svg>
        @endif
        <span>Panel Admin - PPDB / SPMB</span>
    </div>
    <a href="{{ route('display.index') }}" target="_blank" class="btn-nav-home">Buka Monitor TV</a>
</div>

<!-- Admin Container -->
<div class="admin-container">
    <!-- Tabs Header -->
    <div class="tabs-header">
        <button class="tab-btn active" onclick="switchTab('settings')">Pengaturan Umum</button>
        <button class="tab-btn" onclick="switchTab('counters')">Kelola Loket</button>
        <button class="tab-btn" onclick="switchTab('reset')">Reset Antrean</button>
    </div>

    <!-- 1. General Settings Tab -->
    <div id="tabSettings" class="glass-panel tab-content active">
        <form id="settingsForm" enctype="multipart/form-data">
            <div class="form-group">
                <label for="header_logo">Logo Posko (Header)</label>
                <div style="display: flex; align-items: center; gap: 15px; margin-top: 5px;">
                    <div id="logoPreviewContainer" style="width: 70px; height: 70px; display: flex; align-items: center; justify-content: center; background: rgba(0,0,0,0.2); border-radius: 6px; border: 1px solid rgba(255,255,255,0.15); overflow: hidden;">
                        @if(!empty($settings['header_logo']))
                            <img id="logoPreview" src="{{ asset($settings['header_logo']) }}" style="width: 100%; height: 100%; object-fit: contain;">
                        @else
                            <div id="logoPreviewPlaceholder" style="font-size: 0.75rem; color: #94a3b8; text-align: center;">Default DKI</div>
                            <img id="logoPreview" style="width: 100%; height: 100%; object-fit: contain; display: none;">
                        @endif
                    </div>
                    <div style="display: flex; flex-direction: column; gap: 8px;">
                        <input type="file" id="header_logo" name="header_logo" accept="image/*" style="font-size: 0.85rem; color: #94a3b8;">
                        <input type="hidden" id="delete_logo" name="delete_logo" value="0">
                        @if(!empty($settings['header_logo']))
                            <button type="button" id="btnDeleteLogo" class="btn-danger" style="padding: 5px 10px; font-size: 0.75rem; width: fit-content;">Hapus Logo</button>
                        @else
                            <button type="button" id="btnDeleteLogo" class="btn-danger" style="padding: 5px 10px; font-size: 0.75rem; width: fit-content; display: none;">Hapus Logo</button>
                        @endif
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label for="header_title">Judul Posko (Header)</label>
                <input type="text" id="header_title" name="header_title" class="form-control" value="{{ $settings['header_title'] ?? '' }}">
            </div>
            
            <div class="form-group">
                <label for="header_subtitle">Sub Judul (Header)</label>
                <input type="text" id="header_subtitle" name="header_subtitle" class="form-control" value="{{ $settings['header_subtitle'] ?? '' }}">
            </div>

            <div class="form-group">
                <label for="header_address">Alamat Kantor/Posko</label>
                <input type="text" id="header_address" name="header_address" class="form-control" value="{{ $settings['header_address'] ?? '' }}">
            </div>

            <div class="form-group">
                <label for="marquee_text">Teks Berjalan (Footer Marquee)</label>
                <textarea id="marquee_text" name="marquee_text" class="form-control" rows="2">{{ $settings['marquee_text'] ?? '' }}</textarea>
            </div>

            <div class="form-group">
                <label for="static_text">Teks Statis (Di Bawah Header)</label>
                <textarea id="static_text" name="static_text" class="form-control" rows="2">{{ $settings['static_text'] ?? '' }}</textarea>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="media_type">Tipe Media Tampilan</label>
                    <select id="media_type" name="media_type" class="form-control" onchange="toggleMediaFields()">
                        <option value="video" {{ ($settings['media_type'] ?? '') === 'video' ? 'selected' : '' }}>Video YouTube (Embed)</option>
                        <option value="slideshow" {{ ($settings['media_type'] ?? '') === 'slideshow' ? 'selected' : '' }}>Slideshow Gambar</option>
                    </select>
                </div>
                
                <div class="form-group" id="videoField">
                    <label for="video_url">URL Video YouTube</label>
                    <input type="text" id="video_url" name="video_url" class="form-control" value="{{ $settings['video_url'] ?? '' }}" placeholder="https://www.youtube.com/watch?v=5N42v3k6qQk">
                </div>
            </div>

            <div class="form-group" id="slideshowField" style="display: none;">
                <label>Galeri Gambar Slideshow (Unggah Minimal 3, Maksimal 9)</label>
                
                <!-- Hidden file input for dynamic uploads -->
                <input type="file" id="slideshow_file_input" style="display: none;" accept="image/*">
                
                <div class="slideshow-gallery" id="slideshowGallery">
                    @php
                        $slideArr = json_decode($settings['slideshow_images'] ?? '[]');
                    @endphp
                    @foreach($slideArr as $url)
                        @php
                            $resolvedUrl = (strpos($url, 'http://') === 0 || strpos($url, 'https://') === 0) ? $url : asset($url);
                        @endphp
                        <div class="slide-item">
                            <img src="{{ $resolvedUrl }}">
                            <button type="button" class="btn-delete-slide" onclick="deleteSlide('{{ $url }}')">&times;</button>
                        </div>
                    @endforeach
                    
                    @if(count($slideArr) < 9)
                        <div id="uploadTrigger" class="upload-trigger-card" onclick="document.getElementById('slideshow_file_input').click()">
                            <svg viewBox="0 0 24 24">
                                <path d="M19,13H13V19H11V13H5V11H11V5H13V11H19V13Z" />
                            </svg>
                            <span>Unggah Gambar</span>
                            <small style="opacity: 0.7;">({{ count($slideArr) }}/9)</small>
                        </div>
                    @endif
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="speech_rate">Kecepatan Suara Panggilan (Rate)</label>
                    <input type="range" id="speech_rate" name="speech_rate" min="0.5" max="1.5" step="0.1" class="form-control" value="{{ $settings['speech_rate'] ?? '1.0' }}">
                    <span id="rateVal" style="font-size: 0.8rem; color:#94a3b8;">1.0</span>
                </div>
                
                <div class="form-group">
                    <label for="speech_pitch">Tinggi Nada Suara Panggilan (Pitch)</label>
                    <input type="range" id="speech_pitch" name="speech_pitch" min="0.5" max="1.5" step="0.1" class="form-control" value="{{ $settings['speech_pitch'] ?? '1.0' }}">
                    <span id="pitchVal" style="font-size: 0.8rem; color:#94a3b8;">1.0</span>
                </div>
            </div>

            <div style="margin-top: 10px;">
                <button type="submit" class="btn-submit">Simpan Pengaturan</button>
            </div>
        </form>
    </div>

    <!-- 2. Manage Counters Tab -->
    <div id="tabCounters" class="glass-panel tab-content">
        <h3>Daftar Loket Aktif</h3>
        <p style="color: #94a3b8; font-size: 0.85rem; margin-bottom: 20px;">Menambah atau menghapus loket yang terhubung ke sistem panggilan antrean.</p>
        
        <table class="counter-table">
            <thead>
                <tr>
                    <th>Nama Loket</th>
                    <th>Nama Ruangan / Destinasi</th>
                    <th>Urutan Tampil</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody id="counterTableBody">
                @foreach($counters as $c)
                <tr id="counterRow{{ $c->id }}">
                    <td><strong>{{ $c->name }}</strong></td>
                    <td>{{ $c->room }}</td>
                    <td>{{ $c->sort_order }}</td>
                    <td>
                        <button class="btn-danger" onclick="deleteCounter({{ $c->id }})">Hapus</button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <hr style="border-color: rgba(255,255,255,0.1); margin-bottom: 25px;">

        <h3>Tambah Loket Baru</h3>
        <form id="counterForm" style="margin-top: 15px;">
            <div class="form-row">
                <div class="form-group">
                    <label for="counter_name">Nama Loket</label>
                    <input type="text" id="counter_name" name="name" class="form-control" placeholder="Contoh: LOKET 5" required>
                </div>
                
                <div class="form-group">
                    <label for="counter_room">Nama Ruangan / Destinasi</label>
                    <input type="text" id="counter_room" name="room" class="form-control" placeholder="Contoh: LOKET 5" required>
                </div>
            </div>
            
            <div class="form-group">
                <label for="counter_order">Urutan Tampil (Angka)</label>
                <input type="number" id="counter_order" name="sort_order" class="form-control" value="{{ $counters->count() + 1 }}" required>
            </div>

            <button type="submit" class="btn-submit">Tambah Loket</button>
        </form>
    </div>

    <!-- 3. Reset Tab -->
    <div id="tabReset" class="glass-panel tab-content">
        <h3>Reset Antrean Harian</h3>
        <p style="color: #94a3b8; font-size: 0.85rem; margin-bottom: 25px;">Digunakan pada pagi hari untuk memulai antrean dari nomor 1 atau membersihkan riwayat antrean.</p>
        
        <div class="danger-zone-card">
            <div class="danger-zone-text">
                <h4>Hapus Seluruh Antrean Hari Ini</h4>
                <p>Tindakan ini akan menghapus semua nomor antrean yang mengantre, riwayat panggilan, dan mereset loket kembali ke nomor 1.</p>
            </div>
            <button class="btn-danger" style="padding: 12px 24px;" onclick="confirmReset()">Reset Sekarang</button>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Tab switching
    function switchTab(tabId) {
        document.querySelectorAll('.tab-btn').forEach(btn => btn.classList.remove('active'));
        document.querySelectorAll('.tab-content').forEach(content => content.classList.remove('active'));
        
        event.target.classList.add('active');
        if (tabId === 'settings') document.getElementById('tabSettings').classList.add('active');
        if (tabId === 'counters') document.getElementById('tabCounters').classList.add('active');
        if (tabId === 'reset') document.getElementById('tabReset').classList.add('active');
    }

    // Toggle fields based on media type selected
    function toggleMediaFields() {
        const type = document.getElementById('media_type').value;
        const videoField = document.getElementById('videoField');
        const slideshowField = document.getElementById('slideshowField');
        
        if (type === 'video') {
            videoField.style.display = 'block';
            slideshowField.style.display = 'none';
        } else {
            videoField.style.display = 'none';
            slideshowField.style.display = 'block';
        }
    }
    toggleMediaFields();

    // Slider value labels
    const rateSlider = document.getElementById('speech_rate');
    const pitchSlider = document.getElementById('speech_pitch');
    if (rateSlider) {
        rateSlider.addEventListener('input', (e) => document.getElementById('rateVal').textContent = e.target.value);
    }
    if (pitchSlider) {
        pitchSlider.addEventListener('input', (e) => document.getElementById('pitchVal').textContent = e.target.value);
    }

    // Logo preview and deletion scripts
    const logoInput = document.getElementById('header_logo');
    const logoPreview = document.getElementById('logoPreview');
    const logoPlaceholder = document.getElementById('logoPreviewPlaceholder');
    const deleteLogoInput = document.getElementById('delete_logo');
    const btnDeleteLogo = document.getElementById('btnDeleteLogo');

    if (logoInput) {
        logoInput.addEventListener('change', () => {
            if (logoInput.files && logoInput.files[0]) {
                const reader = new FileReader();
                reader.onload = (e) => {
                    if (logoPreview) {
                        logoPreview.src = e.target.result;
                        logoPreview.style.display = 'block';
                    }
                    if (logoPlaceholder) {
                        logoPlaceholder.style.display = 'none';
                    }
                    if (deleteLogoInput) {
                        deleteLogoInput.value = '0';
                    }
                    if (btnDeleteLogo) {
                        btnDeleteLogo.style.display = 'block';
                    }
                };
                reader.readAsDataURL(logoInput.files[0]);
            }
        });
    }

    if (btnDeleteLogo) {
        btnDeleteLogo.addEventListener('click', () => {
            if (logoPreview) {
                logoPreview.src = '';
                logoPreview.style.display = 'none';
            }
            if (logoPlaceholder) {
                logoPlaceholder.style.display = 'block';
            }
            if (logoInput) {
                logoInput.value = '';
            }
            if (deleteLogoInput) {
                deleteLogoInput.value = '1';
            }
            btnDeleteLogo.style.display = 'none';
        });
    }

    // AJAX: Update settings
    const settingsForm = document.getElementById('settingsForm');
    if (settingsForm) {
        settingsForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            
            // Validation for slideshow count
            const mediaType = document.getElementById('media_type').value;
            if (mediaType === 'slideshow') {
                const imageItems = document.querySelectorAll('.slide-item');
                if (imageItems.length < 3) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Validasi Gagal',
                        text: 'Anda harus mengunggah minimal 3 gambar untuk slideshow!',
                        confirmButtonColor: '#00875a'
                    });
                    return;
                }
                if (imageItems.length > 9) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Validasi Gagal',
                        text: 'Maksimal hanya boleh mengunggah 9 gambar untuk slideshow!',
                        confirmButtonColor: '#00875a'
                    });
                    return;
                }
            }

            const formData = new FormData(settingsForm);

            try {
                const response = await fetch('{{ route("admin.update-settings") }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: formData
                });
                const res = await response.json();
                if (res.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil',
                        text: res.message,
                        confirmButtonColor: '#00875a'
                    }).then(() => {
                        location.reload(); // Reload to refresh headers & logo preview status
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: 'Gagal memperbarui pengaturan.',
                        confirmButtonColor: '#00875a'
                    });
                }
            } catch (err) {
                console.error(err);
                Swal.fire({
                    icon: 'error',
                    title: 'Kesalahan',
                    text: 'Koneksi gagal.',
                    confirmButtonColor: '#00875a'
                });
            }
        });
    }

    // AJAX: Store Counter
    const counterForm = document.getElementById('counterForm');
    if (counterForm) {
        counterForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            
            const formData = new FormData(counterForm);
            const data = {};
            formData.forEach((value, key) => data[key] = value);

            try {
                const response = await fetch('{{ route("admin.store-counter") }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(data)
                });
                const res = await response.json();
                if (res.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil',
                        text: res.message,
                        confirmButtonColor: '#00875a'
                    }).then(() => {
                        location.reload(); // Reload to populate the table & fields easily
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: 'Gagal menambahkan loket.',
                        confirmButtonColor: '#00875a'
                    });
                }
            } catch (err) {
                console.error(err);
                Swal.fire({
                    icon: 'error',
                    title: 'Kesalahan',
                    text: 'Koneksi gagal.',
                    confirmButtonColor: '#00875a'
                });
            }
        });
    }

    // AJAX: Delete Counter
    function deleteCounter(id) {
        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: "Loket ini akan dihapus permanen!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal'
        }).then(async (result) => {
            if (result.isConfirmed) {
                try {
                    const response = await fetch('{{ route("admin.delete-counter") }}', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({ id: id })
                    });
                    const res = await response.json();
                    if (res.success) {
                        document.getElementById(`counterRow${id}`).remove();
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: res.message,
                            confirmButtonColor: '#00875a'
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal',
                            text: 'Gagal menghapus loket.',
                            confirmButtonColor: '#00875a'
                        });
                    }
                } catch (err) {
                    console.error(err);
                    Swal.fire({
                        icon: 'error',
                        title: 'Kesalahan',
                        text: 'Koneksi gagal.',
                        confirmButtonColor: '#00875a'
                    });
                }
            }
        });
    }

    // AJAX: Reset queues
    function confirmReset() {
        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: "PERINGATAN! Tindakan ini akan menghapus seluruh data antrean berjalan hari ini.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, reset!',
            cancelButtonText: 'Batal'
        }).then(async (result) => {
            if (result.isConfirmed) {
                try {
                    const response = await fetch('{{ route("admin.reset") }}', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Content-Type': 'application/json'
                        }
                    });
                    const res = await response.json();
                    if (res.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: res.message,
                            confirmButtonColor: '#00875a'
                        }).then(() => {
                            location.reload();
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal',
                            text: 'Gagal mereset antrean.',
                            confirmButtonColor: '#00875a'
                        });
                    }
                } catch (err) {
                    console.error(err);
                    Swal.fire({
                        icon: 'error',
                        title: 'Kesalahan',
                        text: 'Koneksi gagal.',
                        confirmButtonColor: '#00875a'
                    });
                }
            }
        });
    }

    // AJAX Slideshow Upload
    const fileInput = document.getElementById('slideshow_file_input');
    if (fileInput) {
        fileInput.addEventListener('change', async () => {
            if (fileInput.files.length === 0) return;
            
            const file = fileInput.files[0];
            const formData = new FormData();
            formData.append('image', file);
            
            const trigger = document.getElementById('uploadTrigger');
            if (trigger) {
                trigger.style.opacity = '0.5';
                trigger.style.pointerEvents = 'none';
            }

            try {
                const response = await fetch('{{ route("admin.slideshow.upload") }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: formData
                });
                const res = await response.json();
                if (res.success) {
                    renderSlideshowGallery(res.images);
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil',
                        text: 'Gambar berhasil diunggah.',
                        timer: 1500,
                        showConfirmButton: false
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: res.message || 'Gagal mengunggah gambar.',
                        confirmButtonColor: '#00875a'
                    });
                }
            } catch (err) {
                console.error(err);
                Swal.fire({
                    icon: 'error',
                    title: 'Kesalahan',
                    text: 'Gagal menyambung ke server.',
                    confirmButtonColor: '#00875a'
                });
            } finally {
                fileInput.value = ''; 
                if (trigger) {
                    trigger.style.opacity = '1';
                    trigger.style.pointerEvents = 'auto';
                }
            }
        });
    }

    // AJAX Slideshow Delete
    function deleteSlide(imageUrl) {
        Swal.fire({
            title: 'Hapus Gambar?',
            text: "Apakah Anda yakin ingin menghapus gambar ini dari slideshow?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal'
        }).then(async (result) => {
            if (result.isConfirmed) {
                try {
                    const response = await fetch('{{ route("admin.slideshow.delete") }}', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({ image_url: imageUrl })
                    });
                    const res = await response.json();
                    if (res.success) {
                        renderSlideshowGallery(res.images);
                        Swal.fire({
                            icon: 'success',
                            title: 'Terhapus',
                            text: res.message || 'Gambar berhasil dihapus.',
                            timer: 1500,
                            showConfirmButton: false
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal',
                            text: res.message || 'Gagal menghapus gambar.',
                            confirmButtonColor: '#00875a'
                        });
                    }
                } catch (err) {
                    console.error(err);
                    Swal.fire({
                        icon: 'error',
                        title: 'Kesalahan',
                        text: 'Koneksi gagal.',
                        confirmButtonColor: '#00875a'
                    });
                }
            }
        });
    }

    // Re-render gallery grid
    function renderSlideshowGallery(images) {
        const gallery = document.getElementById('slideshowGallery');
        if (!gallery) return;
        
        gallery.innerHTML = '';
        
        images.forEach(url => {
            const item = document.createElement('div');
            item.className = 'slide-item';
            
            const img = document.createElement('img');
            const resolvedUrl = (url.startsWith('http://') || url.startsWith('https://')) ? url : `{{ asset('') }}${url}`;
            img.src = resolvedUrl;
            
            const btn = document.createElement('button');
            btn.type = 'button';
            btn.className = 'btn-delete-slide';
            btn.innerHTML = '&times;';
            btn.onclick = () => deleteSlide(url);
            
            item.appendChild(img);
            item.appendChild(btn);
            gallery.appendChild(item);
        });
        
        // Add back the upload trigger card if count < 9
        if (images.length < 9) {
            const trigger = document.createElement('div');
            trigger.id = 'uploadTrigger';
            trigger.className = 'upload-trigger-card';
            trigger.onclick = () => document.getElementById('slideshow_file_input').click();
            
            // Set up SVG namespace correctly
            const svg = document.createElementNS('http://www.w3.org/2000/svg', 'svg');
            svg.setAttribute('viewBox', '0 0 24 24');
            svg.innerHTML = '<path d="M19,13H13V19H11V13H5V11H11V5H13V11H19V13Z" />';
            
            const label = document.createElement('span');
            label.textContent = 'Unggah Gambar';
            
            const countLabel = document.createElement('small');
            countLabel.style.opacity = '0.7';
            countLabel.textContent = `(${images.length}/9)`;
            
            trigger.appendChild(svg);
            trigger.appendChild(label);
            trigger.appendChild(countLabel);
            gallery.appendChild(trigger);
        }
    }
</script>
@endsection
