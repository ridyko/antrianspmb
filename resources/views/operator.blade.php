@extends('layouts.app')

@section('title', 'Konsol Operator Loket - Posko PPDB/SPMB')

@section('styles')
<style>
    body {
        background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
        color: #f8fafc;
        min-height: 100vh;
        display: flex;
        flex-direction: column;
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

    .btn-switch-counter {
        background: rgba(255, 255, 255, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.2);
        color: #ffffff;
        padding: 8px 16px;
        border-radius: 6px;
        font-size: 0.85rem;
        font-weight: 600;
        cursor: pointer;
        transition: var(--transition-smooth);
    }

    .btn-switch-counter:hover {
        background: rgba(255, 255, 255, 0.2);
    }

    /* Main Console Layout */
    .console-layout {
        flex: 1;
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 24px;
        padding: 24px;
        max-width: 1400px;
        width: 100%;
        margin: 0 auto;
    }

    /* Left Console: Paging Controls */
    .console-card {
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        padding: 30px;
        height: 100%;
    }

    .counter-badge {
        display: inline-block;
        background: #002d62;
        border: 1px solid rgba(255, 255, 255, 0.15);
        color: #ffffff;
        padding: 6px 12px;
        border-radius: 6px;
        font-size: 0.9rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 20px;
    }

    .current-call-display {
        text-align: center;
        padding: 40px 20px;
        border-radius: 12px;
        background: rgba(15, 23, 42, 0.4);
        border: 1px solid rgba(255, 255, 255, 0.05);
        margin-bottom: 30px;
    }

    .current-call-display h3 {
        font-size: 1rem;
        color: #94a3b8;
        text-transform: uppercase;
        letter-spacing: 1px;
        margin-bottom: 10px;
    }

    .current-number {
        font-size: 6.5rem;
        font-weight: 800;
        color: #fbc02d;
        text-shadow: 0 4px 15px rgba(251, 192, 45, 0.2);
        line-height: 1;
        font-family: monospace;
    }

    .control-buttons {
        display: flex;
        flex-direction: column;
        gap: 15px;
        align-items: center;
    }

    .btn-call-next {
        background: linear-gradient(135deg, #00875a 0%, #004d40 100%);
        border: none;
        color: #ffffff;
        font-size: 1.5rem;
        font-weight: 800;
        padding: 24px;
        border-radius: 12px;
        cursor: pointer;
        width: 100%;
        max-width: 450px;
        box-shadow: 0 10px 25px rgba(0, 135, 90, 0.3);
        transition: var(--transition-smooth);
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 12px;
        text-transform: uppercase;
    }

    .btn-call-next:hover {
        background: linear-gradient(135deg, #00a86b 0%, #00796b 100%);
        transform: translateY(-2px);
        box-shadow: 0 15px 30px rgba(0, 168, 107, 0.4);
    }

    .btn-call-next:active {
        transform: translateY(0);
    }

    .btn-call-next svg {
        width: 32px;
        height: 32px;
        fill: currentColor;
    }

    .btn-recall {
        background: linear-gradient(135deg, #fbc02d 0%, #f57f17 100%);
        border: none;
        color: #0f172a;
        font-size: 1.1rem;
        font-weight: 700;
        padding: 16px;
        border-radius: 8px;
        cursor: pointer;
        width: 100%;
        max-width: 450px;
        box-shadow: 0 8px 20px rgba(245, 127, 23, 0.25);
        transition: var(--transition-smooth);
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        text-transform: uppercase;
    }

    .btn-recall:hover {
        background: linear-gradient(135deg, #fff176 0%, #fbc02d 100%);
        transform: translateY(-2px);
        box-shadow: 0 12px 25px rgba(251, 192, 45, 0.35);
    }

    .btn-recall svg {
        width: 24px;
        height: 24px;
        fill: currentColor;
    }

    /* Right Sidebar: Statistics & Counter List */
    .sidebar-panel {
        display: flex;
        flex-direction: column;
        gap: 24px;
    }

    .stats-card {
        padding: 20px;
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 12px;
    }

    .stat-box {
        text-align: center;
        padding: 12px;
        background: rgba(15, 23, 42, 0.3);
        border-radius: 8px;
        border: 1px solid rgba(255,255,255,0.05);
    }

    .stat-label {
        font-size: 0.7rem;
        color: #94a3b8;
        font-weight: 700;
        text-transform: uppercase;
        margin-bottom: 5px;
    }

    .stat-value {
        font-size: 1.6rem;
        font-weight: 800;
    }

    .stat-value.waiting { color: #f43f5e; }
    .stat-value.called { color: #10b981; }
    .stat-value.total { color: #3b82f6; }

    .counters-status-card {
        padding: 20px;
        flex: 1;
    }

    .counters-status-card h3 {
        font-size: 1rem;
        font-weight: 700;
        margin-bottom: 15px;
        color: #ffffff;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        padding-bottom: 8px;
    }

    .counter-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 12px 0;
        border-bottom: 1px solid rgba(255, 255, 255, 0.05);
    }

    .counter-row:last-child {
        border-bottom: none;
    }

    .counter-row-name {
        font-weight: 600;
        font-size: 0.9rem;
    }

    .counter-row-number {
        font-family: monospace;
        font-weight: 800;
        font-size: 1.15rem;
        color: #10b981;
        background: rgba(16, 185, 129, 0.1);
        padding: 3px 8px;
        border-radius: 4px;
        border: 1px solid rgba(16, 185, 129, 0.15);
    }

    .counter-row-number.empty {
        color: #64748b;
        background: rgba(255,255,255,0.02);
        border: 1px solid rgba(255,255,255,0.05);
    }

    /* Counter Selector Modal */
    .selector-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(15, 23, 42, 0.9);
        backdrop-filter: blur(10px);
        display: none;
        justify-content: center;
        align-items: center;
        z-index: 9999;
    }

    .selector-box {
        background: #1e293b;
        border: 1px solid rgba(255,255,255,0.1);
        padding: 40px;
        border-radius: 16px;
        box-shadow: 0 20px 50px rgba(0,0,0,0.5);
        max-width: 500px;
        width: 90%;
        text-align: center;
    }

    .selector-box h2 {
        font-size: 1.7rem;
        font-weight: 800;
        color: #ffffff;
        margin-bottom: 10px;
    }

    .selector-box p {
        color: #94a3b8;
        font-size: 0.9rem;
        margin-bottom: 25px;
    }

    .counter-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 15px;
    }

    .btn-select-counter {
        background: rgba(255,255,255,0.05);
        border: 1px solid rgba(255,255,255,0.1);
        color: #ffffff;
        padding: 16px;
        border-radius: 8px;
        font-size: 1.05rem;
        font-weight: 700;
        cursor: pointer;
        transition: var(--transition-smooth);
    }

    .btn-select-counter:hover {
        background: #00875a;
        border-color: #00a86b;
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(0, 135, 90, 0.3);
    }
</style>
@endsection

@section('content')
<!-- Navbar -->
<div class="navbar">
    <div class="nav-brand">
        @if(!empty($settings['header_logo']))
            <img src="{{ asset($settings['header_logo']) }}?v={{ filemtime(public_path($settings['header_logo'])) }}" style="height: 40px; width: auto; object-fit: contain; border-radius: 4px;" alt="Logo">
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
        <span>Posko PPDB / SPMB Jakarta Pusat 1</span>
    </div>
    <button id="btnSwitchCounter" class="btn-switch-counter">Ganti Loket</button>
</div>

<!-- Main Console Layout -->
<div class="console-layout">
    <!-- Left Console: Calling controls -->
    <div class="glass-panel console-card">
        <div>
            <div id="counterBadge" class="counter-badge">LOKET --</div>
            <div class="current-call-display">
                <h3>Antrean Dipanggil Saat Ini</h3>
                <div id="currentNumber" class="current-number">---</div>
            </div>
        </div>
        
        <div class="control-buttons">
            <button id="btnCallNext" class="btn-call-next">
                <!-- Sound calling speaker icon -->
                <svg viewBox="0 0 24 24">
                    <path d="M14,3.23V5.29C16.89,6.15 19,8.83 19,12C19,15.17 16.89,17.85 14,18.71V20.77C18.07,19.86 21,16.28 21,12C21,7.72 18.07,4.14 14,3.23M16.5,12C16.5,10.23 15.5,8.71 14,7.97V16C15.5,15.29 16.5,13.77 16.5,12M3,9V15H7L12,20V4L7,9H3Z"/>
                </svg>
                Panggil Berikutnya
            </button>
            <button id="btnRecall" class="btn-recall">
                <!-- Reload/Repeat speaker icon -->
                <svg viewBox="0 0 24 24">
                    <path d="M12,5V1L7,6L12,11V7A6,6 0 0,1 18,13A6,6 0 0,1 12,19A6,6 0 0,1 6,13H4A8,8 0 0,0 12,21A8,8 0 0,0 20,13A8,8 0 0,0 12,5M5,9.08V14.91H7.92L11,18V10L7.92,13.08H5" />
                </svg>
                Panggil Ulang
            </button>
        </div>
    </div>

    <!-- Right Sidebar: Stats & Co-workers Status -->
    <div class="sidebar-panel">
        <!-- Stats Card -->
        <div class="glass-panel stats-card">
            <div class="stats-grid">
                <div class="stat-box">
                    <div class="stat-label">Menunggu</div>
                    <div id="statWaiting" class="stat-value waiting">0</div>
                </div>
                <div class="stat-box">
                    <div class="stat-label">Terpanggil</div>
                    <div id="statCalled" class="stat-value called">0</div>
                </div>
                <div class="stat-box">
                    <div class="stat-label">Total Tiket</div>
                    <div id="statTotal" class="stat-value total">0</div>
                </div>
            </div>
        </div>

        <!-- Other counters status -->
        <div class="glass-panel counters-status-card">
            <h3>Status Loket</h3>
            <div id="otherCountersList">
                <!-- Filled dynamically -->
            </div>
        </div>
    </div>
</div>

<!-- Counter Selector Overlay Modal -->
<div id="selectorOverlay" class="selector-overlay">
    <div class="selector-box">
        <h2>PILIH LOKET ANDA</h2>
        <p>Silakan pilih nomor loket tempat Anda bertugas hari ini.</p>
        <div class="counter-grid" id="selectorGrid">
            <!-- Dynamic selector buttons will load here -->
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        let activeCounterId = localStorage.getItem('operator_counter_id');
        let activeCounterName = localStorage.getItem('operator_counter_name');
        
        const selectorOverlay = document.getElementById('selectorOverlay');
        const selectorGrid = document.getElementById('selectorGrid');
        const counterBadge = document.getElementById('counterBadge');
        const currentNumber = document.getElementById('currentNumber');
        const btnCallNext = document.getElementById('btnCallNext');
        const btnRecall = document.getElementById('btnRecall');
        const btnSwitch = document.getElementById('btnSwitchCounter');

        // Fetch counter options from database to render selector
        async function loadCounterOptions() {
            try {
                const response = await fetch('{{ route("operator.stats") }}');
                const data = await response.json();
                
                selectorGrid.innerHTML = '';
                data.counters.forEach(c => {
                    const btn = document.createElement('button');
                    btn.className = 'btn-select-counter';
                    btn.textContent = c.name;
                    btn.addEventListener('click', () => {
                        localStorage.setItem('operator_counter_id', c.id);
                        localStorage.setItem('operator_counter_name', c.name);
                        activeCounterId = c.id;
                        activeCounterName = c.name;
                        
                        selectorOverlay.style.display = 'none';
                        initConsole();
                    });
                    selectorGrid.appendChild(btn);
                });

                if (!activeCounterId) {
                    selectorOverlay.style.display = 'flex';
                } else {
                    initConsole();
                }
            } catch (err) {
                console.error("Failed to load counters:", err);
            }
        }

        // Initialize Console details
        function initConsole() {
            counterBadge.textContent = activeCounterName;
            
            // Start statistics and counter state polling
            updateStatsAndOtherCounters();
            clearInterval(window.statsInterval);
            window.statsInterval = setInterval(updateStatsAndOtherCounters, 2000);
        }

        // Update Statistics & other counters list
        async function updateStatsAndOtherCounters() {
            try {
                const response = await fetch('{{ route("operator.stats") }}');
                const data = await response.json();

                // 1. Update stats indicators
                document.getElementById('statWaiting').textContent = data.waiting;
                document.getElementById('statCalled').textContent = data.called;
                document.getElementById('statTotal').textContent = data.total;

                // 2. Update current active called number for this specific counter
                const myCounter = data.counters.find(c => parseInt(c.id) === parseInt(activeCounterId));
                if (myCounter) {
                    currentNumber.textContent = myCounter.formatted_number;
                }

                // 3. Update co-workers counters list
                const otherList = document.getElementById('otherCountersList');
                otherList.innerHTML = '';
                
                data.counters.forEach(c => {
                    const isSelf = parseInt(c.id) === parseInt(activeCounterId);
                    const row = document.createElement('div');
                    row.className = 'counter-row';
                    if (isSelf) {
                        row.style.background = 'rgba(0, 135, 90, 0.15)';
                        row.style.borderLeft = '3px solid #00875a';
                        row.style.paddingLeft = '8px';
                        row.style.borderRadius = '4px';
                    }
                    
                    const nameEl = document.createElement('div');
                    nameEl.className = 'counter-row-name';
                    if (isSelf) {
                        nameEl.innerHTML = `<strong>${c.name} (${c.room}) <span style="color:#10b981; font-size:0.8rem; font-weight:700;">(Anda)</span></strong>`;
                    } else {
                        nameEl.textContent = `${c.name} (${c.room})`;
                    }
                    
                    const numEl = document.createElement('div');
                    numEl.className = `counter-row-number ${c.current_call_number ? '' : 'empty'}`;
                    if (isSelf && c.current_call_number) {
                        numEl.style.borderColor = '#10b981';
                        numEl.style.background = 'rgba(16, 185, 129, 0.2)';
                        numEl.style.color = '#ffffff';
                    }
                    numEl.textContent = c.formatted_number;
                    
                    row.appendChild(nameEl);
                    row.appendChild(numEl);
                    otherList.appendChild(row);
                });
            } catch (err) {
                console.error("Failed to poll stats:", err);
            }
        }

        // Action: Call Next
        if (btnCallNext) {
            btnCallNext.addEventListener('click', async () => {
                if (!activeCounterId) return;
                
                btnCallNext.disabled = true;
                btnCallNext.style.opacity = '0.7';

                try {
                    const response = await fetch('{{ route("operator.call-next") }}', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({ counter_id: activeCounterId })
                    });
                    
                    const data = await response.json();
                    
                    if (data.success) {
                        currentNumber.textContent = data.formatted_number;
                        updateStatsAndOtherCounters();
                    } else {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Pemberitahuan',
                            text: data.message || "Gagal memanggil antrean berikutnya.",
                            confirmButtonColor: '#00875a'
                        });
                    }
                } catch (err) {
                    console.error("Call next error:", err);
                } finally {
                    btnCallNext.disabled = false;
                    btnCallNext.style.opacity = '1';
                }
            });
        }

        // Action: Recall Current
        if (btnRecall) {
            btnRecall.addEventListener('click', async () => {
                if (!activeCounterId) return;
                
                btnRecall.disabled = true;
                btnRecall.style.opacity = '0.7';

                try {
                    const response = await fetch('{{ route("operator.recall") }}', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({ counter_id: activeCounterId })
                    });
                    
                    const data = await response.json();
                    
                    if (data.success) {
                        currentNumber.textContent = data.formatted_number;
                        updateStatsAndOtherCounters();
                    } else {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Pemberitahuan',
                            text: data.message || "Gagal memanggil ulang.",
                            confirmButtonColor: '#00875a'
                        });
                    }
                } catch (err) {
                    console.error("Recall error:", err);
                } finally {
                    btnRecall.disabled = false;
                    btnRecall.style.opacity = '1';
                }
            });
        }

        // Action: Switch Counter
        if (btnSwitch) {
            btnSwitch.addEventListener('click', () => {
                selectorOverlay.style.display = 'flex';
            });
        }

        // Trigger loading options
        loadCounterOptions();
    });
</script>
@endsection
