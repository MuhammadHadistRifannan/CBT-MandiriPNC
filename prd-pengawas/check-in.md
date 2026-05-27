🎯 GOAL

Memastikan peserta hanya dapat mengikuti ujian CBT setelah diverifikasi oleh pengawas melalui proses scan kartu ujian.

📋 REQUIREMENT
1. Akses Menu
Menu: Check-in Peserta
Route: /pengawas/check-in
Hanya role pengawas yang dapat mengakses
2. Scan Kartu Ujian
Sistem menyediakan:
QR Code scanner (kamera)
Input manual sebagai fallback
Hasil scan berupa kode_ujian
3. Validasi Peserta

Setelah scan, sistem harus:

mencari peserta berdasarkan kode_ujian
memastikan:
peserta ada
belum check-in
belum menyelesaikan ujian
4. Validasi Kelayakan

Peserta hanya bisa check-in jika:

pembayaran status = settlement
sudah memilih program studi
tidak dalam status blocked
5. Tampilkan Data Peserta

Setelah valid:

nama peserta
nomor ujian
pilihan prodi
status pembayaran
status ujian
6. Konfirmasi Check-in
Pengawas klik tombol Konfirmasi Check-in
Sistem menyimpan status check-in
7. Update Status Peserta
status ujian berubah menjadi checked_in
simpan waktu check-in
simpan ID pengawas
8. Pembatasan Akses Ujian
peserta hanya bisa masuk ujian jika status = checked_in
9. Prevent Duplicate
peserta yang sudah check-in tidak bisa check-in ulang
tampilkan notifikasi error
🧱 FIELD
Tabel: ujian (atau tabel terkait peserta ujian)
id
user_id
kode_ujian
status
checked_in_at
pengawas_id
created_at
updated_at
Enum Status Ujian
not_checked_in
checked_in
in_exam
submitted
blocked
Optional (Jika pakai tabel terpisah)

Tabel: exam_check_ins

id
user_id
ujian_id
pengawas_id
checked_in_at
method (qr/manual)
created_at
updated_at
📝 NOTES
Gunakan Service Pattern untuk logic check-in
Gunakan Enum untuk status ujian
Jangan taruh logic di controller
Gunakan middleware role-based (pengawas)
Gunakan validasi backend (jangan hanya frontend)
QR Code harus menggunakan signature (hash) agar tidak bisa dipalsukan
Gunakan transaction saat update status
UI menggunakan Tailwind + AlpineJS
Tambahkan feedback:
success (check-in berhasil)
error (invalid / sudah check-in / belum bayar)
Disarankan gunakan polling untuk update status di sisi peserta