<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
<title>Formulir Pendaftaran – Ayo Kursus</title>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Space+Grotesk:wght@400;500;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">
<style>
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
body{font-family:'Plus Jakarta Sans',sans-serif;background:#f3f0ff;min-height:100vh;padding:2rem 1rem;display:flex;justify-content:center;align-items:flex-start}
.container{width:100%;max-width:700px}
.form-header{background:#1a1a2e;color:#fff;padding:2.5rem 2rem 2rem;position:relative;overflow:hidden;border-radius:16px 16px 0 0}
.form-header::before{content:'';position:absolute;top:-50px;right:-50px;width:220px;height:220px;border-radius:50%;background:rgba(200,77,223,.2)}
.form-header::after{content:'';position:absolute;bottom:-40px;left:35%;width:150px;height:150px;border-radius:50%;background:rgba(168,85,247,.12)}
.brand-badge{display:inline-flex;align-items:center;gap:6px;background:rgba(200,77,223,.25);border:1px solid rgba(200,77,223,.5);color:#e8b4f5;font-size:11px;font-weight:700;letter-spacing:.1em;text-transform:uppercase;padding:5px 12px;border-radius:20px;margin-bottom:14px;position:relative;z-index:1}
.form-header h1{font-family:'Space Grotesk',sans-serif;font-size:30px;font-weight:700;color:#fff;line-height:1.2;position:relative;z-index:1}
.form-header h1 span{color:#ab8db2}
.form-header p{font-size:13px;color:rgba(255,255,255,.5);margin-top:8px;position:relative;z-index:1}
.form-card{background:#fff;border-radius:0 0 16px 16px;box-shadow:0 20px 60px rgba(200,77,223,.1);padding:1.5rem;display:flex;flex-direction:column;gap:1.25rem}
.section{border:1px solid #ede9fe;border-radius:12px;overflow:hidden}
.section-header{background:#1a1a2e;padding:10px 16px;display:flex;align-items:center;gap:8px}
.section-header i{font-size:16px;color:#ab8db2}
.section-header span{font-size:11px;font-weight:800;color:#e9d5ff;letter-spacing:.08em;text-transform:uppercase}
.section-body{padding:1.25rem;display:flex;flex-direction:column;gap:14px}
.field-group{display:flex;flex-direction:column;gap:5px}
.field-group label{font-size:12px;font-weight:700;color:#6b7280;letter-spacing:.03em}
.field-group input[type=text],.field-group input[type=tel],.field-group input[type=date],.field-group textarea,.field-group select{border:1.5px solid #e5e7eb;border-radius:8px;padding:10px 14px;font-size:14px;font-family:'Plus Jakarta Sans',sans-serif;color:#461256;background:#faf9ff;outline:none;width:100%;transition:border-color .2s,box-shadow .2s}
.field-group input:focus,.field-group textarea:focus,.field-group select:focus{border-color:#c84ddf;box-shadow:0 0 0 3px rgba(124,58,237,.1);background:#fff}
.field-group input::placeholder,.field-group textarea::placeholder{color:#e8b4f5}
.field-group select option{background:#fff;color:#111}
.two-col{display:grid;grid-template-columns:1fr 1fr;gap:12px}
.subsection-label{font-size:11px;font-weight:800;color:#c84ddf;letter-spacing:.07em;text-transform:uppercase;padding-bottom:6px;border-bottom:2px solid #ede9fe;display:flex;align-items:center;gap:5px}
.checkbox-grid{display:grid;grid-template-columns:1fr 1fr;gap:6px;margin-top:8px}
.checkbox-grid.three-col{grid-template-columns:1fr 1fr 1fr}
.checkbox-grid.one-col{grid-template-columns:1fr}
.check-item{display:flex;align-items:center;gap:8px;padding:8px 11px;border:1.5px solid #e5e7eb;border-radius:8px;cursor:pointer;transition:border-color .2s,background .2s;background:#faf9ff;user-select:none}
.check-item:hover{border-color:#c84ddf;background:#f5f3ff}
.check-item input[type=checkbox],.check-item input[type=radio]{accent-color:#c84ddf;width:15px;height:15px;cursor:pointer;flex-shrink:0}
.check-item span{font-size:13px;color:#374151;font-weight:500;line-height:1.3}
.group-note{font-size:11px;color:#9ca3af;font-weight:600;padding-left:2px;margin-top:6px}
.submit-btn{width:100%;padding:15px;background:#1a1a2e;color:#fff;border:none;border-radius:10px;font-family:'Plus Jakarta Sans',sans-serif;font-size:15px;font-weight:800;cursor:pointer;display:flex;align-items:center;justify-content:center;gap:8px;transition:transform .15s;position:relative;overflow:hidden;letter-spacing:.02em}
.submit-btn::before{content:'';position:absolute;inset:0;background:linear-gradient(90deg,#c84ddf,#c84ddf);opacity:0;transition:opacity .25s}
.submit-btn:hover::before{opacity:1}
.submit-btn:active{transform:scale(.98)}
.submit-btn span,.submit-btn i{position:relative;z-index:1}
.footer-note{text-align:center;font-size:11px;color:#9ca3af;line-height:1.6}
.success-overlay{position:fixed;inset:0;background:rgba(0,0,0,.6);display:flex;align-items:center;justify-content:center;z-index:999;opacity:0;pointer-events:none;transition:opacity .3s}
.success-overlay.show{opacity:1;pointer-events:all}
.success-box{background:#fff;border-radius:20px;padding:2.5rem 2rem;text-align:center;max-width:380px;width:90%;transform:scale(.9);transition:transform .3s}
.success-overlay.show .success-box{transform:scale(1)}
.success-icon{width:72px;height:72px;border-radius:50%;background:#d1fae5;display:flex;align-items:center;justify-content:center;margin:0 auto 1rem;font-size:32px;color:#059669}
.success-box h2{font-family:'Space Grotesk',sans-serif;font-size:22px;font-weight:700;color:#461256;margin-bottom:8px}
.success-box p{font-size:13px;color:#6b7280;line-height:1.6;margin-bottom:1.5rem}
.success-box .no-reg{font-family:'Space Grotesk',sans-serif;font-size:24px;font-weight:800;color:#c84ddf;margin-bottom:4px}
.btn-ok{background:#c84ddf;color:#fff;border:none;border-radius:8px;padding:12px 32px;font-size:14px;font-weight:700;cursor:pointer;font-family:'Plus Jakarta Sans',sans-serif}
.btn-ok:hover{background:#461256}
@media(max-width:520px){.two-col{grid-template-columns:1fr}.checkbox-grid.three-col{grid-template-columns:1fr 1fr}.form-header h1{font-size:24px}}
</style>
</head>
<body>
<div class="container">
<div class="form-header">
  <div class="brand-badge"><i class="ti ti-school"></i> Ayo Kursus</div>
  <h1>Formulir <span>Pendaftaran</span><br>Siswa Baru</h1>
  <p>Isi data dengan lengkap dan benar untuk proses pendaftaran</p>
</div>
<form class="form-card" id="mainForm" onsubmit="handleSubmit(event)">
  <div class="section">
    <div class="section-header"><i class="ti ti-user-circle"></i><span>Data Siswa</span></div>
    <div class="section-body">
      <div class="field-group"><label>Nama Lengkap *</label><input type="text" id="nama_lengkap" placeholder="Masukkan nama lengkap siswa" required></div>
      <div class="two-col">
        <div class="field-group"><label>Tempat Lahir</label><input type="text" id="tempat_lahir" placeholder="Kota lahir"></div>
        <div class="field-group"><label>Tanggal Lahir</label><input type="date" id="tanggal_lahir"></div>
      </div>
      <div class="field-group"><label>Alamat Tinggal</label><input type="text" id="alamat" placeholder="Alamat lengkap"></div>
      <div class="two-col">
        <div class="field-group"><label>Nomor WA/HP Siswa *</label><input type="tel" id="hp_siswa" placeholder="08xx-xxxx-xxxx" required></div>
        <div class="field-group"><label>Jenis Kelamin</label>
          <select id="jenis_kelamin"><option value="">Pilih</option><option>Laki-laki</option><option>Perempuan</option></select>
        </div>
      </div>
      <div class="field-group">
        <label>Jenis Peserta *</label>
        <select id="jenisPeserta" required><option value="">Pilih Jenis Peserta</option><option value="Pelajar">Pelajar</option><option value="Umum">Umum</option></select>
        <select id="jenjang" style="margin-top:8px;display:none"><option value="">Pilih Jenjang</option></select>
        <select id="kelas" style="margin-top:8px;display:none"><option value="">Pilih Kelas</option></select>
        <input type="text" id="namaSekolah" placeholder="Nama Sekolah" style="margin-top:8px;display:none">
      </div>
    </div>
  </div>
  <div class="section">
    <div class="section-header"><i class="ti ti-users"></i><span>Data Orang Tua / Wali</span></div>
    <div class="section-body">
      <div class="two-col">
        <div class="field-group"><label>Nama Orang Tua / Wali</label><input type="text" id="nama_ortu" placeholder="Nama lengkap"></div>
        <div class="field-group"><label>Nomor WA/HP Orang Tua</label><input type="tel" id="hp_ortu" placeholder="08xx-xxxx-xxxx"></div>
      </div>
      <div class="field-group"><label>Pekerjaan Orang Tua</label><input type="text" id="pekerjaan" placeholder="Pekerjaan orang tua / wali"></div>
    </div>
  </div>
  <div class="section">
    <div class="section-header"><i class="ti ti-books"></i><span>Program Belajar</span></div>
    <div class="section-body">
      <div><div class="subsection-label">Program Belajar</div>
        <div class="checkbox-grid">
          <label class="check-item"><input type="radio" name="program" value="Kelas"><span>Kelas</span></label>
          <label class="check-item"><input type="radio" name="program" value="Privat"><span>Privat (1 guru 1 siswa)</span></label>
        </div></div>
      <div><div class="subsection-label">Sistem Belajar</div>
        <div class="checkbox-grid">
          <label class="check-item"><input type="radio" name="sistem" value="Online"><span>Online (Daring)</span></label>
          <label class="check-item"><input type="radio" name="sistem" value="Offline"><span>Offline (Tatap Muka)</span></label>
        </div></div>
      <div><div class="subsection-label">Tempat Belajar</div>
        <div class="checkbox-grid">
          <label class="check-item"><input type="radio" name="tempat" value="Kantor"><span>Belajar di Kantor</span></label>
          <label class="check-item"><input type="radio" name="tempat" value="Rumah"><span>Guru ke Rumah</span></label>
        </div></div>
      <div><div class="subsection-label">Sistem Pengambilan</div>
        <div class="checkbox-grid">
          <label class="check-item"><input type="radio" name="pengambilan" value="Paket"><span>Paket</span></label>
          <label class="check-item"><input type="radio" name="pengambilan" value="Per Sesi"><span>Pertemuan / Sesi</span></label>
        </div></div>
      <div class="field-group"><label>Pilih Cabang Belajar</label>
        <select id="cabang"><option value="">Pilih Cabang</option>
          <option>Pekanbaru</option><option>Jakarta Selatan</option><option>Jakarta Barat</option>
          <option>Jakarta Timur</option><option>Jakarta Utara</option><option>Jakarta Pusat</option>
          <option>Bandung</option><option>Surabaya</option><option>Medan</option>
          <option>Yogyakarta</option><option>Semarang</option><option>Batam</option>
          <option>Palembang</option><option>Makassar</option><option>Bogor</option>
          <option>Depok</option><option>Tangerang</option><option>Bekasi</option>
        </select>
      </div>
    </div>
  </div>
  <div class="section">
    <div class="section-header"><i class="ti ti-star"></i><span>Program yang Diminati</span></div>
    <div class="section-body">
      <div>
        <div class="subsection-label"><i class="ti ti-device-laptop"></i> Kursus Komputer</div>
        <p class="group-note">Microsoft Office Perkantoran</p>
        <div class="checkbox-grid three-col" style="margin-top:6px">
          <label class="check-item"><input type="checkbox" name="minat" value="Microsoft Office"><span>Microsoft Office</span></label>
        </div>
        <p class="group-note" style="margin-top:10px">Desain Grafis</p>
        <div class="checkbox-grid three-col" style="margin-top:6px">
          <label class="check-item"><input type="checkbox" id="desainGrafis"><span>Desain Grafis</span></label>
        </div>
        <div id="subDesainGrafis" style="display:none;margin-top:8px">
          <div class="checkbox-grid three-col">
            <label class="check-item"><input type="checkbox" name="minat" value="Adobe Photoshop"><span>Adobe Photoshop</span></label>
            <label class="check-item"><input type="checkbox" name="minat" value="Corel Draw"><span>Corel Draw</span></label>
            <label class="check-item"><input type="checkbox" name="minat" value="AutoCAD"><span>AutoCAD</span></label>
          </div>
        </div>
        <div class="checkbox-grid one-col" style="margin-top:8px">
          <label class="check-item"><input type="checkbox" name="minat" value="Programmer/Coding"><span>Programmer / Coding</span></label>
        </div>
      </div>
      <div>
        <div class="subsection-label"><i class="ti ti-language"></i> Kursus Bahasa Asing</div>
        <div class="checkbox-grid" style="margin-top:8px">
          <label class="check-item"><input type="checkbox" name="minat" value="Bahasa Inggris"><span>Bahasa Inggris</span></label>
          <label class="check-item"><input type="checkbox" name="minat" value="Bahasa Arab"><span>Bahasa Arab</span></label>
          <label class="check-item"><input type="checkbox" name="minat" value="Bahasa Mandarin"><span>Bahasa Mandarin</span></label>
          <label class="check-item"><input type="checkbox" name="minat" value="Bahasa Jepang"><span>Bahasa Jepang</span></label>
          <label class="check-item"><input type="checkbox" name="minat" value="Bahasa Korea"><span>Bahasa Korea</span></label>
        </div>
      </div>
      <div>
        <div class="subsection-label"><i class="ti ti-calculator"></i> Mata Pelajaran</div>
        <div class="checkbox-grid" style="margin-top:8px">
          <label class="check-item"><input type="checkbox" name="minat" value="Matematika"><span>Matematika</span></label>
          <label class="check-item"><input type="checkbox" name="minat" value="Kimia"><span>Kimia</span></label>
          <label class="check-item"><input type="checkbox" name="minat" value="Biologi"><span>Biologi</span></label>
          <label class="check-item"><input type="checkbox" name="minat" value="Fisika"><span>Fisika</span></label>
          <label class="check-item"><input type="checkbox" name="minat" value="Bahasa Indonesia"><span>Bahasa Indonesia</span></label>
          <label class="check-item"><input type="checkbox" name="minat" value="B.Inggris (Mapel)"><span>Bahasa Inggris</span></label>
          <label class="check-item"><input type="checkbox" name="minat" value="Akuntansi/Ekonomi"><span>Akuntansi / Ekonomi</span></label>
          <label class="check-item"><input type="checkbox" name="minat" value="Geografi"><span>Geografi</span></label>
          <label class="check-item"><input type="checkbox" name="minat" value="IPA"><span>IPA</span></label>
          <label class="check-item"><input type="checkbox" name="minat" value="IPS"><span>IPS</span></label>
        </div>
      </div>
      <div>
        <div class="subsection-label"><i class="ti ti-building-bank"></i> Program Kedinasan</div>
        <div class="checkbox-grid" style="margin-top:8px">
          <label class="check-item"><input type="checkbox" name="minat" value="PKN STAN"><span>PKN STAN</span></label>
          <label class="check-item"><input type="checkbox" name="minat" value="Polstat STIS"><span>Polstat STIS</span></label>
          <label class="check-item"><input type="checkbox" name="minat" value="IPDN"><span>IPDN</span></label>
          <label class="check-item"><input type="checkbox" name="minat" value="STTD"><span>STTD</span></label>
          <label class="check-item"><input type="checkbox" name="minat" value="STMKG"><span>STMKG</span></label>
          <label class="check-item"><input type="checkbox" name="minat" value="Poltek SSN"><span>Poltek SSN</span></label>
          <label class="check-item"><input type="checkbox" name="minat" value="SIPENCATAR"><span>SIPENCATAR</span></label>
          <label class="check-item"><input type="checkbox" name="minat" value="AKPOL"><span>AKPOL</span></label>
          <label class="check-item"><input type="checkbox" name="minat" value="AKMIL"><span>AKMIL</span></label>
          <label class="check-item"><input type="checkbox" name="minat" value="Bintara"><span>Bintara</span></label>
          <label class="check-item"><input type="checkbox" name="minat" value="CPNS"><span>CPNS</span></label>
          <label class="check-item"><input type="checkbox" name="minat" value="Tamtama"><span>Tamtama</span></label>
          <label class="check-item"><input type="checkbox" name="minat" value="Unhan"><span>Unhan</span></label>
        </div>
      </div>
    </div>
  </div>
  <div class="section">
    <div class="section-header"><i class="ti ti-clock"></i><span>Preferensi Jadwal</span></div>
    <div class="section-body">
      <div>
        <div class="subsection-label">Hari Belajar</div>
        <div class="checkbox-grid three-col" style="margin-top:8px">
          <label class="check-item"><input type="checkbox" name="hari" value="Senin"><span>Senin</span></label>
          <label class="check-item"><input type="checkbox" name="hari" value="Selasa"><span>Selasa</span></label>
          <label class="check-item"><input type="checkbox" name="hari" value="Rabu"><span>Rabu</span></label>
          <label class="check-item"><input type="checkbox" name="hari" value="Kamis"><span>Kamis</span></label>
          <label class="check-item"><input type="checkbox" name="hari" value="Jumat"><span>Jumat</span></label>
          <label class="check-item"><input type="checkbox" name="hari" value="Sabtu"><span>Sabtu</span></label>
        </div>
      </div>
      <div class="two-col">
        <div class="field-group"><label>Jam Belajar</label>
          <select id="jam_belajar">
            <option value="">Pilih Jam</option>
            <option>07:00 – 08:00</option><option>08:00 – 09:00</option><option>09:00 – 10:00</option>
            <option>10:00 – 11:00</option><option>13:00 – 14:00</option><option>14:00 – 15:00</option>
            <option>15:00 – 16:00</option><option>16:00 – 17:00</option><option>19:00 – 20:00</option>
          </select>
        </div>
        <div class="field-group"><label>Tanggal Mulai</label><input type="date" id="tanggal_mulai"></div>
      </div>
      <div class="field-group"><label>Catatan Tambahan</label><textarea id="catatan" rows="3" placeholder="Catatan atau permintaan khusus..."></textarea></div>
    </div>
  </div>
  <button type="submit" class="submit-btn"><i class="ti ti-send"></i><span>Kirim Pendaftaran</span></button>
  <p class="footer-note">Data Anda dijaga kerahasiaannya dan hanya digunakan untuk keperluan pendaftaran.<br>© 2025 Ayo Kursus. All rights reserved.</p>
</form>
</div>
<div class="success-overlay" id="successOverlay">
  <div class="success-box">
    <div class="success-icon"><i class="ti ti-check"></i></div>
    <h2>Pendaftaran Terkirim!</h2>
    <p>Data Anda telah kami terima dan sedang menunggu persetujuan admin.<br>Nomor registrasi Anda:</p>
    <div class="no-reg" id="noReg">–</div>
    <p style="font-size:12px;color:#9ca3af;margin-bottom:1.5rem">Simpan nomor ini untuk keperluan konfirmasi.</p>
    <button class="btn-ok" onclick="closeSuccess()">Oke, Mengerti</button>
  </div>
</div>
<script>
const jenisPeserta=document.getElementById('jenisPeserta');
const jenjang=document.getElementById('jenjang');
const kelas=document.getElementById('kelas');
const namaSekolah=document.getElementById('namaSekolah');
jenisPeserta.addEventListener('change',function(){
  jenjang.style.display='none';kelas.style.display='none';namaSekolah.style.display='none';
  if(this.value==='Pelajar'){
    jenjang.innerHTML=`<option value="">Pilih Jenjang</option><option>TK</option><option>SD</option><option>SMP</option><option>SMA</option>`;
    jenjang.style.display='block';
  }
});
jenjang.addEventListener('change',function(){
  namaSekolah.style.display=this.value?'block':'none';
  const map={TK:'<option value="">Pilih Kelas</option><option>TK A</option><option>TK B</option>',
    SD:'<option value="">Pilih Kelas</option><option>Kelas 1</option><option>Kelas 2</option><option>Kelas 3</option><option>Kelas 4</option><option>Kelas 5</option><option>Kelas 6</option>',
    SMP:'<option value="">Pilih Kelas</option><option>Kelas 7</option><option>Kelas 8</option><option>Kelas 9</option>',
    SMA:'<option value="">Pilih Kelas</option><option>Kelas 10</option><option>Kelas 11</option><option>Kelas 12</option>'};
  kelas.innerHTML=map[this.value]||'';kelas.style.display=this.value?'block':'none';
});
document.getElementById('desainGrafis').addEventListener('change',function(){
  document.getElementById('subDesainGrafis').style.display=this.checked?'block':'none';
});
function handleSubmit(e){
  e.preventDefault();
  const minat=[...document.querySelectorAll('input[name=minat]:checked')].map(x=>x.value);
  const hari=[...document.querySelectorAll('input[name=hari]:checked')].map(x=>x.value);

  const payload={
    nama_lengkap: document.getElementById('nama_lengkap').value,
    tempat_lahir: document.getElementById('tempat_lahir').value,
    tanggal_lahir: document.getElementById('tanggal_lahir').value,
    alamat: document.getElementById('alamat').value,
    hp_siswa: document.getElementById('hp_siswa').value,
    jenis_kelamin: document.getElementById('jenis_kelamin').value,
    jenisPeserta: jenisPeserta.value,
    jenjang: jenjang.style.display !== 'none' ? jenjang.value : '',
    kelas: kelas.style.display !== 'none' ? kelas.value : '',
    namaSekolah: namaSekolah.style.display !== 'none' ? namaSekolah.value : '',
    nama_ortu: document.getElementById('nama_ortu').value,
    hp_ortu: document.getElementById('hp_ortu').value,
    pekerjaan: document.getElementById('pekerjaan').value,
    program: document.querySelector('input[name=program]:checked')?.value || '',
    sistem: document.querySelector('input[name=sistem]:checked')?.value || '',
    tempat: document.querySelector('input[name=tempat]:checked')?.value || '',
    pengambilan: document.querySelector('input[name=pengambilan]:checked')?.value || '',
    cabang: document.getElementById('cabang').value,
    minat: minat,
    hari: hari,
    jam_belajar: document.getElementById('jam_belajar').value,
    tanggal_mulai: document.getElementById('tanggal_mulai').value,
    catatan: document.getElementById('catatan').value,
  };

  fetch('<?php echo e(route("public.student-registrations.store")); ?>', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      'Accept': 'application/json',
      'X-Requested-With': 'XMLHttpRequest',
      'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
    },
    body: JSON.stringify(payload)
  })
  .then(async res => {
    const data = await res.json();
    if (!res.ok) {
      throw new Error(data.message || 'Gagal menyimpan pendaftaran');
    }
    document.getElementById('noReg').textContent = data.data?.no_reg || '–';
    document.getElementById('successOverlay').classList.add('show');
  })
  .catch(err => {
    alert(err.message || 'Terjadi kesalahan.');
  });
}
function closeSuccess(){
  document.getElementById('successOverlay').classList.remove('show');
  document.getElementById('mainForm').reset();
  jenjang.style.display='none';kelas.style.display='none';namaSekolah.style.display='none';
  document.getElementById('subDesainGrafis').style.display='none';
}
</script>
</body>
</html><?php /**PATH /home/runner/workspace/resources/views/formdaftarsiswa.blade.php ENDPATH**/ ?>