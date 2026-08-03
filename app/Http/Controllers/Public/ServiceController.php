<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Models\ServiceCategory;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function index(Request $request)
    {
        $categories = ServiceCategory::orderBy('order', 'asc')->get();
        $selectedCategorySlug = $request->query('kategori');

        $query = Service::with(['serviceCategory', 'penanggungJawabList'])->active();

        $breadcrumbs = [
            ['label' => 'Layanan', 'url' => $selectedCategorySlug ? route('service.index') : null],
        ];

        if ($selectedCategorySlug) {
            $query->whereHas('serviceCategory', function ($q) use ($selectedCategorySlug) {
                $q->where('slug', $selectedCategorySlug);
            });
        }

        $services = $query->orderBy('order', 'asc')->get();
        $selectedCategory = $selectedCategorySlug ? ServiceCategory::where('slug', $selectedCategorySlug)->first() : null;

        if ($selectedCategory) {
            $breadcrumbs[] = ['label' => $selectedCategory->name, 'url' => null];
        }

        return view('public.service.index', compact('services', 'categories', 'selectedCategory', 'selectedCategorySlug', 'breadcrumbs'));
    }

    public function byCategory(string $slug)
    {
        return redirect()->route('service.index', ['kategori' => $slug]);
    }

    public function show(string $slug)
    {
        $service = Service::with(['serviceCategory', 'penanggungJawabList'])->active()->where('slug', $slug)->firstOrFail();
        
        $otherServices = Service::with(['serviceCategory', 'penanggungJawabList'])
            ->active()
            ->where('service_category_id', $service->service_category_id)
            ->where('id', '!=', $service->id)
            ->orderBy('order', 'asc')
            ->get();

        $breadcrumbs = [
            ['label' => 'Layanan', 'url' => route('service.index')],
        ];

        if ($service->serviceCategory) {
            $breadcrumbs[] = [
                'label' => $service->serviceCategory->name,
                'url' => route('service.index', ['kategori' => $service->serviceCategory->slug])
            ];
        }

        $breadcrumbs[] = [
            'label' => $service->title,
            'url' => null
        ];

        return view('public.service.show', compact('service', 'otherServices', 'breadcrumbs'));
    }
}
