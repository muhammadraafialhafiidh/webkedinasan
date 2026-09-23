<?php

namespace App\Http\Controllers\Cms;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\OrganizationMember;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class CmsOrganizationController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:150',
            'position' => 'required|string|max:200',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:10240',
        ]);

        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('organization', 'public');
        }

        $member = DB::transaction(function () use ($request, $photoPath) {
            // Geser seluruh pejabat lama ke bawah (+1)
            OrganizationMember::query()->increment('order');

            // Simpan pejabat baru di posisi paling atas (order = 0)
            return OrganizationMember::create([
                'name' => $request->name,
                'position' => $request->position,
                'photo' => $photoPath ?? 'assets/images/avatar-default.png',
                'order' => 0,
            ]);
        });

        ActivityLog::record('create', 'Struktur Organisasi', "Menambahkan anggota struktur organisasi: {$member->name} ({$member->position})");

        return back()->with('success', 'Anggota struktur organisasi berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $member = OrganizationMember::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:150',
            'position' => 'required|string|max:200',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:10240',
        ]);

        if ($request->hasFile('photo')) {
            if ($member->photo && $member->photo !== 'assets/images/avatar-default.png' && Storage::disk('public')->exists($member->photo)) {
                Storage::disk('public')->delete($member->photo);
            }
            $member->photo = $request->file('photo')->store('organization', 'public');
        }

        $member->name = $request->name;
        $member->position = $request->position;
        // Posisi order tetap dipertahankan tanpa perubahan saat edit
        $member->save();

        ActivityLog::record('update', 'Struktur Organisasi', "Mengubah anggota struktur ID #{$member->id}: {$member->name}");

        return back()->with('success', 'Anggota struktur organisasi berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $member = OrganizationMember::findOrFail($id);
        $name = $member->name;

        if ($member->photo && $member->photo !== 'assets/images/avatar-default.png' && Storage::disk('public')->exists($member->photo)) {
            Storage::disk('public')->delete($member->photo);
        }

        DB::transaction(function () use ($member) {
            $member->delete();

            // Rapikan urutan pejabat yang tersisa agar tidak ada gap (0, 1, 2, ...)
            $members = OrganizationMember::orderBy('order', 'asc')->orderBy('id', 'asc')->get();
            foreach ($members as $index => $m) {
                if ($m->order !== $index) {
                    $m->update(['order' => $index]);
                }
            }
        });

        ActivityLog::record('delete', 'Struktur Organisasi', "Menghapus anggota struktur: {$name}");

        return back()->with('success', 'Anggota struktur organisasi berhasil dihapus.');
    }

    public function reorder(Request $request)
    {
        $request->validate([
            'order' => 'required|array|min:1',
            'order.*' => 'required|integer|exists:organization_members,id',
        ], [
            'order.required' => 'Urutan data wajib dikirimkan.',
            'order.array' => 'Format urutan tidak valid.',
            'order.*.exists' => 'Data pejabat tidak ditemukan dalam database.',
        ]);

        $ids = $request->input('order');

        // Pastikan tidak ada duplikasi ID
        if (count($ids) !== count(array_unique($ids))) {
            return response()->json([
                'success' => false,
                'message' => 'Terdapat duplikasi data dalam urutan yang dikirimkan.',
            ], 422);
        }

        // Validasi bahwa seluruh record di database tercakup
        $totalMembers = OrganizationMember::count();
        if (count($ids) !== $totalMembers) {
            return response()->json([
                'success' => false,
                'message' => 'Jumlah data yang diurutkan tidak sesuai dengan total pejabat di database.',
            ], 422);
        }

        DB::transaction(function () use ($ids) {
            foreach ($ids as $index => $id) {
                OrganizationMember::where('id', $id)->update(['order' => $index]);
            }
        });

        ActivityLog::record('update', 'Struktur Organisasi', 'Memperbarui urutan struktur pejabat melalui drag and drop');

        return response()->json([
            'success' => true,
            'message' => 'Urutan struktur pejabat berhasil diperbarui.',
        ]);
    }
}
