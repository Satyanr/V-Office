<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KaryawanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('karyawan_tbls')->truncate();

        $data = [
            ['name' => 'Maulana Ibrahim', 'cuti' => 'Ya'],
            ['name' => 'Muhammad Farras Adinul Islami', 'cuti' => 'Belum Dapat Cuti'],
            ['name' => 'Affifah Zahra Al-Ghifari', 'cuti' => 'Belum Dapat Cuti'],
            ['name' => 'Ainul Vicky Zulkarnain', 'cuti' => 'Ya'],
            ['name' => 'Idwan Zohar', 'cuti' => 'Ya'],
            ['name' => 'Tsabit Aqdamish Shabir', 'cuti' => 'Belum Dapat Cuti'],
            ['name' => 'Fadhlul Wafi', 'cuti' => 'Ya'],
            ['name' => 'Ginggi Nugrah Ramadhan', 'cuti' => 'Belum Dapat Cuti'],
            ['name' => 'Andika A. Juniansyah', 'cuti' => 'Belum Dapat Cuti'],
            ['name' => 'Rendy D. Saputra', 'cuti' => 'Ya'],
            ['name' => 'Katoning Pamungkas', 'cuti' => 'Belum Dapat Cuti'],
            ['name' => 'Rizqi Rahmatullah', 'cuti' => 'Ya'],
            ['name' => 'Diaz Fadila', 'cuti' => 'Ya'],
            ['name' => 'Reynadi', 'cuti' => 'Belum Dapat Cuti'],
            ['name' => 'Syariifah Hisaanah', 'cuti' => 'Belum Dapat Cuti'],
            ['name' => 'Satya Nurfadillah Rabbani', 'cuti' => 'Ya']
        ];

        foreach ($data as $item) {
            DB::table('karyawan_tbls')->insert([
                'name' => $item['name'],
                'cuti' => $item['cuti'],
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }

    }
}
