<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'CBT Mandiri PNC') }} - Pilih Prodi</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased text-gray-900 bg-white">

    <div x-data="{ sidebarExpanded: true }" class="flex h-screen overflow-hidden">
        
        @include('layouts.dashboard.sidebar')

        <div class="flex-1 flex flex-col overflow-hidden relative">
            
            <header class="h-24 bg-gradient-to-r from-[#1E78D0] via-[#5C9CE0] to-white flex items-center justify-between px-8 sm:px-12 relative z-10 shrink-0">
                <img src="{{ asset('assets/images/corner.png') }}" alt="Wave Header" class="absolute top-0 left-0 h-full w-auto opacity-40 pointer-events-none transform -scale-x-100 object-cover mix-blend-overlay">

                <div class="flex items-center relative z-10">

                    <h1 class="text-3xl sm:text-4xl font-extrabold text-white tracking-wide drop-shadow-sm">
                        Pilih Prodi
                    </h1>
                </div>

                <div class="relative z-10 flex items-center" x-data="{ open: false }">
                    <button @click="open = !open" @click.outside="open = false" class="flex items-center bg-[#F3F4F6] hover:bg-[#E5E7EB] transition-colors duration-200 py-1.5 px-2.5 rounded-md border border-gray-200 shadow-sm focus:outline-none">
                        <img src="https://ui-avatars.com/api/?name=User&background=0D8ABC&color=fff" alt="Profile Avatar" class="w-7 h-7 rounded-full object-cover mr-2">
                        <span class="text-xs font-semibold text-gray-700 mr-2">Profile</span>
                        <svg :class="{'rotate-180': open}" class="w-3 h-3 text-gray-500 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M19 9l-7 7-7-7"></path></svg>
                    </button>
                    
                    <div x-show="open" style="display: none;" class="absolute right-0 top-full mt-2 w-48 bg-white rounded-lg shadow-xl border border-gray-100 py-2">
                        <a href="{{ route('profile.edit') }}" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-[#1E78D0]">Profile</a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="flex items-center w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50">Logout</button>
                        </form>
                    </div>
                </div>
            </header>

            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-white p-8 sm:p-12 relative z-0">
                
                <form action="#" method="POST" class="max-w-5xl mx-auto space-y-8">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        
                        <div>
                            <label class="block text-xl font-extrabold text-black mb-3">Pilihan Prodi 1</label>
                            <div class="relative">
                                <select class="block w-full appearance-none bg-white border border-gray-500 text-black font-bold py-3.5 px-4 rounded-xl leading-tight focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                                    <option>D3 - Teknik Informatika</option>
                                    <option>D3 - Teknik Mesin</option>
                                    <option>D3 - Teknik Listrik</option>
                                </select>
                                
                            </div>
                        </div>

                        <div>
                            <label class="block text-xl font-extrabold text-black mb-3">Pilihan Prodi 2</label>
                            <div class="relative">
                                <select class="block w-full appearance-none bg-white border border-gray-500 text-black font-bold py-3.5 px-4 rounded-xl leading-tight focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                                    <option>D3 - Teknik Elektronika</option>
                                    <option>D3 - Teknik Pengendalian Pencemaran Lingkungan</option>
                                </select>
                                
                            </div>
                        </div>

                    </div>

                    <div>
                        <label class="block text-lg font-extrabold text-black mb-3">Rasio Keketatan Prodi</label>
                        <div class="border border-gray-500 rounded-xl p-5 space-y-4">
                            
                            <div class="bg-[#F4F4F4] rounded-xl p-4">
                                <h3 class="font-bold text-gray-800 text-base mb-1">D3 - Teknik Informatika</h3>
                                <div class="flex flex-wrap gap-x-8 text-sm text-gray-600 font-medium">
                                    <span>Peminat 2025 : 1024</span>
                                    <span>Daya Tampung : 128</span>
                                    <span>Rasio Keketatan : 12.5%</span>
                                </div>
                            </div>

                            <div class="bg-[#F4F4F4] rounded-xl p-4">
                                <h3 class="font-bold text-gray-800 text-base mb-1">D3 - Teknik Elektronika</h3>
                                <div class="flex flex-wrap gap-x-8 text-sm text-gray-600 font-medium">
                                    <span>Peminat 2025 : 3022</span>
                                    <span>Daya Tampung : 128</span>
                                    <span>Rasio Keketatan : 4.23%</span>
                                </div>
                            </div>

                        </div>
                    </div>

                    <div>
                        <label class="block text-lg font-extrabold text-black mb-3">Pernyataan Persetujuan</label>
                        <div class="border border-gray-500 rounded-xl p-5 flex items-start gap-3">
                            <input type="checkbox" id="persetujuan" required class="mt-1 w-5 h-5 rounded border-gray-400 text-[#307DCA] focus:ring-[#307DCA] cursor-pointer">
                            
                            <label for="persetujuan" class="text-xs sm:text-sm text-black font-medium leading-relaxed cursor-pointer">
                                Saya telah membaca seluruh ketentuan yang berlaku dan telah memenuhi syarat yang diperlukan.<br>
                                Saya bersedia menerima konsekuensi jika terdapat pelanggaran aturan oleh Saya sendiri.<br>
                                Saya bersiap menerima seluruh hasil dan hasil ujian tidak dapat diubah lagi dan ditoleransi.
                            </label>
                        </div>
                    </div>

                    <div class="flex justify-center pt-4">
                        <button type="submit" class="bg-[#307DCA] hover:bg-[#2563A3] text-white font-bold py-3 px-10 rounded-xl transition duration-300 shadow-sm text-lg">
                            Simpan Permanen
                        </button>
                    </div>

                </form>

            </main>

        </div>
    </div>

</body>
</html>