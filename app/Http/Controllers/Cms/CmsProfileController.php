<?php

namespace App\Http\Controllers\Cms;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\OrganizationMember;
use App\Models\ProfileContent;
use Illuminate\Http\Request;

class CmsProfileController extends Controller
{
    public function index()
    {
        $contents = [
            'sejarah' => ProfileContent::getContent('sejarah'),
            'visi' => ProfileContent::getContent('visi'),
            'misi' => ProfileContent::getContent('misi'),
            'tupoksi' => ProfileContent::getContent('tupoksi'),
        ];

        $organizationMembers = OrganizationMember::orderBy('order', 'asc')->get();

        return view('cms.profile.index', compact('contents', 'organizationMembers'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'sejarah' => 'required|string',
            'visi' => 'required|string',
            'misi' => 'required|string',
            'tupoksi' => 'required|string',
        ]);

        foreach (['sejarah', 'visi', 'misi', 'tupoksi'] as $key) {
            ProfileContent::updateOrCreate(
                ['key' => $key],
                ['value' => $request->$key]
            );
        }

        ActivityLog::record('update', 'Profil Dinas', "Perbaruan data Sejarah, Visi, Misi, dan Tupoksi Dinas");

        return back()->with('success', 'Profil dinas berhasil diperbarui.');
    }
}
