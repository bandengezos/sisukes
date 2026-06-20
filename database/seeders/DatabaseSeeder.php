<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\ComplaintCategory;
use App\Models\Complaint;
use App\Models\ComplaintResponse;
use App\Models\Survey;
use App\Models\SurveyQuestion;
use App\Models\SurveyResponse;
use App\Models\SurveyAnswer;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Users
        $admin = User::create([
            'name' => 'Admin SiSuKes',
            'email' => 'admin@sisukes.com',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
        ]);

        $petugas = User::create([
            'name' => 'Dr. Budi Santoso',
            'email' => 'petugas@sisukes.com',
            'password' => Hash::make('petugas123'),
            'role' => 'petugas',
        ]);

        // 2. Complaint Categories
        $categories = [
            ['name' => 'Pelayanan Medis', 'slug' => 'pelayanan-medis'],
            ['name' => 'Fasilitas & Sarana', 'slug' => 'fasilitas-sarana'],
            ['name' => 'Administrasi & Pembayaran', 'slug' => 'administrasi-pembayaran'],
            ['name' => 'Kebersihan & Kenyamanan', 'slug' => 'kebersihan-kenyamanan'],
            ['name' => 'Waktu Tunggu', 'slug' => 'waktu-tunggu'],
            ['name' => 'Lain-lain', 'slug' => 'lain-lain'],
        ];

        $categoryModels = [];
        foreach ($categories as $cat) {
            $categoryModels[] = ComplaintCategory::create($cat);
        }

        // 3. Demo Complaints & Responses
        $complaintsData = [
            [
                'complainant_name' => 'Hendra Wijaya',
                'complainant_phone' => '081234567890',
                'complainant_email' => 'hendra@gmail.com',
                'subject' => 'AC Ruang Tunggu Mati',
                'description' => 'AC di ruang tunggu utama mati sejak pagi hari, membuat ruangan sangat panas dan pasien tidak nyaman.',
                'priority' => 'medium',
                'status' => 'resolved',
                'category_index' => 1, // Fasilitas
                'created_days_ago' => 10,
                'responses' => [
                    [
                        'user_id' => $admin->id,
                        'text' => 'Terima kasih atas laporannya. Petugas teknisi kami sedang memeriksa AC tersebut.',
                        'days_ago' => 9,
                    ],
                    [
                        'user_id' => $petugas->id,
                        'text' => 'AC sudah diperbaiki dan saat ini sudah berfungsi kembali dengan normal. Terima kasih.',
                        'days_ago' => 8,
                    ]
                ]
            ],
            [
                'complainant_name' => 'Siti Aminah',
                'complainant_phone' => '082345678901',
                'complainant_email' => 'siti@yahoo.com',
                'subject' => 'Waktu Tunggu Dokter Terlalu Lama',
                'description' => 'Saya antre dari jam 09.00 pagi untuk periksa ke Poli Gigi, tapi baru dipanggil jam 11.30. Waktu tunggu harap diperbaiki.',
                'priority' => 'high',
                'status' => 'in_progress',
                'category_index' => 4, // Waktu Tunggu
                'created_days_ago' => 5,
                'responses' => [
                    [
                        'user_id' => $petugas->id,
                        'text' => 'Mohon maaf atas ketidaknyamanannya Ibu Siti. Pada hari tersebut terjadi lonjakan pasien darurat di poli gigi. Kami sedang mengevaluasi sistem penjadwalan dokter kami.',
                        'days_ago' => 4,
                    ]
                ]
            ],
            [
                'complainant_name' => 'Rian Apriadi',
                'complainant_phone' => '087890123456',
                'complainant_email' => 'rian@outlook.com',
                'subject' => 'Sikap Petugas Pendaftaran Kurang Ramah',
                'description' => 'Petugas pendaftaran di bagian depan melayani dengan wajah cemberut dan kurang menjelaskan alur pendaftaran BPJS.',
                'priority' => 'low',
                'status' => 'received',
                'category_index' => 2, // Administrasi
                'created_days_ago' => 2,
                'responses' => []
            ],
            [
                'complainant_name' => 'Dewi Lestari',
                'complainant_phone' => '08999888777',
                'complainant_email' => 'dewi.lestari@gmail.com',
                'subject' => 'Toilet Pasien Kotor dan Bau',
                'description' => 'Toilet di dekat apotek sangat kotor, tissue habis, dan tidak ada air mengalir di wastafel.',
                'priority' => 'critical',
                'status' => 'resolved',
                'category_index' => 3, // Kebersihan
                'created_days_ago' => 15,
                'responses' => [
                    [
                        'user_id' => $admin->id,
                        'text' => 'Baik, tim cleaning service langsung dikerahkan untuk membersihkan toilet dan mengisi ulang tissue.',
                        'days_ago' => 15,
                    ]
                ]
            ],
            [
                'complainant_name' => 'Ahmad Fikri',
                'complainant_phone' => '085678901234',
                'complainant_email' => 'ahmad.fikri@gmail.com',
                'subject' => 'Kesalahan Pemberian Label Obat',
                'description' => 'Nama di label obat saya tertukar dengan pasien lain yang namanya mirip. Untung saya baca dulu sebelum minum.',
                'priority' => 'critical',
                'status' => 'closed',
                'category_index' => 0, // Pelayanan Medis
                'created_days_ago' => 20,
                'responses' => [
                    [
                        'user_id' => $petugas->id,
                        'text' => 'Kami memohon maaf sebesar-besarnya atas kelalaian petugas apotek kami. Ini adalah masalah serius dan kami telah memberikan teguran keras serta pengetatan double-checking di bagian farmasi.',
                        'days_ago' => 19,
                    ],
                    [
                        'user_id' => $admin->id,
                        'text' => 'Laporan ditutup. Terima kasih atas koreksinya yang menyelamatkan keselamatan pasien.',
                        'days_ago' => 18,
                    ]
                ]
            ]
        ];

        foreach ($complaintsData as $data) {
            $complaint = Complaint::create([
                'category_id' => $categoryModels[$data['category_index']]->id,
                'complainant_name' => $data['complainant_name'],
                'complainant_phone' => $data['complainant_phone'],
                'complainant_email' => $data['complainant_email'],
                'subject' => $data['subject'],
                'description' => $data['description'],
                'priority' => $data['priority'],
                'status' => $data['status'],
                'created_at' => Carbon::now()->subDays($data['created_days_ago']),
            ]);

            foreach ($data['responses'] as $res) {
                ComplaintResponse::create([
                    'complaint_id' => $complaint->id,
                    'user_id' => $res['user_id'],
                    'response_text' => $res['text'],
                    'created_at' => Carbon::now()->subDays($res['days_ago']),
                ]);
            }
        }

        // 4. Surveys
        $survey = Survey::create([
            'title' => 'Survey Kepuasan Pasien Klinik SiSuKes',
            'description' => 'Survey berkala untuk mengukur tingkat kepuasan pelayanan medis, fasilitas, administrasi, dan kebersihan di Klinik SiSuKes.',
            'status' => 'active',
            'start_date' => Carbon::now()->subMonths(1),
            'end_date' => Carbon::now()->addMonths(2),
            'created_by' => $admin->id,
        ]);

        $q1 = SurveyQuestion::create([
            'survey_id' => $survey->id,
            'question_text' => 'Bagaimana penilaian Anda terhadap keramahan petugas pendaftaran?',
            'type' => 'rating',
            'options' => null,
            'sort_order' => 1,
        ]);

        $q2 = SurveyQuestion::create([
            'survey_id' => $survey->id,
            'question_text' => 'Bagaimana kualitas kebersihan ruang tunggu dan toilet?',
            'type' => 'rating',
            'options' => null,
            'sort_order' => 2,
        ]);

        $q3 = SurveyQuestion::create([
            'survey_id' => $survey->id,
            'question_text' => 'Bagaimana penjelasan dokter terkait penyakit dan obat yang Anda terima?',
            'type' => 'rating',
            'options' => null,
            'sort_order' => 3,
        ]);

        $q4 = SurveyQuestion::create([
            'survey_id' => $survey->id,
            'question_text' => 'Bagaimana waktu tunggu pelayanan resep obat di apotek?',
            'type' => 'multiple_choice',
            'options' => ['Sangat Cepat', 'Cukup Cepat', 'Lambat', 'Sangat Lambat'],
            'sort_order' => 4,
        ]);

        $q5 = SurveyQuestion::create([
            'survey_id' => $survey->id,
            'question_text' => 'Berikan saran atau masukan Anda untuk peningkatan layanan kami.',
            'type' => 'text',
            'options' => null,
            'sort_order' => 5,
        ]);

        // Generate survey responses
        $respondents = [
            ['name' => 'Bambang', 'q1' => '5', 'q2' => '4', 'q3' => '5', 'q4' => 'Sangat Cepat', 'q5' => 'Dokter sangat informatif, pelayanan pendaftaran juga cepat.'],
            ['name' => 'Yanti', 'q1' => '4', 'q2' => '3', 'q3' => '4', 'q4' => 'Cukup Cepat', 'q5' => 'Toilet tolong dibersihkan lebih sering.'],
            ['name' => 'Joko', 'q1' => '5', 'q2' => '5', 'q3' => '5', 'q4' => 'Sangat Cepat', 'q5' => 'Klinik terbaik, bersih dan ramah.'],
            ['name' => 'Megawati', 'q1' => '3', 'q2' => '2', 'q3' => '4', 'q4' => 'Lambat', 'q5' => 'Antrean apotek tolong ditambah petugasnya.'],
            ['name' => 'Susilo', 'q1' => '4', 'q2' => '4', 'q3' => '5', 'q4' => 'Cukup Cepat', 'q5' => 'Secara umum memuaskan.'],
            ['name' => 'Prabowo', 'q1' => '4', 'q2' => '3', 'q3' => '4', 'q4' => 'Cukup Cepat', 'q5' => 'Sudah bagus, tolong pertahankan.'],
            ['name' => 'Anies', 'q1' => '3', 'q2' => '3', 'q3' => '4', 'q4' => 'Sangat Lambat', 'q5' => 'Menunggu obat hampir 1 jam, kasihan pasien yang lemas.'],
            ['name' => 'Ganjar', 'q1' => '5', 'q2' => '4', 'q3' => '5', 'q4' => 'Cukup Cepat', 'q5' => 'Layanan cepat dan tanggap.'],
            ['name' => 'Gibran', 'q1' => '4', 'q2' => '5', 'q3' => '4', 'q4' => 'Sangat Cepat', 'q5' => 'Fasilitas sangat memadai.'],
            ['name' => 'Mahfud', 'q1' => '4', 'q2' => '4', 'q3' => '5', 'q4' => 'Cukup Cepat', 'q5' => 'Dokter menjelaskan dengan sangat detail dan mudah dimengerti.'],
        ];

        foreach ($respondents as $idx => $resp) {
            $response = SurveyResponse::create([
                'survey_id' => $survey->id,
                'respondent_name' => $resp['name'],
                'submitted_at' => Carbon::now()->subDays(10 - $idx),
            ]);

            SurveyAnswer::create([
                'response_id' => $response->id,
                'question_id' => $q1->id,
                'answer_value' => $resp['q1'],
            ]);

            SurveyAnswer::create([
                'response_id' => $response->id,
                'question_id' => $q2->id,
                'answer_value' => $resp['q2'],
            ]);

            SurveyAnswer::create([
                'response_id' => $response->id,
                'question_id' => $q3->id,
                'answer_value' => $resp['q3'],
            ]);

            SurveyAnswer::create([
                'response_id' => $response->id,
                'question_id' => $q4->id,
                'answer_value' => $resp['q4'],
            ]);

            SurveyAnswer::create([
                'response_id' => $response->id,
                'question_id' => $q5->id,
                'answer_value' => $resp['q5'],
            ]);
        }
    }
}
