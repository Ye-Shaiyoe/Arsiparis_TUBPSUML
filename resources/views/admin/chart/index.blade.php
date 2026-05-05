@extends('layouts.admin')
@section('title', 'Statistik & Grafik')

@section('content')

<style>
@import url('https://fonts.googleapis.com/css2?family=DM+Mono:wght@400;500&display=swap');

.chart-page { color: inherit; }

.chart-top-bar { display: flex; align-items: center; justify-content: space-between; margin-bottom: 24px; flex-wrap: wrap; gap: 12px; color: var(--text-primary); }
.chart-top-title { font-size: 18px; font-weight: 600; letter-spacing: -0.3px; }
.chart-top-sub { font-size: 11px; color: var(--text-secondary); margin-top: 2px; font-family: 'DM Mono', monospace; display: flex; align-items: center; gap: 6px; }

.live-indicator { display: flex; align-items: center; gap: 5px; color: #16a34a; font-weight: 600; letter-spacing: 0.5px; }
.live-dot { width: 6px; height: 6px; background: #16a34a; border-radius: 50%; position: relative; }
.live-dot::after { content: ''; position: absolute; width: 100%; height: 100%; background: inherit; border-radius: 50%; animation: pulse-live 2s infinite; }

@keyframes pulse-live {
  0% { transform: scale(1); opacity: 0.8; }
  100% { transform: scale(3); opacity: 0; }
}

.chart-filter-row { display: flex; align-items: center; gap: 10px; }
.chart-filter-row label { font-size: 12px; color: var(--text-secondary); }
.chart-filter-row select { padding: 6px 12px; border-radius: 8px; font-size: 12px; cursor: pointer; outline: none; border: 1px solid var(--border-color); background: var(--bg-secondary); color: var(--text-primary); transition: all 0.3s; }
.chart-filter-row select:focus { border-color: #3b82f6; }
.chart-refresh-btn { padding: 6px 14px; border-radius: 8px; font-size: 12px; cursor: pointer; display: flex; align-items: center; gap: 6px; transition: all .15s; border: 1px solid var(--border-color); background: var(--bg-secondary); color: var(--text-primary); }
.chart-refresh-btn:hover { background: var(--bg-tertiary); border-color: #6366f1; color: #6366f1; }

.stat-grid { display: grid; grid-template-columns: repeat(4, minmax(0, 1fr)); gap: 14px; margin-bottom: 20px; }
@media(max-width:640px) { .stat-grid { grid-template-columns: repeat(2, 1fr); } }

.stat-card { background: var(--bg-secondary); border: 1px solid var(--border-color); border-radius: 12px; padding: 18px 20px; position: relative; overflow: hidden; transition: border-color .2s, background 0.3s; box-shadow: 0 1px 3px rgba(0,0,0,.05); }
.stat-card:hover { border-color: #818cf866; }
.stat-card::before { content: ''; position: absolute; top: 0; left: 0; right: 0; height: 3px; border-radius: 12px 12px 0 0; }
.stat-card.blue::before  { background: linear-gradient(90deg, #3b82f6, #60a5fa); }
.stat-card.green::before { background: linear-gradient(90deg, #16a34a, #4ade80); }
.stat-card.amber::before { background: linear-gradient(90deg, #d97706, #fbbf24); }
.stat-card.red::before   { background: linear-gradient(90deg, #dc2626, #f87171); }

.stat-label { font-size: 11px; color: var(--text-secondary); text-transform: uppercase; letter-spacing: 0.8px; margin-bottom: 8px; transition: color 0.3s; }
.stat-num   { font-size: 32px; font-weight: 700; letter-spacing: -1px; font-family: 'DM Mono', monospace; }
.stat-card.blue .stat-num  { color: #2563eb; }
.stat-card.green .stat-num { color: #16a34a; }
.stat-card.amber .stat-num { color: #d97706; }
.stat-card.red .stat-num   { color: #dc2626; }
.stat-note { font-size: 11px; color: var(--text-secondary); margin-top: 4px; font-family: 'DM Mono', monospace; transition: color 0.3s; }

.charts-row  { display: grid; grid-template-columns: 2fr 1fr; gap: 14px; margin-bottom: 20px; }
.charts-row2 { display: grid; grid-template-columns: 5fr 7fr; gap: 14px; margin-bottom: 20px; }
.charts-row3 { display: grid; grid-template-columns: 7fr 5fr; gap: 14px; margin-bottom: 20px; }
.charts-row4 { display: grid; grid-template-columns: 1fr 1fr;  gap: 14px; }
@media(max-width:800px) {
    .charts-row, .charts-row2, .charts-row3, .charts-row4 { grid-template-columns: 1fr; }
}

.ch-card { background: var(--bg-secondary); border: 1px solid var(--border-color); border-radius: 12px; padding: 20px; box-shadow: 0 1px 3px rgba(0,0,0,.04); transition: background 0.3s, border-color 0.3s; }
.ch-card-header { margin-bottom: 14px; }
.ch-card-title { font-size: 13px; font-weight: 600; color: var(--text-primary); display: flex; align-items: center; gap: 8px; transition: color 0.3s; }
.ch-dot { width: 7px; height: 7px; border-radius: 50%; background: #3b82f6; display: inline-block; flex-shrink: 0; }
.ch-dot.green  { background: #16a34a; }
.ch-dot.amber  { background: #d97706; }
.ch-dot.red    { background: #dc2626; }
.ch-dot.purple { background: #7c3aed; }
.ch-sub { font-size: 11px; color: var(--text-secondary); margin-top: 3px; margin-left: 15px; font-family: 'DM Mono', monospace; transition: color 0.3s; }

.toggle-grp { display: flex; gap: 4px; }
.tog-btn { background: transparent; border: 1px solid var(--border-color); color: var(--text-secondary); padding: 3px 10px; border-radius: 6px; font-size: 11px; cursor: pointer; transition: all .15s; }
.tog-btn:hover { border-color: #6366f144; color: var(--text-primary); }
.tog-btn.active { background: #eff6ff; border-color: #3b82f6; color: #2563eb; }

html.dark-mode .tog-btn.active { background: rgba(59, 130, 246, 0.2); }

.legend-row  { display: flex; flex-wrap: wrap; gap: 12px; margin-bottom: 10px; }
.legend-item { display: flex; align-items: center; gap: 5px; font-size: 11px; color: var(--text-secondary); transition: color 0.3s; }
.legend-dot  { width: 8px; height: 8px; border-radius: 2px; flex-shrink: 0; }

.chart-wrap { position: relative; }

table.ringkasan { width: 100%; border-collapse: collapse; }
table.ringkasan thead th { font-size: 10px; color: var(--text-secondary); text-transform: uppercase; letter-spacing: 0.8px; border-bottom: 1px solid var(--border-color); padding-bottom: 8px; font-weight: 500; transition: color 0.3s, border-color 0.3s; }
table.ringkasan tbody td { font-size: 12px; color: var(--text-secondary); padding: 8px 0; border-bottom: 1px solid var(--border-color); transition: color 0.3s, border-color 0.3s; }
table.ringkasan tbody tr:last-child td { border-bottom: none; }
table.ringkasan .num-cell { text-align: right; font-family: 'DM Mono', monospace; color: var(--text-primary); font-weight: 600; transition: color 0.3s; }
.jenis-badge { display: inline-block; padding: 2px 8px; border-radius: 4px; background: var(--bg-tertiary); font-size: 11px; color: var(--text-secondary); transition: background 0.3s, color 0.3s; }

.debug-box { background: #fffbeb; border: 1px solid #fcd34d; color: #92400e; border-radius: 8px; padding: 12px; font-size: 12px; margin-bottom: 16px; display: none; }
</style>

<div class="chart-page">

  {{-- TOP BAR --}}
  <div class="chart-top-bar">
    <div>
      <div class="chart-top-title">Statistik & Grafik</div>
      <div class="chart-top-sub">
        <span class="live-indicator">
          <span class="live-dot"></span>
          REAL-TIME
        </span>
        · DATABASE
      </div>
    </div>
    <div class="chart-filter-row">
      <label>Bulan</label>
      <select id="filter-bulan">
        @foreach(range(1, 12) as $m)
          <option value="{{ $m }}" {{ $m == now()->month ? 'selected' : '' }}>
            {{ \Carbon\Carbon::create()->month($m)->translatedFormat('F') }}
          </option>
        @endforeach
      </select>
      <label>Tahun</label>
      <select id="filter-tahun">
        @foreach(range(now()->year, now()->year - 3) as $y)
          <option value="{{ $y }}" {{ $y == now()->year ? 'selected' : '' }}>{{ $y }}</option>
        @endforeach
      </select>
      <button class="chart-refresh-btn" onclick="loadCharts()">
        <svg width="12" height="12" viewBox="0 0 16 16" fill="currentColor">
          <path d="M8 3a5 5 0 1 0 4.546 2.914.5.5 0 0 1 .908-.417A6 6 0 1 1 8 2v1z"/>
          <path d="M8 4.466V.534a.25.25 0 0 1 .41-.192l2.36 1.966c.12.1.12.284 0 .384L8.41 4.658A.25.25 0 0 1 8 4.466z"/>
        </svg>
        Refresh
      </button>
    </div>
  </div>

  <div class="debug-box" id="debug-alert"></div>

  {{-- STAT CARDS --}}
  <div class="stat-grid">
    <div class="stat-card blue">
      <div class="stat-label">Total Surat</div>
      <div class="stat-num" id="sc-total">—</div>
      <div class="stat-note">tahun ini</div>
    </div>
    <div class="stat-card green">
      <div class="stat-label">Selesai</div>
      <div class="stat-num" id="sc-selesai">—</div>
      <div class="stat-note">tahun ini</div>
    </div>
    <div class="stat-card amber">
      <div class="stat-label">Proses</div>
      <div class="stat-num" id="sc-proses">—</div>
      <div class="stat-note">saat ini</div>
    </div>
    <div class="stat-card red">
      <div class="stat-label">Ditolak</div>
      <div class="stat-num" id="sc-ditolak">—</div>
      <div class="stat-note">tahun ini</div>
    </div>
  </div>
  
  {{-- ROW 0: Lifetime Mixed Chart (NEW) --}}
  <div class="ch-card" style="margin-bottom: 20px;">
    <div class="ch-card-header" style="display:flex; justify-content: space-between; align-items: flex-start;">
      <div>
        <div class="ch-card-title"><span class="ch-dot blue"></span>Tren Seluruh Surat (12 Bulan Terakhir)</div>
        <div class="ch-sub">perbandingan surat masuk (bar) vs selesai (line) tanpa filter tahun</div>
      </div>
      <div class="legend-row" style="margin-top:5px;">
        <span class="legend-item"><span class="legend-dot" style="background:#3b82f6"></span>Surat Masuk</span>
        <span class="legend-item"><span class="legend-dot" style="background:#16a34a"></span>Surat Selesai</span>
      </div>
    </div>
    <div class="chart-wrap" style="height:280px;"><canvas id="chart-lifetime"></canvas></div>
  </div>

  {{-- ROW 1: Bulanan + Status --}}
  <div class="charts-row">
    <div class="ch-card">
      <div class="ch-card-header" style="display:flex;align-items:flex-start;justify-content:space-between;">
        <div>
          <div class="ch-card-title"><span class="ch-dot"></span>Surat Per Bulan</div>
          <div class="ch-sub">total · selesai · proses · ditolak</div>
          <div class="legend-row" style="margin-top:10px;margin-left:0;">
            <span class="legend-item"><span class="legend-dot" style="background:#3b82f6"></span>Total</span>
            <span class="legend-item"><span class="legend-dot" style="background:#16a34a"></span>Selesai</span>
            <span class="legend-item"><span class="legend-dot" style="background:#d97706"></span>Proses</span>
            <span class="legend-item"><span class="legend-dot" style="background:#dc2626"></span>Ditolak</span>
            <span class="legend-item"><span class="legend-dot" style="background:#7c3aed"></span>Revisi</span>
          </div>
        </div>
        <div class="toggle-grp">
          <button class="tog-btn active" id="btn-bar"  onclick="toggleBulanChart('bar')">Bar</button>
          <button class="tog-btn"        id="btn-line" onclick="toggleBulanChart('line')">Line</button>
        </div>
      </div>
      <div class="chart-wrap" style="height:240px;"><canvas id="chart-bulanan"></canvas></div>
    </div>
    <div class="ch-card">
      <div class="ch-card-header">
        <div class="ch-card-title"><span class="ch-dot green"></span>Status Bulan Ini</div>
        <div class="ch-sub">distribusi status surat</div>
      </div>
      <div class="chart-wrap" style="height:220px;"><canvas id="chart-status"></canvas></div>
    </div>
  </div>

  {{-- ROW 2: Jenis + SLA --}}
  <div class="charts-row2">
    <div class="ch-card">
      <div class="ch-card-header">
        <div class="ch-card-title"><span class="ch-dot purple"></span>Jenis Surat</div>
        <div class="ch-sub">distribusi per jenis</div>
      </div>
      <div class="chart-wrap" style="height:220px;"><canvas id="chart-jenis"></canvas></div>
    </div>
    <div class="ch-card">
      <div class="ch-card-header">
        <div class="ch-card-title"><span class="ch-dot amber"></span>Pemenuhan SLA Per Bulan</div>
        <div class="ch-sub">tepat waktu vs terlambat/ditolak</div>
        <div class="legend-row" style="margin-top:10px;margin-left:0;">
          <span class="legend-item"><span class="legend-dot" style="background:#16a34a"></span>Tepat Waktu</span>
          <span class="legend-item"><span class="legend-dot" style="background:#dc2626"></span>Terlambat / Ditolak</span>
        </div>
      </div>
      <div class="chart-wrap" style="height:200px;"><canvas id="chart-sla"></canvas></div>
    </div>
  </div>

  {{-- ROW 3: Trend + Tahap --}}
  <div class="charts-row3">
    <div class="ch-card">
      <div class="ch-card-header">
        <div class="ch-card-title"><span class="ch-dot"></span>Trend 30 Hari Terakhir</div>
        <div class="ch-sub">jumlah pengajuan per hari</div>
      </div>
      <div class="chart-wrap" style="height:200px;"><canvas id="chart-trend"></canvas></div>
    </div>
    <div class="ch-card">
      <div class="ch-card-header">
        <div class="ch-card-title"><span class="ch-dot red"></span>Surat Aktif Per Tahap</div>
        <div class="ch-sub">distribusi tahap sedang proses</div>
      </div>
      <div class="chart-wrap" style="height:200px;"><canvas id="chart-tahap"></canvas></div>
    </div>
  </div>

  {{-- ROW 4: Top Pengusul + Ringkasan --}}
  <div class="charts-row4">
    <div class="ch-card">
      <div class="ch-card-header">
        <div class="ch-card-title"><span class="ch-dot green"></span>Top Pengusul Bulan Ini</div>
        <div class="ch-sub">5 pegawai pengajuan terbanyak</div>
      </div>
      <div class="chart-wrap" style="height:200px;"><canvas id="chart-pengusul"></canvas></div>
    </div>
    <div class="ch-card">
      <div class="ch-card-header">
        <div class="ch-card-title"><span class="ch-dot purple"></span>Ringkasan Per Jenis</div>
        <div class="ch-sub">total pengajuan per kategori</div>
      </div>
      <table class="ringkasan">
        <thead><tr><th style="text-align:left">Jenis</th><th style="text-align:right">Total</th></tr></thead>
        <tbody id="tbl-ringkasan">
          <tr><td colspan="2" style="text-align:center;color:#9ca3af;padding:20px 0;">Memuat...</td></tr>
        </tbody>
      </table>
    </div>
  </div>

  {{-- ROW 5: Top 10 Admin --}}
  <div class="ch-card" style="margin-bottom: 20px;">
    <div class="ch-card-header">
      <div class="ch-card-title"><span class="ch-dot teal"></span>Top 10 Admin Pengurus Surat</div>
      <div class="ch-sub">admin dengan pemrosesan tahap terbanyak tahun ini</div>
    </div>
    <div class="chart-wrap" style="height:250px;"><canvas id="chart-top-admin"></canvas></div>
  </div>

  {{-- ROW 6: Revisi per Bulan + Distribusi Hari --}}
  <div class="charts-row4" style="margin-bottom: 20px;">
    <div class="ch-card">
      <div class="ch-card-header">
        <div class="ch-card-title"><span class="ch-dot amber"></span>Frekuensi Revisi per Bulan</div>
        <div class="ch-sub">revisi oleh user vs revisi oleh admin aspirasi</div>
        <div class="legend-row" style="margin-top:10px;margin-left:0;">
          <span class="legend-item"><span class="legend-dot" style="background:#d97706"></span>Revisi User</span>
          <span class="legend-item"><span class="legend-dot" style="background:#7c3aed"></span>Revisi Admin Aspirasi</span>
        </div>
      </div>
      <div class="chart-wrap" style="height:220px;"><canvas id="chart-revisi-bulan"></canvas></div>
    </div>
    <div class="ch-card">
      <div class="ch-card-header">
        <div class="ch-card-title"><span class="ch-dot green"></span>Distribusi Hari Pengajuan</div>
        <div class="ch-sub">pengajuan surat per hari dalam seminggu</div>
      </div>
      <div class="chart-wrap" style="height:220px;"><canvas id="chart-hari"></canvas></div>
    </div>
  </div>

  {{-- ROW 7: Completion Rate per Tahap + Rata-rata Waktu Proses --}}
  <div class="charts-row" style="margin-bottom: 20px;">
    <div class="ch-card">
      <div class="ch-card-header">
        <div class="ch-card-title"><span class="ch-dot red"></span>Tingkat Lolos & Tolak per Tahap</div>
        <div class="ch-sub">jumlah surat selesai vs ditolak di setiap tahap</div>
        <div class="legend-row" style="margin-top:10px;margin-left:0;">
          <span class="legend-item"><span class="legend-dot" style="background:#16a34a"></span>Lolos / Selesai</span>
          <span class="legend-item"><span class="legend-dot" style="background:#dc2626"></span>Ditolak</span>
        </div>
      </div>
      <div class="chart-wrap" style="height:240px;"><canvas id="chart-completion"></canvas></div>
    </div>
    <div class="ch-card">
      <div class="ch-card-header">
        <div class="ch-card-title"><span class="ch-dot purple"></span>Sifat & Prioritas Surat</div>
        <div class="ch-sub">distribusi surat biasa, segera, dan rahasia</div>
      </div>
      <div class="chart-wrap" style="height:220px;"><canvas id="chart-sifat"></canvas></div>
    </div>
  </div>

  {{-- ROW 8: Rata-rata Waktu Proses per Jenis (full-width) --}}
  <div class="ch-card" style="margin-bottom:20px;">
    <div class="ch-card-header">
      <div class="ch-card-title"><span class="ch-dot blue"></span>Rata-rata Waktu Penyelesaian per Jenis Surat</div>
      <div class="ch-sub">dalam jam — hanya untuk surat yang sudah selesai</div>
    </div>
    <div class="chart-wrap" style="height:220px;"><canvas id="chart-avg-proses"></canvas></div>
  </div>
  
  {{-- ROW 9: Lifetime Total Summary Card (Bottom) --}}
  <div class="ch-card" style="background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%); color: white; border: none; margin-bottom: 30px;">
    <div style="display: flex; align-items: center; justify-content: space-between; padding: 10px;">
      <div>
        <div style="font-size: 11px; text-transform: uppercase; letter-spacing: 1px; opacity: 0.8; font-weight: 700;">Akumulasi Seluruh Waktu</div>
        <div style="font-size: 14px; font-weight: 600; margin-top: 4px;">Total Keseluruhan Surat di Database</div>
      </div>
      <div style="text-align: right;">
        <div id="sc-lifetime-total" style="font-size: 48px; font-weight: 800; font-family: 'DM Mono', monospace; line-height: 1;">—</div>
        <div style="font-size: 10px; opacity: 0.8; font-weight: 600; margin-top: 5px;">SURAT TERARSIP</div>
      </div>
    </div>
  </div>

</div>{{-- .chart-page --}}

<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.js"></script>
<script>
const CHART_DATA_URL = "{{ route('admin.chart.data') }}";

const C = {
  blue:   '#3b82f6', blueA:  'rgba(59,130,246,0.12)',
  green:  '#16a34a', greenA: 'rgba(22,163,74,0.12)',
  amber:  '#d97706', amberA: 'rgba(217,119,6,0.12)',
  red:    '#dc2626', redA:   'rgba(220,38,38,0.12)',
  purple: '#7c3aed', purpleA:'rgba(124,58,237,0.12)',
  teal:   '#0891b2', pink:   '#db2777',
  orange: '#ea580c', cyan:   '#0e7490',
  grid:   '#f3f4f6', text:   '#9ca3af',
};

const charts = {};
function destroyChart(id) { if (charts[id]) { charts[id].destroy(); delete charts[id]; } }

let isFetching = false;
function loadCharts() {
  if (isFetching) return;
  isFetching = true;

  const tahun = document.getElementById('filter-tahun').value;
  const bulan = document.getElementById('filter-bulan').value;
  const debug = document.getElementById('debug-alert');
  const refreshBtn = document.querySelector('.chart-refresh-btn');
  
  if (refreshBtn) refreshBtn.style.opacity = '0.5';
  debug.style.display = 'none';

  fetch(CHART_DATA_URL + '?tahun=' + tahun + '&bulan=' + bulan, {
    headers: { 'X-Requested-With': 'XMLHttpRequest' }
  })
    .then(r => {
      if (!r.ok) throw new Error('HTTP ' + r.status + ' — ' + CHART_DATA_URL);
      return r.json();
    })
    .then(d => {
      updateStatCards(d);
      buildBulanChart(d.suratPerBulan);
      buildStatusChart(d.suratPerStatus);
      buildJenisChart(d.suratPerJenis);
      buildSlaChart(d.slaChart);
      buildTrendChart(d.trendHarian);
      buildTahapChart(d.suratPerTahap);
      buildPengusulChart(d.topPengusul);
      buildTopAdminChart(d.topAdmin);
      buildTableRingkasan(d.suratPerJenis);
      // ── Chart baru ──────────────────────────────────────────────
      buildRevisiChart(d.revisiPerBulan);
      buildHariChart(d.hariPengajuan);
      buildCompletionChart(d.completionTahap);
      buildSifatChart(d.sifatSurat);
      buildAvgProsesChart(d.avgWaktuProses);
      buildLifetimeChart(d.lifetimeMixed);
      console.log('Charts auto-updated at:', new Date().toLocaleTimeString());
    })
    .catch(err => {
      console.error('Chart error:', err);
      debug.textContent = '⚠ Gagal load data: ' + err.message + '. Buka DevTools → Network untuk detail.';
      debug.style.display = 'block';
    })
    .finally(() => {
      isFetching = false;
      if (refreshBtn) refreshBtn.style.opacity = '1';
    });
}

// Simple Polling: Refresh data tiap 30 detik secara otomatis
setInterval(() => {
    // Hanya refresh kalau tab sedang dibuka (biar hemat resource)
    if (!document.hidden) {
        loadCharts();
    }
}, 30000);

document.addEventListener('DOMContentLoaded', loadCharts);

function updateStatCards(d) {
  const b = d.suratPerBulan;
  document.getElementById('sc-total').textContent   = b.total.reduce((a, v) => a + v, 0);
  document.getElementById('sc-selesai').textContent = b.selesai.reduce((a, v) => a + v, 0);
  document.getElementById('sc-proses').textContent  = d.suratPerStatus.proses;
  document.getElementById('sc-ditolak').textContent = b.ditolak.reduce((a, v) => a + v, 0);
  
  if (document.getElementById('sc-lifetime-total')) {
    document.getElementById('sc-lifetime-total').textContent = d.totalSemua || 0;
  }
}

let bulanType = 'bar', bulanData = null;

function buildBulanChart(data) {
  bulanData = data;
  destroyChart('bulanan');
  const ctx    = document.getElementById('chart-bulanan').getContext('2d');
  const isLine = bulanType === 'line';
  const ds = (label, values, color, colorA) => ({
    label, data: values,
    borderColor: color,
    backgroundColor: isLine ? colorA : color + 'cc',
    borderWidth: isLine ? 2 : 0,
    fill: isLine, tension: isLine ? 0.4 : 0,
    pointRadius: 0, pointHoverRadius: 4,
    borderRadius: isLine ? 0 : 4, borderSkipped: false,
  });
  charts['bulanan'] = new Chart(ctx, {
    type: bulanType,
    data: {
      labels: data.labels,
      datasets: [
        ds('Total',   data.total,   C.blue,  C.blueA),
        ds('Selesai', data.selesai, C.green, C.greenA),
        ds('Proses',  data.proses,  C.amber, C.amberA),
        ds('Ditolak', data.ditolak, C.red,   C.redA),
        ds('Revisi',  data.revisi,  C.purple, C.purpleA),
      ]
    },
    options: {
      responsive: true, maintainAspectRatio: false,
      plugins: { legend: { display: false } },
      scales: {
        x: { grid: { color: C.grid }, ticks: { font: { size: 11 }, color: C.text }, border: { color: C.grid } },
        y: { beginAtZero: true, grid: { color: C.grid }, ticks: { font: { size: 11 }, precision: 0, color: C.text }, border: { color: C.grid } }
      }
    }
  });
}

function toggleBulanChart(type) {
  bulanType = type;
  document.querySelectorAll('.tog-btn').forEach(b => b.classList.remove('active'));
  document.getElementById('btn-' + type).classList.add('active');
  if (bulanData) buildBulanChart(bulanData);
}

function buildStatusChart(data) {
  destroyChart('status');
  const ctx   = document.getElementById('chart-status').getContext('2d');
  const total = (data.proses || 0) + (data.selesai || 0) + (data.ditolak || 0) + (data.revisi || 0) + (data.revisi_admin || 0);
  charts['status'] = new Chart(ctx, {
    type: 'doughnut',
    data: {
      labels: ['Proses', 'Selesai', 'Ditolak', 'Revisi', 'Revisi Admin'],
      datasets: [{
        data: total > 0 ? [data.proses, data.selesai, data.ditolak, data.revisi, data.revisi_admin] : [1, 0, 0, 0, 0],
        backgroundColor: total > 0 ? [C.amber, C.green, C.red, C.purple, '#a78bfa'] : ['#e5e7eb', '#e5e7eb', '#e5e7eb', '#e5e7eb', '#e5e7eb'],
        borderColor: '#fff', borderWidth: 3, hoverOffset: 6
      }]
    },
    options: {
      responsive: true, maintainAspectRatio: false, cutout: '72%',
      plugins: {
        legend: { position: 'bottom', labels: { font: { size: 11 }, boxWidth: 10, padding: 14, color: '#6b7280' } },
        tooltip: { callbacks: { label: c => total > 0 ? ` ${c.label}: ${c.raw}` : ' Belum ada data' } }
      }
    }
  });
}

function buildJenisChart(data) {
  destroyChart('jenis');
  const ctx     = document.getElementById('chart-jenis').getContext('2d');
  const hasData = data.labels.length > 0;
  charts['jenis'] = new Chart(ctx, {
    type: 'radar',
    data: {
      labels: hasData ? data.labels : ['Belum ada data'],
      datasets: [{
        label: 'Jumlah Surat',
        data: hasData ? data.data : [0],
        backgroundColor: 'rgba(59, 130, 246, 0.2)',
        borderColor: '#3b82f6',
        borderWidth: 2,
        pointBackgroundColor: '#3b82f6',
        pointBorderColor: '#fff',
        pointHoverBackgroundColor: '#fff',
        pointHoverBorderColor: '#3b82f6'
      }]
    },
    options: {
      responsive: true, maintainAspectRatio: false,
      plugins: { 
        legend: { display: false } 
      },
      scales: {
        r: {
          angleLines: { color: C.grid },
          grid: { color: C.grid },
          pointLabels: { font: { size: 10 }, color: C.text },
          ticks: { display: false, stepSize: 1 },
          suggestedMin: 0
        }
      }
    }
  });
}

function buildSlaChart(data) {
  destroyChart('sla');
  const ctx = document.getElementById('chart-sla').getContext('2d');
  charts['sla'] = new Chart(ctx, {
    type: 'bar',
    data: {
      labels: data.labels,
      datasets: [
        { label: 'Tepat Waktu', data: data.tepat,     backgroundColor: C.green + 'cc', borderRadius: 4, stack: 's', borderSkipped: false },
        { label: 'Terlambat',   data: data.terlambat, backgroundColor: C.red   + 'cc', borderRadius: 4, stack: 's', borderSkipped: false },
      ]
    },
    options: {
      responsive: true, maintainAspectRatio: false,
      plugins: { legend: { display: false } },
      scales: {
        x: { stacked: true, grid: { color: C.grid }, ticks: { font: { size: 10 }, color: C.text }, border: { color: C.grid } },
        y: { stacked: true, beginAtZero: true, grid: { color: C.grid }, ticks: { font: { size: 10 }, precision: 0, color: C.text }, border: { color: C.grid } }
      }
    }
  });
}

function buildTrendChart(data) {
  destroyChart('trend');
  const ctx = document.getElementById('chart-trend').getContext('2d');
  charts['trend'] = new Chart(ctx, {
    type: 'line',
    data: {
      labels: data.labels,
      datasets: [{
        data: data.data,
        borderColor: C.blue, backgroundColor: C.blueA,
        fill: true, tension: 0.4, pointRadius: 0, pointHoverRadius: 4, borderWidth: 2
      }]
    },
    options: {
      responsive: true, maintainAspectRatio: false,
      plugins: { legend: { display: false } },
      scales: {
        x: { grid: { color: C.grid }, ticks: { font: { size: 10 }, maxTicksLimit: 10, color: C.text }, border: { color: C.grid } },
        y: { beginAtZero: true, grid: { color: C.grid }, ticks: { font: { size: 10 }, precision: 0, color: C.text }, border: { color: C.grid } }
      }
    }
  });
}

function buildTahapChart(data) {
  destroyChart('tahap');
  const ctx    = document.getElementById('chart-tahap').getContext('2d');
  const labels = (data.labels || []).map(l => l.length > 22 ? l.substring(0, 20) + '…' : l);
  const hasData = labels.length > 0;
  document.querySelector('#chart-tahap').parentElement.style.height =
    Math.max(hasData ? labels.length * 40 + 60 : 100, 200) + 'px';
  charts['tahap'] = new Chart(ctx, {
    type: 'bar',
    data: {
      labels: hasData ? labels : ['Tidak ada surat proses'],
      datasets: [{ data: hasData ? data.data : [0], backgroundColor: C.red + 'cc', borderRadius: 4, borderSkipped: false }]
    },
    options: {
      indexAxis: 'y', responsive: true, maintainAspectRatio: false,
      plugins: { legend: { display: false } },
      scales: {
        x: { beginAtZero: true, grid: { color: C.grid }, ticks: { font: { size: 10 }, precision: 0, color: C.text }, border: { color: C.grid } },
        y: { grid: { display: false }, ticks: { font: { size: 10 }, color: C.text }, border: { color: C.grid } }
      }
    }
  });
}

function buildPengusulChart(data) {
  destroyChart('pengusul');
  const ctx     = document.getElementById('chart-pengusul').getContext('2d');
  const palette = [C.blue, C.green, C.amber, C.purple, C.teal];
  const hasData = data.labels.length > 0;
  document.querySelector('#chart-pengusul').parentElement.style.height =
    Math.max(hasData ? data.labels.length * 40 + 60 : 100, 200) + 'px';
  charts['pengusul'] = new Chart(ctx, {
    type: 'bar',
    data: {
      labels: hasData ? data.labels : ['Belum ada data'],
      datasets: [{
        data: hasData ? data.data : [0],
        backgroundColor: hasData ? palette.slice(0, data.labels.length).map(c => c + 'cc') : ['#e5e7eb'],
        borderRadius: 6, borderSkipped: false
      }]
    },
    options: {
      indexAxis: 'y', responsive: true, maintainAspectRatio: false,
      plugins: { legend: { display: false } },
      scales: {
        x: { beginAtZero: true, grid: { color: C.grid }, ticks: { font: { size: 10 }, precision: 0, color: C.text }, border: { color: C.grid } },
        y: { grid: { display: false }, ticks: { font: { size: 11 }, color: C.text }, border: { color: C.grid } }
      }
    }
  });
}

function buildTableRingkasan(data) {
  const tbody  = document.getElementById('tbl-ringkasan');
  const colors = ['#3b82f6', '#16a34a', '#d97706', '#7c3aed', '#0891b2', '#db2777'];
  if (!data.labels || !data.labels.length) {
    tbody.innerHTML = '<tr><td colspan="2" style="text-align:center;color:#9ca3af;padding:16px 0;">Belum ada data</td></tr>';
    return;
  }
  tbody.innerHTML = data.labels.map((label, i) => `
    <tr>
      <td><span class="jenis-badge" style="border-left:2px solid ${colors[i % colors.length]};padding-left:8px;">${label}</span></td>
      <td class="num-cell">${data.data[i]}</td>
    </tr>
  `).join('');
}

function buildTopAdminChart(data) {
  destroyChart('topAdmin');
  const ctx = document.getElementById('chart-top-admin').getContext('2d');
  const palette = [C.blue, C.green, C.amber, C.purple, C.teal, C.pink, C.red, '#4ade80', '#fbbf24', '#a78bfa'];
  const hasData = data.labels.length > 0;
  charts['topAdmin'] = new Chart(ctx, {
    type: 'bar',
    data: {
      labels: hasData ? data.labels : ['Belum ada data'],
      datasets: [{
        label: 'Total Proses',
        data: hasData ? data.data : [0],
        backgroundColor: hasData ? palette.slice(0, data.labels.length).map(c => c + 'cc') : ['#e5e7eb'],
        borderRadius: 4, borderSkipped: false
      }]
    },
    options: {
      responsive: true, maintainAspectRatio: false,
      plugins: { legend: { display: false } },
      scales: {
        x: { grid: { display: false }, ticks: { font: { size: 10 }, color: C.text }, border: { color: C.grid } },
        y: { beginAtZero: true, grid: { color: C.grid }, ticks: { font: { size: 11 }, precision: 0, color: C.text }, border: { color: C.grid } }
      }
    }
  });
}

// ════════════════════════════════════════════════════════════════════
// CHART BARU
// ════════════════════════════════════════════════════════════════════

// 1. Revisi per Bulan (grouped bar)
function buildRevisiChart(data) {
  destroyChart('revisi-bulan');
  const ctx = document.getElementById('chart-revisi-bulan').getContext('2d');
  charts['revisi-bulan'] = new Chart(ctx, {
    type: 'bar',
    data: {
      labels: data.labels,
      datasets: [
        {
          label: 'Revisi User',
          data: data.revisiUser,
          backgroundColor: C.amber + 'cc',
          borderRadius: 4, borderSkipped: false,
        },
        {
          label: 'Revisi Admin Aspirasi',
          data: data.revisiAdmin,
          backgroundColor: C.purple + 'cc',
          borderRadius: 4, borderSkipped: false,
        }
      ]
    },
    options: {
      responsive: true, maintainAspectRatio: false,
      plugins: { legend: { display: false } },
      scales: {
        x: { grid: { color: C.grid }, ticks: { font: { size: 10 }, color: C.text }, border: { color: C.grid } },
        y: { beginAtZero: true, grid: { color: C.grid }, ticks: { font: { size: 10 }, precision: 0, color: C.text }, border: { color: C.grid } }
      }
    }
  });
}

// 2. Distribusi Hari Pengajuan (polar area)
function buildHariChart(data) {
  destroyChart('hari');
  const ctx = document.getElementById('chart-hari').getContext('2d');
  const palette = [C.red, C.blue, C.green, C.amber, C.teal, C.orange, C.purple];
  const hasData = data.data.some(v => v > 0);
  charts['hari'] = new Chart(ctx, {
    type: 'polarArea',
    data: {
      labels: data.labels,
      datasets: [{
        data: hasData ? data.data : data.labels.map(() => 1),
        backgroundColor: palette.map(c => c + '99'),
        borderColor: palette.map(c => c),
        borderWidth: 1.5,
      }]
    },
    options: {
      responsive: true, maintainAspectRatio: false,
      plugins: {
        legend: {
          position: 'right',
          labels: { font: { size: 10 }, boxWidth: 10, padding: 8, color: '#6b7280' }
        },
        tooltip: {
          callbacks: {
            label: c => hasData ? ` ${c.label}: ${c.raw} surat` : ' Belum ada data'
          }
        }
      },
      scales: { r: { ticks: { display: false }, grid: { color: C.grid } } }
    }
  });
}

// 3. Completion Rate per Tahap (grouped bar)
function buildCompletionChart(data) {
  destroyChart('completion');
  const ctx = document.getElementById('chart-completion').getContext('2d');
  const hasData = data.labels.length > 0;
  charts['completion'] = new Chart(ctx, {
    type: 'bar',
    data: {
      labels: hasData ? data.labels : ['Belum ada data'],
      datasets: [
        {
          label: 'Lolos / Selesai',
          data: hasData ? data.selesai : [0],
          backgroundColor: C.green + 'cc',
          borderRadius: 4, borderSkipped: false, stack: 's',
        },
        {
          label: 'Ditolak',
          data: hasData ? data.ditolak : [0],
          backgroundColor: C.red + 'cc',
          borderRadius: 4, borderSkipped: false, stack: 's',
        }
      ]
    },
    options: {
      responsive: true, maintainAspectRatio: false,
      plugins: { legend: { display: false } },
      scales: {
        x: { stacked: true, grid: { display: false }, ticks: { font: { size: 10 }, color: C.text }, border: { color: C.grid } },
        y: { stacked: true, beginAtZero: true, grid: { color: C.grid }, ticks: { font: { size: 10 }, precision: 0, color: C.text }, border: { color: C.grid } }
      }
    }
  });
}

// 4. Sifat Surat (doughnut)
function buildSifatChart(data) {
  destroyChart('sifat');
  const ctx = document.getElementById('chart-sifat').getContext('2d');
  const palette = [C.blue, C.red, C.purple];
  const total = data.data.reduce((a, v) => a + v, 0);
  charts['sifat'] = new Chart(ctx, {
    type: 'doughnut',
    data: {
      labels: data.labels,
      datasets: [{
        data: total > 0 ? data.data : [1, 1, 1],
        backgroundColor: total > 0 ? palette : ['#e5e7eb', '#e5e7eb', '#e5e7eb'],
        borderColor: '#fff', borderWidth: 3, hoverOffset: 6
      }]
    },
    options: {
      responsive: true, maintainAspectRatio: false, cutout: '68%',
      plugins: {
        legend: {
          position: 'bottom',
          labels: { font: { size: 11 }, boxWidth: 10, padding: 14, color: '#6b7280' }
        },
        tooltip: {
          callbacks: {
            label: c => total > 0
              ? ` ${c.label}: ${c.raw} (${Math.round(c.raw / total * 100)}%)`
              : ' Belum ada data'
          }
        }
      }
    }
  });
}

// 5. Rata-rata Waktu Proses per Jenis (horizontal bar)
function buildAvgProsesChart(data) {
  destroyChart('avg-proses');
  const ctx = document.getElementById('chart-avg-proses').getContext('2d');
  const hasData = data.labels.length > 0;
  const palette = [C.blue, C.teal, C.green, C.amber, C.orange, C.purple, C.pink];
  charts['avg-proses'] = new Chart(ctx, {
    type: 'bar',
    data: {
      labels: hasData ? data.labels : ['Belum ada surat selesai'],
      datasets: [{
        label: 'Rata-rata Jam',
        data: hasData ? data.data : [0],
        backgroundColor: hasData
          ? data.labels.map((_, i) => palette[i % palette.length] + 'cc')
          : ['#e5e7eb'],
        borderRadius: 6, borderSkipped: false
      }]
    },
    options: {
      indexAxis: 'y',
      responsive: true, maintainAspectRatio: false,
      plugins: {
        legend: { display: false },
        tooltip: {
          callbacks: { label: c => ` ${c.raw} jam rata-rata` }
        }
      },
      scales: {
        x: {
          beginAtZero: true,
          grid: { color: C.grid },
          ticks: { font: { size: 10 }, color: C.text, callback: v => v + ' jam' },
          border: { color: C.grid }
        },
        y: {
          grid: { display: false },
          ticks: { font: { size: 11 }, color: C.text },
          border: { color: C.grid }
        }
      }
    }
  });
}

document.addEventListener('DOMContentLoaded', loadCharts);
// 6. Lifetime Mixed Chart
function buildLifetimeChart(data) {
  destroyChart('lifetime');
  const ctx = document.getElementById('chart-lifetime').getContext('2d');
  charts['lifetime'] = new Chart(ctx, {
    type: 'bar',
    data: {
      labels: data.labels,
      datasets: [
        {
          label: 'Surat Selesai',
          type: 'line',
          data: data.selesai,
          borderColor: C.green,
          backgroundColor: 'transparent',
          borderWidth: 3,
          tension: 0.4,
          pointRadius: 4,
          pointBackgroundColor: C.green,
          pointBorderColor: '#fff',
          pointBorderWidth: 2,
          yAxisID: 'y',
        },
        {
          label: 'Surat Masuk',
          data: data.masuk,
          backgroundColor: 'rgba(59, 130, 246, 0.6)',
          borderRadius: 6,
          barThickness: 'flex',
          yAxisID: 'y',
        }
      ]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: {
        legend: { display: false },
        tooltip: { mode: 'index', intersect: false }
      },
      scales: {
        x: { grid: { display: false }, ticks: { font: { size: 10 }, color: C.text } },
        y: { 
          beginAtZero: true, 
          grid: { color: C.grid }, 
          ticks: { font: { size: 10 }, precision: 0, color: C.text },
          border: { display: false }
        }
      }
    }
  });
}
</script>

@endsection