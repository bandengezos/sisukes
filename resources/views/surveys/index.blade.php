@extends('layouts.app')

@section('title', 'Manajemen Survey Kepuasan')

@section('content')
<div class="space-y-6">
    <!-- Header Actions -->
    <div class="flex justify-between items-center">
        <p class="text-xs text-slate-500 font-medium">Kelola kuesioner survey kepuasan pasien dan pantau hasil analisa data secara real-time.</p>
        <a href="{{ route('surveys.create') }}" class="inline-flex items-center px-4 h-10 bg-sky-600 hover:bg-sky-700 text-white font-bold rounded-lg text-xs transition shadow-sm">
            ➕ Buat Survey Baru
        </a>
    </div>

    <!-- Table Card -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs border-collapse">
                <thead>
                    <tr class="bg-slate-50/50 border-b border-slate-200 text-slate-400 font-bold uppercase">
                        <th class="px-6 py-4">Judul Survey</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4">Total Responden</th>
                        <th class="px-6 py-4">Tgl Dibuat</th>
                        <th class="px-6 py-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    @forelse($surveys as $s)
                        <tr class="hover:bg-slate-50/50 transition">
                            <td class="px-6 py-4">
                                <span class="font-bold text-slate-800 block text-sm">{{ $s->title }}</span>
                                @if($s->description)
                                    <span class="text-[10px] text-slate-400 block truncate max-w-xs">{{ $s->description }}</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase
                                    @if($s->status === 'active') badge-resolved
                                    @elseif($s->status === 'draft') badge-received
                                    @else badge-closed @endif">
                                    @if($s->status === 'active') Aktif
                                    @elseif($s->status === 'draft') Draft
                                    @else Ditutup @endif
                                </span>
                            </td>
                            <td class="px-6 py-4 font-bold text-slate-800">{{ $s->responses_count }} Responden</td>
                            <td class="px-6 py-4 text-slate-400">{{ $s->created_at->format('d-m-Y') }}</td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end space-x-2">
                                    @if($s->status === 'active')
                                        <a href="{{ route('survey.fill', $s->id) }}" target="_blank" class="inline-flex items-center justify-center px-3 h-8 bg-sky-50 hover:bg-sky-100 text-sky-700 font-semibold rounded-lg border border-sky-100 transition text-[10px]" title="Buka Link Pengisian">
                                            🔗 Link Form
                                        </a>
                                    @endif
                                    
                                    <a href="{{ route('surveys.show', $s->id) }}" class="inline-flex items-center justify-center px-3 h-8 bg-slate-800 hover:bg-slate-900 text-white font-semibold rounded-lg transition text-[10px]">
                                        📊 Analisis Hasil
                                    </a>

                                    <form action="{{ route('surveys.destroy', $s->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus survey ini beserta semua jawabannya?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="inline-flex items-center justify-center w-8 h-8 bg-rose-50 hover:bg-rose-100 text-rose-600 rounded-lg border border-rose-100 transition" title="Hapus">
                                            🗑️
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center text-slate-400 font-semibold">
                                Belum ada kuesioner survey yang dibuat.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($surveys->hasPages())
            <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/20">
                {{ $surveys->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
