@extends('layouts.app')

@section('title', 'Daftar Laporan Pengaduan')

@section('content')
<div class="space-y-6">
    
    <!-- Filters Card -->
    <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm">
        <form action="{{ route('complaints.index') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
            <!-- Search -->
            <div class="space-y-1">
                <label for="search" class="block text-[10px] font-bold uppercase tracking-wider text-slate-400">Pencarian</label>
                <input type="text" name="search" id="search" value="{{ request('search') }}"
                       class="w-full px-3 h-10 border border-slate-200 rounded-lg text-xs focus:outline-none focus:ring-2 focus:ring-sky-500/20 focus:border-sky-500 transition text-slate-800"
                       placeholder="Cari subjek, nama pengadu...">
            </div>

            <!-- Kategori -->
            <div class="space-y-1">
                <label for="category_id" class="block text-[10px] font-bold uppercase tracking-wider text-slate-400">Kategori</label>
                <select name="category_id" id="category_id"
                        class="w-full px-3 h-10 border border-slate-200 rounded-lg text-xs focus:outline-none focus:ring-2 focus:ring-sky-500/20 focus:border-sky-500 transition text-slate-800 bg-white">
                    <option value="">Semua Kategori</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Status -->
            <div class="space-y-1">
                <label for="status" class="block text-[10px] font-bold uppercase tracking-wider text-slate-400">Status</label>
                <select name="status" id="status"
                        class="w-full px-3 h-10 border border-slate-200 rounded-lg text-xs focus:outline-none focus:ring-2 focus:ring-sky-500/20 focus:border-sky-500 transition text-slate-800 bg-white">
                    <option value="">Semua Status</option>
                    <option value="received" {{ request('status') === 'received' ? 'selected' : '' }}>Diterima (Received)</option>
                    <option value="in_progress" {{ request('status') === 'in_progress' ? 'selected' : '' }}>Diproses (In Progress)</option>
                    <option value="resolved" {{ request('status') === 'resolved' ? 'selected' : '' }}>Selesai (Resolved)</option>
                    <option value="closed" {{ request('status') === 'closed' ? 'selected' : '' }}>Ditutup (Closed)</option>
                </select>
            </div>

            <!-- Urgensi / Prioritas -->
            <div class="space-y-1">
                <label for="priority" class="block text-[10px] font-bold uppercase tracking-wider text-slate-400">Urgensi</label>
                <select name="priority" id="priority"
                        class="w-full px-3 h-10 border border-slate-200 rounded-lg text-xs focus:outline-none focus:ring-2 focus:ring-sky-500/20 focus:border-sky-500 transition text-slate-800 bg-white">
                    <option value="">Semua Urgensi</option>
                    <option value="low" {{ request('priority') === 'low' ? 'selected' : '' }}>Rendah (Low)</option>
                    <option value="medium" {{ request('priority') === 'medium' ? 'selected' : '' }}>Sedang (Medium)</option>
                    <option value="high" {{ request('priority') === 'high' ? 'selected' : '' }}>Tinggi (High)</option>
                    <option value="critical" {{ request('priority') === 'critical' ? 'selected' : '' }}>Sangat Penting (Critical)</option>
                </select>
            </div>

            <!-- Action Buttons -->
            <div class="flex items-end space-x-2">
                <button type="submit" class="flex-grow inline-flex items-center justify-center h-10 bg-sky-600 hover:bg-sky-700 text-white font-bold rounded-lg text-xs transition">
                    Cari & Filter
                </button>
                <a href="{{ route('complaints.index') }}" class="inline-flex items-center justify-center w-10 h-10 bg-slate-100 hover:bg-slate-200 text-slate-500 rounded-lg transition" title="Reset Filter">
                    🔄
                </a>
            </div>
        </form>
    </div>

    <!-- Table Card -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs border-collapse">
                <thead>
                    <tr class="bg-slate-50/50 border-b border-slate-200 text-slate-400 font-bold uppercase">
                        <th class="px-6 py-4">Tiket</th>
                        <th class="px-6 py-4">Pengadu</th>
                        <th class="px-6 py-4">Subjek / Masalah</th>
                        <th class="px-6 py-4">Kategori</th>
                        <th class="px-6 py-4">Urgensi</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4">Tanggal Masuk</th>
                        <th class="px-6 py-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    @forelse($complaints as $c)
                        <tr class="hover:bg-slate-50/50 transition">
                            <td class="px-6 py-4 font-bold text-sky-600">#{{ $c->id }}</td>
                            <td class="px-6 py-4">
                                <span class="font-bold block text-slate-800">{{ $c->complainant_name }}</span>
                                <span class="text-[10px] text-slate-400 block">{{ $c->complainant_phone ?: 'No Phone' }}</span>
                            </td>
                            <td class="px-6 py-4 max-w-[200px] truncate">
                                <span class="font-semibold block text-slate-800 truncate">{{ $c->subject }}</span>
                                <span class="text-[10px] text-slate-400 block truncate">{{ $c->description }}</span>
                            </td>
                            <td class="px-6 py-4 font-medium">{{ $c->category->name }}</td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase
                                    @if($c->priority === 'low') badge-low
                                    @elseif($c->priority === 'medium') badge-medium
                                    @elseif($c->priority === 'high') badge-high
                                    @else badge-critical @endif">
                                    @if($c->priority === 'low') Rendah
                                    @elseif($c->priority === 'medium') Sedang
                                    @elseif($c->priority === 'high') Tinggi
                                    @else Critical @endif
                                </span>
                            </td>
                            <td class="px-6 py-4">
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
                            <td class="px-6 py-4 text-slate-400">{{ $c->created_at->format('d-m-Y H:i') }}</td>
                            <td class="px-6 py-4 text-right">
                                <a href="{{ route('complaints.show', $c->id) }}" class="inline-flex items-center justify-center px-3 h-8 bg-sky-50 hover:bg-sky-100 text-sky-700 font-bold rounded-lg border border-sky-100 transition">
                                    Detail &rarr;
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-8 text-center text-slate-400 font-semibold">
                                Tidak ada data pengaduan yang ditemukan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($complaints->hasPages())
            <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/20">
                {{ $complaints->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
