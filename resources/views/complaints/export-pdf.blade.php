<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Laporan Pengaduan Masyarakat</title>
    <style>
        @page {
            margin: 20px 25px;
        }
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 9px;
            color: #1e293b;
            line-height: 1.4;
        }

        /* Kop Surat */
        .header {
            text-align: center;
            border-bottom: 3px double #1e40af;
            padding-bottom: 12px;
            margin-bottom: 18px;
        }
        .header .title {
            font-size: 16px;
            font-weight: bold;
            color: #1e3a5f;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 2px;
        }
        .header .subtitle {
            font-size: 11px;
            color: #475569;
            font-weight: normal;
        }
        .header .divider-text {
            font-size: 8px;
            color: #94a3b8;
            margin-top: 4px;
        }

        /* Info Laporan */
        .report-info {
            margin-bottom: 14px;
            padding: 8px 12px;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 4px;
        }
        .report-info table {
            width: 100%;
        }
        .report-info td {
            padding: 2px 8px;
            font-size: 8.5px;
            vertical-align: top;
        }
        .report-info .label {
            color: #64748b;
            width: 100px;
        }
        .report-info .value {
            color: #1e293b;
            font-weight: bold;
        }

        /* Statistik */
        .stats-container {
            display: flex;
            justify-content: space-between;
            margin-bottom: 14px;
            gap: 6px;
        }
        .stat-box {
            flex: 1;
            text-align: center;
            padding: 6px 4px;
            border-radius: 4px;
            border: 1px solid #e2e8f0;
        }
        .stat-box .stat-number {
            font-size: 16px;
            font-weight: bold;
        }
        .stat-box .stat-label {
            font-size: 7px;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .stat-total { background: #eff6ff; border-color: #bfdbfe; }
        .stat-total .stat-number { color: #1d4ed8; }
        .stat-received { background: #fefce8; border-color: #fde68a; }
        .stat-received .stat-number { color: #a16207; }
        .stat-progress { background: #fff7ed; border-color: #fed7aa; }
        .stat-progress .stat-number { color: #c2410c; }
        .stat-resolved { background: #f0fdf4; border-color: #bbf7d0; }
        .stat-resolved .stat-number { color: #15803d; }
        .stat-closed { background: #f1f5f9; border-color: #cbd5e1; }
        .stat-closed .stat-number { color: #475569; }

        /* Tabel Data */
        table.data {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }
        table.data thead th {
            background: #1e3a5f;
            color: white;
            padding: 6px 5px;
            font-size: 7.5px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.3px;
            text-align: left;
            border: 1px solid #1e3a5f;
        }
        table.data tbody td {
            padding: 4px 5px;
            border: 1px solid #e2e8f0;
            font-size: 7.5px;
            vertical-align: top;
        }
        table.data tbody tr:nth-child(even) {
            background: #f8fafc;
        }
        table.data tbody tr:hover {
            background: #f1f5f9;
        }

        /* Badge Status */
        .badge {
            display: inline-block;
            padding: 1px 6px;
            border-radius: 8px;
            font-size: 7px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .badge-received { background: #fef9c3; color: #854d0e; }
        .badge-progress { background: #fed7aa; color: #9a3412; }
        .badge-resolved { background: #bbf7d0; color: #166534; }
        .badge-closed { background: #e2e8f0; color: #475569; }
        .badge-low { background: #dbeafe; color: #1e40af; }
        .badge-medium { background: #fef9c3; color: #854d0e; }
        .badge-high { background: #fed7aa; color: #9a3412; }
        .badge-critical { background: #fecaca; color: #991b1b; }

        /* Footer */
        .footer {
            margin-top: 20px;
            padding-top: 10px;
            border-top: 1px solid #e2e8f0;
            font-size: 7.5px;
            color: #94a3b8;
            text-align: center;
        }
        .footer .signature {
            margin-top: 8px;
            text-align: right;
            color: #475569;
        }

        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .font-bold { font-weight: bold; }
        .text-muted { color: #94a3b8; }
        .mb-1 { margin-bottom: 4px; }
        .mt-1 { margin-top: 4px; }
        .w-1 { width: 20px; }
        .w-2 { width: 40px; }
        .w-3 { width: 60px; }
        .nowrap { white-space: nowrap; }
    </style>
</head>
<body>

    <!-- Kop Surat -->
    <div class="header">
        <div class="title">Laporan Pengaduan Masyarakat</div>
        <div class="subtitle">Sistem Informasi Pengaduan & Survei Kepuasan (SISUKES)</div>
        <div class="divider-text">— Laporan Resmi —</div>
    </div>

    <!-- Info Laporan -->
    <div class="report-info">
        <table>
            <tr>
                <td class="label">Periode Laporan</td>
                <td class="value">: {{ date('d F Y H:i') }}</td>
                <td class="label">Filter Kategori</td>
                <td class="value">: {{ $filters['category'] }}</td>
            </tr>
            <tr>
                <td class="label">Jumlah Data</td>
                <td class="value">: {{ $stats['total'] }} pengaduan</td>
                <td class="label">Filter Status</td>
                <td class="value">: {{ $filters['status'] ? ($statusLabels[$filters['status']] ?? $filters['status']) : 'Semua' }}</td>
            </tr>
            <tr>
                <td class="label">Dicetak Oleh</td>
                <td class="value">: {{ Auth::user()->name ?? 'Sistem' }}</td>
                <td class="label">Filter Prioritas</td>
                <td class="value">: {{ $filters['priority'] ? ($priorityLabels[$filters['priority']] ?? $filters['priority']) : 'Semua' }}</td>
            </tr>
        </table>
    </div>

    <!-- Statistik -->
    <div class="stats-container">
        <div class="stat-box stat-total">
            <div class="stat-number">{{ $stats['total'] }}</div>
            <div class="stat-label">Total</div>
        </div>
        <div class="stat-box stat-received">
            <div class="stat-number">{{ $stats['received'] }}</div>
            <div class="stat-label">Diterima</div>
        </div>
        <div class="stat-box stat-progress">
            <div class="stat-number">{{ $stats['in_progress'] }}</div>
            <div class="stat-label">Diproses</div>
        </div>
        <div class="stat-box stat-resolved">
            <div class="stat-number">{{ $stats['resolved'] }}</div>
            <div class="stat-label">Selesai</div>
        </div>
        <div class="stat-box stat-closed">
            <div class="stat-number">{{ $stats['closed'] }}</div>
            <div class="stat-label">Ditutup</div>
        </div>
    </div>

    <!-- Tabel Data -->
    <table class="data">
        <thead>
            <tr>
                <th class="w-1">#</th>
                <th class="w-2">Tiket</th>
                <th>Nama Pengadu</th>
                <th>Kategori</th>
                <th>Subjek</th>
                <th class="w-2">Prioritas</th>
                <th class="w-2">Status</th>
                <th class="w-3">Tanggal</th>
            </tr>
        </thead>
        <tbody>
            @forelse($complaints as $index => $c)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td class="font-bold text-center">#{{ $c->id }}</td>
                    <td>
                        <span class="font-bold">{{ $c->complainant_name }}</span>
                        @if($c->complainant_phone)
                            <br><span class="text-muted">{{ $c->complainant_phone }}</span>
                        @endif
                    </td>
                    <td>{{ $categories[$c->category_id]->name ?? '-' }}</td>
                    <td>
                        <span class="font-bold">{{ $c->subject }}</span>
                        <br><span class="text-muted" style="font-size:7px;">{{ Str::limit(strip_tags($c->description), 80) }}</span>
                    </td>
                    <td class="text-center">
                        <span class="badge badge-{{ $c->priority }}">
                            {{ $priorityLabels[$c->priority] ?? $c->priority }}
                        </span>
                    </td>
                    <td class="text-center">
                        <span class="badge badge-{{ $c->status === 'in_progress' ? 'progress' : $c->status }}">
                            {{ $statusLabels[$c->status] ?? $c->status }}
                        </span>
                    </td>
                    <td class="text-center nowrap">{{ $c->created_at->format('d-m-Y') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="text-center" style="padding:20px; color:#94a3b8;">
                        Tidak ada data pengaduan yang ditemukan.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <!-- Footer -->
    <div class="footer">
        <div>Dicetak pada {{ date('d F Y H:i:s') }} melalui Sistem Informasi Pengaduan & Survei Kepuasan (SISUKES)</div>
        <div class="signature">
            <div>Mengetahui,</div>
            <br><br>
            <div class="font-bold">{{ Auth::user()->name ?? 'Petugas' }}</div>
            <div class="text-muted" style="font-size:7px;">Petugas Pengelola Pengaduan</div>
        </div>
    </div>

</body>
</html>