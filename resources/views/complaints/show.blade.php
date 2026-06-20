@extends('layouts.app')

@section('title')
    Detail Pengaduan #{{ $complaint->id }}
@endsection

@section('content')
<div class="space-y-6">
    <!-- Back button & Status header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <a href="{{ route('complaints.index') }}" class="inline-flex items-center text-xs font-semibold text-slate-500 hover:text-sky-600 transition">
            &larr; Kembali ke Daftar Pengaduan
        </a>
        
        <div class="flex items-center space-x-2">
            <span class="text-xs font-bold text-slate-400 uppercase">Status Saat Ini:</span>
            <span class="px-2 py-0.5 rounded-full text-xs font-bold uppercase
                @if($complaint->status === 'received') badge-received
                @elseif($complaint->status === 'in_progress') badge-progress
                @elseif($complaint->status === 'resolved') badge-resolved
                @else badge-closed @endif">
                @if($complaint->status === 'received') Diterima
                @elseif($complaint->status === 'in_progress') Diproses
                @elseif($complaint->status === 'resolved') Selesai
                @else Ditutup @endif
            </span>
        </div>
    </div>

    <!-- Main Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Left Column: Details -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Complaint Details Card -->
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-8 space-y-6">
                <div class="border-b border-slate-100 pb-4 space-y-2">
                    <span class="px-2 py-0.5 rounded-md text-[10px] font-bold uppercase
                        @if($complaint->priority === 'low') badge-low
                        @elseif($complaint->priority === 'medium') badge-medium
                        @elseif($complaint->priority === 'high') badge-high
                        @else badge-critical @endif">
                        Prioritas: @if($complaint->priority === 'low') Rendah @elseif($complaint->priority === 'medium') Sedang @elseif($complaint->priority === 'high') Tinggi @else Kritis @endif
                    </span>
                    <h2 class="text-2xl font-black text-slate-800 tracking-tight">{{ $complaint->subject }}</h2>
                </div>

                <!-- Sender Info -->
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 p-4 bg-slate-50 rounded-xl border border-slate-100/50 text-xs">
                    <div>
                        <span class="block text-slate-400 font-bold uppercase tracking-wider mb-1">Nama Pengadu</span>
                        <span class="font-bold text-slate-800 block text-sm">{{ $complaint->complainant_name }}</span>
                    </div>
                    <div>
                        <span class="block text-slate-400 font-bold uppercase tracking-wider mb-1">Kontak Telepon</span>
                        <span class="font-bold text-slate-800 block text-sm">{{ $complaint->complainant_phone ?: '-' }}</span>
                    </div>
                    <div>
                        <span class="block text-slate-400 font-bold uppercase tracking-wider mb-1">Alamat Email</span>
                        <span class="font-bold text-slate-800 block text-sm">{{ $complaint->complainant_email ?: '-' }}</span>
                    </div>
                </div>

                <!-- Description -->
                <div class="space-y-2">
                    <span class="block text-xs font-bold uppercase tracking-wider text-slate-400">Deskripsi Keluhan</span>
                    <p class="text-sm text-slate-700 leading-relaxed whitespace-pre-line">{{ $complaint->description }}</p>
                </div>

                <!-- Additional Metadata -->
                <div class="flex justify-between items-center text-[10px] text-slate-400 font-medium border-t border-slate-100 pt-4">
                    <span>Kategori: <strong class="text-slate-600">{{ $complaint->category->name }}</strong></span>
                    <span>Dilaporkan: {{ $complaint->created_at->format('d F Y, H:i') }} ({{ $complaint->created_at->diffForHumans() }})</span>
                </div>
            </div>

            <!-- Response / Action Form Card -->
            @if($complaint->status !== 'closed')
                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-8 space-y-4">
                    <h3 class="text-sm font-bold text-slate-800 uppercase tracking-wider">Tulis Tanggapan / Tindak Lanjut</h3>
                    <form action="{{ route('complaints.respond', $complaint->id) }}" method="POST" class="space-y-4">
                        @csrf
                        <textarea name="response_text" rows="4" required
                                  class="w-full p-4 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-sky-500/20 focus:border-sky-500 transition text-sm text-slate-800"
                                  placeholder="Tuliskan respon, tindakan yang sudah diambil, atau instruksi tindak lanjut..."></textarea>
                        @error('response_text')
                            <span class="text-xs font-semibold text-red-500 block">{{ $message }}</span>
                        @enderror
                        <div class="flex justify-between items-center">
                            <span class="text-[10px] text-slate-400">Status akan otomatis berubah ke <strong>Diproses</strong> jika sebelumnya masih <strong>Diterima</strong>.</span>
                            <button type="submit" class="inline-flex items-center px-4 h-10 bg-sky-600 hover:bg-sky-700 text-white font-bold rounded-lg text-xs transition shadow-sm">
                                Kirim Tanggapan
                            </button>
                        </div>
                    </form>
                </div>
            @endif
        </div>

        <!-- Right Column: Status & Timeline -->
        <div class="space-y-6">
            <!-- Update Status Card -->
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 space-y-4">
                <h3 class="text-sm font-bold text-slate-800 uppercase tracking-wider">Perbarui Status</h3>
                <form action="{{ route('complaints.status', $complaint->id) }}" method="POST" class="space-y-3">
                    @csrf
                    @method('PUT')
                    
                    <select name="status"
                            class="w-full px-3 h-10 border border-slate-200 rounded-lg text-xs focus:outline-none focus:ring-2 focus:ring-sky-500/20 focus:border-sky-500 transition text-slate-800 bg-white">
                        <option value="received" {{ $complaint->status === 'received' ? 'selected' : '' }}>Diterima</option>
                        <option value="in_progress" {{ $complaint->status === 'in_progress' ? 'selected' : '' }}>Diproses</option>
                        <option value="resolved" {{ $complaint->status === 'resolved' ? 'selected' : '' }}>Selesai</option>
                        <option value="closed" {{ $complaint->status === 'closed' ? 'selected' : '' }}>Ditutup</option>
                    </select>

                    <button type="submit" class="w-full inline-flex items-center justify-center h-10 bg-slate-800 hover:bg-slate-900 text-white font-bold rounded-lg text-xs transition">
                        Update Status
                    </button>
                </form>
            </div>

            <!-- Timeline Card -->
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 space-y-4">
                <h3 class="text-sm font-bold text-slate-800 uppercase tracking-wider">Timeline Aktivitas</h3>
                
                <div class="timeline-container space-y-6">
                    <!-- Ticket Created Log -->
                    <div class="relative pl-6">
                        <div class="timeline-dot system"></div>
                        <span class="block text-[10px] text-slate-400 font-bold uppercase">{{ $complaint->created_at->format('d-m-Y H:i') }}</span>
                        <p class="text-xs font-semibold text-slate-700">Tiket Pengaduan Dibuat</p>
                        <span class="text-[10px] text-slate-400">Oleh: {{ $complaint->complainant_name }}</span>
                    </div>

                    <!-- Timeline Responses -->
                    @foreach($complaint->responses as $response)
                        <div class="relative pl-6">
                            <div class="timeline-dot {{ str_contains($response->response_text, 'Mengubah status') ? 'system' : '' }}"></div>
                            <span class="block text-[10px] text-slate-400 font-bold uppercase">{{ $response->created_at->format('d-m-Y H:i') }}</span>
                            <p class="text-xs text-slate-700 leading-relaxed">{{ $response->response_text }}</p>
                            <span class="text-[10px] text-slate-400">Oleh: <strong>{{ $response->user->name }}</strong></span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
