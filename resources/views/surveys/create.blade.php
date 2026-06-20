@extends('layouts.app')

@section('title', 'Buat Kuesioner Baru')

@section('content')
<div class="animate-fade-in max-w-3xl mx-auto space-y-6">
    <!-- Back link -->
    <a href="{{ route('surveys.index') }}" class="inline-flex items-center text-xs font-semibold text-slate-500 hover:text-sky-600 transition">
        &larr; Kembali ke Daftar Kuesioner
    </a>

    <!-- Main Card -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-8"
         x-data="{ 
            questions: [
                { question_text: '', type: 'rating', options: '' }
            ],
            addQuestion() {
                this.questions.push({ question_text: '', type: 'rating', options: '' });
            },
            removeQuestion(index) {
                if(this.questions.length > 1) {
                    this.questions.splice(index, 1);
                }
            }
         }">
        
        <form action="{{ route('surveys.store') }}" method="POST" class="space-y-8">
            @csrf

            <!-- Section 1: Survey Identity -->
            <div class="space-y-6">
                <h3 class="text-sm font-bold text-slate-800 uppercase tracking-wider border-b border-slate-100 pb-2">1. Informasi Utama Survey</h3>
                
                <div class="space-y-1">
                    <label for="title" class="block text-xs font-bold uppercase tracking-wider text-slate-700">Judul Survey <span class="text-red-500">*</span></label>
                    <input type="text" name="title" id="title" value="{{ old('title') }}" required
                           class="w-full px-4 h-11 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-sky-500/20 focus:border-sky-500 transition text-sm text-slate-800"
                           placeholder="e.g. Survey Kepuasan Pasien Triwulan I - 2026">
                    @error('title')
                        <span class="text-xs font-semibold text-red-500 block">{{ $message }}</span>
                    @enderror
                </div>

                <div class="space-y-1">
                    <label for="description" class="block text-xs font-bold uppercase tracking-wider text-slate-700">Deskripsi / Petunjuk Pengisian</label>
                    <textarea name="description" id="description" rows="3"
                              class="w-full p-4 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-sky-500/20 focus:border-sky-500 transition text-sm text-slate-800"
                              placeholder="Masukkan deskripsi singkat atau instruksi pengisian kuesioner bagi responden..."></textarea>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="space-y-1">
                        <label for="status" class="block text-xs font-bold uppercase tracking-wider text-slate-700">Status Awal</label>
                        <select name="status" id="status" required
                                class="w-full px-4 h-11 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-sky-500/20 focus:border-sky-500 transition text-sm text-slate-800 bg-white">
                            <option value="draft">Draft</option>
                            <option value="active">Aktif (Langsung Buka)</option>
                            <option value="closed">Ditutup</option>
                        </select>
                    </div>

                    <div class="space-y-1">
                        <label for="start_date" class="block text-xs font-bold uppercase tracking-wider text-slate-700">Tanggal Mulai</label>
                        <input type="date" name="start_date" id="start_date"
                               class="w-full px-4 h-11 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-sky-500/20 focus:border-sky-500 transition text-sm text-slate-800">
                    </div>

                    <div class="space-y-1">
                        <label for="end_date" class="block text-xs font-bold uppercase tracking-wider text-slate-700">Tanggal Selesai</label>
                        <input type="date" name="end_date" id="end_date"
                               class="w-full px-4 h-11 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-sky-500/20 focus:border-sky-500 transition text-sm text-slate-800">
                    </div>
                </div>
            </div>

            <!-- Section 2: Questions Builder -->
            <div class="space-y-6">
                <div class="flex justify-between items-center border-b border-slate-100 pb-2">
                    <h3 class="text-sm font-bold text-slate-800 uppercase tracking-wider">2. Daftar Pertanyaan</h3>
                    <button type="button" @click="addQuestion()" class="inline-flex items-center px-3 h-8 bg-sky-50 hover:bg-sky-100 text-sky-700 font-bold rounded-lg text-xs transition border border-sky-100">
                        ➕ Tambah Baris
                    </button>
                </div>

                <div class="space-y-6">
                    <template x-for="(q, index) in questions" :key="index">
                        <div class="p-6 bg-slate-50 border border-slate-200 rounded-2xl space-y-4 relative animate-fade-in">
                            <!-- Remove Button -->
                            <button type="button" @click="removeQuestion(index)" 
                                    class="absolute top-4 right-4 text-xs font-bold text-rose-600 hover:text-rose-700 transition" 
                                    x-show="questions.length > 1">
                                🗑️ Hapus
                            </button>

                            <h4 class="text-xs font-bold text-sky-600 uppercase tracking-wider">Pertanyaan #<span x-text="index + 1"></span></h4>

                            <!-- Question Text -->
                            <div class="space-y-1">
                                <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-700">Teks Pertanyaan <span class="text-red-500">*</span></label>
                                <input type="text" :name="`questions[${index}][question_text]`" x-model="q.question_text" required
                                       class="w-full px-4 h-10 border border-slate-200 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-sky-500/20 focus:border-sky-500 transition text-slate-800"
                                       placeholder="e.g. Bagaimana penilaian Anda terhadap kebersihan poli gigi?">
                            </div>

                            <!-- Choice Fields -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="space-y-1">
                                    <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-700">Tipe Jawaban</label>
                                    <select :name="`questions[${index}][type]`" x-model="q.type" required
                                            class="w-full px-4 h-10 border border-slate-200 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-sky-500/20 focus:border-sky-500 transition text-slate-800 bg-white">
                                        <option value="rating">Rating Skala (1-5)</option>
                                        <option value="multiple_choice">Pilihan Ganda (Single Choice)</option>
                                        <option value="text">Teks Bebas / Deskriptif</option>
                                    </select>
                                </div>

                                <!-- Multiple Choice Options -->
                                <div class="space-y-1" x-show="q.type === 'multiple_choice'">
                                    <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-700">Opsi Pilihan (Pisahkan dengan koma)</label>
                                    <input type="text" :name="`questions[${index}][options]`" x-model="q.options" :required="q.type === 'multiple_choice'"
                                           class="w-full px-4 h-10 border border-slate-200 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-sky-500/20 focus:border-sky-500 transition text-slate-800"
                                           placeholder="e.g. Sangat Cepat, Cukup Lambat, Sangat Lambat">
                                </div>
                            </div>
                        </div>
                    </template>
                </div>
            </div>

            <!-- Submit Buttons -->
            <div class="flex justify-end space-x-3 border-t border-slate-100 pt-6">
                <a href="{{ route('surveys.index') }}" class="inline-flex items-center justify-center px-4 h-11 bg-slate-100 hover:bg-slate-200 text-slate-600 font-bold rounded-xl text-xs transition">
                    Batal
                </a>
                <button type="submit" class="inline-flex items-center justify-center px-6 h-11 bg-sky-600 hover:bg-sky-700 text-white font-bold rounded-xl text-xs transition shadow-md shadow-sky-200">
                    Simpan & Publikasikan Survey
                </button>
            </div>

        </form>

    </div>
</div>
@endsection
