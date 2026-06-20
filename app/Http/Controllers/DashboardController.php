<?php

namespace App\Http\Controllers;

use App\Models\Complaint;
use App\Models\Survey;
use App\Models\SurveyAnswer;
use App\Models\SurveyResponse;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Str;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. Stats
        $activeSurveysCount = Survey::where('status', 'active')->count();
        $totalComplaintsCount = Complaint::count();
        $resolvedComplaintsCount = Complaint::where('status', 'resolved')->count();
        
        $avgSatisfaction = SurveyAnswer::whereHas('question', function ($q) {
            $q->where('type', 'rating');
        })->avg('answer_value');
        $avgSatisfaction = $avgSatisfaction ? round($avgSatisfaction, 1) : 0.0;

        // 2. Complaint Trend (Last 6 Months)
        $trendLabels = [];
        $trendData = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $trendLabels[] = $date->translatedFormat('F Y');
            $trendData[] = Complaint::whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->count();
        }

        // 3. Complaint Status Distribution
        $statusCountsRaw = Complaint::selectRaw('status, count(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();
            
        $statusLabels = ['Diterima', 'Diproses', 'Selesai', 'Ditutup'];
        $statusData = [
            $statusCountsRaw['received'] ?? 0,
            $statusCountsRaw['in_progress'] ?? 0,
            $statusCountsRaw['resolved'] ?? 0,
            $statusCountsRaw['closed'] ?? 0,
        ];

        // 4. Survey Questions Average Rating (For Chart)
        $surveyRatingLabels = [];
        $surveyRatingData = [];
        $activeSurvey = Survey::where('status', 'active')->first();
        if ($activeSurvey) {
            $questions = $activeSurvey->questions()->where('type', 'rating')->get();
            foreach ($questions as $q) {
                $surveyRatingLabels[] = Str::limit($q->question_text, 25);
                $surveyRatingData[] = round($q->answers()->avg('answer_value') ?? 0, 1);
            }
        }

        // 5. Recent Complaints
        $recentComplaints = Complaint::with('category')->latest()->take(5)->get();

        // 6. Recent Responses
        $recentResponses = SurveyResponse::with('survey')->latest()->take(5)->get();

        return view('dashboard.index', compact(
            'activeSurveysCount',
            'totalComplaintsCount',
            'resolvedComplaintsCount',
            'avgSatisfaction',
            'trendLabels',
            'trendData',
            'statusLabels',
            'statusData',
            'surveyRatingLabels',
            'surveyRatingData',
            'recentComplaints',
            'recentResponses'
        ));
    }
}
