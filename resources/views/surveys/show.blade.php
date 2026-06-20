@extends('layouts.app')

@section('title', 'Laporan Analisis Hasil Survey')

@section('content')
<div class="animate-fade-in space-y-6">
    <!-- Header with Back Link -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <a href="{{ route('surveys.index') }}" class="inline-flex items-center text-xs font-semibold text-slate-500 hover:text-sky-600 transition">
            &larr; Kembali ke Daftar Kuesioner
        </a>
        <span class="text-xs font-bold text-slate-400">Total Responden saat ini: <strong class="text-slate-800 text-sm bg-white border border-slate-200 px-3 py-1 rounded-lg ml-1 shadow-sm">{{ $totalResponses }} Pasien</strong></span>
    </div>

    <!-- Survey Meta Info -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 space-y-2">
        <h2 class="text-xl font-black text-slate-800 tracking-tight">{{ $survey->title }}</h2>
        @if($survey->description)
            <p class="text-xs text-slate-500 leading-relaxed">{{ $survey->description }}</p>
        @endif
        <div class="flex flex-wrap gap-4 text-[10px] text-slate-400 font-semibold pt-2">
            <span>Status: 
                <span class="px-2 py-0.5 rounded-full text-[9px] uppercase font-bold
                    @if($survey->status === 'active') badge-resolved
                    @elseif($survey->status === 'draft') badge-received
                    @else badge-closed @endif">
                    {{ $survey->status }}
                </span>
            </span>
            <span>Mulai: <strong class="text-slate-600">{{ $survey->start_date ?: '-' }}</strong></span>
            <span>Selesai: <strong class="text-slate-600">{{ $survey->end_date ?: '-' }}</strong></span>
        </div>
    </div>

    <!-- Processed Reports Loop -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        @foreach($questionsReport as $index => $rep)
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 flex flex-col justify-between space-y-4">
                
                <!-- Question Title Header -->
                <div class="space-y-1">
                    <span class="text-[10px] font-bold text-sky-600 uppercase tracking-wider block">Pertanyaan #{{ $index + 1 }}</span>
                    <h3 class="text-sm font-bold text-slate-800 leading-snug">{{ $rep['question']->question_text }}</h3>
                </div>

                <!-- Report Visualization -->
                <div class="flex-grow flex flex-col justify-center py-4">
                    <!-- Case 1: Rating Type -->
                    @if($rep['type'] === 'rating')
                        <div class="space-y-4 w-full">
                            <!-- Overall Score -->
                            <div class="text-center p-3 bg-sky-50 rounded-xl border border-sky-100/50">
                                <span class="text-xs font-bold text-slate-500 uppercase tracking-wider block">Skor Kepuasan</span>
                                <span class="text-3xl font-black text-sky-600">{{ $rep['average'] }}</span>
                                <span class="text-xs text-slate-400">/ 5.0</span>
                            </div>

                            <!-- Rating bars -->
                            <div class="space-y-2">
                                @for($i = 5; $i >= 1; $i--)
                                    @php
                                        $count = $rep['distribution'][$i] ?? 0;
                                        $percent = $rep['total_answers'] > 0 ? round(($count / $rep['total_answers']) * 100) : 0;
                                    @endphp
                                    <div class="flex items-center text-xs text-slate-600 gap-2">
                                        <span class="w-12 text-right font-bold text-slate-500">Skor {{ $i }}</span>
                                        <div class="flex-grow h-3 bg-slate-100 rounded-full overflow-hidden">
                                            <div class="h-full bg-sky-500 rounded-full transition-all duration-500" style="width: {{ $percent }}%"></div>
                                        </div>
                                        <span class="w-8 text-slate-400 text-right">{{ $count }} ({{ $percent }}%)</span>
                                    </div>
                                @endfor
                            </div>
                        </div>

                    <!-- Case 2: Multiple Choice Type -->
                    @elseif($rep['type'] === 'multiple_choice')
                        <div class="h-48 relative">
                            <canvas id="mcChart_{{ $rep['question']->id }}"></canvas>
                        </div>

                    <!-- Case 3: Text Type -->
                    @elseif($rep['type'] === 'text')
                        <div class="space-y-2 w-full max-h-48 overflow-y-auto pr-2">
                            @forelse($rep['recent_answers'] as $textAns)
                                <div class="p-3 bg-slate-50 border border-slate-100 rounded-lg text-xs text-slate-700 leading-relaxed">
                                    &ldquo;{{ $textAns }}&rdquo;
                                </div>
                            @empty
                                <div class="text-center py-8 text-slate-400 font-medium">Belum ada saran/tanggapan teks masuk.</div>
                            @endforelse
                        </div>
                    @endif
                </div>

                <!-- Footer Summary Info -->
                <div class="text-[10px] text-slate-400 font-semibold pt-2 border-t border-slate-50 flex justify-between">
                    <span>Jawaban masuk: <strong class="text-slate-600">{{ $rep['total_answers'] }}</strong></span>
                    <span>Tipe: <strong class="text-slate-600 capitalize">{{ $rep['type'] }}</strong></span>
                </div>

            </div>
        @endforeach
    </div>
</div>

<!-- Load Chart.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    @foreach($questionsReport as $rep)
        @if($rep['type'] === 'multiple_choice')
            const ctx_{{ $rep['question']->id }} = document.getElementById('mcChart_{{ $rep['question']->id }}').getContext('2d');
            new Chart(ctx_{{ $rep['question']->id }}, {
                type: 'pie',
                data: {
                    labels: {!! json_encode(array_keys($rep['distribution'])) !!},
                    datasets: [{
                        data: {!! json_encode(array_values($rep['distribution'])) !!},
                        backgroundColor: ['#38bdf8', '#0ea5e9', '#0284c7', '#0369a1', '#075985', '#0c4a6e'],
                        borderWidth: 1,
                        borderColor: '#ffffff'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                boxWidth: 12,
                                font: { size: 10 }
                            }
                        }
                    }
                }
            });
        @endif
    @endforeach
</script>
@endsection
