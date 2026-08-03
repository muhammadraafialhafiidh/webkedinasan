<?php

namespace Database\Seeders;

use App\Models\PenanggungJawab;
use App\Models\Service;
use Illuminate\Database\Seeder;

class PenanggungJawabSeeder extends Seeder
{
    public function run(): void
    {
        $officersData = [
            [
                'nama' => 'Hengky Prabowo, S.Pi',
                'nomor_hp' => '081234567890',
            ],
            [
                'nama' => 'Siti Rahmawati, S.St.Pi',
                'nomor_hp' => '085712345678',
            ],
            [
                'nama' => 'Drh. Ahmad Fauzi',
                'nomor_hp' => '081398765432',
            ],
            [
                'nama' => 'Dewi Anggraini, M.Si',
                'nomor_hp' => '082134567891',
            ],
        ];

        $officers = [];
        foreach ($officersData as $data) {
            $officers[] = PenanggungJawab::firstOrCreate(
                ['nama' => $data['nama']],
                $data
            );
        }

        $allOfficers = PenanggungJawab::all();
        $services = Service::all();

        foreach ($services as $index => $service) {
            // Check if this is the combined Uji PSAT-PDUK & PSAI service
            if (str_contains($service->title, 'PSAT-PDUK') && str_contains($service->title, 'PSAI')) {
                // Attach 2 officers with specific sub-keterangan!
                $officer1 = $allOfficers->where('nama', 'Hengky Prabowo, S.Pi')->first() ?? $allOfficers[0];
                $officer2 = $allOfficers->where('nama', 'Siti Rahmawati, S.St.Pi')->first() ?? $allOfficers[1];

                $service->penanggungJawabList()->sync([
                    $officer1->id => ['keterangan' => 'Penanggung Jawab PSAT-PDUK'],
                    $officer2->id => ['keterangan' => 'Penanggung Jawab PSAI'],
                ]);
            } else {
                // Regular single officer assignment
                $assignedOfficer = $allOfficers[$index % $allOfficers->count()];
                $service->penanggung_jawab_id = $assignedOfficer->id;
                $service->save();

                $service->penanggungJawabList()->sync([
                    $assignedOfficer->id => ['keterangan' => 'Penanggung Jawab Utama'],
                ]);
            }
        }
    }
}
