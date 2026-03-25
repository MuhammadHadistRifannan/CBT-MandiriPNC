<x-app-layout>

    {{-- ============================================================
         PROFILE PAGE — CBT Mandiri PNC
         Desain: card terpusat dengan header gradient biru,
         avatar overlapping, form informasi & password dalam
         satu card scrollable, tombol Hapus Akun & Back.
    ============================================================ --}}

    <style>
        .profile-card {
            background: #fff;
            border-radius: 18px;
            box-shadow: 0 8px 32px rgba(15, 101, 182, 0.13);
            overflow: hidden;
            max-width: 420px;
            width: 100%;
            margin: 0 auto;
        }

        .profile-header {
            background: linear-gradient(135deg, #0F65B6 0%, #1a85e8 60%, #E0F0FF 100%);
            padding: 22px 28px 54px;
            position: relative;
            min-height: 80px;
        }

        .profile-header::after {
            content: '';
            position: absolute;
            top: -30px;
            right: -30px;
            width: 100px;
            height: 100px;
            border-radius: 50%;
            background: rgba(255,255,255,0.13);
        }

        .profile-header-blob {
            position: absolute;
            top: 0;
            right: 0;
            width: 110px;
            height: 110px;
            background: rgba(255,255,255,0.18);
            border-radius: 0 18px 0 80px;
        }

        .profile-header h1 {
            font-size: 1.35rem;
            font-weight: 800;
            color: #fff;
            letter-spacing: 0.01em;
            position: relative;
            z-index: 1;
        }

        .profile-body {
            padding: 0 28px 24px;
        }

        /* Avatar floating overlap */
        .avatar-wrap {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-top: -48px;
            margin-bottom: 10px;
            position: relative;
            z-index: 2;
        }

        .avatar-img {
            width: 96px;
            height: 96px;
            border-radius: 50%;
            object-fit: cover;
            border: 4px solid #fff;
            box-shadow: 0 4px 16px rgba(15,101,182,0.18);
            background: #e0f0ff;
        }

        .avatar-placeholder {
            width: 96px;
            height: 96px;
            border-radius: 50%;
            border: 4px solid #fff;
            box-shadow: 0 4px 16px rgba(15,101,182,0.18);
            background: linear-gradient(135deg, #0F65B6, #1a85e8);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .avatar-placeholder svg {
            width: 48px;
            height: 48px;
            color: rgba(255,255,255,0.85);
        }

        .change-avatar-btn {
            margin-top: 8px;
            font-size: 0.78rem;
            color: #0F65B6;
            font-weight: 600;
            cursor: pointer;
            background: none;
            border: none;
            padding: 0;
            text-decoration: underline;
            text-underline-offset: 2px;
            transition: color 0.2s;
        }

        .change-avatar-btn:hover { color: #1a85e8; }

        /* Section titles */
        .section-title {
            font-size: 1rem;
            font-weight: 800;
            color: #1a2a4a;
            margin-bottom: 2px;
            margin-top: 18px;
        }

        .section-sub {
            font-size: 0.74rem;
            color: #8fa3bf;
            margin-bottom: 14px;
        }

        .divider {
            height: 1px;
            background: linear-gradient(90deg, #e0edf7 0%, #b8d4ef 50%, #e0edf7 100%);
            margin: 6px 0 20px;
        }

        /* Input fields */
        .field-label {
            font-size: 0.78rem;
            font-weight: 600;
            color: #3a5a80;
            margin-bottom: 5px;
            display: block;
        }

        .field-wrap {
            position: relative;
            margin-bottom: 13px;
        }

        .field-icon {
            position: absolute;
            left: 11px;
            top: 50%;
            transform: translateY(-50%);
            color: #7ba7cc;
            width: 16px;
            height: 16px;
        }

        .field-input {
            width: 100%;
            border: 1.5px solid #d0e4f5;
            border-radius: 9px;
            padding: 9px 12px 9px 34px;
            font-size: 0.82rem;
            color: #1a2a4a;
            background: #f7fbff;
            outline: none;
            transition: border-color 0.2s, box-shadow 0.2s;
            box-sizing: border-box;
        }

        .field-input::placeholder { color: #b0c8df; }

        .field-input:focus {
            border-color: #0F65B6;
            box-shadow: 0 0 0 3px rgba(15,101,182,0.10);
            background: #fff;
        }

        .field-input.has-toggle { padding-right: 38px; }

        .toggle-pw {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            cursor: pointer;
            color: #7ba7cc;
            padding: 0;
            display: flex;
            align-items: center;
        }

        .toggle-pw:hover { color: #0F65B6; }

        /* Buttons */
        .btn-save {
            background: linear-gradient(90deg, #0F65B6, #1a85e8);
            color: #fff;
            font-weight: 700;
            font-size: 0.82rem;
            border: none;
            border-radius: 9px;
            padding: 10px 22px;
            cursor: pointer;
            transition: opacity 0.2s, transform 0.15s;
            box-shadow: 0 2px 8px rgba(15,101,182,0.18);
        }

        .btn-save:hover { opacity: 0.88; transform: translateY(-1px); }
        .btn-save:active { transform: translateY(0); }

        .btn-delete {
            background: #1a2a4a;
            color: #fff;
            font-weight: 700;
            font-size: 0.78rem;
            border: none;
            border-radius: 9px;
            padding: 9px 18px;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            transition: background 0.2s, transform 0.15s;
            box-shadow: 0 2px 8px rgba(26,42,74,0.15);
        }

        .btn-delete:hover { background: #c0392b; transform: translateY(-1px); }

        .btn-back {
            width: 100%;
            background: #f3f7fb;
            color: #3a5a80;
            font-weight: 600;
            font-size: 0.82rem;
            border: 1.5px solid #d0e4f5;
            border-radius: 10px;
            padding: 11px;
            cursor: pointer;
            transition: background 0.2s;
            margin-top: 6px;
        }

        .btn-back:hover { background: #e0edf7; }

        /* Footer action row */
        .footer-row {
            display: flex;
            justify-content: flex-end;
            margin-top: 18px;
            margin-bottom: 4px;
        }

        /* Error / success messages */
        .msg-error {
            font-size: 0.75rem;
            color: #e53e3e;
            margin-top: 3px;
        }

        .msg-success {
            font-size: 0.78rem;
            color: #22863a;
            background: #eafaf1;
            border: 1px solid #b7ebc9;
            border-radius: 8px;
            padding: 8px 12px;
            margin-bottom: 10px;
        }

        /* Page wrapper */
        .page-center {
            min-height: 100vh;
            display: flex;
            align-items: flex-start;
            justify-content: center;
            padding: 32px 16px 48px;
        }
    </style>

    <div class="page-center">
        <div class="profile-card">

            {{-- ── Header ── --}}
            <div class="profile-header">
                <div class="profile-header-blob"></div>
                <h1>Profile</h1>
            </div>

            <div class="profile-body">

                {{-- ── Avatar ── --}}
                <div class="avatar-wrap">
                    @if(Auth::user()->avatar)
                        <img src="{{ asset('storage/' . Auth::user()->avatar) }}"
                             alt="Avatar"
                             class="avatar-img"
                             id="avatar-preview">
                    @else
                        <div class="avatar-placeholder" id="avatar-placeholder">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                      d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                        </div>
                    @endif
                    <label for="avatar-input" class="change-avatar-btn">Change Avatar</label>
                    <input type="file" id="avatar-input" name="avatar" accept="image/*" style="display:none"
                           onchange="previewAvatar(event)">
                </div>

                {{-- ── Edit Profile card sub-label ── --}}
                <div style="text-align:left; margin-bottom:2px;">
                    <span style="font-size:1rem;font-weight:800;color:#1a2a4a;">Edit Profile</span><br>
                    <span style="font-size:0.74rem;color:#8fa3bf;">Kustomisasi profile Anda.</span>
                </div>
                <div class="divider"></div>

                {{-- ── Informasi Profile ── --}}
                <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data" id="profile-info-form">
                    @csrf
                    @method('patch')

                    @if(session('status') === 'profile-updated')
                        <div class="msg-success">✓ Informasi profil berhasil disimpan.</div>
                    @endif

                    <div class="section-title">Informasi Profile</div>
                    <div class="section-sub">Update informasi profil dan email akun Anda.</div>

                    {{-- Name --}}
                    <label class="field-label" for="name">Name</label>
                    <div class="field-wrap">
                        <svg class="field-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                        <input id="name" name="name" type="text"
                               class="field-input"
                               value="{{ old('name', Auth::user()->name) }}"
                               placeholder="Masukan Nama Anda"
                               required autocomplete="name">
                        @error('name', 'updateProfileInformation')
                            <p class="msg-error">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Email --}}
                    <label class="field-label" for="email">Email</label>
                    <div class="field-wrap">
                        <svg class="field-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                        <input id="email" name="email" type="email"
                               class="field-input"
                               value="{{ old('email', Auth::user()->email) }}"
                               placeholder="Masukkan Email Aktif"
                               required autocomplete="email">
                        @error('email', 'updateProfileInformation')
                            <p class="msg-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <button type="submit" class="btn-save">Save Changes</button>
                </form>

                {{-- ── Update Password ── --}}
                <form method="POST" action="{{ route('password.update') }}" id="password-form">
                    @csrf
                    @method('put')

                    @if(session('status') === 'password-updated')
                        <div class="msg-success" style="margin-top:14px;">✓ Password berhasil diperbarui.</div>
                    @endif

                    <div class="section-title" style="margin-top:22px;">Update Password</div>
                    <div class="section-sub">Ganti password secara berkala untuk keamanan akun Anda.</div>

                    {{-- Password Sekarang --}}
                    <label class="field-label" for="current_password">Password Sekarang</label>
                    <div class="field-wrap">
                        <svg class="field-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                        <input id="current_password" name="current_password" type="password"
                               class="field-input has-toggle"
                               placeholder="Masukkan Password"
                               autocomplete="current-password">
                        <button type="button" class="toggle-pw" onclick="togglePw('current_password', this)">
                            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                        </button>
                        @error('current_password', 'updatePassword')
                            <p class="msg-error">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Password Baru --}}
                    <label class="field-label" for="password">Password Baru</label>
                    <div class="field-wrap">
                        <svg class="field-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                        <input id="password" name="password" type="password"
                               class="field-input has-toggle"
                               placeholder="Masukkan Password Baru"
                               autocomplete="new-password">
                        <button type="button" class="toggle-pw" onclick="togglePw('password', this)">
                            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                        </button>
                        @error('password', 'updatePassword')
                            <p class="msg-error">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Konfirmasi Password Baru --}}
                    <label class="field-label" for="password_confirmation">Konfirmasi Password Baru</label>
                    <div class="field-wrap">
                        <svg class="field-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                        <input id="password_confirmation" name="password_confirmation" type="password"
                               class="field-input has-toggle"
                               placeholder="Konfirmasi Password Baru"
                               autocomplete="new-password">
                        <button type="button" class="toggle-pw" onclick="togglePw('password_confirmation', this)">
                            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                        </button>
                        @error('password_confirmation', 'updatePassword')
                            <p class="msg-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <button type="submit" class="btn-save">Save Changes</button>
                </form>

                {{-- ── Hapus Akun ── --}}
                <div class="footer-row">
                    <button type="button" class="btn-delete" onclick="document.getElementById('confirm-delete-modal').style.display='flex'">
                        <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                        Hapus Akun
                    </button>
                </div>

                {{-- ── Back ── --}}
                <button type="button" class="btn-back" onclick="history.back()">Back</button>

            </div>{{-- end .profile-body --}}
        </div>{{-- end .profile-card --}}
    </div>

    {{-- ============================================================
         MODAL KONFIRMASI HAPUS AKUN
    ============================================================ --}}
    <div id="confirm-delete-modal"
         style="display:none; position:fixed; inset:0; background:rgba(15,30,60,0.45); z-index:9999;
                align-items:center; justify-content:center; padding:16px;">
        <div style="background:#fff; border-radius:16px; padding:28px 28px 22px; max-width:360px; width:100%;
                    box-shadow:0 12px 40px rgba(15,101,182,0.18);">
            <h3 style="font-size:1rem;font-weight:800;color:#1a2a4a;margin-bottom:6px;">Hapus Akun?</h3>
            <p style="font-size:0.8rem;color:#8fa3bf;margin-bottom:18px;line-height:1.5;">
                Tindakan ini tidak dapat dibatalkan. Semua data Anda akan dihapus secara permanen.
                Masukkan password Anda untuk konfirmasi.
            </p>

            <form method="POST" action="{{ route('profile.destroy') }}">
                @csrf
                @method('delete')

                <label class="field-label" for="delete_password">Password</label>
                <div class="field-wrap">
                    <svg class="field-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                    </svg>
                    <input id="delete_password" name="password" type="password"
                           class="field-input"
                           placeholder="Masukkan password Anda"
                           required>
                    @error('password', 'userDeletion')
                        <p class="msg-error">{{ $message }}</p>
                    @enderror
                </div>

                <div style="display:flex;gap:10px;margin-top:6px;">
                    <button type="button"
                            onclick="document.getElementById('confirm-delete-modal').style.display='none'"
                            style="flex:1;padding:10px;border-radius:9px;border:1.5px solid #d0e4f5;
                                   background:#f3f7fb;color:#3a5a80;font-weight:600;font-size:0.82rem;cursor:pointer;">
                        Batal
                    </button>
                    <button type="submit"
                            style="flex:1;padding:10px;border-radius:9px;border:none;
                                   background:#c0392b;color:#fff;font-weight:700;font-size:0.82rem;cursor:pointer;">
                        Hapus Akun
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- ============================================================
         SCRIPTS
    ============================================================ --}}
    <script>
        // Toggle show/hide password
        function togglePw(inputId, btn) {
            const input = document.getElementById(inputId);
            if (!input) return;
            const isHidden = input.type === 'password';
            input.type = isHidden ? 'text' : 'password';
            btn.style.color = isHidden ? '#0F65B6' : '#7ba7cc';
        }

        // Preview avatar sebelum upload
        function previewAvatar(event) {
            const file = event.target.files[0];
            if (!file) return;
            const url = URL.createObjectURL(file);

            // Hapus placeholder jika ada
            const placeholder = document.getElementById('avatar-placeholder');
            if (placeholder) placeholder.remove();

            let preview = document.getElementById('avatar-preview');
            if (!preview) {
                preview = document.createElement('img');
                preview.id = 'avatar-preview';
                preview.className = 'avatar-img';
                preview.alt = 'Avatar';
                const wrap = document.querySelector('.avatar-wrap');
                wrap.insertBefore(preview, wrap.firstChild);
            }
            preview.src = url;
        }

        // Tutup modal jika klik di luar
        document.getElementById('confirm-delete-modal').addEventListener('click', function(e) {
            if (e.target === this) this.style.display = 'none';
        });
    </script>

</x-app-layout>