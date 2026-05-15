<div class="hidden lg:flex lg:w-1/2 relative overflow-hidden flex-col justify-center items-center p-12 bg-cover bg-center bg-no-repeat" 
     style="background-image: url('assets/images/bg.png');">

    <!-- Overlay agar foto agak gelap dan teks tetap terbaca -->
    <div class="absolute inset-0 bg-[#0F65B6]/40"></div>

    <img src="{{ asset('assets/images/corner.png') }}" 
         alt="Wave Decoration Top Right" 
         class="absolute top-0 right-0 w-64 opacity-80 pointer-events-none transform rotate-90 z-10">

    <img src="{{ asset('assets/images/corner.png') }}" 
         alt="Wave Decoration Bottom Left" 
         class="absolute bottom-0 left-0 w-80 opacity-80 pointer-events-none transform -rotate-90 z-10">

    <div class="relative z-20 flex flex-col items-center">
        <div class="mb-6">
            <img src="{{ asset('assets/images/pnc-logo.png') }}" 
                 alt="Logo CBT Mandiri PNC" 
                 class="w-32 h-auto drop-shadow-md">
        </div>

        <h1 class="text-3xl font-bold text-white mb-3 tracking-wide drop-shadow-sm">
            CBT Mandiri PNC
        </h1>

        <p class="text-center text-white text-base leading-relaxed max-w-sm font-medium opacity-90 drop-shadow-sm">
            Platform ujian mandiri Politeknik Negeri Cilacap. <br>
            Terintegrasi dengan sistem kampus.
        </p>
    </div>

</div>
<x-alert/>