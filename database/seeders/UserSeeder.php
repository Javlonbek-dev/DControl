<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory()->create([
            'name'=>'Admin',
            'email'=>'admin@gmail.com',
            'password'=>'admin',
            'role'=>'moderator'
        ]);

        $users = [
            ['name' => 'IBRAGIMOV AYDOS MARATOVICH', 'email' => 'ibragimov@gmail.com', 'password' => 'ibragimov'],
            ['name' => 'YULDASHEV ABDUMUTALLIBABDURASULOVICH', 'email' => 'yuldashev@gmail.com', 'password' => 'yuldashev'],
            ['name' => 'JO‘RAYEV FARRUX SHODIYEVICH', 'email' => 'farrux@gmail.com', 'password' => 'farrux'],
            ['name' => 'QAHHOROV FARZIDDIN FAXRIDIN O‘G‘LI', 'email' => 'f.qahhorov@example.com', 'password' => 'qahhorov'],
            ['name' => 'HIKMATOV NODIRJON ALISHER O‘G‘LI', 'email' => 'hikmatov@gmail.com', 'password' => 'hikmatov'],
            ['name' => 'Aripov To‘lqin', 'email' => 'aripov@gmail.com', 'password' => 'aripov'],
            ['name' => 'NEGMATULLAYEV IBROXIM ERGASHEVICH', 'email' => 'negmatullayev@gmail.com', 'password' => 'ibroxim'],
            ['name' => 'Saidov Mamasodiq', 'email' => 'saidov@gmail.com', 'password' => 'saidov'],
            ['name' => 'KARIMOV G‘AYRAT XAYTALIYEVICH', 'email' => 'karimov@gmail.com', 'password' => 'karimov'],
            ['name' => 'MARASULOV ADHAMMAMIRYOVICH', 'email' => 'marasulov@gmail.com', 'password' => 'marasulov'],
            ['name' => 'ALIMDJANOV DILMURAD AKBARJANOVICH', 'email' => 'alimdjanov@gmail.com', 'password' => 'alimdjanov'],
            ['name' => 'SAPAYEV RAVSHON RUSTAMOVICH', 'email' => 'sapayev@gmail.com', 'password' => 'sapayev'],
            ['name' => 'VASITOV SARVARBADUVAХABOVICH', 'email' => 'vasitov@gmail.com', 'password' => 'vasitov'],
            ['name' => 'SUYARQULOV SHUHRAT ALISHEROVICH', 'email' => 'suyarqulov@gmail.com', 'password' => 'suyarqulov'],
        ];

        foreach ($users as $user) {
            User::factory()->create([
                'name' => $user['name'],
                'email' => $user['email'],
                'password' => bcrypt($user['password']),
                'role' => 'user',
            ]);
        }
    }
}
