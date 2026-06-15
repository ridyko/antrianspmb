document.addEventListener('DOMContentLoaded', () => {
    // 1. Digital Clock & Date Display
    function updateClockAndDate() {
        const now = new Date();
        
        // Digital Clock (HH:MM:SS)
        const clockDisplay = document.getElementById('clockDisplay');
        if (clockDisplay) {
            const hours = String(now.getHours()).padStart(2, '0');
            const minutes = String(now.getMinutes()).padStart(2, '0');
            const seconds = String(now.getSeconds()).padStart(2, '0');
            clockDisplay.textContent = `${hours}:${minutes}:${seconds}`;
        }
        
        // Local Indonesian Date
        const dateDisplay = document.getElementById('dateDisplay');
        if (dateDisplay) {
            const options = { weekday: 'long', day: 'numeric', month: 'long', year: 'numeric' };
            dateDisplay.textContent = now.toLocaleDateString('id-ID', options);
        }
    }
    updateClockAndDate();
    setInterval(updateClockAndDate, 1000);

    // 2. Web Audio API Clinic Chime (Ding Dong)
    let audioCtx = null;
    function playClinicChime() {
        return new Promise((resolve) => {
            try {
                if (!audioCtx) {
                    audioCtx = new (window.AudioContext || window.webkitAudioContext)();
                }
                
                if (audioCtx.state === 'suspended') {
                    audioCtx.resume();
                }

                const time = audioCtx.currentTime;

                // Ding (C#5 - 554.37 Hz)
                const osc1 = audioCtx.createOscillator();
                const gain1 = audioCtx.createGain();
                osc1.connect(gain1);
                gain1.connect(audioCtx.destination);
                
                osc1.type = 'sine';
                osc1.frequency.setValueAtTime(554.37, time);
                gain1.gain.setValueAtTime(0, time);
                gain1.gain.linearRampToValueAtTime(0.4, time + 0.05);
                gain1.gain.exponentialRampToValueAtTime(0.001, time + 0.8);
                
                osc1.start(time);
                osc1.stop(time + 0.8);

                // Dong (A4 - 440.00 Hz)
                const osc2 = audioCtx.createOscillator();
                const gain2 = audioCtx.createGain();
                osc2.connect(gain2);
                gain2.connect(audioCtx.destination);
                
                osc2.type = 'sine';
                osc2.frequency.setValueAtTime(440.00, time + 0.3);
                gain2.gain.setValueAtTime(0, time + 0.3);
                gain2.gain.linearRampToValueAtTime(0.4, time + 0.35);
                gain2.gain.exponentialRampToValueAtTime(0.001, time + 1.2);
                
                osc2.start(time + 0.3);
                osc2.stop(time + 1.2);

                setTimeout(() => {
                    resolve();
                }, 1200);
            } catch (err) {
                console.error("Audio Context Error:", err);
                resolve(); // resolve so we don't stall the voice calling queue
            }
        });
    }

    // 3. Indonesian Text-to-Speech (TTS) Number-to-Words Parser
    function numberToIndonesianWords(n) {
        const units = ['', 'satu', 'dua', 'tiga', 'empat', 'lima', 'enam', 'tujuh', 'delapan', 'sembilan', 'sepuluh', 'sebelas'];
        if (n < 12) return units[n];
        if (n < 20) return units[n - 10] + ' belas';
        if (n < 100) {
            const tens = Math.floor(n / 10);
            const rem = n % 10;
            return units[tens] + ' puluh ' + units[rem];
        }
        if (n < 200) return 'seratus ' + numberToIndonesianWords(n - 100);
        if (n < 1000) {
            const hundreds = Math.floor(n / 100);
            const rem = n % 100;
            if (hundreds === 1) return 'seratus ' + numberToIndonesianWords(rem);
            return units[hundreds] + ' ratus ' + numberToIndonesianWords(rem);
        }
        return n.toString();
    }

    function announceQueue(ticketNumber, roomName) {
        return new Promise((resolve) => {
            if (!('speechSynthesis' in window)) {
                console.warn("Speech Synthesis not supported in this browser.");
                resolve();
                return;
            }

            const numberWords = numberToIndonesianWords(ticketNumber);
            // Use commas and periods to introduce natural human pauses and inflections
            const textToSpeak = `Nomor antrean, ${numberWords}. Silakan menuju ke, ${roomName}.`;
            
            const utterance = new SpeechSynthesisUtterance(textToSpeak);
            utterance.lang = 'id-ID';
            
            // Calibrate speaking rate (around 0.85-0.9 is most natural for queue calling)
            const baseRate = window.appSettings.speech_rate || 1.0;
            utterance.rate = baseRate * 0.88; 
            utterance.pitch = window.appSettings.speech_pitch || 1.0;

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

            utterance.onend = () => {
                resolve();
            };
            utterance.onerror = (err) => {
                console.error("Speech Synthesis Error:", err);
                resolve();
            };

            window.speechSynthesis.speak(utterance);
        });
    }

    // 4. Media Management (Looping YouTube Video & Slideshow Image)
    let slideshowInterval = null;
    let currentSlideIndex = 0;

    function initMedia() {
        const type = window.appSettings.media_type;
        const videoPlayer = document.getElementById('videoPlayer');
        const slideshowPlayer = document.getElementById('slideshowPlayer');
        
        if (type === 'video') {
            videoPlayer.style.display = 'block';
            slideshowPlayer.style.display = 'none';
            if (slideshowInterval) clearInterval(slideshowInterval);
            
            // Format YouTube URL to enable looping and autoplays
            let youtubeUrl = window.appSettings.video_url || 'https://www.youtube.com/embed/5N42v3k6qQk';
            
            // Extract YouTube ID if it is a full link
            let videoId = '5N42v3k6qQk';
            const regExp = /^.*(youtu.be\/|v\/|u\/\w\/|embed\/|watch\?v=|\&v=)([^#\&\?]*).*/;
            const match = youtubeUrl.match(regExp);
            if (match && match[2].length === 11) {
                videoId = match[2];
            } else if (youtubeUrl.includes('embed/')) {
                const parts = youtubeUrl.split('embed/');
                if (parts[1]) {
                    videoId = parts[1].split('?')[0];
                }
            }
            
            const embedUrl = `https://www.youtube.com/embed/${videoId}?autoplay=1&mute=1&loop=1&playlist=${videoId}&controls=0&rel=0`;
            const mediaIframe = document.getElementById('mediaIframe');
            if (mediaIframe && mediaIframe.src !== embedUrl) {
                mediaIframe.src = embedUrl;
            }
        } else {
            videoPlayer.style.display = 'none';
            slideshowPlayer.style.display = 'block';
            
            // Load Slideshow images
            const images = window.appSettings.slideshow_images || [];
            const slideshowSlides = document.getElementById('slideshowSlides');
            if (slideshowSlides) {
                slideshowSlides.innerHTML = '';
            }
            
            if (images.length === 0) {
                // Fallback local or placeholder images
                images.push('https://images.unsplash.com/photo-1546410531-bb4caa6b424d?w=1200');
                images.push('https://images.unsplash.com/photo-1509062522246-3755977927d7?w=1200');
            }
            
            images.forEach((imgUrl, idx) => {
                const slide = document.createElement('div');
                slide.className = `slide ${idx === 0 ? 'active' : ''}`;
                const resolvedUrl = (imgUrl.startsWith('http://') || imgUrl.startsWith('https://')) 
                    ? imgUrl 
                    : (window.appSettings.asset_base_url || '') + imgUrl;
                
                const blurBg = document.createElement('div');
                blurBg.className = 'slide-blur-bg';
                blurBg.style.backgroundImage = `url('${resolvedUrl}')`;
                
                const fg = document.createElement('div');
                fg.className = 'slide-fg';
                fg.style.backgroundImage = `url('${resolvedUrl}')`;
                
                slide.appendChild(blurBg);
                slide.appendChild(fg);
                if (slideshowSlides) {
                    slideshowSlides.appendChild(slide);
                }
            });
            
            currentSlideIndex = 0;
            if (slideshowInterval) clearInterval(slideshowInterval);
            
            function showSlide(index) {
                const slides = slideshowSlides ? slideshowSlides.querySelectorAll('.slide') : [];
                if (slides.length > 0) {
                    slides[currentSlideIndex].classList.remove('active');
                    currentSlideIndex = (index + slides.length) % slides.length;
                    slides[currentSlideIndex].classList.add('active');
                }
            }
            
            function startSlideshowInterval() {
                if (slideshowInterval) clearInterval(slideshowInterval);
                slideshowInterval = setInterval(() => {
                    showSlide(currentSlideIndex + 1);
                }, 30000); // rotate every 30s
            }
            
            if (images.length > 1) {
                startSlideshowInterval();
                
                // Bind navigation buttons
                const btnPrev = document.getElementById('btnPrevSlide');
                const btnNext = document.getElementById('btnNextSlide');
                
                if (btnPrev) {
                    btnPrev.onclick = (e) => {
                        e.stopPropagation();
                        showSlide(currentSlideIndex - 1);
                        startSlideshowInterval(); // reset auto-rotation timer
                    };
                }
                if (btnNext) {
                    btnNext.onclick = (e) => {
                        e.stopPropagation();
                        showSlide(currentSlideIndex + 1);
                        startSlideshowInterval(); // reset auto-rotation timer
                    };
                }
            }
        }
    }

    // 5. Polling Queue State
    let pollingActive = false;
    let isCalling = false; // Queue locker to prevent sound overlaps
    const callQueue = []; // In-memory queue stack for pending calls

    async function processCallQueue() {
        if (isCalling || callQueue.length === 0) return;
        isCalling = true;
        
        const nextCall = callQueue.shift();
        console.log("[Queue] Starting call processing for ticket:", nextCall.ticket_number, "at room:", nextCall.room, "ID:", nextCall.id);
        
        // Dynamic Call Blink Animations
        const activeCard = document.getElementById('activeCallCard');
        const numberDisplay = document.getElementById('activeNumber');
        const roomDisplay = document.getElementById('activeRoom');
        
        // 1. Update text displays
        const formattedNum = String(nextCall.ticket_number).padStart(3, '0');
        const prefix = formattedNum.substring(0, 1);
        const suffix = formattedNum.substring(1);
        
        if (numberDisplay) {
            numberDisplay.innerHTML = `<span class="prefix-digit">${prefix}</span><span class="main-digits">${suffix}</span>`;
        }
        if (roomDisplay) {
            roomDisplay.textContent = nextCall.room;
        }

        // 2. Blink corresponding mini-card if available in bottom list
        const counters = document.querySelectorAll('.mini-card');
        let matchedCard = null;
        counters.forEach(card => {
            const counterId = card.getAttribute('data-counter-id');
            if (parseInt(counterId) === parseInt(nextCall.counter_id)) {
                matchedCard = card;
                const bodyNum = card.querySelector('.mini-card-body');
                if (bodyNum) {
                    bodyNum.innerHTML = `<span class="prefix-digit">${prefix}</span><span class="main-digits">${suffix}</span>`;
                }
            }
        });

        // Add blink animation class
        if (activeCard) activeCard.classList.add('calling-flash');
        if (matchedCard) matchedCard.classList.add('calling-flash');

        // 3. Play Chime
        await playClinicChime();
        
        // 4. Voice TTS Announcement
        await announceQueue(nextCall.ticket_number, nextCall.room);
        
        // Let it flash for 3 more seconds after announcement finishes
        setTimeout(() => {
            if (activeCard) activeCard.classList.remove('calling-flash');
            if (matchedCard) matchedCard.classList.remove('calling-flash');
            
            console.log("[Queue] Finished call processing for ticket:", nextCall.ticket_number, "ID:", nextCall.id);
            isCalling = false;
            // Process next item in callQueue if any
            processCallQueue();
        }, 3000);
    }

    async function pollState() {
        if (!pollingActive) return;
        
        try {
            const url = `${window.appSettings.state_url}?last_call_id=${window.lastCallId}`;
            const response = await fetch(url);
            const data = await response.json();
            
            // Sync settings
            const settings = data.settings || {};
            
            // Update Headers & Marquee text dynamically
            const headerTitle = document.getElementById('titleHeader');
            const headerSubtitle = document.getElementById('subtitleHeader');
            const headerAddress = document.getElementById('addressHeader');
            const marqueeText = document.getElementById('marqueeDisplay');
            const staticText = document.getElementById('staticDisplay');
            
            if (headerTitle && headerTitle.textContent !== settings.header_title) {
                headerTitle.textContent = settings.header_title;
            }
            if (headerSubtitle && headerSubtitle.textContent !== settings.header_subtitle) {
                headerSubtitle.textContent = settings.header_subtitle;
            }
            if (headerAddress && headerAddress.textContent !== settings.header_address) {
                headerAddress.textContent = settings.header_address;
            }
            if (marqueeText && marqueeText.textContent !== settings.marquee_text) {
                marqueeText.textContent = settings.marquee_text;
            }
            if (staticText && staticText.textContent !== settings.static_text) {
                staticText.textContent = settings.static_text || '';
            }
            
            // If media settings changed, re-init media
            const incomingImages = JSON.parse(settings.slideshow_images || '[]');
            if (window.appSettings.media_type !== settings.media_type || 
                window.appSettings.video_url !== settings.video_url ||
                JSON.stringify(window.appSettings.slideshow_images) !== JSON.stringify(incomingImages)) {
                
                window.appSettings.media_type = settings.media_type;
                window.appSettings.video_url = settings.video_url;
                window.appSettings.slideshow_images = incomingImages;
                initMedia();
            }

            // Sync TTS properties
            window.appSettings.speech_rate = parseFloat(settings.speech_rate || 1.0);
            window.appSettings.speech_pitch = parseFloat(settings.speech_pitch || 1.0);

            // Sync Bottom 4 Cards numbers
            const miniCards = document.querySelectorAll('.mini-card');
            const countersList = data.counters || [];
            
            miniCards.forEach((card, idx) => {
                const c = countersList[idx];
                if (c) {
                    card.setAttribute('data-counter-id', c.id);
                    const headerEl = card.querySelector('.mini-card-header');
                    const footerEl = card.querySelector('.mini-card-footer');
                    const bodyEl = card.querySelector('.mini-card-body');
                    
                    if (headerEl) headerEl.textContent = c.name;
                    if (footerEl) footerEl.textContent = c.room;
                    
                    // Only update bottom card if it's NOT currently calling to prevent visual interruption
                    if (!card.classList.contains('calling-flash')) {
                        if (c.current_call_number) {
                            const cNumStr = String(c.current_call_number).padStart(3, '0');
                            const prefix = cNumStr.substring(0, 1);
                            const suffix = cNumStr.substring(1);
                            if (bodyEl) bodyEl.innerHTML = `<span class="prefix-digit">${prefix}</span><span class="main-digits">${suffix}</span>`;
                        } else {
                            if (bodyEl) bodyEl.innerHTML = `<span class="prefix-digit">-</span><span class="main-digits">--</span>`;
                        }
                    }
                }
            });

            // Sync latest call ID on initial load if window.lastCallId is 0 and a latest call exists
            if (window.lastCallId === 0 && data.last_call) {
                window.lastCallId = data.last_call.id;
            }

            // Process all new calls in order of occurrence
            const newCalls = data.new_calls || [];
            if (newCalls.length > 0) {
                newCalls.forEach(call => {
                    if (call.id > window.lastCallId) {
                        window.lastCallId = call.id;
                    }
                    callQueue.push(call);
                });
                processCallQueue();
            }
        } catch (err) {
            console.error("Polling Error:", err);
        }
    }

    // 6. Overlay Startup authorized trigger
    const btnStart = document.getElementById('btnStart');
    const startOverlay = document.getElementById('startOverlay');
    
    if (btnStart && startOverlay) {
        btnStart.addEventListener('click', () => {
            // Unlock browser audio context
            try {
                audioCtx = new (window.AudioContext || window.webkitAudioContext)();
                if (audioCtx.state === 'suspended') {
                    audioCtx.resume();
                }
            } catch (e) {
                console.warn("Failed to initialize AudioContext:", e);
            }

            // Hide overlay
            startOverlay.style.display = 'none';
            
            // Start components
            pollingActive = true;
            initMedia();
            
            // Immediately run a state sync check, then start loop
            pollState();
            setInterval(pollState, 1000);
            
            // Pre-fetch speech voices to cache id-ID voice
            if ('speechSynthesis' in window) {
                window.speechSynthesis.getVoices();
            }
        });
    }
});
