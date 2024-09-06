<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Divisi;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        $roleDirektur = Role::create(['nama_role' => 'Direktur']);
        $roleManager = Role::create(['nama_role' => 'Manager']);
        $roleStaff = Role::create(['nama_role' => 'Staff']);

        $divisiKeuangan = Divisi::create(['nama_divisi' => 'Divisi Keuangan']);
        $divisiOperasional = Divisi::create(['nama_divisi' => 'Divisi Operasional']);

        User::create([
            'username' => 'direktur_wps',
            'nama' => 'Direktur WPS',
            'email' => 'direktur@wps.com',
            'password' => Hash::make('Wps12345'),
            'role_id' => $roleDirektur->id,
        ]);

        User::create([
            'username' => 'managerKeuangan_wps',
            'nama' => 'Manager Keuangan WPS',
            'email' => 'managerKeuangan@wps.com',
            'password' => Hash::make('Wps12345'),
            'role_id' => $roleManager->id,
            'divisi_id' => $divisiKeuangan->id,
        ]);

        User::create([
            'username' => 'managerOperasional_wps',
            'nama' => 'Manager Operasional WPS',
            'email' => 'managerOperasional@wps.com',
            'password' => Hash::make('Wps12345'),
            'role_id' => $roleManager->id,
            'divisi_id' => $divisiOperasional->id,
        ]);

        User::create([
            'username' => 'staffKeuangan_wps',
            'nama' => 'Staff Keuangan WPS',
            'email' => 'staffKeuangan@wps.com',
            'password' => Hash::make('Wps12345'),
            'role_id' => $roleStaff->id,
            'divisi_id' => $divisiKeuangan->id,
        ]);

        User::create([
            'username' => 'staffOperasional_wps',
            'nama' => 'Staff Operasional WPS',
            'email' => 'staffOperasional@wps.com',
            'password' => Hash::make('Wps12345'),
            'role_id' => $roleStaff->id,
            'divisi_id' => $divisiOperasional->id,
        ]);

    }
}
