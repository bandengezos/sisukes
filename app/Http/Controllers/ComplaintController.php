<?php

namespace App\Http\Controllers;

use App\Models\Complaint;
use App\Models\ComplaintCategory;
use App\Models\ComplaintResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class ComplaintController extends Controller
{
    /**
     * Build query with filters applied (shared logic).
     */
    private function buildFilteredQuery(Request $request)
    {
        $query = Complaint::with('category')->latest();

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('subject', 'like', "%{$search}%")
                  ->orWhere('complainant_name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->input('category_id'));
        }

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        if ($request->filled('priority')) {
            $query->where('priority', $request->input('priority'));
        }

        return $query;
    }

    /**
     * Display a listing of complaints in the dashboard.
     */
    public function index(Request $request)
    {
        $query = $this->buildFilteredQuery($request);
        $complaints = $query->paginate(10)->withQueryString();
        $categories = ComplaintCategory::all();

        return view('complaints.index', compact('complaints', 'categories'));
    }

    /**
     * Export complaints to PDF with filters.
     */
    public function exportPdf(Request $request)
    {
        $complaints = $this->buildFilteredQuery($request)->get();
        $categories = ComplaintCategory::all()->keyBy('id');
        $filters = [
            'search' => $request->input('search'),
            'category' => $request->filled('category_id') ? ($categories[$request->input('category_id')]->name ?? '-') : 'Semua',
            'status' => $request->input('status'),
            'priority' => $request->input('priority'),
        ];

        $statusLabels = [
            'received' => 'Diterima',
            'in_progress' => 'Diproses',
            'resolved' => 'Selesai',
            'closed' => 'Ditutup',
        ];

        $priorityLabels = [
            'low' => 'Rendah',
            'medium' => 'Sedang',
            'high' => 'Tinggi',
            'critical' => 'Critical',
        ];

        $stats = [
            'total' => $complaints->count(),
            'received' => $complaints->where('status', 'received')->count(),
            'in_progress' => $complaints->where('status', 'in_progress')->count(),
            'resolved' => $complaints->where('status', 'resolved')->count(),
            'closed' => $complaints->where('status', 'closed')->count(),
        ];

        $pdf = Pdf::loadView('complaints.export-pdf', compact(
            'complaints', 'categories', 'filters', 'statusLabels', 'priorityLabels', 'stats'
        ));
        $pdf->setPaper('A4', 'landscape');

        return $pdf->download('laporan-pengaduan-' . date('Y-m-d-His') . '.pdf');
    }

    /**
     * Export complaints to CSV with filters.
     */
    public function exportCsv(Request $request)
    {
        $complaints = $this->buildFilteredQuery($request)->get();
        $categories = ComplaintCategory::all()->keyBy('id');

        $statusLabels = [
            'received' => 'Diterima',
            'in_progress' => 'Diproses',
            'resolved' => 'Selesai',
            'closed' => 'Ditutup',
        ];

        $priorityLabels = [
            'low' => 'Rendah',
            'medium' => 'Sedang',
            'high' => 'Tinggi',
            'critical' => 'Critical',
        ];

        $filename = 'laporan-pengaduan-' . date('Y-m-d-His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function () use ($complaints, $categories, $statusLabels, $priorityLabels) {
            $handle = fopen('php://output', 'w');

            // BOM for Excel UTF-8 support
            fprintf($handle, chr(0xEF) . chr(0xBB) . chr(0xBF));

            // Header row
            fputcsv($handle, [
                'No Tiket',
                'Tanggal Masuk',
                'Nama Pengadu',
                'No. Telepon',
                'Email',
                'Kategori',
                'Subjek',
                'Deskripsi',
                'Prioritas',
                'Status',
                'Tanggal Update',
            ]);

            foreach ($complaints as $c) {
                fputcsv($handle, [
                    '#' . $c->id,
                    $c->created_at->format('d-m-Y H:i'),
                    $c->complainant_name,
                    $c->complainant_phone ?? '-',
                    $c->complainant_email ?? '-',
                    $categories[$c->category_id]->name ?? '-',
                    $c->subject,
                    strip_tags($c->description),
                    $priorityLabels[$c->priority] ?? $c->priority,
                    $statusLabels[$c->status] ?? $c->status,
                    $c->updated_at->format('d-m-Y H:i'),
                ]);
            }

            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Show detail of a complaint in the dashboard.
     */
    public function show($id)
    {
        $complaint = Complaint::with(['category', 'responses.user'])->findOrFail($id);
        return view('complaints.show', compact('complaint'));
    }

    /**
     * Update the status of a complaint.
     */
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:received,in_progress,resolved,closed'
        ]);

        $complaint = Complaint::findOrFail($id);
        $oldStatus = $complaint->status;
        $complaint->status = $request->input('status');
        $complaint->save();

        // Automatically log this change in timeline response
        $statusLabels = [
            'received' => 'Diterima',
            'in_progress' => 'Diproses',
            'resolved' => 'Selesai',
            'closed' => 'Ditutup'
        ];

        ComplaintResponse::create([
            'complaint_id' => $complaint->id,
            'user_id' => Auth::id(),
            'response_text' => "Mengubah status pengaduan dari [" . $statusLabels[$oldStatus] . "] menjadi [" . $statusLabels[$complaint->status] . "]."
        ]);

        return redirect()->route('complaints.show', $complaint->id)->with('success', 'Status pengaduan berhasil diperbarui.');
    }

    /**
     * Add response/timeline reply to a complaint.
     */
    public function respond(Request $request, $id)
    {
        $request->validate([
            'response_text' => 'required|string|min:5'
        ]);

        $complaint = Complaint::findOrFail($id);

        ComplaintResponse::create([
            'complaint_id' => $complaint->id,
            'user_id' => Auth::id(),
            'response_text' => $request->input('response_text')
        ]);

        // If status is received, automatically mark it as in_progress when responded
        if ($complaint->status === 'received') {
            $complaint->status = 'in_progress';
            $complaint->save();
        }

        return redirect()->route('complaints.show', $complaint->id)->with('success', 'Tanggapan berhasil dikirim.');
    }

    /**
     * Show the public complaint submission form.
     */
    public function publicCreate()
    {
        $categories = ComplaintCategory::all();
        return view('complaints.create', compact('categories'));
    }

    /**
     * Store a complaint from the public form.
     */
    public function publicStore(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:complaint_categories,id',
            'complainant_name' => 'required|string|max:100',
            'complainant_phone' => 'nullable|string|max:20',
            'complainant_email' => 'nullable|email|max:100',
            'subject' => 'required|string|max:150',
            'description' => 'required|string|min:10',
            'priority' => 'required|in:low,medium,high,critical'
        ], [
            'category_id.required' => 'Kategori aduan wajib dipilih.',
            'complainant_name.required' => 'Nama lengkap wajib diisi.',
            'subject.required' => 'Subjek aduan wajib diisi.',
            'description.required' => 'Deskripsi detail aduan wajib diisi.',
            'description.min' => 'Deskripsi minimal berisi 10 karakter.',
        ]);

        $complaint = Complaint::create($request->only([
            'category_id',
            'complainant_name',
            'complainant_phone',
            'complainant_email',
            'subject',
            'description',
            'priority'
        ]));

        return redirect()->route('pengaduan.create')->with('success', 'Pengaduan Anda berhasil dikirim dengan Kode Tiket: #' . $complaint->id . '. Kami akan segera menindaklanjuti.');
    }
}