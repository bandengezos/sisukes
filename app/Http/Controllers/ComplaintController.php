<?php

namespace App\Http\Controllers;

use App\Models\Complaint;
use App\Models\ComplaintCategory;
use App\Models\ComplaintResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ComplaintController extends Controller
{
    /**
     * Display a listing of complaints in the dashboard.
     */
    public function index(Request $request)
    {
        $query = Complaint::with('category')->latest();

        // Search
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('subject', 'like', "%{$search}%")
                  ->orWhere('complainant_name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Filter Category
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->input('category_id'));
        }

        // Filter Status
        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        // Filter Priority
        if ($request->filled('priority')) {
            $query->where('priority', $request->input('priority'));
        }

        $complaints = $query->paginate(10)->withQueryString();
        $categories = ComplaintCategory::all();

        return view('complaints.index', compact('complaints', 'categories'));
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
