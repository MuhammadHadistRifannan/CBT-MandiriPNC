# 🎯 Goal

Menampilkan daftar peserta ujian secara real-time.

# 📋 Requirement
Tampilkan list peserta yang sedang ujian
# Field:
nama
status (belum mulai, sedang, selesai, idle)
waktu mulai
sisa waktu
progress (%)
UI table + badge status (Tailwind)
# ✅ Acceptance Criteria
Data tampil tanpa reload (polling minimal 5 detik)
Status berubah sesuai kondisi peserta
Responsive (mobile + desktop)
# 📝 Notes
Gunakan AlpineJS polling atau interval fetch
Gunakan enum untuk status ujian