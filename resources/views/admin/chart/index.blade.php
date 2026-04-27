@extends('layouts.admin')
@section('title', 'Statistik & Grafik')

@section('content')

<style>
@import url('https://fonts.googleapis.com/css2?family=DM+Mono:wght@400;500&display=swap');

.chart-page { color: inherit; }

.chart-top-bar { display: flex; align-items: center; justify-content: space-between; margin-bottom: 24px; flex-wrap: wrap; gap: 12px; color: var(--text-primary); }
.chart-top-title { font-size: 18px; font-weight: 600; letter-spacing: -0.3px; }
.chart-top-sub { font-size: 12px; color: var(--text-secondary); margin-top: 2px; font-family: 'DM Mono', monospace; }

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
      <div class="chart-top-sub">real-time · database</div>
    </div>
    <div class="chart-filter-row">
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
      <div class="ch-sub">admin dengan pemrosesan tahap terbanyak</div>
    </div>
    <div class="chart-wrap" style="height:250px;"><canvas id="chart-top-admin"></canvas></div>
  </div>

</div>{{-- .chart-page --}}

<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.js"></script>
<script>
const CHART_DATA_URL = "{{ route('admin.chart.data') }}";

// Warna disesuaikan dengan tema light (cocok dengan admin layout)
const C = {
  blue:   '#3b82f6', blueA:  'rgba(59,130,246,0.12)',
  green:  '#16a34a', greenA: 'rgba(22,163,74,0.12)',
  amber:  '#d97706', amberA: 'rgba(217,119,6,0.12)',
  red:    '#dc2626', redA:   'rgba(220,38,38,0.12)',
  purple: '#7c3aed', teal:   '#0891b2', pink: '#db2777',
  grid:   '#f3f4f6', text:   '#9ca3af',
};

const charts = {};
function destroyChart(id) { if (charts[id]) { charts[id].destroy(); delete charts[id]; } }

function loadCharts() {
  const tahun = document.getElementById('filter-tahun').value;
  const debug = document.getElementById('debug-alert');
  debug.style.display = 'none';

  fetch(CHART_DATA_URL + '?tahun=' + tahun, {
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
    })
    .catch(err => {
      console.error('Chart error:', err);
      debug.textContent = '⚠ Gagal load data: ' + err.message + '. Buka DevTools → Network untuk detail.';
      debug.style.display = 'block';
    });
}

function updateStatCards(d) {
  const b = d.suratPerBulan;
  document.getElementById('sc-total').textContent   = b.total.reduce((a, v) => a + v, 0);
  document.getElementById('sc-selesai').textContent = b.selesai.reduce((a, v) => a + v, 0);
  document.getElementById('sc-proses').textContent  = d.suratPerStatus.proses;
  document.getElementById('sc-ditolak').textContent = b.ditolak.reduce((a, v) => a + v, 0);
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
  const total = data.proses + data.selesai + data.ditolak;
  charts['status'] = new Chart(ctx, {
    type: 'doughnut',
    data: {
      labels: ['Proses', 'Selesai', 'Ditolak'],
      datasets: [{
        data: total > 0 ? [data.proses, data.selesai, data.ditolak] : [1, 0, 0],
        backgroundColor: total > 0 ? [C.amber, C.green, C.red] : ['#e5e7eb', '#e5e7eb', '#e5e7eb'],
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
  const palette = [C.blue, C.green, C.amber, C.purple, C.teal, C.pink];
  const hasData = data.labels.length > 0;
  charts['jenis'] = new Chart(ctx, {
    type: 'doughnut',
    data: {
      labels: hasData ? data.labels : ['Belum ada data'],
      datasets: [{
        data: hasData ? data.data : [1],
        backgroundColor: hasData ? palette.slice(0, data.labels.length) : ['#e5e7eb'],
        borderColor: '#fff', borderWidth: 3, hoverOffset: 6
      }]
    },
    options: {
      responsive: true, maintainAspectRatio: false, cutout: '65%',
      plugins: { legend: { position: 'right', labels: { font: { size: 10 }, boxWidth: 10, padding: 10, color: '#6b7280' } } }
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

document.addEventListener('DOMContentLoaded', loadCharts);
</script>

@endsection