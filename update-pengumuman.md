PRD — Sistem Pengumuman Berbasis Ranking Kuota Prodi
GOAL

Membuat sistem pengumuman PMB yang menentukan kelulusan peserta secara otomatis berdasarkan:

skor akhir peserta
ranking pada prodi pilihan 1
ranking pada prodi pilihan 2
kuota masing-masing prodi

Admin tidak boleh menentukan siapa lulus secara manual.

REQUIREMENT
1. Input Nomor Peserta

Peserta hanya perlu memasukkan:

nomor_peserta

Route publik:

/home/pengumuman

Sistem mencari peserta berdasarkan nomor peserta.

2. Pengumuman Hanya Aktif Sesuai Jadwal

Hasil hanya tampil jika:

announcement_batch.status = published
AND now() >= announcement_batch.announcement_date

Kalau belum waktunya:

Pengumuman belum tersedia.
Silakan kembali sesuai jadwal yang telah ditentukan.
3. Kelulusan Berdasarkan Ranking Otomatis

Sistem menghitung hasil berdasarkan:

skor_akhir peserta
pilihan_1
pilihan_2
kuota prodi

Flow seleksi:

1. Semua peserta diranking berdasarkan skor akhir
2. Peserta dicek ke pilihan_1
3. Jika ranking peserta masuk kuota pilihan_1:
   → LULUS pilihan_1
4. Jika tidak masuk kuota pilihan_1:
   → dicek ke pilihan_2
5. Jika ranking peserta masuk kuota pilihan_2:
   → LULUS pilihan_2
6. Jika tidak masuk dua-duanya:
   → TIDAK LULUS
4. Admin Tidak Mengatur Kelulusan Manual

Admin hanya boleh:

- mengatur jadwal pengumuman
- mengatur kuota prodi
- menjalankan proses generate ranking
- publish / unpublish batch pengumuman

Admin tidak boleh:

- mengubah status lulus peserta secara manual
- memilih peserta lulus satu per satu
- edit hasil akhir setelah ranking dikunci
5. Generate Hasil Seleksi

Sistem harus punya proses:

Generate Ranking Hasil Seleksi

Saat dijalankan:

- ambil semua peserta yang sudah submit ujian
- hitung skor akhir
- urutkan skor tertinggi ke terendah
- alokasikan peserta ke pilihan_1 terlebih dahulu
- jika tidak masuk, alokasikan ke pilihan_2
- simpan hasil final
6. Lock Hasil Seleksi

Setelah hasil digenerate:

ranking_locked = true

Jika sudah locked:

- hasil tidak bisa diubah manual
- skor tidak bisa dihitung ulang sembarangan
- perlu reset resmi jika ingin generate ulang
7. Barcode Jika Lulus

Jika peserta dinyatakan lulus, tampilkan barcode/QR Code.

Barcode mengarah ke:

https://pnc.ac.id

Tampilan hasil lulus:

SELAMAT!
Anda dinyatakan LULUS seleksi PMB.

Program Studi:
[Nama Prodi Diterima]

Silakan scan barcode berikut untuk informasi daftar ulang.
8. Jika Tidak Lulus

Tampilkan pesan formal:

Mohon maaf, Anda belum dinyatakan lulus pada seleksi PMB ini.
Tetap semangat dan sukses selalu.

Jangan tampilkan ranking detail jika tidak diperlukan.

FIELD
Tabel: prodis

Tambahkan:

kuota
Tabel: exam_results

Atau tabel hasil ujian:

id
user_id
ujian_id
score
submitted_at
created_at
updated_at
Tabel: announcement_batches
id
title
tahun
announcement_date
status
ranking_locked
generated_at
published_at
created_by
created_at
updated_at
Tabel: announcement_results
id
announcement_batch_id
user_id
nomor_peserta
skor_akhir
pilihan_1_id
pilihan_2_id
prodi_diterima_id
status_hasil
ranking_position
created_at
updated_at
ENUM
AnnouncementStatus
draft
published
closed
ResultStatus
lulus
tidak_lulus
NOTES
Gunakan SelectionRankingService
Gunakan database transaction saat generate hasil
Jangan lakukan ranking di Blade
Jangan admin input status kelulusan manual
Hasil final harus berasal dari skor akhir peserta
Gunakan sorting descending:
ORDER BY skor_akhir DESC
Jika skor sama, tentukan tie breaker.

Contoh tie breaker:

1. skor_akhir tertinggi
2. waktu submit lebih awal
3. nomor peserta lebih kecil
ALGORITHM RULE
for each peserta sorted by skor_akhir descending:

    if kuota pilihan_1 masih tersedia:
        lulus di pilihan_1

    else if kuota pilihan_2 masih tersedia:
        lulus di pilihan_2

    else:
        tidak_lulus
ADMIN RULE

Admin hanya mengatur:

- tanggal pengumuman
- kuota prodi
- publish batch
- generate ranking

Admin tidak boleh mengatur:

- peserta A lulus
- peserta B tidak lulus
- ubah prodi diterima manual
FINAL FLOW
Peserta submit ujian
↓
Sistem simpan skor akhir
↓
Admin set kuota prodi
↓
Admin set tanggal pengumuman
↓
Admin klik generate ranking
↓
Sistem menentukan kelulusan otomatis
↓
Admin publish pengumuman
↓
Peserta input nomor peserta
↓
Sistem tampilkan hasil
↓
Jika lulus, tampil barcode ke pnc.ac.id

Ini konsepnya lebih aman karena kelulusan berbasis sistem, bukan keputusan manual admin.