# DESIGN.md
# PMB + CBT ADMIN SYSTEM
# Laravel 12 + TailwindCSS + AlpineJS

---

# OVERVIEW

Project ini adalah sistem PMB (Penerimaan Mahasiswa Baru) + CBT (Computer Based Test) modern berbasis Laravel 12.

Design system harus mengikuti prinsip:

- Clean UI
- Professional enterprise dashboard
- Modern academic admin panel
- High readability
- Responsive all devices
- Minimal but informative
- Production-ready layout
- SaaS-style admin interface
- Tidak berlebihan / gaming UI
- Fokus usability admin

---

# STACK

Backend:
- Laravel 12

Frontend:
- TailwindCSS
- AlpineJS
- Blade Components
- Chart.js

Build:
- Vite

---

# COLOR SYSTEM

Primary:
- #0F4C81

Primary Hover:
- #0B3A63

Background:
- #F5F7FA
- #F8FAFC

Text:
- Slate

Success:
- Emerald

Warning:
- Amber

Danger:
- Red

Border:
- slate-200

---

# UI PRINCIPLES

WAJIB:

- Rounded modern UI
- Soft shadow
- Spacious layout
- Large whitespace
- Responsive mobile first
- Professional typography
- Consistent spacing
- Minimal visual noise
- Elegant table design
- Modern SaaS feeling
- Academic enterprise style

DILARANG:

- Warna terlalu mencolok
- UI gaming
- Card terlalu ramai
- Gradient berlebihan
- Shadow terlalu keras
- Sidebar terlalu kompleks
- Analitik berlebihan
- Layout sempit

---

# GLOBAL LAYOUT

Semua halaman menggunakan struktur:

```blade
<body class="bg-[#F5F7FA] text-slate-800 antialiased">

<div
    x-data="{
        sidebarOpen: false
    }"
    class="flex h-screen overflow-hidden">

    @include('layouts.admin.sidebar')

    <div class="flex-1 flex flex-col overflow-hidden">

        {{-- TOPBAR --}}
        <header>
        </header>

        {{-- MAIN --}}
        <main class="flex-1 overflow-y-auto">
        </main>

    </div>

</div>

</body>