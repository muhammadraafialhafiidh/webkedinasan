<?php

namespace Database\Seeders;

use App\Models\OrganizationMember;
use Illuminate\Database\Seeder;

class OrganizationMemberSeeder extends Seeder
{
    public function run(): void
    {
        $members = [
            ['name' => 'Drs. Budi Santoso, M.Si', 'position' => 'Kepala Dinas', 'order' => 1],
            ['name' => 'Ir. Siti Rahayu', 'position' => 'Sekretaris Dinas', 'order' => 2],
            ['name' => 'Ahmad Fauzi, S.Pi', 'position' => 'Kabid Budidaya Perikanan', 'order' => 3],
            ['name' => 'Dewi Kusuma, S.St.Pi', 'position' => 'Kabid Penangkapan Ikan', 'order' => 4],
            ['name' => 'Eko Prasetyo, S.Pi', 'position' => 'Kabid Pengolahan & Pemasaran', 'order' => 5],
            ['name' => 'Rina Hartati, S.E', 'position' => 'Kasubbag Keuangan', 'order' => 6],
        ];

        foreach ($members as $member) {
            OrganizationMember::updateOrCreate(
                ['name' => $member['name']],
                [
                    'position' => $member['position'],
                    'photo' => 'assets/images/avatar-default.png',
                    'order' => $member['order'],
                ]
            );
        }
    }
}
