<?php

namespace Database\Seeders;

use App\Models\District;
use Illuminate\Database\Seeder;

class DistrictSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tashkent_shahars = [
            'Bektemir',
            'Chilonzor',
            'Mirzo Ulug‘bek',
            'Mirobod',
            'Sergeli',
            'Shayxontohur',
            'Uchtepa',
            'Olmazor',
            'Yakkasaroy',
            'Yunusobod',
            'Yashnobod'
        ];
        foreach ($tashkent_shahars as $tashkent_shahar) {
            District::create([
                'name' => $tashkent_shahar,
                'region_id' => 1
            ]);
        }


        // Toshkent viloyati
        $tashkent_viloyat = [
            'Angren shahri',
            'Bekobod tumani',
            'Bekobod shahri',
            'Bo‘ka',
            'Bo‘stonliq',
            'Zangiota',
            'Qibray',
            'Ohangaron',
            'Ohangaron shahri',
            'Oqqo‘rg‘on',
            'Parkent',
            'Piskent',
            'Quyi Chirchiq',
            'Yangiyo‘l',
            'Yangiyo‘l shahri',
            'Yuqori Chirchiq',
            'Chinoz',
        ];
        foreach ($tashkent_viloyat as $name) {
            District::create([
                'name' => $name,
                'region_id' => 2, // Toshkent viloyati
            ]);
        }

        // Andijon viloyati
        $andijon = [
            'Andijon shahri',
            'Andijon tumani',
            'Asaka',
            'Baliqchi',
            'Bo‘z',
            'Buloqboshi',
            'Izboskan',
            'Jalolquduq',
            'Marhamat',
            'Oltinko‘l',
            'Paxtaobod',
            'Xonobod shahri',
            'Xo‘jaobod',
            'Shahrixon',
        ];
        foreach ($andijon as $name) {
            District::create([
                'name' => $name,
                'region_id' => 3, // Andijon
            ]);
        }

        // Farg‘ona viloyati
        $fargona = [
            'Farg‘ona shahri',
            'Beshariq',
            'Bog‘dod',
            'Buvayda',
            'Dang‘ara',
            'Furqat',
            'O‘zbekiston',
            'Oltiariq',
            'Quva',
            'Qo‘qon shahri',
            'Quvasoy shahri',
            'Rishton',
            'So‘x',
            'Toshloq',
            'Uchko‘prik',
            'Yozyovon',
        ];
        foreach ($fargona as $name) {
            District::create([
                'name' => $name,
                'region_id' => 4, // Farg‘ona
            ]);
        }

        // Namangan viloyati
        $namangan = [
            'Namangan shahri',
            'Chortoq',
            'Chust',
            'Kosonsoy',
            'Mingbuloq',
            'Norin',
            'Pop',
            'To‘raqo‘rg‘on',
            'Uchqo‘rg‘on',
            'Yangiqo‘rg‘on',
        ];
        foreach ($namangan as $name) {
            District::create([
                'name' => $name,
                'region_id' => 5, // Namangan
            ]);
        }

        // Samarqand viloyati
        $samarqand = [
            'Samarqand shahri',
            'Bulung‘ur',
            'Ishtixon',
            'Jomboy',
            'Kattaqo‘rg‘on tumani',
            'Kattaqo‘rg‘on shahri',
            'Narpay',
            'Nurobod',
            'Oqdaryo',
            'Paxtachi',
            'Payariq',
            'Pastdarg‘om',
            'Qo‘shrabot',
            'Samarqand tumani',
            'Toyloq',
            'Urgut',
        ];
        foreach ($samarqand as $name) {
            District::create([
                'name' => $name,
                'region_id' => 6, // Samarqand
            ]);
        }

        // Qashqadaryo viloyati
        $qashqadaryo = [
            'Qarshi shahri',
            'Qarshi tumani',
            'Chiroqchi',
            'Dehqonobod',
            'G‘uzor',
            'Kasbi',
            'Kitob',
            'Koson',
            'Mirishkor',
            'Muborak',
            'Nishon',
            'Qamashi',
            'Shahrisabz tumani',
            'Shahrisabz shahri',
            'Yakkabog‘',
        ];
        foreach ($qashqadaryo as $name) {
            District::create([
                'name' => $name,
                'region_id' => 7, // Qashqadaryo
            ]);
        }

        // Surxondaryo viloyati
        $surxondaryo = [
            'Termiz shahri',
            'Angor',
            'Bandixon',
            'Boysun',
            'Denov',
            'Jarqo‘rg‘on',
            'Muzrabot',
            'Oltinsoy',
            'Qiziriq',
            'Qumqo‘rg‘on',
            'Sariosiyo',
            'Sherobod',
            'Shurchi',
            'Termiz tumani',
        ];
        foreach ($surxondaryo as $name) {
            District::create([
                'name' => $name,
                'region_id' => 8, // Surxondaryo
            ]);
        }

        // Buxoro viloyati
        $buxoro = [
            'Buxoro shahri',
            'Buxoro tumani',
            'G‘ijduvon',
            'Jondor',
            'Kogon tumani',
            'Kogon shahri',
            'Olot',
            'Peshku',
            'Qorako‘l',
            'Qorovulbozor',
            'Romitan',
            'Shofirkon',
            'Vobkent',
        ];
        foreach ($buxoro as $name) {
            District::create([
                'name' => $name,
                'region_id' => 9, // Buxoro
            ]);
        }

        // Navoiy viloyati
        $navoiy = [
            'Navoiy shahri',
            'Karmana',
            'Konimex',
            'Navbahor',
            'Nurota',
            'Qiziltepa',
            'Uchquduq',
            'Xatirchi',
            'Zarafshon shahri',
        ];
        foreach ($navoiy as $name) {
            District::create([
                'name' => $name,
                'region_id' => 10, // Navoiy
            ]);
        }

        // Xorazm viloyati
        $xorazm = [
            'Urganch shahri',
            'Bog‘ot',
            'Gurlan',
            'Qo‘shko‘pir',
            'Shovot',
            'Xazorasp',
            'Xiva shahri',
            'Xiva tumani',
            'Yangibozor',
            'Yangiariq',
        ];
        foreach ($xorazm as $name) {
            District::create([
                'name' => $name,
                'region_id' => 11, // Xorazm
            ]);
        }

        // Sirdaryo viloyati
        $sirdaryo = [
            'Guliston shahri',
            'Sirdaryo tumani',
            'Boyovut',
            'Oqoltin',
            'Sayxunobod',
            'Sardoba',
            'Xovos',
            'Mirzaobod',
        ];
        foreach ($sirdaryo as $name) {
            District::create([
                'name' => $name,
                'region_id' => 12, // Sirdaryo
            ]);
        }

        // Jizzax viloyati
        $jizzax = [
            'Jizzax shahri',
            'Arnasoy',
            'Baxmal',
            'Do‘stlik',
            'Forish',
            'G‘allaorol',
            'Mirzacho‘l',
            'Paxtakor',
            'Yangiobod',
            'Zarbdor',
            'Zafarobod',
            'Zomin',
        ];
        foreach ($jizzax as $name) {
            District::create([
                'name' => $name,
                'region_id' => 13, // Jizzax
            ]);
        }

        // Qoraqalpog‘iston Respublikasi
        $qoraqalpogiston = [
            'Nukus shahri',
            'Amudaryo',
            'Beruniy',
            'Chimboy',
            'Ellikqal’a',
            'Kegeyli',
            'Mo‘ynoq',
            'Qonliko‘l',
            'Qorao‘zak',
            'Qo‘ng‘irot',
            'Shumanay',
            'Taxtako‘pir',
            'To‘rtko‘l',
            'Xo‘jayli',
        ];
        foreach ($qoraqalpogiston as $name) {
            District::create([
                'name' => $name,
                'region_id' => 14, // Qoraqalpog‘iston
            ]);
        }
    }
}
