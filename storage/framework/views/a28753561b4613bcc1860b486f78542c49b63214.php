<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title>Registrasi Guru | Smart Center Indonesia</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #c84ddf;
            --primary-dark: #68117e;
            --deep: #260632;
            --text: #1e0828;
            --muted: #6b5878;
            --white: #ffffff;
            --soft: #f8f5fb;
            --border: rgba(104, 17, 126, 0.12);
            --font-sans: 'Inter', 'Segoe UI', sans-serif;
            --font-display: 'Plus Jakarta Sans', 'Inter', sans-serif;
        }

        * { box-sizing: border-box; }
        body {
            margin: 0;
            min-height: 100vh;
            font-family: var(--font-sans);
            color: var(--text);
            background:
                radial-gradient(circle at top left, rgba(200, 77, 223, 0.18), transparent 32%),
                linear-gradient(135deg, #fdf8ff 0%, #f8f5fb 60%, #fff 100%);
        }

        .register-shell {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .register-hero {
            position: relative;
            overflow: hidden;
            padding: 48px;
            display: flex;
            align-items: center;
            background:
                linear-gradient(135deg, rgba(38, 6, 50, 0.92), rgba(104, 17, 126, 0.88)),
                url('https://images.unsplash.com/photo-1522202176988-66273c2fd55f?auto=format&fit=crop&w=1200&q=80') center/cover;
            color: white;
        }

        .register-hero::after {
            content: '';
            position: absolute;
            inset: 0;
            background: radial-gradient(circle at top right, rgba(255,255,255,0.2), transparent 32%);
            pointer-events: none;
        }

        .register-hero-inner {
            position: relative;
            z-index: 1;
            max-width: 520px;
        }

        .register-kicker {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 12px;
            border-radius: 999px;
            background: rgba(255,255,255,0.16);
            border: 1px solid rgba(255,255,255,0.18);
            font-size: 12px;
            letter-spacing: 0.06em;
            text-transform: uppercase;
            font-weight: 700;
        }

        .register-hero h1 {
            margin: 18px 0 12px;
            font-family: var(--font-display);
            font-weight: 800;
            font-size: clamp(2rem, 4vw, 3.55rem);
            line-height: 1.05;
        }

        .register-hero p {
            margin: 0;
            color: rgba(255,255,255,0.86);
            font-size: 1rem;
            line-height: 1.75;
        }

        .register-points {
            list-style: none;
            padding: 0;
            margin: 24px 0 0;
            display: grid;
            gap: 12px;
        }

        .register-points li {
            display: flex;
            gap: 10px;
            align-items: flex-start;
            font-size: 0.96rem;
        }

        .register-points i {
            color: #ffd166;
            font-size: 1.1rem;
            margin-top: 2px;
        }

        .register-panel {
            display: flex;
            align-items: stretch;
            justify-content: stretch;
            padding: 0;
        }

        .register-card {
            width: 100%;
            max-width: none;
            background: var(--white);
            border: 0;
            border-radius: 0;
            box-shadow: none;
            padding: 32px 32px 40px;
        }

        .register-header {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 16px;
            margin-bottom: 22px;
        }

        .register-header h2 {
            margin: 0 0 6px;
            font-family: var(--font-display);
            font-weight: 800;
            font-size: 1.55rem;
        }

        .register-header p {
            margin: 0;
            color: var(--muted);
            font-size: 0.92rem;
        }

        .register-badge {
            flex-shrink: 0;
            border-radius: 999px;
            padding: 8px 12px;
            background: rgba(200, 77, 223, 0.12);
            color: var(--primary-dark);
            font-size: 12px;
            font-weight: 700;
        }

        .form-control,
        .form-select,
        textarea {
            border-radius: 12px;
            border: 1px solid rgba(104, 17, 126, 0.18);
            padding: 10px 12px;
            font-size: 0.94rem;
            box-shadow: none;
        }

        .form-control:focus,
        .form-select:focus,
        textarea:focus {
            border-color: rgba(200, 77, 223, 0.7);
            box-shadow: 0 0 0 0.2rem rgba(200, 77, 223, 0.14);
        }

        .form-label {
            margin-bottom: 6px;
            font-size: 0.82rem;
            color: var(--deep);
            font-weight: 700;
        }

        .btn-primary {
            border: none;
            border-radius: 12px;
            background: linear-gradient(135deg, var(--primary-dark), var(--primary));
            padding: 10px 18px;
            font-weight: 700;
            box-shadow: 0 10px 20px rgba(200, 77, 223, 0.28);
        }

        .btn-outline-secondary {
            border-radius: 12px;
            font-weight: 700;
        }

        .split-note {
            margin-top: 20px;
            font-size: 0.82rem;
            color: var(--muted);
        }

        .status-alert {
            display: none;
            border-radius: 14px;
            padding: 12px 14px;
            margin-bottom: 16px;
            font-size: 0.92rem;
            border: 1px solid transparent;
        }

        .status-alert.show {
            display: block;
        }

        .status-alert.success {
            background: rgba(16, 185, 129, 0.1);
            color: #047857;
            border-color: rgba(16, 185, 129, 0.24);
        }

        .status-alert.error {
            background: rgba(239, 68, 68, 0.1);
            color: #b91c1c;
            border-color: rgba(239, 68, 68, 0.25);
        }

        .status-alert.warning {
            background: rgba(245, 158, 11, 0.12);
            color: #92400e;
            border-color: rgba(245, 158, 11, 0.24);
        }

        @media (max-width: 980px) {
            .register-hero { min-height: 320px; }
        }

        @media (max-width: 640px) {
            .register-hero { padding: 20px; }
            .register-card { padding: 20px; }
            .register-header { flex-direction: column; }
        }
    </style>
</head>
<body>
    <main class="register-shell">
        <section class="register-hero">
            <div class="register-hero-inner">
                <div class="register-kicker"><i class="bi bi-person-plus-fill"></i> Join Our Teacher Team</div>
                <h1>Daftar menjadi pengajar Smart Center.</h1>
                <p>Lengkapi data profesional Anda, pilih cabang dan mata pelajaran yang sesuai, lalu kirim formulir. Tim admin akan melakukan verifikasi sebelum Anda aktif masuk ke sistem.</p>
                <ul class="register-points">
                    <li><i class="bi bi-check-circle-fill"></i><span>Proses pendaftaran cepat, ringkas, dan mudah diikuti.</span></li>
                    <li><i class="bi bi-check-circle-fill"></i><span>Anda bisa memilih cabang dan mapel yang relevan.</span></li>
                    <li><i class="bi bi-check-circle-fill"></i><span>Setelah diverifikasi, akun dan status guru Anda akan diproses oleh admin.</span></li>
                </ul>
            </div>
        </section>

        <section class="register-panel">
            <div class="register-card">
                <div class="register-header">
                    <div>
                        <h2>Form Registrasi Guru Baru</h2>
                        <p>Silakan isi data diri dan kualifikasi Anda. Data akan diteruskan ke admin untuk verifikasi.</p>
                    </div>
                </div>

                <div id="registrationStatus" class="status-alert" role="alert"></div>

                <form id="teacherRegistrationForm" method="POST" action="<?php echo e(route('public.teacher-registrations.store')); ?>" enctype="multipart/form-data">
                    <?php echo csrf_field(); ?>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">NIG <span class="text-danger">*</span></label>
                            <input type="text" name="nig" class="form-control" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Jenis Kelamin <span class="text-danger">*</span></label>
                            <select name="gender" class="form-select" required>
                                <option value="">Pilih...</option>
                                <option value="L">Laki-laki</option>
                                <option value="P">Perempuan</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Tanggal Lahir</label>
                            <input type="date" name="birth_date" class="form-control">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Pendidikan</label>
                            <select name="education" class="form-select">
                                <option value="">Pilih...</option>
                                <option value="D3">D3</option>
                                <option value="S1">S1</option>
                                <option value="S2">S2</option>
                                <option value="S3">S3</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">No. HP</label>
                            <input type="text" name="phone" class="form-control">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Alamat</label>
                            <textarea name="address" class="form-control" rows="2"></textarea>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Upload CV</label>
                            <input type="file" name="cv" class="form-control" accept=".pdf,.doc,.docx">
                        </div>
                    </div>

                    <div class="d-flex gap-2 mt-4">
                        <button type="submit" class="btn btn-primary px-4">Kirim Pendaftaran</button>
                        <a href="<?php echo e(url('/')); ?>" class="btn btn-outline-secondary px-4">Kembali</a>
                    </div>
                    <div class="split-note">* Data yang Anda kirim akan diproses dan diverifikasi oleh admin sebelum masuk ke sistem.</div>
                </form>
            </div>
        </section>
    </main>

    <script>
        (function () {
            const form = document.getElementById('teacherRegistrationForm');
            const statusBox = document.getElementById('registrationStatus');
            if (!form) return;

            function showStatus(message, type = 'info') {
                if (!statusBox) return;
                statusBox.className = 'status-alert show ' + type;
                statusBox.textContent = message;
                statusBox.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }

            form.addEventListener('submit', function (e) {
                e.preventDefault();
                showStatus('Mengirim pendaftaran...', 'warning');
                const fd = new FormData(form);
                fetch(form.action, {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>', 'Accept': 'application/json' },
                    body: fd,
                }).then(async r => {
                    const data = await r.json().catch(() => ({}));
                    if (!r.ok) {
                        throw new Error(data.message || 'Gagal mengirim pendaftaran');
                    }
                    const msg = data.message || 'Pendaftaran berhasil dikirim dan menunggu verifikasi admin.';
                    if (window.showToast) {
                        window.showToast(msg, 'success');
                    }
                    showStatus(msg, 'success');
                    form.reset();
                }).catch(err => {
                    const msg = err.message || 'Terjadi kesalahan';
                    if (window.showToast) {
                        window.showToast(msg, 'error');
                    }
                    showStatus(msg, 'error');
                });
            });
        })();
    </script>

    <!-- Bootstrap JS (bundle includes Popper) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php /**PATH C:\Users\Edu Juanda Pratama\Downloads\smart-center-indonesia-1 (2)\smart-center-indonesia\resources\views/public/teacher-registration.blade.php ENDPATH**/ ?>