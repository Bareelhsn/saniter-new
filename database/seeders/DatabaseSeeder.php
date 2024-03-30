<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\KategoriMenu;
use App\Models\Lokasi;
use App\Models\Menu;
use App\Models\MenuKategori;
use App\Models\Regional;
use App\Models\Shift;
use App\Models\User;
use App\Models\UserRole;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $regional = ['Barat','Tengah', 'Pusat'];
        $lokasiRegional = [
            ['-6.2279776','106.912502'],
            [-6.175357, 106.827192],
            [-8.661063, 115.214712],
        ];
        $kategori = ['Rekapan', 'Proyek', 'Administrasi', 'Pengaturan'];
        for ($i=0; $i < count($regional); $i++) {
            Regional::create([
                'nama' => $regional[$i],
                'latitude' => $lokasiRegional[$i][0],
                'longitude' => $lokasiRegional[$i][1]
            ]);

            KategoriMenu::create([
                'nama_kategori' => $kategori[$i],
                'order' => $i,
                'show' => 1
            ]);
        }

        KategoriMenu::create([
            'nama_kategori' => 'Pengaturan',
            'order' => 3,
            'show' => 1
        ]);

        Menu::create([
            'id_kategori' => 3,
            'judul' => 'Absen',
            'order' => 1,
            'url' => 'administrasi/absen',
            'icon' => 'calendar-check',
            'show' => 1,
        ]);

        Menu::create([
            'id_kategori' => 2,
            'judul' => 'Regional',
            'order' => 1,
            'url' => 'pengaturan/regional',
            'icon' => 'map',
            'show' => 1,
        ]);

        Menu::create([
            'id_kategori' => 2,
            'judul' => 'Lokasi',
            'order' => 1,
            'url' => 'lokasi',
            'icon' => 'map-pin',
            'show' => 1,
        ]);

        User::create([
            'regional_id' => 1,
            'role_id' => 1,
            'lokasi_id' => 1,
            'name' => 'Admin Saniter',
            'email' => 'admin@gmail.com',
            'nik' => '5171012103010002',
            'telp' => '0819034078903',
            'password' => Hash::make('admin'),
            'is_active' => 1
        ]);

        User::create([
            'regional_id' => 2,
            'role_id' => 2,
            'lokasi_id' => 1,
            'name' => 'Staff Saniter',
            'email' => 'staff@gmail.com',
            'nik' => '5171012103010002',
            'telp' => '0819034078902',
            'password' => Hash::make('staff'),
            'is_active' => 1
        ]);

        User::create([
            'regional_id' => 3,
            'role_id' => 3,
            'lokasi_id' => 1,
            'name' => 'Teknisi Saniter',
            'email' => 'teknisi@gmail.com',
            'nik' => '5171012103010002',
            'telp' => '0819034078901',
            'password' => Hash::make('teknisi'),
            'is_active' => 1
        ]);

        Lokasi::create([
            'regional_id' => 1,
            'nama_bandara' => fake()->country(),
            'lokasi_proyek' => fake()->address(),
            'latitude' => -8.6608598,
            'longitude' => 115.2149947,
            'radius' => rand(10,200)
        ]);

        for ($i=2; $i <= 5; $i++) {
            Lokasi::create([
                'regional_id' => $i,
                'nama_bandara' => fake()->country(),
                'lokasi_proyek' => fake()->address(),
                'latitude' => rand(100000,500000),
                'longitude' => rand(100000,500000),
                'radius' => rand(10,200)
            ]);
        }

        Shift::create([
            'nama' => 'Pagi',
            'server_time' => 1,
            'jam_masuk' => "09:00:00",
            'jam_pulang' => "17:00:00"
        ]);

        Shift::create([
            'nama' => 'Sore',
            'server_time' => 1,
            'jam_masuk' => "15:00:00",
            'jam_pulang' => "23:00:00"
        ]);

        Shift::create([
            'nama' => 'Malam',
            'server_time' => 1,
            'jam_masuk' => "23:00:00",
            'jam_pulang' => "07:00:00"
        ]);

        $this->call(PermissionSeeder::class);

        $user = User::find(1);
        $user->assignRole(['Admin']);

        $user = User::find(2);
        $user->assignRole(['Staff']);

        $user = User::find(3);
        $user->assignRole(['Teknisi']);
    }
}
