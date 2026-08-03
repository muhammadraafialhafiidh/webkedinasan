<?php

namespace App\Http\Controllers\Cms;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Document;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CmsDocumentController extends Controller
{
    public function index(Request $request)
    {
        $query = Document::query();

        if ($request->filled('q')) {
            $search = $request->q;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('category', 'like', "%{$search}%");
            });
        }

        $documents = $query->orderBy('created_at', 'desc')->paginate(10)->withQueryString();

        return view('cms.document.index', compact('documents'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|string|max:100',
            'description' => 'nullable|string',
            'file' => 'required|file|mimes:pdf,docx,xlsx,doc,xls|max:10240',
        ], [
            'title.required' => 'Judul dokumen wajib diisi.',
            'category.required' => 'Kategori dokumen wajib diisi.',
            'file.required' => 'File dokumen wajib diunggah.',
            'file.mimes' => 'Format file harus berupa PDF, Word (docx), atau Excel (xlsx).',
            'file.max' => 'Ukuran file maksimal 10MB.',
        ]);

        $filePath = $request->file('file')->store('documents', 'public');

        $doc = Document::create([
            'title' => $request->title,
            'category' => $request->category,
            'description' => $request->description,
            'file' => $filePath,
        ]);

        ActivityLog::record('create', 'Dokumen', "Mengunggah dokumen baru: {$doc->title}");

        return back()->with('success', 'Dokumen berhasil diunggah.');
    }

    public function update(Request $request, $id)
    {
        $doc = Document::findOrFail($id);

        $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|string|max:100',
            'description' => 'nullable|string',
            'file' => 'nullable|file|mimes:pdf,docx,xlsx,doc,xls|max:10240',
        ]);

        if ($request->hasFile('file')) {
            if ($doc->file && Storage::disk('public')->exists($doc->file)) {
                Storage::disk('public')->delete($doc->file);
            }
            $doc->file = $request->file('file')->store('documents', 'public');
        }

        $doc->title = $request->title;
        $doc->category = $request->category;
        $doc->description = $request->description;
        $doc->save();

        ActivityLog::record('update', 'Dokumen', "Mengubah dokumen ID #{$doc->id}: {$doc->title}");

        return back()->with('success', 'Dokumen berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $doc = Document::findOrFail($id);
        $title = $doc->title;

        if ($doc->file && Storage::disk('public')->exists($doc->file)) {
            Storage::disk('public')->delete($doc->file);
        }

        $doc->delete();

        ActivityLog::record('delete', 'Dokumen', "Menghapus dokumen: {$title}");

        return back()->with('success', 'Dokumen berhasil dihapus.');
    }
}
