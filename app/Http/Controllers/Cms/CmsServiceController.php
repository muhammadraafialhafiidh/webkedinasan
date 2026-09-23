<?php

namespace App\Http\Controllers\Cms;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\PenanggungJawab;
use App\Models\Service;
use App\Models\ServiceCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CmsServiceController extends Controller
{
    public function index(Request $request)
    {
        $categories = ServiceCategory::orderBy('order', 'asc')->get();
        $query = Service::with(['serviceCategory', 'penanggungJawabList']);

        if ($request->filled('kategori')) {
            $query->where('service_category_id', $request->kategori);
        }

        if ($request->filled('q')) {
            $search = $request->q;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $services = $query->orderBy('order', 'asc')->paginate(10)->withQueryString();

        return view('cms.service.index', compact('services', 'categories'));
    }

    public function create()
    {
        $categories = ServiceCategory::orderBy('order', 'asc')->get();
        $penanggungJawabList = PenanggungJawab::orderBy('nama', 'asc')->get();
        return view('cms.service.create', compact('categories', 'penanggungJawabList'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'service_category_id' => 'required|exists:service_categories,id',
            'title' => 'required|string|max:200',
            'description' => 'required|string',
            'requirements' => 'nullable|string',
            'procedure' => 'nullable|string',
            'duration' => 'nullable|string|max:150',
            'cost' => 'nullable|string|max:150',
            'product' => 'nullable|string|max:255',
            'icon' => 'nullable|image|mimes:jpeg,png,jpg,svg|max:10240',
            'is_active' => 'required|boolean',
            'penanggung_jawab_ids' => 'nullable|array',
            'penanggung_jawab_ids.*' => 'nullable|exists:penanggung_jawab,id',
            'penanggung_jawab_keterangans' => 'nullable|array',
            'penanggung_jawab_keterangans.*' => 'nullable|string|max:255',
        ], [
            'service_category_id.required' => 'Kategori/bidang layanan wajib dipilih.',
            'title.required' => 'Nama layanan wajib diisi.',
            'description.required' => 'Deskripsi singkat layanan wajib diisi.',
        ]);

        $iconPath = null;
        if ($request->hasFile('icon')) {
            $iconPath = $request->file('icon')->store('services', 'public');
        }

        $slug = Str::slug($request->title);
        $originalSlug = $slug;
        $count = 1;
        while (Service::where('slug', $slug)->exists()) {
            $slug = "{$originalSlug}-{$count}";
            $count++;
        }

        $service = DB::transaction(function () use ($request, $slug, $iconPath) {
            // Geser seluruh urutan layanan yang ada ke bawah (+1)
            Service::query()->increment('order');

            // Simpan layanan baru di posisi paling atas (order = 0)
            $service = Service::create([
                'service_category_id' => $request->service_category_id,
                'title' => $request->title,
                'slug' => $slug,
                'description' => $request->description,
                'requirements' => $request->requirements,
                'procedure' => $request->procedure,
                'duration' => $request->duration,
                'cost' => $request->cost,
                'product' => $request->product,
                'icon' => $iconPath ?? 'assets/images/service-icon-default.png',
                'order' => 0,
                'is_active' => $request->is_active,
            ]);

            // Sync Multi Officers with Keterangan
            $syncData = [];
            if ($request->has('penanggung_jawab_ids') && is_array($request->penanggung_jawab_ids)) {
                foreach ($request->penanggung_jawab_ids as $idx => $pjId) {
                    if (!empty($pjId)) {
                        $ket = $request->penanggung_jawab_keterangans[$idx] ?? null;
                        $syncData[$pjId] = ['keterangan' => $ket];
                    }
                }
            }
            $service->penanggungJawabList()->sync($syncData);

            return $service;
        });

        ActivityLog::record('create', 'Layanan', "Menambahkan layanan baru: {$service->title}");

        return redirect()->route('cms.layanan.index')->with('success', 'Layanan berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $service = Service::with('penanggungJawabList')->findOrFail($id);
        $categories = ServiceCategory::orderBy('order', 'asc')->get();
        $penanggungJawabList = PenanggungJawab::orderBy('nama', 'asc')->get();
        return view('cms.service.edit', compact('service', 'categories', 'penanggungJawabList'));
    }

    public function update(Request $request, $id)
    {
        $service = Service::findOrFail($id);

        $request->validate([
            'service_category_id' => 'required|exists:service_categories,id',
            'title' => 'required|string|max:200',
            'description' => 'required|string',
            'requirements' => 'nullable|string',
            'procedure' => 'nullable|string',
            'duration' => 'nullable|string|max:150',
            'cost' => 'nullable|string|max:150',
            'product' => 'nullable|string|max:255',
            'icon' => 'nullable|image|mimes:jpeg,png,jpg,svg|max:10240',
            'is_active' => 'required|boolean',
            'penanggung_jawab_ids' => 'nullable|array',
            'penanggung_jawab_ids.*' => 'nullable|exists:penanggung_jawab,id',
            'penanggung_jawab_keterangans' => 'nullable|array',
            'penanggung_jawab_keterangans.*' => 'nullable|string|max:255',
        ]);

        if ($request->hasFile('icon')) {
            if ($service->icon && $service->icon !== 'assets/images/service-icon-default.png' && Storage::disk('public')->exists($service->icon)) {
                Storage::disk('public')->delete($service->icon);
            }
            $service->icon = $request->file('icon')->store('services', 'public');
        }

        if ($service->title !== $request->title) {
            $slug = Str::slug($request->title);
            $originalSlug = $slug;
            $count = 1;
            while (Service::where('slug', $slug)->where('id', '!=', $id)->exists()) {
                $slug = "{$originalSlug}-{$count}";
                $count++;
            }
            $service->slug = $slug;
        }

        $service->service_category_id = $request->service_category_id;
        $service->title = $request->title;
        $service->description = $request->description;
        $service->requirements = $request->requirements;
        $service->procedure = $request->procedure;
        $service->duration = $request->duration;
        $service->cost = $request->cost;
        $service->product = $request->product;
        // Posisi order tetap dipertahankan tanpa perubahan saat edit
        $service->is_active = $request->is_active;
        $service->save();

        // Sync Multi Officers with Keterangan
        $syncData = [];
        if ($request->has('penanggung_jawab_ids') && is_array($request->penanggung_jawab_ids)) {
            foreach ($request->penanggung_jawab_ids as $idx => $pjId) {
                if (!empty($pjId)) {
                    $ket = $request->penanggung_jawab_keterangans[$idx] ?? null;
                    $syncData[$pjId] = ['keterangan' => $ket];
                }
            }
        }
        $service->penanggungJawabList()->sync($syncData);

        ActivityLog::record('update', 'Layanan', "Mengubah layanan ID #{$service->id}: {$service->title}");

        return redirect()->route('cms.layanan.index')->with('success', 'Layanan berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $service = Service::findOrFail($id);
        $title = $service->title;
        $iconPath = $service->icon;

        DB::transaction(function () use ($service) {
            $service->delete();

            // Rapikan urutan layanan yang tersisa agar tidak ada gap (0, 1, 2, ...)
            $services = Service::orderBy('order', 'asc')->get();
            foreach ($services as $index => $srv) {
                if ($srv->order !== $index) {
                    $srv->update(['order' => $index]);
                }
            }
        });

        if ($iconPath && $iconPath !== 'assets/images/service-icon-default.png' && Storage::disk('public')->exists($iconPath)) {
            Storage::disk('public')->delete($iconPath);
        }

        ActivityLog::record('delete', 'Layanan', "Menghapus layanan: {$title}");

        return redirect()->route('cms.layanan.index')->with('success', 'Layanan berhasil dihapus.');
    }

    public function toggleActive($id)
    {
        $service = Service::findOrFail($id);
        $service->is_active = !$service->is_active;
        $service->save();

        ActivityLog::record('toggle_active', 'Layanan', "Mengubah status aktif layanan '{$service->title}' menjadi " . ($service->is_active ? 'Aktif' : 'Nonaktif'));

        return back()->with('success', "Status layanan '{$service->title}' berhasil diubah.");
    }
}
