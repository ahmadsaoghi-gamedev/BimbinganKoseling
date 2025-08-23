<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rekap Curhat Rahasia - SMK Siliwangi</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            font-size: 12px;
            line-height: 1.4;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #333;
            padding-bottom: 20px;
        }
        .header h1 {
            margin: 0;
            font-size: 18px;
            font-weight: bold;
        }
        .header h2 {
            margin: 5px 0;
            font-size: 16px;
        }
        .header p {
            margin: 5px 0;
            font-size: 14px;
        }
        .info {
            margin-bottom: 20px;
        }
        .info table {
            width: 100%;
            border-collapse: collapse;
        }
        .info td {
            padding: 5px;
            border: 1px solid #ddd;
        }
        .info td:first-child {
            font-weight: bold;
            width: 150px;
            background-color: #f5f5f5;
        }
        .reports-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        .reports-table th,
        .reports-table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
            font-size: 11px;
        }
        .reports-table th {
            background-color: #f5f5f5;
            font-weight: bold;
        }
        .reports-table tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .status-badge {
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 10px;
            font-weight: bold;
        }
        .status-pending { background-color: #fff3cd; color: #856404; }
        .status-dalam_proses { background-color: #d1ecf1; color: #0c5460; }
        .status-selesai { background-color: #d4edda; color: #155724; }
        .urgensi-badge {
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 10px;
            font-weight: bold;
        }
        .urgensi-rendah { background-color: #d4edda; color: #155724; }
        .urgensi-sedang { background-color: #fff3cd; color: #856404; }
        .urgensi-tinggi { background-color: #f8d7da; color: #721c24; }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 10px;
            color: #666;
        }
        @media print {
            body { margin: 0; }
            .header { margin-bottom: 20px; }
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>REKAP CURHAT RAHASIA</h1>
        <h2>SMK SILIWANGI</h2>
        <p>Periode: {{ date('d/m/Y') }}</p>
    </div>

    <div class="info">
        <table>
            <tr>
                <td>Total Laporan</td>
                <td>{{ $reports->count() }}</td>
            </tr>
            <tr>
                <td>Laporan Aktif</td>
                <td>{{ $reports->where('status_progress', '!=', 'selesai')->count() }}</td>
            </tr>
            <tr>
                <td>Laporan Selesai</td>
                <td>{{ $reports->where('status_progress', 'selesai')->count() }}</td>
            </tr>
            <tr>
                <td>Urgensi Tinggi</td>
                <td>{{ $reports->where('tingkat_urgensi', 'tinggi')->count() }}</td>
            </tr>
            <tr>
                <td>Urgensi Sedang</td>
                <td>{{ $reports->where('tingkat_urgensi', 'sedang')->count() }}</td>
            </tr>
            <tr>
                <td>Urgensi Rendah</td>
                <td>{{ $reports->where('tingkat_urgensi', 'rendah')->count() }}</td>
            </tr>
            <tr>
                <td>Dalam Proses</td>
                <td>{{ $reports->where('status_progress', 'dalam_proses')->count() }}</td>
            </tr>
            <tr>
                <td>Menunggu Tindak Lanjut</td>
                <td>{{ $reports->where('status_progress', 'menunggu_tindak_lanjut')->count() }}</td>
            </tr>
        </table>
    </div>

    <table class="reports-table">
        <thead>
            <tr>
                <th>No</th>
                <th>Tanggal</th>
                <th>Siswa</th>
                <th>Kategori</th>
                <th>Urgensi</th>
                <th>Status</th>
                <th>Guru BK</th>
                <th>Jumlah Sesi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($reports as $index => $report)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $report->tanggal_curhat ? $report->tanggal_curhat->format('d/m/Y') : '-' }}</td>
                <td>{{ $report->siswa->nama ?? 'N/A' }}</td>
                <td>{{ ucfirst($report->kategori_masalah ?? 'N/A') }}</td>
                <td>
                    <span class="urgensi-badge urgensi-{{ $report->tingkat_urgensi ?? 'rendah' }}">
                        {{ ucfirst($report->tingkat_urgensi ?? 'N/A') }}
                    </span>
                </td>
                <td>
                    <span class="status-badge status-{{ $report->status_progress ?? 'pending' }}">
                        {{ ucfirst(str_replace('_', ' ', $report->status_progress ?? 'pending')) }}
                    </span>
                </td>
                <td>{{ $report->guruBk->nama ?? 'N/A' }}</td>
                <td>{{ $report->jumlah_sesi ?? 'N/A' }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="8" style="text-align: center;">Tidak ada data laporan curhat</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <!-- Statistics Chart -->
    <div style="margin-top: 30px;">
        <h3 style="text-align: center; margin-bottom: 20px; font-size: 14px; font-weight: bold;">GRAFIK STATISTIK CURHAT RAHASIA</h3>
        
        <!-- Status Chart -->
        <div style="margin-bottom: 30px;">
            <h4 style="font-size: 12px; font-weight: bold; margin-bottom: 10px;">Distribusi Status Laporan:</h4>
            <table style="width: 100%; border-collapse: collapse;">
                <tr>
                    <td style="width: 30%; padding: 5px; border: 1px solid #ddd; font-size: 10px;">Status</td>
                    <td style="width: 20%; padding: 5px; border: 1px solid #ddd; font-size: 10px;">Jumlah</td>
                    <td style="width: 50%; padding: 5px; border: 1px solid #ddd; font-size: 10px;">Grafik</td>
                </tr>
                @php
                    $totalReports = $reports->count();
                    $statuses = [
                        'pending' => $reports->where('status_progress', 'pending')->count(),
                        'dalam_proses' => $reports->where('status_progress', 'dalam_proses')->count(),
                        'menunggu_tindak_lanjut' => $reports->where('status_progress', 'menunggu_tindak_lanjut')->count(),
                        'selesai' => $reports->where('status_progress', 'selesai')->count(),
                    ];
                @endphp
                @foreach($statuses as $status => $count)
                    @php
                        $percentage = $totalReports > 0 ? ($count / $totalReports) * 100 : 0;
                        $barWidth = $percentage * 2; // Scale for better visibility
                    @endphp
                    <tr>
                        <td style="padding: 5px; border: 1px solid #ddd; font-size: 10px;">{{ ucfirst(str_replace('_', ' ', $status)) }}</td>
                        <td style="padding: 5px; border: 1px solid #ddd; font-size: 10px; text-align: center;">{{ $count }}</td>
                        <td style="padding: 5px; border: 1px solid #ddd; font-size: 10px;">
                            <div style="background-color: #e5e7eb; height: 15px; position: relative;">
                                <div style="background-color: #3b82f6; height: 100%; width: {{ $barWidth }}%;"></div>
                                <span style="position: absolute; top: 0; left: 5px; font-size: 8px; color: #000;">{{ round($percentage, 1) }}%</span>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </table>
        </div>

        <!-- Urgency Chart -->
        <div style="margin-bottom: 30px;">
            <h4 style="font-size: 12px; font-weight: bold; margin-bottom: 10px;">Distribusi Tingkat Urgensi:</h4>
            <table style="width: 100%; border-collapse: collapse;">
                <tr>
                    <td style="width: 30%; padding: 5px; border: 1px solid #ddd; font-size: 10px;">Urgensi</td>
                    <td style="width: 20%; padding: 5px; border: 1px solid #ddd; font-size: 10px;">Jumlah</td>
                    <td style="width: 50%; padding: 5px; border: 1px solid #ddd; font-size: 10px;">Grafik</td>
                </tr>
                @php
                    $urgencies = [
                        'tinggi' => $reports->where('tingkat_urgensi', 'tinggi')->count(),
                        'sedang' => $reports->where('tingkat_urgensi', 'sedang')->count(),
                        'rendah' => $reports->where('tingkat_urgensi', 'rendah')->count(),
                    ];
                @endphp
                @foreach($urgencies as $urgency => $count)
                    @php
                        $percentage = $totalReports > 0 ? ($count / $totalReports) * 100 : 0;
                        $barWidth = $percentage * 2;
                        $color = $urgency === 'tinggi' ? '#ef4444' : ($urgency === 'sedang' ? '#f59e0b' : '#10b981');
                    @endphp
                    <tr>
                        <td style="padding: 5px; border: 1px solid #ddd; font-size: 10px;">{{ ucfirst($urgency) }}</td>
                        <td style="padding: 5px; border: 1px solid #ddd; font-size: 10px; text-align: center;">{{ $count }}</td>
                        <td style="padding: 5px; border: 1px solid #ddd; font-size: 10px;">
                            <div style="background-color: #e5e7eb; height: 15px; position: relative;">
                                <div style="background-color: {{ $color }}; height: 100%; width: {{ $barWidth }}%;"></div>
                                <span style="position: absolute; top: 0; left: 5px; font-size: 8px; color: #000;">{{ round($percentage, 1) }}%</span>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </table>
        </div>
    </div>

    <div class="footer">
        <p>Dicetak pada: {{ date('d/m/Y H:i:s') }}</p>
        <p>Dokumen ini bersifat rahasia dan hanya untuk keperluan internal sekolah</p>
    </div>
</body>
</html>
