<?php

namespace App\Http\Controllers;

use App\Models\Survey;
use App\Models\SurveyQuestion;
use App\Models\SurveyResponse;
use App\Models\SurveyAnswer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SurveyController extends Controller
{
    /**
     * Display surveys list.
     */
    public function index()
    {
        $surveys = Survey::withCount('responses')->latest()->paginate(10);
        return view('surveys.index', compact('surveys'));
    }

    /**
     * Show survey builder form.
     */
    public function create()
    {
        return view('surveys.create');
    }

    /**
     * Store new survey with dynamic questions.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:200',
            'description' => 'nullable|string',
            'status' => 'required|in:draft,active,closed',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'questions' => 'required|array|min:1',
            'questions.*.question_text' => 'required|string',
            'questions.*.type' => 'required|in:rating,text,multiple_choice',
        ], [
            'title.required' => 'Judul survey wajib diisi.',
            'questions.required' => 'Survey wajib memiliki minimal 1 pertanyaan.',
            'questions.*.question_text.required' => 'Teks pertanyaan wajib diisi.',
        ]);

        // If status is active, deactivate other surveys (only one active survey at a time)
        if ($request->input('status') === 'active') {
            Survey::where('status', 'active')->update(['status' => 'closed']);
        }

        $survey = Survey::create([
            'title' => $request->input('title'),
            'description' => $request->input('description'),
            'status' => $request->input('status'),
            'start_date' => $request->input('start_date'),
            'end_date' => $request->input('end_date'),
            'created_by' => Auth::id()
        ]);

        foreach ($request->input('questions') as $index => $qData) {
            $options = null;
            if ($qData['type'] === 'multiple_choice' && !empty($qData['options'])) {
                $options = array_map('trim', explode(',', $qData['options']));
            }

            SurveyQuestion::create([
                'survey_id' => $survey->id,
                'question_text' => $qData['question_text'],
                'type' => $qData['type'],
                'options' => $options,
                'sort_order' => $index + 1
            ]);
        }

        return redirect()->route('surveys.index')->with('success', 'Survey baru berhasil dipublikasikan.');
    }

    /**
     * Show survey analytics.
     */
    public function show($id)
    {
        $survey = Survey::with(['questions.answers.response', 'responses'])->findOrFail($id);
        $totalResponses = $survey->responses->count();

        // Process analytical data for each question
        $questionsReport = [];
        foreach ($survey->questions as $q) {
            $report = [
                'question' => $q,
                'type' => $q->type,
                'total_answers' => $q->answers->count(),
            ];

            if ($q->type === 'rating') {
                $report['average'] = round($q->answers->avg('answer_value') ?? 0, 1);
                $distribution = [1 => 0, 2 => 0, 3 => 0, 4 => 0, 5 => 0];
                foreach ($q->answers as $ans) {
                    $val = (int)$ans->answer_value;
                    if (isset($distribution[$val])) {
                        $distribution[$val]++;
                    }
                }
                $report['distribution'] = $distribution;
            } elseif ($q->type === 'multiple_choice') {
                $distribution = [];
                // Initialise options
                if (is_array($q->options)) {
                    foreach ($q->options as $opt) {
                        $distribution[$opt] = 0;
                    }
                }
                foreach ($q->answers as $ans) {
                    $val = $ans->answer_value;
                    if (isset($distribution[$val])) {
                        $distribution[$val]++;
                    } else {
                        $distribution[$val] = 1;
                    }
                }
                $report['distribution'] = $distribution;
            } elseif ($q->type === 'text') {
                $report['recent_answers'] = $q->answers()->latest()->take(10)->pluck('answer_value')->toArray();
            }

            $questionsReport[] = $report;
        }

        return view('surveys.show', compact('survey', 'totalResponses', 'questionsReport'));
    }

    /**
     * Delete survey.
     */
    public function destroy($id)
    {
        $survey = Survey::findOrFail($id);
        $survey->delete();
        return redirect()->route('surveys.index')->with('success', 'Survey berhasil dihapus.');
    }

    /**
     * Public page to fill active survey.
     */
    public function publicFill($id = null)
    {
        if ($id) {
            $survey = Survey::with('questions')->findOrFail($id);
        } else {
            $survey = Survey::with('questions')->where('status', 'active')->first();
        }

        return view('surveys.fill', compact('survey'));
    }

    /**
     * Submit survey response from public page.
     */
    public function publicSubmit(Request $request, $id)
    {
        $survey = Survey::findOrFail($id);

        $rules = [
            'respondent_name' => 'nullable|string|max:100'
        ];
        $messages = [];

        foreach ($survey->questions as $q) {
            $rules['q_' . $q->id] = 'required';
            $messages['q_' . $q->id . '.required'] = 'Pertanyaan wajib dijawab.';
        }

        $request->validate($rules, $messages);

        $response = SurveyResponse::create([
            'survey_id' => $survey->id,
            'respondent_name' => $request->input('respondent_name') ?: 'Anonim'
        ]);

        foreach ($survey->questions as $q) {
            SurveyAnswer::create([
                'response_id' => $response->id,
                'question_id' => $q->id,
                'answer_value' => $request->input('q_' . $q->id)
            ]);
        }

        return redirect()->route('survey.fill', $survey->id)->with('success', 'Survey berhasil terkirim. Terima kasih atas masukan Anda!');
    }
}
