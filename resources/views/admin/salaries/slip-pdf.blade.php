<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<style>
    body { font-family: DejaVu Sans, Arial, sans-serif; font-size: 12px; color: #1a1a1a; margin: 0; padding: 0; }
    .page { padding: 40px; }
    .header { text-align: center; border-bottom: 3px solid #c84ddf; padding-bottom: 20px; margin-bottom: 24px; }
    .header h1 { font-size: 20px; margin: 0; color: #260632; }
    .header p { margin: 4px 0 0; color: #666; font-size: 11px; }
    .slip-title { background: #260632; color: white; text-align: center; padding: 10px; font-size: 14px; font-weight: bold; border-radius: 6px; margin-bottom: 20px; }
    table { width: 100%; border-collapse: collapse; margin-bottom: 16px; }
    td { padding: 8px 12px; border: 1px solid #e5e7eb; }
    .label { background: #f9fafb; font-weight: bold; width: 40%; }
    .total-row td { background: #260632; color: white; font-weight: bold; font-size: 14px; }
    .footer { margin-top: 40px; display: flex; justify-content: space-between; }
    .sign-box { text-align: center; width: 180px; }
    .sign-line { border-top: 1px solid #333; margin-top: 60px; padding-top: 4px; }
    .badge { display: inline-block; padding: 4px 12px; border-radius: 20px; font-size: 11px; font-weight: bold; }
    .badge-success { background: #d1fae5; color: #065f46; }
    .badge-warning { background: #fef3c7; color: #92400e; }
</style>
</head>
<body>
<div class="page">
    <div class="header">
        <h1>Smart Center Indonesia</h1>
        <p>Slip Gaji Tenaga Pengajar</p>
    </div>
    <div class="slip-title">SLIP GAJI — {{ strtoupper($salary->periode) }}</div>

    <table>
        <tr><td class="label">Nama Guru</td><td>{{ $salary->guru->name ?? '-' }}</td></tr>
        <tr><td class="label">NIK/NIK Guru</td><td>{{ $salary->guru->nig ?? '-' }}</td></tr>
        <tr><td class="label">Cabang</td><td>{{ $salary->cabang->name ?? 'Pusat' }}</td></tr>
        <tr><td class="label">Periode</td><td>{{ $salary->periode }}</td></tr>
        <tr><td class="label">Status</td><td>
            <span class="badge {{ $salary->status === 'dibayar' ? 'badge-success' : 'badge-warning' }}">
                {{ strtoupper($salary->status) }}
            </span>
        </td></tr>
        @if($salary->tanggal_pembayaran)
        <tr><td class="label">Tanggal Pembayaran</td><td>{{ $salary->tanggal_pembayaran->format('d/m/Y') }}</td></tr>
        @endif
        @if($salary->metode_pembayaran)
        <tr><td class="label">Metode</td><td>{{ $salary->metode_pembayaran }}</td></tr>
        @endif
        @if($salary->nama_bank)
        <tr><td class="label">Bank</td><td>{{ $salary->nama_bank }} – {{ $salary->nomor_rekening }}</td></tr>
        @endif
    </table>

    <table>
        <tr><td colspan="2" style="background:#f0fdf4;font-weight:bold;color:#065f46;border:1px solid #e5e7eb;">RINCIAN PENGHASILAN</td></tr>
        <tr><td class="label">Gaji Pokok</td><td>Rp {{ number_format($salary->gaji_pokok, 0, ',', '.') }}</td></tr>
        <tr><td class="label">Jam Mengajar</td><td>{{ $salary->jam_mengajar ?? 0 }} jam × Rp {{ number_format($salary->tarif_per_jam ?? 0, 0, ',', '.') }}</td></tr>
        <tr><td class="label">Honor Mengajar</td><td>Rp {{ number_format($salary->total_gaji_mengajar ?? 0, 0, ',', '.') }}</td></tr>
        <tr><td class="label">Bonus</td><td>Rp {{ number_format($salary->bonus ?? 0, 0, ',', '.') }}</td></tr>
        <tr><td class="label" style="color:#ef4444">Potongan</td><td style="color:#ef4444">– Rp {{ number_format($salary->potongan ?? 0, 0, ',', '.') }}</td></tr>
        <tr class="total-row"><td>TOTAL GAJI</td><td>Rp {{ number_format($salary->total_gaji, 0, ',', '.') }}</td></tr>
    </table>

    @if($salary->catatan)
    <p style="font-size:11px;color:#666;margin-top:8px"><strong>Catatan:</strong> {{ $salary->catatan }}</p>
    @endif

    <div class="footer">
        <div class="sign-box">
            <div>Penerima,</div>
            <div class="sign-line">{{ $salary->guru->name ?? '-' }}</div>
        </div>
        <div class="sign-box">
            <div>Dibayarkan oleh,</div>
            <div class="sign-line">{{ $salary->pembayaranOleh->name ?? 'Admin' }}</div>
        </div>
    </div>
    <p style="text-align:center;font-size:10px;color:#999;margin-top:30px">Dicetak pada {{ now()->format('d/m/Y H:i') }} — Smart Center Indonesia</p>
</div>
</body>
</html>
