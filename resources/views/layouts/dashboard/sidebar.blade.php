<aside x-data="{ expanded: true }" 
       :class="expanded ? 'w-64' : 'w-20'"
       class="h-screen flex flex-col bg-gradient-to-b from-[#0F65B6] to-[#E0F0FF] relative overflow-hidden shrink-0 shadow-lg transition-all duration-300 ease-in-out z-20">
    
    <div class="absolute top-6 left-6 z-30">
        <button @click="expanded = !expanded" class="text-white hover:text-gray-200 focus:outline-none transition-transform hover:scale-110">
            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
            </svg>
        </button>
    </div>

    <img src="{{ asset('assets/images/corner.png') }}" 
         :class="expanded ? 'opacity-80' : 'opacity-0'"
         alt="Wave Top" 
         class="absolute top-0 right-0 w-32 pointer-events-none transform rotate-90 transition-opacity duration-300">
    <img src="{{ asset('assets/images/corner.png') }}" 
         :class="expanded ? 'opacity-80' : 'opacity-0'"
         alt="Wave Bottom" 
         class="absolute bottom-0 left-0 w-48 pointer-events-none transform -rotate-90 transition-opacity duration-300">

    <div class="relative z-10 flex flex-col h-full mt-12">
        
        <div class="flex flex-col items-center pt-6 pb-6 transition-all duration-300">
            <img src="{{ asset('assets/images/pnc-logo.png') }}" 
                 alt="Logo CBT Mandiri PNC" 
                 :class="expanded ? 'w-20 h-20 mb-3' : 'w-10 h-10 mb-1'"
                 class="drop-shadow-md transition-all duration-300">
            <h2 x-show="expanded" x-transition.opacity.duration.300ms class="text-white text-sm font-bold tracking-widest drop-shadow-sm whitespace-nowrap">
                CBT Mandiri PNC
            </h2>
        </div>

        <nav :class="expanded ? 'px-4' : 'px-2'" class="flex-1 space-y-2 mt-2 transition-all duration-300">

            {{-- Home --}}
            <a href="{{ route('dashboard') }}" 
               data-spa-link
               :class="expanded ? 'justify-start px-4' : 'justify-center px-0'" 
               class="spa-nav-link flex items-center py-3 rounded-xl transition-all duration-300 group {{ request()->routeIs('dashboard') ? 'bg-white/20 text-white font-bold shadow-sm' : 'text-white/80 hover:bg-white/20 hover:text-white font-medium' }}">
                <svg :class="expanded ? 'mr-3' : 'mr-0'" class="w-5 h-5 shrink-0 transition-all duration-300 group-hover:scale-110" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path>
                </svg>
                <span x-show="expanded" class="text-sm whitespace-nowrap">Home</span>
            </a>

            {{-- Pilih Prodi --}}
            <a href="{{ route('prodi') }}" 
               data-spa-link
               :class="expanded ? 'justify-start px-4' : 'justify-center px-0'" 
               class="spa-nav-link flex items-center py-3 rounded-xl transition-all duration-300 group {{ request()->routeIs('prodi') ? 'bg-white/20 text-white font-bold shadow-sm' : 'text-white/80 hover:bg-white/20 hover:text-white font-medium' }}">
                <img src="{{ asset('assets/images/school.png') }}" alt="Pilih Prodi" :class="expanded ? 'mr-3' : 'mr-0'" class="w-5 h-5 object-contain shrink-0 transition-all duration-300 group-hover:scale-110">
                <span x-show="expanded" class="text-sm whitespace-nowrap">Pilih Prodi</span>
            </a>

            {{-- Mulai Ujian --}}
            <a href="#" 
               data-spa-link
               :class="expanded ? 'justify-start px-4' : 'justify-center px-0'" 
               class="spa-nav-link flex items-center py-3 rounded-xl transition-all duration-300 ease-in-out group text-white/80 hover:bg-white/20 hover:text-white font-medium">
                <img src="{{ asset('assets/images/draw-pen.png') }}" alt="Mulai Ujian" :class="expanded ? 'mr-3' : 'mr-0'" class="w-5 h-5 object-contain shrink-0 transition-all duration-300 group-hover:scale-110">
                <span x-show="expanded" class="text-sm whitespace-nowrap">Mulai Ujian</span>
            </a>

            {{-- Cetak Kartu --}}
            <a href="#" 
               data-spa-link
               :class="expanded ? 'justify-start px-4' : 'justify-center px-0'" 
               class="spa-nav-link flex items-center py-3 rounded-xl transition-all duration-300 ease-in-out group text-white/80 hover:bg-white/20 hover:text-white font-medium">
                <img src="{{ asset('assets/images/credit-card.png') }}" alt="Cetak Kartu" :class="expanded ? 'mr-3' : 'mr-0'" class="w-5 h-5 object-contain shrink-0 transition-all duration-300 group-hover:scale-110">
                <span x-show="expanded" class="text-sm whitespace-nowrap">Cetak Kartu</span>
            </a>

        </nav>

        <div :class="expanded ? 'p-6' : 'p-3'" class="mb-2 mt-auto transition-all duration-300">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" :class="expanded ? 'px-4' : 'px-0'" title="Sign Out" class="w-full bg-[#F59E0B] hover:bg-[#D97706] text-white font-bold py-3 rounded-xl shadow-md transition duration-300 flex justify-center items-center">
                    <svg x-show="!expanded" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display: none;">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                    </svg>
                    <span x-show="expanded" class="whitespace-nowrap">Sign Out</span>
                </button>
            </form>
        </div>

    </div>
</aside>

{{-- =============================================
     SPA NAVIGATION SCRIPT (UPDATED)
     ============================================= --}}
<style>
    /* Progress bar di atas halaman */
    #spa-progress {
        position: fixed;
        top: 0;
        left: 0;
        height: 3px;
        width: 0%;
        background: linear-gradient(90deg, #F59E0B, #fff, #F59E0B);
        background-size: 200% 100%;
        z-index: 9999;
        transition: width 0.3s ease;
        animation: spaProgressShimmer 1s linear infinite;
        border-radius: 0 2px 2px 0;
        display: none;
    }

    @keyframes spaProgressShimmer {
        0%   { background-position: 200% 0; }
        100% { background-position: -200% 0; }
    }

    /* Animasi konten masuk & keluar */
    @keyframes spaFadeSlideIn {
        from { opacity: 0; transform: translateY(10px); }
        to   { opacity: 1; transform: translateY(0); }
    }
    @keyframes spaFadeSlideOut {
        from { opacity: 1; transform: translateY(0); }
        to   { opacity: 0; transform: translateY(-8px); }
    }

    .spa-content-enter {
        animation: spaFadeSlideIn 0.3s cubic-bezier(0.25, 0.46, 0.45, 0.94) forwards;
    }
    .spa-content-leave {
        animation: spaFadeSlideOut 0.2s ease forwards;
    }

    /* Active link highlight */
    .spa-nav-link.spa-active {
        background-color: rgba(255, 255, 255, 0.2);
        color: white;
        font-weight: 700;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    }
</style>

{{-- Progress bar element --}}
<div id="spa-progress"></div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const CONTENT_ID    = 'spa-content';   
    const NAV_LINK_ATTR = 'data-spa-link'; 
    const ACTIVE_CLASS  = 'spa-active';    

    const progressBar = document.getElementById('spa-progress');
    let isNavigating = false;

    // ---- Progress Bar Logic ----
    function showProgress() {
        progressBar.style.display = 'block';
        progressBar.style.width   = '0%';
        requestAnimationFrame(() => {
            progressBar.style.width = '70%';
        });
    }

    function finishProgress() {
        progressBar.style.width = '100%';
        setTimeout(() => {
            progressBar.style.display = 'none';
            progressBar.style.width   = '0%';
        }, 300);
    }

    // ---- Update Active Link ----
    function updateActiveLinks(url) {
        document.querySelectorAll(`[${NAV_LINK_ATTR}]`).forEach(link => {
            const href = link.getAttribute('href');
            // Pastikan tidak me-match link kosong atau '#'
            if (href && href !== '#' && url.split('?')[0] === href.split('?')[0]) {
                link.classList.add(ACTIVE_CLASS);
            } else {
                link.classList.remove(ACTIVE_CLASS);
            }
        });
    }

    // ---- Fungsi Utama Navigasi SPA ----
    async function navigateTo(url, pushState = true) {
        if (isNavigating || url === window.location.href || url === '#') return;
        isNavigating = true;

        showProgress();
        const contentEl = document.getElementById(CONTENT_ID);

        // 1. Animasi keluar konten lama
        if (contentEl) {
            contentEl.classList.add('spa-content-leave');
            await new Promise(r => setTimeout(r, 200)); // Tunggu animasi selesai
        }

        try {
            // 2. Fetch halaman baru
            const response = await fetch(url, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-SPA-Request': 'true',
                },
            });

            if (!response.ok) throw new Error(`HTTP Error ${response.status}`);

            const html = await response.text();
            const parser = new DOMParser();
            const doc = parser.parseFromString(html, 'text/html');

            const newContent = doc.getElementById(CONTENT_ID);
            const newTitle = doc.title;

            if (newContent && contentEl) {
                // 3. Swap Konten
                contentEl.innerHTML = newContent.innerHTML;

                // 4. Update Judul & History Browser
                document.title = newTitle;
                if (pushState) {
                    history.pushState({ url }, newTitle, url);
                }

                // 5. Re-init Alpine.js (Kompatibilitas Laravel Breeze / Alpine v3)
                if (window.Alpine) {
                    Alpine.initTree(contentEl); 
                }

                // 6. Jalankan Animasi Masuk
                contentEl.classList.remove('spa-content-leave');
                contentEl.classList.add('spa-content-enter');
                contentEl.addEventListener('animationend', () => {
                    contentEl.classList.remove('spa-content-enter');
                }, { once: true });

                updateActiveLinks(url);
                window.scrollTo({ top: 0, behavior: 'smooth' });
            } else {
                // Fallback jika tidak ada id="spa-content" di halaman tujuan
                window.location.href = url;
            }
        } catch (err) {
            console.error('[SPA Error]:', err);
            window.location.href = url; // Fallback hard-reload jika error
        } finally {
            finishProgress();
            isNavigating = false;
        }
    }

    // ---- Event Listener untuk klik link ----
    document.addEventListener('click', function (e) {
        const link = e.target.closest(`[${NAV_LINK_ATTR}]`);
        if (!link) return;

        const href = link.getAttribute('href');
        if (!href || href === '#') return;

        // Izinkan open in new tab (Ctrl+Click / Cmd+Click)
        if (e.ctrlKey || e.metaKey || e.shiftKey) return;

        e.preventDefault();
        navigateTo(href);
    });

    // ---- Handle tombol Back/Forward Browser ----
    window.addEventListener('popstate', function (e) {
        if (e.state && e.state.url) {
            navigateTo(e.state.url, false);
        } else {
            window.location.reload();
        }
    });

    // Inisialisasi awal
    history.replaceState({ url: window.location.href }, document.title, window.location.href);
    updateActiveLinks(window.location.href);
});
</script>