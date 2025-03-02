<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = \App\Models\Category::factory(5)->create();
        $categories->each(function ($category) {
            $product =  $category->products()->saveMany(\App\Models\Product::factory(10)->make());
            $product->each(function ($product) {
                $product->skus()->saveMany(\App\Models\Sku::factory(5)->make());
            });
        });
    }
}
