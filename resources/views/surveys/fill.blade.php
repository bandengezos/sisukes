@extends('layouts.public')

@section('content')
<div class="animate-fade-in max-w-2xl mx-auto">
    <div class="space-y-6">
        <!-- Back Link -->
        <a href="{{ route('welcome') }}" class="inline-flex items-center text-xs font-semibold text-slate-500 hover:text-sky-600 transition">
            &larr; Kembali ke Beranda
        </a>

        @if(!$survey)
            <!-- No Active Survey Card -->
            <div class="bg-white rounded-2xl border border-slate-200 p-8 text-center space-y-4">
                <div class="text-4xl">📋</div>
                <h1 class="text-2xl font-bold text-slate-800">Tidak Ada Survey Aktif</h1>
                <p class="text-sm text-slate-600">Saat ini belum ada formulir survey kepuasan yang dibuka oleh manajemen klinik. Silakan kembali di lain waktu.</p>
                <a href="{{ route('welcome') }}" class="inline-flex items-center px-4 h-10 bg-slate-800 text-white text-xs font-bold rounded-xl hover:bg-slate-900 transition">
                    Kembali ke Beranda
                </a>
            </div>
        @else
            <!-- Header -->
            <div class="space-y-2">
                <h1 class="text-3xl font-black text-slate-800 tracking-tight">{{ $survey->title }}</h1>
                @if($survey->description)
                    <p class="text-sm text-slate-600 leading-relaxed">{{ $survey->description }}</p>
                @endif
            </div>

            @if(session('success'))
                <!-- Success Notification -->
                <div class="p-6 bg-emerald-50 border border-emerald-100 rounded-2xl space-y-3">
                    <div class="flex items-center space-x-2 text-emerald-800 font-bold">
                        <span class="text-xl">🌟</span>
                        <span>Survey Berhasil Dikirim!</span>
                    </div>
                    <p class="text-sm text-emerald-700 leading-relaxed">{{ session('success') }}</p>
                    <a href="{{ route('welcome') }}" class="inline-flex items-center justify-center px-5 h-10 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold rounded-xl text-xs transition">
                        Kembali ke Beranda
                    </a>
                </div>
            @else
                <!-- Survey Form Card -->
                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-8">
                    <form action="{{ route('survey.submit', $survey->id) }}" method="POST" class="space-y-8">
                        @csrf

                        <!-- Respondent Name -->
                        <div class="p-5 bg-sky-50/50 border border-sky-100/50 rounded-xl space-y-2">
                            <label for="respondent_name" class="block text-xs font-bold uppercase tracking-wider text-slate-700">Nama Responden <span class="text-slate-400 font-normal">(Opsional)</span></label>
                            <input type="text" name="respondent_name" id="respondent_name" value="{{ old('respondent_name') }}"
                                class="w-full px-4 h-11 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-sky-500/20 focus:border-sky-500 transition text-sm text-slate-800 bg-white"
                                placeholder="Masukkan nama Anda (kosongkan jika ingin anonim)">
                        </div>

                        <!-- Dynamic Questions Loop -->
                        <div class="space-y-8 divide-y divide-slate-100">
                            @foreach($survey->questions as $index => $q)
                                <div class="pt-6 first:pt-0 space-y-4">
                                    <h3 class="text-base font-bold text-slate-800">
                                        {{ $index + 1 }}. {{ $q->question_text }}
                                        <span class="text-red-500">*</span>
                                    </h3>

                                    <!-- Rating Input (1-5 stars/numbers) -->
                                    @if($q->type === 'rating')
                                        <div class="flex items-center space-x-2 md:space-x-4">
                                            @for($i = 1; $i <= 5; $i++)
                                                <label class="flex-1 cursor-pointer">
                                                    <input type="radio" name="q_{{ $q->id }}" value="{{ $i }}" required {{ old('q_'.$q->id) == $i ? 'checked' : '' }} class="sr-only peer">
                                                    <div class="flex flex-col items-center justify-center py-3 border border-slate-200 rounded-xl peer-checked:border-sky-500 peer-checked:bg-sky-50 hover:bg-slate-50 transition text-center">
                                                        <span class="text-lg font-bold text-slate-700 peer-checked:text-sky-600">{{ $i }}</span>
                                                        <span class="text-[10px] text-slate-400 mt-1 uppercase tracking-tighter">
                                                            @if($i == 1) Buruk
                                                            @elseif($i == 3) Cukup
                                                            @elseif($i == 5) Sangat Baik
                                                            @else &nbsp;
                                                            @endif
                                                        </span>
                                                    </div>
                                                </label>
                                            @endfor
                                        </div>

                                    <!-- Multiple Choice Input -->
                                    @elseif($q->type === 'multiple_choice')
                                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                            @if(is_array($q->options))
                                                @foreach($q->options as $opt)
                                                    <label class="flex items-center p-4 border border-slate-200 rounded-xl cursor-pointer hover:bg-slate-50 transition">
                                                        <input type="radio" name="q_{{ $q->id }}" value="{{ $opt }}" required {{ old('q_'.$q->id) == $opt ? 'checked' : '' }} class="w-4 h-4 text-sky-600 border-slate-300 focus:ring-sky-500">
                                                        <span class="ml-3 text-sm font-medium text-slate-700">{{ $opt }}</span>
                                                    </label>
                                                @endforeach
                                            @endif
                                        </div>

                                    <!-- Text Area Input -->
                                    @elseif($q->type === 'text')
                                        <textarea name="q_{{ $q->id }}" rows="4" required
                                            class="w-full p-4 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-sky-500/20 focus:border-sky-500 transition text-sm text-slate-800"
                                            placeholder="Tuliskan ulasan atau saran Anda disini...">{{ old('q_'.$q->id) }}</textarea>
                                    @endif

                                    @error('q_'.$q->id)
                                        <span class="text-xs font-semibold text-red-500 block mt-1">{{ $message }}</span>
                                    @enderror
                                </div>
                            @endforeach
                        </div>

                        <!-- Submit Button -->
                        <button type="submit" class="w-full inline-flex items-center justify-center px-6 h-12 bg-sky-600 hover:bg-sky-700 text-white font-bold rounded-xl text-sm transition shadow-md shadow-sky-200">
                            Kirim Jawaban Survey &rarr;
                        </button>
                    </form>
                </div>
            @endif
        @endif
    </div>
</div>
@endsection
