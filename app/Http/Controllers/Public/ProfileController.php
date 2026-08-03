<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\OrganizationMember;
use App\Models\ProfileContent;

class ProfileController extends Controller
{
    public function index()
    {
        $sejarah = ProfileContent::getContent('sejarah');
        $visi = ProfileContent::getContent('visi');
        $misi = ProfileContent::getContent('misi');
        $tupoksi = ProfileContent::getContent('tupoksi');
        $organizationMembers = OrganizationMember::orderBy('order', 'asc')->get();

        $breadcrumbs = [
            ['label' => 'Profil Dinas', 'url' => null],
        ];

        return view('public.profile.index', compact('sejarah', 'visi', 'misi', 'tupoksi', 'organizationMembers', 'breadcrumbs'));
    }
}
