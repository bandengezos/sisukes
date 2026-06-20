@extends('layouts.public')

@section('content')
<div class="animate-fade-in max-w-2xl mx-auto">
    <div class="space-y-6">
        <!-- Breadcrumb / Back Link -->
        <a href="{{ route('welcome') }}" class="inline-flex items-center text-xs font-semibold text-slate-500 hover:text-sky-600 transition">
            &larr; Kembali ke Beranda
        </a>

        <!-- Header -->
        <div class="space-y-2">
            <h1 class="text-3xl font-black text-slate-800 tracking-tight">Formulir Pengaduan Pasien</h1>
            <p class="text-sm text-slate-600">Sampaikan keluhan Anda secara konstruktif demi perbaikan kualitas layanan klinik kami.</p>
        </div>

        @if(session('success'))
            <!-- Success Notification Card -->
            <div class="p-6 bg-emerald-50 border border-emerald-100 rounded-2xl space-y-3">
                <div class="flex items-center space-x-2 text-emerald-800 font-bold">
                    <span class="text-xl">✅</span>
                    <span>Pengaduan Berhasil Dikirim!</span>
                </div>
                <p class="text-sm text-emerald-700 leading-relaxed">{{ session('success') }}</p>
                <div class="text-xs text-emerald-600 font-medium bg-white/60 p-3 rounded-lg border border-emerald-100/50">
                    Mohon simpan Kode Tiket Anda di atas untuk memudahkan pengecekan status di kemudian hari.
                </div>
            </div>
        @else
            <!-- Main Form Card -->
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-8">
                <form action="{{ route('pengaduan.store') }}" method="POST" class="space-y-6">
                    @csrf

                    <!-- Identity -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-1">
                            <label for="complainant_name" class="block text-xs font-bold uppercase tracking-wider text-slate-700">Nama Lengkap <span class="text-red-500">*</span></label>
                            <input type="text" name="complainant_name" id="complainant_name" value="{{ old('complainant_name') }}" required
                                class="w-full px-4 h-11 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-sky-500/20 focus:border-sky-500 transition text-sm text-slate-800"
                                placeholder="e.g. Hendra Wijaya">
                            @error('complainant_name')
                                <span class="text-xs font-semibold text-red-500 block">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="space-y-1">
                            <label for="complainant_phone" class="block text-xs font-bold uppercase tracking-wider text-slate-700">No. Telepon / WhatsApp</label>
                            <input type="text" name="complainant_phone" id="complainant_phone" value="{{ old('complainant_phone') }}"
                                class="w-full px-4 h-11 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-sky-500/20 focus:border-sky-500 transition text-sm text-slate-800"
                                placeholder="e.g. 0812XXXXXXXX">
                            @error('complainant_phone')
                                <span class="text-xs font-semibold text-red-500 block">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="space-y-1">
                        <label for="complainant_email" class="block text-xs font-bold uppercase tracking-wider text-slate-700">Alamat Email</label>
                        <input type="email" name="complainant_email" id="complainant_email" value="{{ old('complainant_email') }}"
                            class="w-full px-4 h-11 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-sky-500/20 focus:border-sky-500 transition text-sm text-slate-800"
                            placeholder="e.g. nama@email.com">
                        @error('complainant_email')
                            <span class="text-xs font-semibold text-red-500 block">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Category & Priority -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-1">
                            <label for="category_id" class="block text-xs font-bold uppercase tracking-wider text-slate-700">Kategori Aduan <span class="text-red-500">*</span></label>
                            <select name="category_id" id="category_id" required
                                class="w-full px-4 h-11 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-sky-500/20 focus:border-sky-500 transition text-sm text-slate-800 bg-white">
                                <option value="">Pilih Kategori...</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                @endforeach
                            </select>
                            @error('category_id')
                                <span class="text-xs font-semibold text-red-500 block">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="space-y-1">
                            <label for="priority" class="block text-xs font-bold uppercase tracking-wider text-slate-700">Skala Urgensi <span class="text-red-500">*</span></label>
                            <select name="priority" id="priority" required
                                class="w-full px-4 h-11 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-sky-500/20 focus:border-sky-500 transition text-sm text-slate-800 bg-white">
                                <option value="low" {{ old('priority') == 'low' ? 'selected' : '' }}>Rendah (Low)</option>
                                <option value="medium" {{ old('priority', 'medium') == 'medium' ? 'selected' : '' }}>Sedang (Medium)</option>
                                <option value="high" {{ old('priority') == 'high' ? 'selected' : '' }}>Tinggi (High)</option>
                                <option value="critical" {{ old('priority') == 'critical' ? 'selected' : '' }}>Sangat Penting (Critical)</option>
                            </select>
                            @error('priority')
                                <span class="text-xs font-semibold text-red-500 block">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- Subject & Description -->
                    <div class="space-y-1">
                        <label for="subject" class="block text-xs font-bold uppercase tracking-wider text-slate-700">Subjek Pengaduan <span class="text-red-500">*</span></label>
                        <input type="text" name="subject" id="subject" value="{{ old('subject') }}" required
                            class="w-full px-4 h-11 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-sky-500/20 focus:border-sky-500 transition text-sm text-slate-800"
                            placeholder="e.g. AC Mati, Pelayanan Farmasi Lambat">
                        @error('subject')
                            <span class="text-xs font-semibold text-red-500 block">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="space-y-1">
                        <label for="description" class="block text-xs font-bold uppercase tracking-wider text-slate-700">Deskripsi Detail Keluhan <span class="text-red-500">*</span></label>
                        <textarea name="description" id="description" rows="5" required
                            class="w-full p-4 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-sky-500/20 focus:border-sky-500 transition text-sm text-slate-800"
                            placeholder="Tuliskan detail kronologi, lokasi, atau waktu kejadian secara lengkap agar memudahkan evaluasi kami..."></textarea>
                        @error('description')
                            <span class="text-xs font-semibold text-red-500 block">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="w-full inline-flex items-center justify-center px-6 h-12 bg-sky-600 hover:bg-sky-700 text-white font-bold rounded-xl text-sm transition shadow-md shadow-sky-200">
                        Kirim Pengaduan Sekarang &rarr;
                    </button>
                </form>
            </div>
        @endif
    </div>
</div>
@endsection
