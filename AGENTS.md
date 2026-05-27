# AGENTS.md

# PROJECT CONTEXT

Project ini adalah sistem PMB (Penerimaan Mahasiswa Baru) + CBT (Computer Based Test) berbasis Laravel 12.

Tujuan sistem:

* Pendaftaran peserta PMB
* Pemilihan Program Studi
* Pembayaran menggunakan Midtrans
* Upload dan verifikasi dokumen
* CBT / ujian online
* Dashboard admin dan pengawas

Stack utama:

* Laravel 12
* PHP 8.3+
* TailwindCSS
* AlpineJS
* MySQL
* Midtrans Snap API
* Breeze Authentication

Frontend menggunakan:

* Blade Template
* AlpineJS
* TailwindCSS
* Responsive Design
* Modern SaaS Dashboard UI

Backend menggunakan:

* Service Pattern
* Repository optional
* Enum untuk role/status
* Middleware role-based access

Constraints:
- Laravel 12
- Use Service Pattern
- Use Enum for status
- No business logic in controller

Deliver:
- Controller
- Service class
- Route
- Validation

==================================================
ROLE SYSTEM
===========

Project memiliki 3 role utama:

1. admin
2. pengawas
3. user

Behavior:

* admin masuk ke /admin/dashboard
* pengawas masuk ke /pengawas/dashboard
* user masuk ke /dashboard

Role disimpan di:
users.role

Gunakan middleware role-based.

JANGAN:

* hardcode role di controller
* menggunakan gate sederhana jika middleware sudah ada

Gunakan:

* RoleBasedMiddleware
* enum role

==================================================
AUTHENTICATION
==============

Authentication menggunakan Laravel Breeze.

Redirect login berdasarkan role:

* admin -> admin.dashboard
* pengawas -> pengawas.dashboard
* user -> dashboard

==================================================
PAYMENT SYSTEM
==============

Pembayaran menggunakan Midtrans Snap API.

Payment methods:

* BNI Virtual Account
* QRIS

Flow:

1. User simpan permanen pilihan prodi
2. System generate billing
3. Midtrans generate snap token
4. Snap token disimpan ke database
5. User bayar
6. Midtrans callback update status pembayaran

IMPORTANT:

* Tidak ada verifikasi pembayaran manual
* Status transaksi otomatis dari Midtrans webhook

Billing fields:

* kode_bayar
* snap_token
* virtual_account
* payment_type
* transaction_status
* gross_amount

Gunakan:

* settlement = pembayaran berhasil
* pending = belum bayar
* expire = expired

==================================================
PROGRAM STUDI SYSTEM
====================

User memilih:

* pilihan_1
* pilihan_2

Pilihan tidak boleh sama.

Setelah simpan permanen:

* pilihan tidak dapat diubah
* billing otomatis dibuat

Keketatan:

* disimpan sebagai decimal
* contoh: 0.42
* tampilkan sebagai persen di frontend

==================================================
DOCUMENT SYSTEM
===============

Peserta upload:

* pas foto
* kartu identitas
* surat keterangan
* ijazah

Storage:
storage/app/public

Gunakan:
php artisan storage:link

Dokumen diverifikasi admin.

Status dokumen:

* pending
* verified
* rejected

==================================================
CBT SYSTEM
==========

Fitur CBT:

* bank soal
* import soal excel
* ujian online
* monitoring peserta
* nilai otomatis

Jenis soal:

* pilihan ganda
* essay

==================================================
ADMIN PANEL
===========

Admin dashboard memiliki fitur:

1. Dashboard statistik
2. Manajemen peserta
3. Verifikasi dokumen
4. Monitoring pembayaran
5. Manajemen program studi
6. Bank soal
7. CBT monitoring
8. Export laporan

UI style:

* modern
* clean
* premium SaaS
* rounded card
* responsive
* dark sidebar

==================================================
CODING STANDARDS
================

Gunakan:

* clean architecture sederhana
* service class untuk business logic
* enum untuk constant status
* request validation
* eager loading jika perlu
* tailwind utility classes
* alpinejs reactive state

JANGAN:

* query database langsung di blade
* business logic di view
* hardcode URL
* hardcode role string berulang
* membuat controller terlalu besar

==================================================
LARAVEL CONVENTION
==================

Gunakan:

* route name
* route group middleware
* form request validation
* updateOrCreate jika diperlukan
* storage facade
* auth helper

Prefer:

* service layer
* reusable component
* blade partial/component

==================================================
UI / UX RULES
=============

Style UI:

* modern academic dashboard
* clean spacing
* rounded-2xl / rounded-3xl
* soft shadow
* responsive mobile-first

Color palette:

* Primary: #0F4C81
* Secondary: slate
* Success: emerald
* Warning: amber
* Danger: red

==================================================
IMPORTANT PROJECT RULES
=======================

* Jangan ubah struktur role system tanpa alasan
* Jangan menghapus Midtrans flow
* Jangan membuat pembayaran manual
* Jangan membuat logic di blade
* Jangan generate fake endpoint
* Jangan hallucinate nama model/table
* Selalu gunakan existing naming convention

==================================================
MAIN ENTITIES
=============

Core models:

* User
* Prodi
* PilihanProdi
* Billing
* Dokumen
* Soal
* Ujian

==================================================
EXPECTED AI BEHAVIOR
====================

AI Agent harus:

* mengikuti struktur Laravel 12
* menjaga konsistensi naming
* menggunakan TailwindCSS
* mempertahankan arsitektur role-based
* tidak membuat feature di luar konteks PMB + CBT
* menggunakan clean modern admin dashboard pattern

Jika membuat UI:

* gunakan TailwindCSS
* gunakan AlpineJS bila perlu
* responsive
* production-ready

Jika membuat backend:

* gunakan Laravel convention
* gunakan validation
* gunakan middleware
* gunakan enum jika cocok
* gunakan service pattern bila logic kompleks
