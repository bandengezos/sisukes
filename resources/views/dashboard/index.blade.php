@extends('layouts.app')

@section('title', 'Ringkasan Dashboard Klinik')

@section('content')
<div class="space-y-6">
    
    <!-- Statistics Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Card 1: Active Surveys -->
        <div class="medical-card border-accent-blue p-6 flex items-center justify-between">
            <div class="space-y-1">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Survey Aktif</span>
                <span class="block text-3xl font-black text-slate-800">{{ $activeSurveysCount }}</span>
            </div>
            <div class="w-12 h-12 bg-sky-50 rounded-2xl flex items-center justify-center text-xl text-sky-600 border border-sky-100">
                📋
            </div>
        </div>

        <!-- Card 2: Total Complaints -->
        <div class="medical-card border-accent-warning p-6 flex items-center justify-between">
            <div class="space-y-1">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Total Pengaduan</span>
                <span class="block text-3xl font-black text-slate-800">{{ $totalComplaintsCount }}</span>
            </div>
            <div class="w-12 h-12 bg-amber-50 rounded-2xl flex items-center justify-center text-xl text-amber-600 border border-amber-100">
                📩
            </div>
        </div>

        <!-- Card 3: Resolved Complaints -->
        <div class="medical-card border-accent-success p-6 flex items-center justify-between">
            <div class="space-y-1">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Pengaduan Selesai</span>
                <span class="block text-3xl font-black text-slate-800">{{ $resolvedComplaintsCount }}</span>
            </div>
            <div class="w-12 h-12 bg-emerald-50 rounded-2xl flex items-center justify-center text-xl text-emerald-600 border border-emerald-100">
                ✅
            </div>
        </div>

        <!-- Card 4: Avg Satisfaction -->
        <div class="medical-card border-accent-teal p-6 flex items-center justify-between">
            <div class="space-y-1">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Rerata Kepuasan</span>
                <span class="block text-3xl font-black text-slate-800">{{ $avgSatisfaction }} <span class="text-sm font-normal text-slate-400">/ 5.0</span></span>
            </div>
            <div class="w-12 h-12 bg-teal-50 rounded-2xl flex items-center justify-center text-xl text-teal-600 border border-teal-100">
                ⭐
            </div>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Chart 1: Trend -->
        <div class="bg-white rounded-2xl border border-slate-200 p-6 lg:col-span-2 space-y-4">
            <h3 class="text-sm font-bold text-slate-800 uppercase tracking-wider">Tren Pengaduan Pasien (6 Bulan)</h3>
            <div class="h-64 relative">
                <canvas id="trendChart"></canvas>
            </div>
        </div>

        <!-- Chart 2: Status distribution -->
        <div class="bg-white rounded-2xl border border-slate-200 p-6 space-y-4">
            <h3 class="text-sm font-bold text-slate-800 uppercase tracking-wider">Distribusi Status Pengaduan</h3>
            <div class="h-64 relative">
                <canvas id="statusChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Survey Rating Averages Chart -->
    <div class="bg-white rounded-2xl border border-slate-200 p-6 space-y-4">
        <h3 class="text-sm font-bold text-slate-800 uppercase tracking-wider">Rata-rata Penilaian per Item Survey Aktif</h3>
        <div class="h-64 relative">
            <canvas id="ratingChart"></canvas>
        </div>
    </div>

    <!-- Recent Data Lists (Grid) -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Recent Complaints Table -->
        <div class="bg-white rounded-2xl border border-slate-200 p-6 space-y-4">
            <div class="flex justify-between items-center">
                <h3 class="text-sm font-bold text-slate-800 uppercase tracking-wider">Pengaduan Terbaru</h3>
                <a href="{{ route('complaints.index') }}" class="text-xs font-bold text-sky-600 hover:text-sky-700 transition">Lihat Semua &rarr;</a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs border-collapse">
                    <thead>
                        <tr class="border-b border-slate-100 text-slate-400 font-bold uppercase">
                            <th class="py-3">Pengadu</th>
                            <th class="py-3">Subjek</th>
                            <th class="py-3">Kategori</th>
                            <th class="py-3">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50 text-slate-700">
                        @forelse($recentComplaints as $c)
                            <tr>
                                <td class="py-3 font-semibold">{{ $c->complainant_name }}</td>
                                <td class="py-3 max-w-[150px] truncate">{{ $c->subject }}</td>
                                <td class="py-3">{{ $c->category->name }}</td>
                                <td class="py-3">
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase
                                        @if($c->status === 'received') badge-received
                                        @elseif($c->status === 'in_progress') badge-progress
                                        @elseif($c->status === 'resolved') badge-resolved
                                        @else badge-closed @endif">
                                        @if($c->status === 'received') Diterima
                                        @elseif($c->status === 'in_progress') Diproses
                                        @elseif($c->status === 'resolved') Selesai
                                        @else Ditutup @endif
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="py-4 text-center text-slate-400 font-medium">Belum ada pengaduan masuk.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Recent Survey Responses Table -->
        <div class="bg-white rounded-2xl border border-slate-200 p-6 space-y-4">
            <div class="flex justify-between items-center">
                <h3 class="text-sm font-bold text-slate-800 uppercase tracking-wider">Tanggapan Survey Terbaru</h3>
                <a href="{{ route('surveys.index') }}" class="text-xs font-bold text-sky-600 hover:text-sky-700 transition">Lihat Semua &rarr;</a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs border-collapse">
                    <thead>
                        <tr class="border-b border-slate-100 text-slate-400 font-bold uppercase">
                            <th class="py-3">Nama Responden</th>
                            <th class="py-3">Survey</th>
                            <th class="py-3">Tanggal Isi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50 text-slate-700">
                        @forelse($recentResponses as $r)
                            <tr>
                                <td class="py-3 font-semibold">{{ $r->respondent_name }}</td>
                                <td class="py-3 max-w-[200px] truncate">{{ $r->survey->title }}</td>
                                <td class="py-3 text-slate-400">{{ $r->submitted_at->diffForHumans() }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="py-4 text-center text-slate-400 font-medium">Belum ada tanggapan masuk.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Load Chart.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // 1. Line Chart: Complaints Trend
    const ctxTrend = document.getElementById('trendChart').getContext('2d');
    new Chart(ctxTrend, {
        type: 'line',
        data: {
            labels: {!! json_encode($trendLabels) !!},
            datasets: [{
                label: 'Jumlah Pengaduan',
                data: {!! json_encode($trendData) !!},
                borderColor: '#0284c7',
                backgroundColor: 'rgba(2, 132, 199, 0.1)',
                fill: true,
                tension: 0.3,
                borderWidth: 3
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false }
            },
            scales: {
                y: { beginAtZero: true, ticks: { stepSize: 1 } }
            }
        }
    });

    // 2. Doughnut Chart: Complaint Status Distribution
    const ctxStatus = document.getElementById('statusChart').getContext('2d');
    new Chart(ctxStatus, {
        type: 'doughnut',
        data: {
            labels: {!! json_encode($statusLabels) !!},
            datasets: [{
                data: {!! json_encode($statusData) !!},
                backgroundColor: ['#e0f2fe', '#fef3c7', '#d1fae5', '#f1f5f9'],
                borderColor: ['#0284c7', '#f59e0b', '#10b981', '#64748b'],
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { position: 'bottom' }
            }
        }
    });

    // 3. Bar Chart: Survey Rating Averages
    const ctxRating = document.getElementById('ratingChart').getContext('2d');
    new Chart(ctxRating, {
        type: 'bar',
        data: {
            labels: {!! json_encode($surveyRatingLabels) !!},
            datasets: [{
                label: 'Skor Rerata (1-5)',
                data: {!! json_encode($surveyRatingData) !!},
                backgroundColor: 'rgba(13, 148, 136, 0.15)',
                borderColor: '#0d9488',
                borderWidth: 2,
                borderRadius: 8
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false }
            },
            scales: {
                y: { min: 0, max: 5 }
            }
        }
    });
</script>
@endsection
