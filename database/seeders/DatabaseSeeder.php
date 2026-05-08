<?php

namespace Database\Seeders;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        $sony = Brand::create([
            'name' => 'Sony',
        ]);

        $ps5 = Product::create([
            'name' => 'PlayStation 5 - White',
            'price' => '499.99',
            'color' => 'White',
            'description' => 'The PlayStation 5 (PS5) is the home video game console developed by Sony Interactive '
                . 'Entertainment for the fifth iteration of their PlayStation brand. It was announced as '
                . 'the successor to the PlayStation 4 in April 2019, was launched on November 12, 2020, in Australia, '
                . 'Japan, New  Zealand, North America, and South Korea, and was released worldwide a week later. '
                . 'The PS5 is part of the ninth generation of video game consoles, along with Microsoft\'s Xbox Series '
                . 'X/S consoles, which were released in the same month.'
        ]);

        $ps5->brand()->associate($sony);

        $consoles = Category::create([
            'name' => 'Consoles',
        ]);

        $ps5->categories()->attach($consoles->id);

        (new DeliverySeeder)->run();
    }
}
