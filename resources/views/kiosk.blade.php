@extends('layouts.app')

@section('title', 'Kios Ambil Tiket - PPDB/SPMB Jakarta Pusat 1')

@section('styles')
<style>
    body {
        background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
        color: #ffffff;
        min-height: 100vh;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        align-items: center;
        padding: 40px 20px;
    }

    /* Kiosk Header */
    .kiosk-header {
        text-align: center;
        margin-bottom: 20px;
    }

    .kiosk-header svg {
        width: 80px;
        height: 90px;
        margin-bottom: 15px;
    }

    .kiosk-header h1 {
        font-size: 2rem;
        font-weight: 800;
        letter-spacing: 0.5px;
        color: #ffffff;
    }

    .kiosk-header h2 {
        font-size: 1.1rem;
        font-weight: 500;
        color: #94a3b8;
        margin-top: 5px;
    }

    /* Kiosk Button Area */
    .kiosk-content {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        flex: 1;
        width: 100%;
        max-width: 600px;
    }

    .ticket-button-container {
        position: relative;
        width: 280px;
        height: 280px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    /* Glowing Pulsing Ring around the button */
    .pulse-ring {
        position: absolute;
        width: 100%;
        height: 100%;
        border-radius: 50%;
        background: rgba(0, 168, 107, 0.2);
        animation: pulseWave 2s infinite ease-out;
        z-index: 1;
    }

    .ticket-btn {
        position: relative;
        width: 240px;
        height: 240px;
        border-radius: 50%;
        background: linear-gradient(135deg, #00875a 0%, #004d40 100%);
        border: 8px solid rgba(255, 255, 255, 0.15);
        color: #ffffff;
        font-size: 1.5rem;
        font-weight: 800;
        cursor: pointer;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 12px;
        box-shadow: 0 15px 40px rgba(0, 135, 90, 0.4), 
                    inset 0 4px 10px rgba(255, 255, 255, 0.3);
        transition: all 0.2s ease;
        z-index: 2;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .ticket-btn svg {
        width: 50px;
        height: 50px;
        fill: #ffffff;
        transition: transform 0.2s ease;
    }

    .ticket-btn:hover {
        transform: scale(1.03);
        background: linear-gradient(135deg, #00a86b 0%, #00796b 100%);
        box-shadow: 0 20px 50px rgba(0, 168, 107, 0.5);
    }

    .ticket-btn:active {
        transform: scale(0.97);
        box-shadow: 0 5px 15px rgba(0, 135, 90, 0.4);
    }

    .ticket-btn:hover svg {
        transform: translateY(-5px);
    }

    @keyframes pulseWave {
        0% {
            transform: scale(0.85);
            opacity: 0.8;
        }
        50% {
            opacity: 0.5;
        }
        100% {
            transform: scale(1.25);
            opacity: 0;
        }
    }

    .kiosk-instruction {
        margin-top: 30px;
        font-size: 1.1rem;
        font-weight: 500;
        color: #e2e8f0;
        text-align: center;
    }

    /* Kiosk Footer */
    .kiosk-footer {
        text-align: center;
        color: #64748b;
        font-size: 0.85rem;
    }

    /* Print Preview Modal */
    .print-modal {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(15, 23, 42, 0.85);
        display: none;
        justify-content: center;
        align-items: center;
        z-index: 999;
        backdrop-filter: blur(8px);
    }

    /* Receipt Styling - Looks like actual thermal receipt roll */
    .thermal-receipt {
        background: #ffffff;
        color: #000000;
        width: 320px;
        padding: 30px 20px;
        border-radius: 4px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.5);
        font-family: 'Courier New', Courier, monospace;
        text-align: center;
        position: relative;
    }

    /* Thermal jagged paper cut lines */
    .thermal-receipt::before, .thermal-receipt::after {
        content: '';
        position: absolute;
        left: 0;
        width: 100%;
        height: 8px;
        background-image: linear-gradient(135deg, #ffffff 4px, transparent 4px), 
                          linear-gradient(225deg, #ffffff 4px, transparent 4px);
        background-size: 8px 8px;
        background-position: left bottom;
    }
    .thermal-receipt::before {
        top: -8px;
        transform: rotate(180deg);
    }
    .thermal-receipt::after {
        bottom: -8px;
    }

    .receipt-header h3 {
        font-size: 0.95rem;
        font-weight: 800;
        margin-bottom: 2px;
    }

    .receipt-header p {
        font-size: 0.7rem;
        margin-bottom: 10px;
        line-height: 1.3;
    }

    .receipt-divider {
        border-top: 1px dashed #000000;
        margin: 12px 0;
    }

    .receipt-number-label {
        font-size: 0.85rem;
        font-weight: 700;
        letter-spacing: 1px;
    }

    .receipt-number {
        font-size: 3.5rem;
        font-weight: 800;
        margin: 10px 0;
        letter-spacing: -1px;
    }

    .receipt-time {
        font-size: 0.75rem;
    }

    .receipt-footer {
        font-size: 0.7rem;
        margin-top: 15px;
        line-height: 1.4;
    }

    .modal-close-btn {
        margin-top: 20px;
        background: #00875a;
        color: #ffffff;
        border: none;
        padding: 10px 20px;
        font-size: 0.9rem;
        font-weight: 700;
        border-radius: 6px;
        cursor: pointer;
        font-family: var(--font-outfit);
        width: 100%;
    }

    /* PRINT ONLY STYLES */
    @media print {
        body * {
            visibility: hidden;
        }
        #printArea, #printArea * {
            visibility: visible;
        }
        #printArea {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
            margin: 0;
            padding: 0;
            box-shadow: none;
        }
        .modal-close-btn, .thermal-receipt::before, .thermal-receipt::after {
            display: none !important;
        }
    }
</style>
@endsection
@section('content')
<!-- Header -->
<div class="kiosk-header">
    @if(!empty($settings['header_logo']))
        <img src="{{ asset($settings['header_logo']) }}?v={{ filemtime(public_path($settings['header_logo'])) }}" style="height: 70px; width: auto; object-fit: contain; border-radius: 6px; margin-bottom: 10px;" alt="Logo">
    @else
        <!-- Inline DKI SVG -->
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
    <h1>KIOS AMBIL TIKET ANTREAN</h1>
    <h2>{{ $settings['header_title'] ?? 'POSKO PPDB / SPMB' }}{{ !empty($settings['header_subtitle']) ? ' - ' . $settings['header_subtitle'] : '' }}</h2>
</div>

<!-- Touch Button Area -->
<div class="kiosk-content">
    <div class="ticket-button-container">
        <div class="pulse-ring"></div>
        <button id="btnIssueTicket" class="ticket-btn">
            <!-- Printing Ticket SVG Icon -->
            <svg viewBox="0 0 24 24">
                <path d="M19,8H5C3.34,8 2,9.34 2,11V17H6V21H18V17H22V11C22,9.34 20.66,8 19,8M16,19H8V15H16V19M19,12C18.45,12 18,11.55 18,11C18,10.45 18.45,10 19,10C19.55,10 20,10.45 20,11C20,11.55 19.55,12 19,12M17,3H7V6H17V3Z" />
            </svg>
            Ambil Tiket
        </button>
    </div>
    <div class="kiosk-instruction">
        Sentuh tombol di atas untuk mencetak nomor antrean Anda.
    </div>
</div>

<!-- Footer -->
<div class="kiosk-footer">
    questkomapp.com &copy; {{ date('Y') }}
</div>

<!-- Print Preview Modal -->
<div id="printModal" class="print-modal">
    <div id="printArea" class="thermal-receipt">
        <div class="receipt-header">
            <h3>POSKO PPDB / SPMB</h3>
            <p>SUDIN PENDIDIKAN WILAYAH 1<br>KOTA ADM. JAKARTA PUSAT</p>
            <p style="font-size: 0.6rem; margin-top: 5px;">Kantor Walikota Jakarta Pusat Blok C Lt. 4</p>
        </div>
        <div class="receipt-divider"></div>
        <div class="receipt-number-label">NOMOR ANTREAN</div>
        <div id="receiptNumber" class="receipt-number">000</div>
        <div class="receipt-divider"></div>
        <div id="receiptDateTime" class="receipt-time">Senin, 15 Juni 2026<br>11:15:30</div>
        <div class="receipt-divider"></div>
        <div class="receipt-footer">
            Harap menunggu nomor Anda dipanggil.<br>Terima kasih atas kunjungan Anda.
        </div>
        <button id="btnCloseModal" class="modal-close-btn">Selesai</button>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const btnIssue = document.getElementById('btnIssueTicket');
        const printModal = document.getElementById('printModal');
        const receiptNumber = document.getElementById('receiptNumber');
        const receiptDateTime = document.getElementById('receiptDateTime');
        const btnClose = document.getElementById('btnCloseModal');

        // Speak function using Web Speech API in Indonesian
        function speakTicketIssued(number) {
            if (!('speechSynthesis' in window)) return;
            
            // Helper to parse numbers to words
            function numberToIndonesianWords(num) {
                const units = ['', 'satu', 'dua', 'tiga', 'empat', 'lima', 'enam', 'tujuh', 'delapan', 'sembilan', 'sepuluh', 'sebelas'];
                if (num < 12) return units[num];
                if (num < 20) return units[num - 10] + ' belas';
                if (num < 100) {
                    const tens = Math.floor(num / 10);
                    const rem = num % 10;
                    return units[tens] + ' puluh ' + units[rem];
                }
                if (num < 200) return 'seratus ' + numberToIndonesianWords(num - 100);
                if (num < 1000) {
                    const hundreds = Math.floor(num / 100);
                    const rem = num % 100;
                    if (hundreds === 1) return 'seratus ' + numberToIndonesianWords(rem);
                    return units[hundreds] + ' ratus ' + numberToIndonesianWords(rem);
                }
                return num.toString();
            }

            const numberWords = numberToIndonesianWords(number);
            // Use commas for natural human pauses
            const textToSpeak = `Nomor antrean Anda, adalah, ${numberWords}. Silakan menunggu.`;
            
            const utterance = new SpeechSynthesisUtterance(textToSpeak);
            utterance.lang = 'id-ID';
            
            // Set natural human speed default
            utterance.rate = 0.88;
            utterance.pitch = 1.0;
            
            // Prioritize high-quality human-like voices (Google cloud voice or Siri)
            const voices = window.speechSynthesis.getVoices();
            let idVoice = voices.find(voice => voice.name === 'Google Bahasa Indonesia');
            if (!idVoice) {
                idVoice = voices.find(voice => voice.name.includes('Siri') && (voice.lang.includes('id') || voice.lang.includes('ID')));
            }
            if (!idVoice) {
                idVoice = voices.find(voice => voice.name.includes('Bahasa') || voice.lang.includes('id') || voice.lang.includes('ID'));
            }
            
            if (idVoice) {
                utterance.voice = idVoice;
            }

            window.speechSynthesis.speak(utterance);
        }

        if (btnIssue) {
            btnIssue.addEventListener('click', async () => {
                // Disable button during call
                btnIssue.disabled = true;
                btnIssue.style.opacity = '0.7';

                try {
                    const response = await fetch('{{ route("kiosk.issue") }}', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Content-Type': 'application/json'
                        }
                    });
                    
                    const data = await response.json();
                    
                    if (data.success) {
                        // 1. Populate receipt modal
                        receiptNumber.textContent = data.formatted_number;
                        receiptDateTime.innerHTML = `${data.date}<br>${data.time}`;
                        
                        // 2. Show Modal
                        printModal.style.display = 'flex';
                        
                        // 3. Play sound announcement on kiosk
                        speakTicketIssued(data.ticket_number);

                        // 4. Trigger print dial
                        setTimeout(() => {
                            window.print();
                        }, 500);
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal',
                            text: 'Gagal mengambil tiket antrean. Silakan coba kembali.',
                            confirmButtonColor: '#00875a'
                        });
                    }
                } catch (err) {
                    console.error("Kiosk Error:", err);
                    Swal.fire({
                        icon: 'error',
                        title: 'Koneksi Gagal',
                        text: 'Koneksi gagal. Pastikan XAMPP berjalan.',
                        confirmButtonColor: '#00875a'
                    });
                } finally {
                    btnIssue.disabled = false;
                    btnIssue.style.opacity = '1';
                }
            });
        }

        if (btnClose) {
            btnClose.addEventListener('click', () => {
                printModal.style.display = 'none';
            });
        }
    });
</script>
@endsection
