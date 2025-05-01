<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GovernorateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        
        DB::table('governorates')->insert([
            ['name' => 'دمشق'],
            ['name' => 'ريف دمشق'],
            ['name' => 'حمص'],
            ['name' => 'حماة'],
            ['name' => 'درعا'],
            ['name' => 'حلب'],
            ['name' => 'ادلب'],
            ['name' => 'اللاذقية'],
            ['name' => 'طرطوس'],
            ['name' => 'القنيطرة'],
            ['name' => 'السويداء'],
            ['name' => 'الرقة'],
            ['name' => 'الحسكة'],
            ['name' => 'دير الزور'],
        ]);
        
    }

    
}
