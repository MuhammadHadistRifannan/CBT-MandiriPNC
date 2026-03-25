<div class="hidden lg:flex lg:w-1/2 bg-gradient-to-b from-[#0F65B6] to-[#E0F0FF] relative overflow-hidden flex-col justify-center items-center p-12">

    <img src="{{ asset('assets/images/corner.png') }}" 
         alt="Wave Decoration Top Right" 
         class="absolute top-0 right-0 w-64 opacity-80 pointer-events-none transform rotate-90">

    <img src="{{ asset('assets/images/corner.png') }}" 
         alt="Wave Decoration Bottom Left" 
         class="absolute bottom-0 left-0 w-80 opacity-80 pointer-events-none transform -rotate-90">

    <div class="relative z-10 flex flex-col items-center">
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