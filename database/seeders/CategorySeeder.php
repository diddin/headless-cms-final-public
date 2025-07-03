<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //Category::factory()->count(3)->create();
        collect(['News', 'Tutorial', 'Announcement'])->each(function ($name) {
            Category::create(['name' => $name]);
        });
    }
}
