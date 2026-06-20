@extends('layouts.public')

@section('content')
<div class="animate-fade-in space-y-16">
    <!-- Hero Section -->
    <div class="text-center max-w-3xl mx-auto space-y-6 py-8">
        <span class="px-3 py-1 bg-sky-50 text-sky-700 text-xs font-semibold rounded-full border border-sky-100 uppercase tracking-wider">Layanan Pengaduan & Kepuasan</span>
        <h1 class="text-4xl md:text-5xl font-black text-slate-800 tracking-tight leading-tight">
            Membantu Mewujudkan <span class="text-sky-600 bg-gradient-to-r from-sky-600 to-teal-500 bg-clip-text text-transparent">Klinik yang Lebih Baik</span>
        </h1>
        <p class="text-base text-slate-600 leading-relaxed">
            Suara Anda sangat berharga bagi kami. Laporkan keluhan Anda atau isi survey kepuasan pasien untuk membantu kami meningkatkan kualitas layanan medis dan fasilitas secara berkala.
        </p>
    </div>

    <!-- Cards Grid (Main Action Gateway) -->
    <div class="grid md:grid-cols-2 gap-8 max-w-4xl mx-auto">
        <!-- Complaint Card -->
        <div class="medical-card border-accent-warning p-8 flex flex-col justify-between space-y-6 bg-white">
            <div class="space-y-4">
                <div class="w-12 h-12 bg-amber-50 rounded-2xl flex items-center justify-center text-2xl shadow-sm border border-amber-100">
                    📩
                </div>
                <h2 class="text-2xl font-bold text-slate-800">Layanan Pengaduan Pasien</h2>
                <p class="text-slate-600 text-sm leading-relaxed">
                    Mengalami ketidaknyamanan terhadap pelayanan dokter, antrean obat, kebersihan toilet, atau fasilitas penunjang? Laporkan secara transparan. Anda akan mendapatkan tiket pengaduan untuk memantau status tindak lanjut.
                </p>
            </div>
            <a href="{{ route('pengaduan.create') }}" class="w-full inline-flex items-center justify-center px-5 h-11 bg-amber-500 hover:bg-amber-600 text-white font-semibold rounded-xl text-sm transition shadow-md shadow-amber-200">
                Kirim Pengaduan &rarr;
            </a>
        </div>

        <!-- Survey Card -->
        <div class="medical-card border-accent-blue p-8 flex flex-col justify-between space-y-6 bg-white">
            <div class="space-y-4">
                <div class="w-12 h-12 bg-sky-50 rounded-2xl flex items-center justify-center text-2xl shadow-sm border border-sky-100">
                    📋
                </div>
                <h2 class="text-2xl font-bold text-slate-800">Survey Kepuasan Pasien</h2>
                <p class="text-slate-600 text-sm leading-relaxed">
                    Bantu kami menilai kepuasan Anda secara berkala. Berikan rating bintang Anda untuk keramahan pendaftaran, kebersihan poli, kualitas pelayanan medis, dan kejelasan informasi dokter.
                </p>
            </div>
            <a href="{{ route('survey.fill') }}" class="w-full inline-flex items-center justify-center px-5 h-11 bg-sky-600 hover:bg-sky-700 text-white font-semibold rounded-xl text-sm transition shadow-md shadow-sky-200">
                Isi Survey Kepuasan &rarr;
            </a>
        </div>
    </div>

    <!-- Quick Stats Summary -->
    <div class="glass-panel rounded-3xl p-8 max-w-4xl mx-auto border border-sky-50 grid grid-cols-3 gap-4 text-center">
        <div>
            <span class="block text-3xl font-black text-sky-600">98%</span>
            <span class="text-xs font-semibold uppercase tracking-wider text-slate-500">Tanggapan Cepat</span>
        </div>
        <div class="border-x border-sky-100">
            <span class="block text-3xl font-black text-teal-600">4.5<span class="text-sm font-normal text-slate-400">/5</span></span>
            <span class="text-xs font-semibold uppercase tracking-wider text-slate-500">Indeks Kepuasan</span>
        </div>
        <div>
            <span class="block text-3xl font-black text-amber-500">24/7</span>
            <span class="text-xs font-semibold uppercase tracking-wider text-slate-500">Aduan Ditangani</span>
        </div>
    </div>

    <!-- Section: Klinik Poli / Services Overview -->
    <div class="max-w-4xl mx-auto space-y-8">
        <div class="text-center space-y-2">
            <h3 class="text-2xl font-bold text-slate-800">Poli & Unit Layanan Medis Kami</h3>
            <p class="text-sm text-slate-500">Kami melayani dengan sepenuh hati di berbagai unit spesialisasi klinik berikut:</p>
        </div>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <div class="p-6 bg-white rounded-2xl border border-slate-100 text-center space-y-2 shadow-sm">
                <span class="text-3xl block">🩺</span>
                <h4 class="font-bold text-slate-800 text-sm">Poli Umum</h4>
                <p class="text-[10px] text-slate-400">Pemeriksaan & konsultasi kesehatan primer umum.</p>
            </div>
            <div class="p-6 bg-white rounded-2xl border border-slate-100 text-center space-y-2 shadow-sm">
                <span class="text-3xl block">🦷</span>
                <h4 class="font-bold text-slate-800 text-sm">Poli Gigi</h4>
                <p class="text-[10px] text-slate-400">Perawatan gigi, scaling, penambalan, & cabut gigi.</p>
            </div>
            <div class="p-6 bg-white rounded-2xl border border-slate-100 text-center space-y-2 shadow-sm">
                <span class="text-3xl block">🍼</span>
                <h4 class="font-bold text-slate-800 text-sm">Poli KIA & KB</h4>
                <p class="text-[10px] text-slate-400">Kesehatan ibu dan anak, imunisasi, & program KB.</p>
            </div>
            <div class="p-6 bg-white rounded-2xl border border-slate-100 text-center space-y-2 shadow-sm">
                <span class="text-3xl block">💊</span>
                <h4 class="font-bold text-slate-800 text-sm">Apotek & Obat</h4>
                <p class="text-[10px] text-slate-400">Penyediaan dan penebusan resep obat klinik cepat.</p>
            </div>
        </div>
    </div>

    <!-- Section: Workflow / Alur Pengaduan -->
    <div class="max-w-4xl mx-auto space-y-8 bg-sky-50/50 border border-sky-100/50 rounded-3xl p-8">
        <div class="text-center space-y-2">
            <h3 class="text-2xl font-bold text-slate-800">Alur Penyampaian & Tindak Lanjut</h3>
            <p class="text-sm text-slate-500">Bagaimana laporan keluhan Anda ditanggapi secara terstruktur oleh tim manajemen kami?</p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 relative">
            <div class="space-y-2">
                <div class="w-8 h-8 rounded-full bg-sky-600 text-white font-bold flex items-center justify-center text-xs">1</div>
                <h4 class="font-bold text-slate-800 text-sm">Kirim Laporan</h4>
                <p class="text-xs text-slate-500">Pasien mengisi form keluhan secara mendetail sesuai fakta kejadian beserta kategori keluhan.</p>
            </div>
            <div class="space-y-2">
                <div class="w-8 h-8 rounded-full bg-sky-600 text-white font-bold flex items-center justify-center text-xs">2</div>
                <h4 class="font-bold text-slate-800 text-sm">Verifikasi & Proses</h4>
                <p class="text-xs text-slate-500">Staf admin memverifikasi keluhan, mengubah status menjadi 'Diproses', dan berkoordinasi dengan kepala poli terkait.</p>
            </div>
            <div class="space-y-2">
                <div class="w-8 h-8 rounded-full bg-sky-600 text-white font-bold flex items-center justify-center text-xs">3</div>
                <h4 class="font-bold text-slate-800 text-sm">Tindak Lanjut & Selesai</h4>
                <p class="text-xs text-slate-500">Tim kami mengambil aksi perbaikan di lapangan, membalas laporan via portal, lalu menutup aduan (Status Selesai).</p>
            </div>
        </div>
    </div>

    <!-- Section: F.A.Q & Jam Operasional -->
    <div class="grid md:grid-cols-2 gap-8 max-w-4xl mx-auto items-start">
        <!-- FAQ -->
        <div class="space-y-4">
            <h3 class="text-lg font-bold text-slate-800">Tanya Jawab Pasien (FAQ)</h3>
            
            <div class="space-y-3">
                <div class="p-4 bg-white rounded-xl border border-slate-100 space-y-1 shadow-sm">
                    <h4 class="font-bold text-slate-800 text-xs">Apakah data pengadu dirahasiakan?</h4>
                    <p class="text-[11px] text-slate-500 leading-relaxed">Ya, identitas pelapor hanya dapat diakses oleh administrator dan staf berwenang untuk keperluan klarifikasi keluhan saja.</p>
                </div>
                <div class="p-4 bg-white rounded-xl border border-slate-100 space-y-1 shadow-sm">
                    <h4 class="font-bold text-slate-800 text-xs">Berapa lama laporan ditanggapi?</h4>
                    <p class="text-[11px] text-slate-500 leading-relaxed">Keluhan kritis akan ditinjau langsung dalam 1x24 jam. Keluhan umum biasanya memerlukan waktu verifikasi lapangan 1-2 hari kerja.</p>
                </div>
                <div class="p-4 bg-white rounded-xl border border-slate-100 space-y-1 shadow-sm">
                    <h4 class="font-bold text-slate-800 text-xs">Bagaimana cara mengisi survey kepuasan?</h4>
                    <p class="text-[11px] text-slate-500 leading-relaxed">Anda cukup mengklik tombol 'Isi Survey Kepuasan', memilih skor rating bintang 1 hingga 5, dan menuliskan saran konstruktif tanpa perlu masuk akun.</p>
                </div>
            </div>
        </div>

        <!-- Operational Info -->
        <div class="bg-white rounded-2xl border border-slate-200 p-6 space-y-4 shadow-sm">
            <h3 class="text-lg font-bold text-slate-800">Informasi & Kontak Klinik</h3>
            
            <div class="space-y-3 text-xs text-slate-600">
                <div class="flex items-start space-x-2">
                    <span class="text-base">📍</span>
                    <div>
                        <span class="font-bold block text-slate-800">Alamat Klinik</span>
                        <span>Jl. Raya Kesehatan No. 45, Kebayoran, Jakarta Selatan</span>
                    </div>
                </div>
                <div class="flex items-start space-x-2">
                    <span class="text-base">📞</span>
                    <div>
                        <span class="font-bold block text-slate-800">Telepon & WhatsApp</span>
                        <span>(021) 7890-1234 / +62 812-3456-7890</span>
                    </div>
                </div>
                <div class="flex items-start space-x-2">
                    <span class="text-base">🕒</span>
                    <div>
                        <span class="font-bold block text-slate-800">Jam Operasional Layanan</span>
                        <span>Senin - Sabtu: 07:00 - 21:00 WIB<br>Minggu & Hari Libur: Tutup</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
