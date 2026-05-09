<?php

namespace Database\Seeders;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Log;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileDoesNotExist;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileIsTooBig;

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

        $brands = [
            ['id' => 1, 'name' => 'Sony'],
            ['id' => 2, 'name' => 'Apple'],
            ['id' => 3, 'name' => 'Xbox'],
            ['id' => 4, 'name' => 'FROM SOFTWARE'],
            ['id' => 5, 'name' => 'NVIDIA'],
            ['id' => 6, 'name' => 'Logitech'],
            ['id' => 7, 'name' => 'JBL'],
            ['id' => 8, 'name' => 'Rockstar Games'],
        ];
        foreach ($brands as $brand) {
            $brand['created_at'] = now();
            $brand['updated_at'] = now();
        }
        Brand::insert($brands);

        $categories = [
            ['id' => 1, 'name' => 'Consoles'],
            ['id' => 2, 'name' => 'Smartphones'],
            ['id' => 3, 'name' => 'Gaming'],
            ['id' => 4, 'name' => 'Headphones'],
            ['id' => 5, 'name' => 'PC'],
            ['id' => 6, 'name' => 'Controllers'],
            ['id' => 7, 'name' => 'Software'],
        ];
        foreach ($categories as $category) {
            $category['created_at'] = now();
            $category['updated_at'] = now();
        }
        Category::insert($categories);

        $products = [
            ['id' => 1, 'name' => 'PlayStation 5 - White', 'price' => '499.99', 'color' => 'White', 'brand_id' => 1, 'description' => 'The PlayStation 5 (PS5) is the home video game console developed by Sony Interactive Entertainment for the fifth iteration of their PlayStation brand. It was announced as the successor to the PlayStation 4 in April 2019, was launched on November 12, 2020, in Australia, Japan, New Zealand, North America, and South Korea, and was released worldwide a week later. The PS5 is part of the ninth generation of video game consoles, along with Microsoft\'s Xbox Series X/S consoles, which were released in the same month.', 'categories' => [1, 3]],
            ['id' => 2, 'name' => 'iPhone 17 - Gray', 'price' => '899.99', 'color' => 'Gray', 'brand_id' => 2, 'description' => 'Designed for Apple Intelligence. Use the worst possible versions of ChatGPT and Siri on our most overpriced product yet.', 'categories' => [2]],
            ['id' => 3, 'name' => 'Sony WH1000-XM5', 'price' => '229.99', 'color' => null, 'brand_id' => 1, 'description' => 'Top of the line quality. Is it worth the price? Hell nah.', 'categories' => [4]],
            ['id' => 4, 'name' => 'Xbox Series S  - White', 'price' => '399.99', 'color' => 'White', 'brand_id' => 3, 'description' => 'The new, faster and more affordable Xbox. Play thousands of games included with Game Pass with new titles coming in every month.', 'categories' => [1, 3]],
            ['id' => 5, 'name' => 'Elden Ring (PS5)', 'price' => '59.99', 'color' => null, 'brand_id' => 4, 'description' => 'GAME OF THE CENTURY - Experience poison swamps, complicated mechanics, unfair boss fights and the most obscure lore ever made for a game, written by the one and only George R. R. Martin and told exclusively using item descriptions!', 'categories' => [3, 7]],
            ['id' => 6, 'name' => 'Xbox One Wireless Controller - Black', 'price' => '49.99', 'color' => 'Black', 'brand_id' => 3, 'description' => 'Control your games from the other side of your home. Batteries not included.', 'categories' => [3, 6]],
            ['id' => 8, 'name' => 'Nvidia GeForce RTX 5060', 'price' => '349.99', 'color' => null, 'brand_id' => 5, 'description' => 'Experience the AI-powered magic of NVIDIA Ultra Frame Generation(tm) today. Get up to 50 FPS* on medium settings in all your favorite Unreal Engine 5 powered games.\n\n* average frame rate, effect is cosmetic only', 'categories' => [3, 5]],
            ['id' => 9, 'name' => 'JBL Wave Buds', 'price' => '29.99', 'color' => 'Black', 'brand_id' => 7, 'description' => 'Low price. Average quality. Super hard to set up on Windows 11. But that\'s not our fault, blame Microsoft.', 'categories' => [4]],
            ['id' => 10, 'name' => 'DualSense Wireless Controller - Red', 'price' => '69.99', 'color' => 'Red', 'brand_id' => 1, 'description' => 'Made for PlayStation 5. Also supports PC and Mac. Features include: haptic feedback, adaptive triggers and stick drift. A lot of stick drift.', 'categories' => [3, 6]],
            ['id' => 11, 'name' => 'Logitech G 102', 'price' => '19.99', 'color' => 'Black', 'brand_id' => 6, 'description' => 'Every broke boy\'s first real gaming mouse. Never breaks even if you throw it at a wall. Perfect for popping heads and screaming slurs on CS.', 'categories' => [3, 5]],
            ['id' => 12, 'name' => 'Nvidia GeForce GTX 1650', 'price' => '169.99', 'color' => null, 'brand_id' => 5, 'description' => 'Slow. No ray tracing . No DLSS. Out of production. Still one of the most popular GPUs of all time. And we had to raise the price because it comes with 4 GB of VRAM.', 'categories' => [3, 5]],
            ['id' => 13, 'name' => 'PlayStation 5 - Black', 'price' => '519.99', 'color' => 'Black', 'brand_id' => 1, 'description' => 'Comes with pre-installed black covers.', 'categories' => [1, 3]],
            ['id' => 15, 'name' => 'Xbox One Wireless Controller - White', 'price' => '49.99', 'color' => 'White', 'brand_id' => 3, 'description' => 'Control your games from the other side of your home. Batteries not included.', 'categories' => [3, 6]],
            ['id' => 16, 'name' => 'Grand Theft Auto V (PS5)', 'price' => '31.99', 'color' => null, 'brand_id' => 8, 'description' => 'New trailer for GTA 6? Story mode DLC? Nah all we got is oppressors and shark cards.', 'categories' => [3, 7]],
        ];
        foreach ($products as $product) {
            $product['created_at'] = now();
            $product['updated_at'] = now();
        }
        Product::insert($products);

        foreach ($products as $product) {
            if (!empty($product['categories'])) {
                Product::find($product['id'])?->categories()->attach($product['categories']);
            }
        }

        $images = [
            ['id' => 1, 'path' => 'public/storage/24/MSX0052b11-01.webp'],
            ['id' => 2, 'path' => 'public/storage/21/000003385510_0.webp'],
            ['id' => 3, 'path' => 'public/storage/18/103375_original_local_1200x1050_v3_converted.webp'],
            ['id' => 4, 'path' => 'public/storage/22/5DUP600301-600-600.webp'],
            ['id' => 5, 'path' => 'public/storage/4/er-ps5.webp'],
            ['id' => 5, 'path' => 'public/storage/6/er-gameplay.jpg'],
            ['id' => 6, 'path' => 'public/storage/11/D5EW000501-600-600.webp'],
            ['id' => 8, 'path' => 'public/storage/20/881aeaef-e37e-4bb6-b260-2e2f1093610d.avif'],
            ['id' => 9, 'path' => 'public/storage/14/1.JBL_Wave_Vibe_-Buds_Product-Image_Hero_Black.webp'],
            ['id' => 10, 'path' => 'public/storage/17/qq892.jpg'],
            ['id' => 11, 'path' => 'public/storage/10/g102.webp'],
            ['id' => 12, 'path' => 'public/storage/19/32648522-6598f51b6653a.jpg'],
            ['id' => 13, 'path' => 'public/storage/23/290202_or.jpg'],
            ['id' => 15, 'path' => 'public/storage/12/MXO008b17.webp'],
            ['id' => 15, 'path' => 'public/storage/13/D5EW000501-600-600.webp'],
            ['id' => 16, 'path' => 'public/storage/15/gta-ps5.webp'],
        ];

        foreach ($images as $image) {
            $product = Product::find($image['id']);

            if ($product) {
                try {
                    $product->addMedia($image['path'])->toMediaCollection('images');
                } catch (FileDoesNotExist|FileIsTooBig $e) {
                    Log::error($e);
                }
            }
        }

        (new DeliverySeeder)->run();
    }
}
