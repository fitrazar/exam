<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class StudentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('students')->insert([
            'grade_id' => 1,
            'major_id' => 1,
            'group_id' => 1,
            'name' => 'Muhammad Fitra Fajar Rusamsi',
            'nisn' => mt_rand(1000, 9999),
            'gender' => 'Laki - Laki',
            'phone' => '6281385931773',
            'point' => 100,
            'password' => Hash::make('password'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
