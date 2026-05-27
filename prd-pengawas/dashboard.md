FITUR PENGAWAS 
 - SIDEBAR (DASHBOARD , MONITORING UJIAN , CHECK-IN PESERTA , PESERTA AKTIF , AKTIFITAS PESERTA , BROADCAST PESAN , PENGATURAN SESI , LAPORAN PENGAWAS , LOGOUT)

🔹 1. Dashboard

Route: /pengawas/dashboard
Isi:

summary cards (total, aktif, selesai, bermasalah)
quick monitoring
alert singkat

👉 ini landing utama pengawas

🔹 2. Monitoring Ujian

Route: /pengawas/ujian/monitoring
Isi:

live table semua peserta
status real-time
aksi:
detail
pause
force submit
flag

👉 ini core feature utama

🔹 3. Peserta Aktif

Route: /pengawas/peserta-aktif
Isi:

hanya peserta yang sedang ujian
fokus ke yang berjalan sekarang

👉 biar gak ke-distract sama yang selesai

🔹 4. Aktivitas Peserta

Route: /pengawas/aktivitas
Isi:

log:
tab switching
idle
refresh
filter per user

👉 buat deteksi kecurangan

🔹 5. Broadcast Pesan

Route: /pengawas/broadcast
Isi:

kirim pesan ke semua peserta
history pesan

👉 komunikasi cepat saat ujian

🔹 6. Pengaturan Sesi

Route: /pengawas/sesi
Isi:

start ujian
stop ujian
extend waktu

👉 kontrol global ujian

🔹 7. Laporan Pengawasan

Route: /pengawas/laporan
Isi:

peserta flagged
aktivitas mencurigakan
export (optional)

👉 buat admin review nanti

🔹 8. Logout

👉 obvious, tapi tetap di sidebar/footer

    9. Check-in Peserta Ujian (Scan Kartu Ujian)
    Memastikan hanya peserta yang sudah diverifikasi secara fisik oleh pengawas yang dapat mengikuti ujian CBT.
    