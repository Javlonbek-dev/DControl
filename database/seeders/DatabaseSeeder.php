<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();
//
        User::factory()->create([
            'name' => "A.Mo'sajonov",
            'email' => 'a.mosajonov@example.com',
        ]);

        User::factory()->create([
            'name' => 'A.Toshqulov',
            'email' => 'a.toshqulov@example.com',
        ]);

        User::factory()->create([
            'name' => 'B.Ibodullayev',
            'email' => 'b.ibodullayev@example.com',
        ]);

        User::factory()->create([
            'name' => 'F.Latipov',
            'email' => 'f.latipov@example.com',
        ]);

        User::factory()->create([
            'name' => 'F.Nametjanov',
            'email' => 'f.nametjanov@example.com',
        ]);

        User::factory()->create([
            'name' => 'F.Xakimova',
            'email' => 'f.xakimova@example.com',
        ]);

        User::factory()->create([
            'name' => "M.Bo'riyev",
            'email' => 'm.boriyev@example.com',
        ]);

        User::factory()->create([
            'name' => 'Sh.Mirshohidov',
            'email' => 'sh.mirshohidov@example.com',
        ]);

        User::factory()->create([
            'name' => 'A.Tadjibayev',
            'email' => 'a.tadjibayev@example.com',
        ]);

        User::factory()->create([
            'name' => 'A.Buronov',
            'email' => 'a.buronov@example.com',
        ]);

        User::factory()->create([
            'name' => 'A.Marasulov',
            'email' => 'a.marasulov@example.com',
        ]);

        User::factory()->create([
            'name' => 'A.Ostonayev',
            'email' => 'a.ostonayev@example.com',
        ]);

        User::factory()->create([
            'name' => 'A.Perdebayev',
            'email' => 'a.perdebayev@example.com',
        ]);

        User::factory()->create([
            'name' => 'A.Rahimov',
            'email' => 'a.rahimov@example.com',
        ]);

        User::factory()->create([
            'name' => 'A.Tojibayev',
            'email' => 'a.tojibayev@example.com',
        ]);

        User::factory()->create([
            'name' => 'A.Khasanov',
            'email' => 'a.khasanov@example.com',
        ]);

        User::factory()->create([
            'name' => 'A.Khudayberdiyev',
            'email' => 'a.khudayberdiyev@example.com',
        ]);

        User::factory()->create([
            'name' => 'B.Khudaybergenov',
            'email' => 'b.khudaybergenov@example.com',
        ]);

        User::factory()->create([
            'name' => 'G.Musaeva',
            'email' => 'g.musaeva@example.com',
        ]);

        User::factory()->create([
            'name' => 'D.Alimjonov',
            'email' => 'd.alimjonov@example.com',
        ]);

        User::factory()->create([
            'name' => 'D.Karimov',
            'email' => 'd.karimov@example.com',
        ]);

        User::factory()->create([
            'name' => 'J.Otayorov',
            'email' => 'j.otayorov@example.com',
        ]);

        User::factory()->create([
            'name' => 'J.Tuxtayev',
            'email' => 'j.tuxtayev@example.com',
        ]);

        User::factory()->create([
            'name' => 'Z.Mamatqulov',
            'email' => 'z.mamatqulov@example.com',
        ]);

        User::factory()->create([
            'name' => 'Z.Jumayev',
            'email' => 'z.jumayev@example.com',
        ]);

        User::factory()->create([
            'name' => 'I.Negmatulayev',
            'email' => 'i.negmatulayev@example.com',
        ]);

        User::factory()->create([
            'name' => 'M.Nurboev',
            'email' => 'm.nurboev@example.com',
        ]);

        User::factory()->create([
            'name' => 'M.Hafizov',
            'email' => 'm.hafizov@example.com',
        ]);

        User::factory()->create([
            'name' => 'M.Kholmatov',
            'email' => 'm.kholmatov@example.com',
        ]);

        User::factory()->create([
            'name' => 'N.Hikmatov',
            'email' => 'n.hikmatov@example.com',
        ]);

        User::factory()->create([
            'name' => 'N.Yuldashev',
            'email' => 'n.yuldashev@example.com',
        ]);

        User::factory()->create([
            'name' => 'P.Shavkatov',
            'email' => 'p.shavkatov@example.com',
        ]);

        User::factory()->create([
            'name' => 'R.Madjikhanov',
            'email' => 'r.madjikhanov@example.com',
        ]);

        User::factory()->create([
            'name' => 'S.Vasitov',
            'email' => 's.vasitov@example.com',
        ]);

        User::factory()->create([
            'name' => 'S.Ochilov',
            'email' => 's.ochilov@example.com',
        ]);

        User::factory()->create([
            'name' => 'U.Tajibayev',
            'email' => 'u.tajibayev@example.com',
        ]);

        User::factory()->create([
            'name' => 'U.Ergasheva',
            'email' => 'u.ergasheva@example.com',
        ]);

        User::factory()->create([
            'name' => 'F.Qahhorov',
            'email' => 'f.qahhorov@example.com',
        ]);

        User::factory()->create([
            'name' => 'X.Eshkaraev',
            'email' => 'x.eshkaraev@example.com',
        ]);

        User::factory()->create([
            'name' => 'Sh.Ataev',
            'email' => 'sh.ataev@example.com',
        ]);

//        $this->call(
//            [
//                HududSeeder::class
//            ]
//        );
    }
}
