<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Document;
use Illuminate\Http\Request;

class DocumentController extends Controller
{
    public function index(Request $request)
    {
        $categories = Document::select('category')->distinct()->pluck('category');
        $selectedCategory = $request->query('kategori');

        $query = Document::query();

        $breadcrumbs = [
            ['label' => 'Dokumen', 'url' => $selectedCategory ? route('document.index') : null],
        ];

        if ($selectedCategory) {
            $query->where('category', $selectedCategory);
            $breadcrumbs[] = ['label' => $selectedCategory, 'url' => null];
        }

        if ($request->filled('q')) {
            $search = $request->q;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $documents = $query->orderBy('created_at', 'desc')->paginate(10)->withQueryString();

        return view('public.document.index', compact('documents', 'categories', 'selectedCategory', 'breadcrumbs'));
    }
}
