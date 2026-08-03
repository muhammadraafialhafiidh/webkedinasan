<?php

namespace App\Http\Controllers\Cms;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\PenanggungJawab;
use Illuminate\Http\Request;

class CmsPenanggungJawabController extends Controller
{
    public function index(Request $request)
    {
        $query = PenanggungJawab::withCount('services');

        if ($request->filled('q')) {
            $search = $request->q;
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('nomor_hp', 'like', "%{$search}%");
            });
        }

        $officers = $query->orderBy('nama', 'asc')->paginate(10)->withQueryString();

        return view('cms.penanggung-jawab.index', compact('officers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:150',
            'nomor_hp' => 'required|string|max:20|regex:/^[0-9\+\-\s]+$/',
        ], [
            'nama.required' => 'Nama penanggung jawab wajib diisi.',
            'nama.max' => 'Nama penanggung jawab maksimal 150 karakter.',
            'nomor_hp.required' => 'Nomor HP wajib diisi.',
            'nomor_hp.regex' => 'Nomor HP hanya boleh berisi angka, tanda +, - dan spasi.',
            'nomor_hp.max' => 'Nomor HP maksimal 20 karakter.',
        ]);

        $officer = PenanggungJawab::create([
            'nama' => $request->nama,
            'nomor_hp' => $request->nomor_hp,
        ]);

        ActivityLog::record('create', 'Penanggung Jawab', "Menambahkan penanggung jawab: {$officer->nama}");

        return back()->with('success', 'Data Penanggung Jawab berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $officer = PenanggungJawab::findOrFail($id);

        $request->validate([
            'nama' => 'required|string|max:150',
            'nomor_hp' => 'required|string|max:20|regex:/^[0-9\+\-\s]+$/',
        ], [
            'nama.required' => 'Nama penanggung jawab wajib diisi.',
            'nomor_hp.required' => 'Nomor HP wajib diisi.',
            'nomor_hp.regex' => 'Nomor HP hanya boleh berisi angka, tanda +, - dan spasi.',
        ]);

        $officer->nama = $request->nama;
        $officer->nomor_hp = $request->nomor_hp;
        $officer->save();

        ActivityLog::record('update', 'Penanggung Jawab', "Mengubah data penanggung jawab ID #{$officer->id}: {$officer->nama}");

        return back()->with('success', 'Data Penanggung Jawab berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $officer = PenanggungJawab::findOrFail($id);
        $nama = $officer->nama;
        $officer->delete();

        ActivityLog::record('delete', 'Penanggung Jawab', "Menghapus penanggung jawab: {$nama}");

        return back()->with('success', 'Data Penanggung Jawab berhasil dihapus.');
    }
}
