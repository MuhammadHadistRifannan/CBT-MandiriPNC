# PRD — HALAMAN PENGUMUMAN HASIL PMB

## 🎯 GOAL

Menyediakan halaman resmi pengumuman hasil seleksi PMB yang dapat diakses publik melalui halaman utama `/home`, dengan konsep pengalaman pengguna yang menyerupai sistem pengumuman SNBT/SNBP pemerintah.

Peserta dapat melakukan pengecekan hasil seleksi menggunakan nomor peserta.

---

# 📋 REQUIREMENT

## 1. Integrasi Navigasi Home

Halaman pengumuman harus tersedia pada navigasi utama website publik.

Navigation:

```txt
/home
```

Route:

```txt
/home/pengumuman
```

---

## 2. Halaman Landing Pengumuman

Halaman pengumuman harus memiliki tampilan formal, clean, dan akademik seperti portal SNBT/SNBP.

Konten utama:

* Logo institusi
* Judul pengumuman
* Tahun seleksi
* Informasi jadwal pengumuman
* Form input nomor peserta
* Tombol cek hasil

---

## 3. Form Cek Pengumuman

Peserta melakukan pengecekan menggunakan:

* nomor peserta

Optional tambahan:

* tanggal lahir
* captcha

---

## 4. Validasi Pengumuman

Sistem harus:

* mencari data berdasarkan nomor peserta
* memastikan pengumuman sudah dipublish admin
* menolak akses jika pengumuman belum dibuka

---

## 5. Tampilan Hasil Pengumuman

Jika data ditemukan, tampilkan:

### Informasi Peserta

* nama peserta
* nomor peserta
* program studi diterima
* jalur seleksi
* tahun seleksi

### Status Hasil

* LULUS
* TIDAK LULUS
* CADANGAN

---

## 6. Desain Status Hasil

### Jika LULUS

* card hijau / emerald
* typography besar
* ucapan selamat

Contoh:

```txt
SELAMAT!
Anda dinyatakan LULUS seleksi PMB PNC 2026.
Anda diterima di Program Studi (D3/D4) (Prodi)
```

---

### Jika TIDAK LULUS

* card netral/slate
* tetap clean dan formal

Contoh:

```txt
Peserta belum dinyatakan lulus pada jalur seleksi ini.
Tetap semangat dan sukses selalu.
```

---

### Jika CADANGAN

* card amber/warning

---

## 7. Publish Control

Pengumuman hanya dapat ditampilkan jika:

```txt
announcement_status = published
```

Sebelum dipublish:

* peserta tidak dapat melihat hasil
* tampilkan halaman holding

Contoh:

```txt
Pengumuman belum tersedia.
Silakan kembali sesuai jadwal yang telah ditentukan.
```

---

## 8. Responsive Design

Halaman wajib:

* mobile friendly
* responsive
* centered layout
* modern academic style

---

## 9. Anti Enumeration

Sistem harus:

* membatasi request berulang
* menggunakan rate limit
* mencegah brute-force nomor peserta

---

# 🧱 FIELD

## Tabel: announcements

```txt
id
user_id
nomor_peserta
status_hasil
prodi_diterima
jalur_seleksi
announcement_status
published_at
created_at
updated_at
```

---

## ENUM

### status_hasil

```txt
lulus
tidak_lulus
cadangan
```

### announcement_status

```txt
draft
published
closed
```

---

# 📝 NOTES

* Gunakan desain mirip portal SNBT/SNBP:

  * clean
  * formal
  * centered
  * dominasi putih + biru akademik
  * minim clutter

* Jangan tampilkan:

  * nilai mentah CBT
  * ranking internal
  * detail scoring

* Fokus hanya pada hasil akhir seleksi.

* Gunakan TailwindCSS.

* Gunakan Blade template.

* Gunakan route name Laravel.

* Gunakan controller terpisah:

```txt
AnnouncementController
```

* Gunakan middleware throttling/rate-limit.

* Jangan expose seluruh data peserta melalui endpoint publik.

* Disarankan menggunakan:

```txt
nomor_peserta + tanggal_lahir
```

untuk validasi tambahan.

---

# 🎨 UI CONCEPT

Konsep visual:

* seperti SNBT pemerintah
* clean white layout
* centered card
* institutional feel
* formal typography
* soft shadow
* rounded-xl / rounded-2xl
* large title section
* minimal sidebar
* public landing style (bukan dashboard admin)

---

# 🔐 SECURITY NOTES

* gunakan rate limiting
* jangan gunakan incremental predictable endpoint
* validasi semua input backend
* gunakan query exact match
* log aktivitas pengecekan hasil

---

# 🚫 OUT OF SCOPE

* edit hasil oleh peserta
* realtime ranking
* tampilan nilai CBT detail
* export sertifikat kelulusan
