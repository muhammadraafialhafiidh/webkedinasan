<?php

namespace App\Http\Controllers\Cms;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\OrganizationMember;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CmsOrganizationController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:150',
            'position' => 'required|string|max:200',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:10240',
            'order' => 'nullable|integer',
        ]);

        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('organization', 'public');
        }

        $member = OrganizationMember::create([
            'name' => $request->name,
            'position' => $request->position,
            'photo' => $photoPath ?? 'assets/images/avatar-default.png',
            'order' => $request->order ?? 0,
        ]);

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
            'order' => 'nullable|integer',
        ]);

        if ($request->hasFile('photo')) {
            if ($member->photo && $member->photo !== 'assets/images/avatar-default.png' && Storage::disk('public')->exists($member->photo)) {
                Storage::disk('public')->delete($member->photo);
            }
            $member->photo = $request->file('photo')->store('organization', 'public');
        }

        $member->name = $request->name;
        $member->position = $request->position;
        $member->order = $request->order ?? 0;
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

        $member->delete();

        ActivityLog::record('delete', 'Struktur Organisasi', "Menghapus anggota struktur: {$name}");

        return back()->with('success', 'Anggota struktur organisasi berhasil dihapus.');
    }
}
