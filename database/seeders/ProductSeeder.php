<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\Category;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $smartphones = Category::where('name', 'Smartphones')->first();
        $laptops = Category::where('name', 'Laptops')->first();
        $mensClothing = Category::where('name', 'Men\'s Clothing')->first();

        $products = [
            [
                'name' => 'iPhone 15 Pro',
                'description' => 'Latest iPhone with advanced features and A17 Pro chip.',
                'short_description' => 'Premium smartphone with exceptional performance.',
                'sku' => 'IPH15PRO001',
                'price' => 999.99,
                'sale_price' => 899.99,
                'stock_quantity' => 50,
                'category_id' => $smartphones->id,
                'is_featured' => true,
                'weight' => 0.2,
                'dimensions' => '147.6 x 71.6 x 7.8 mm',
            ],
            [
                'name' => 'Samsung Galaxy S24',
                'description' => 'Flagship Android smartphone with amazing camera and display.',
                'short_description' => 'Premium Android smartphone.',
                'sku' => 'SGS24001',
                'price' => 849.99,
                'stock_quantity' => 30,
                'category_id' => $smartphones->id,
                'is_featured' => true,
                'weight' => 0.21,
                'dimensions' => '147.0 x 70.6 x 7.6 mm',
            ],
            [
                'name' => 'MacBook Pro 16"',
                'description' => 'Professional laptop with M3 chip for maximum performance.',
                'short_description' => 'High-performance laptop for professionals.',
                'sku' => 'MBP16001',
                'price' => 2499.99,
                'stock_quantity' => 20,
                'category_id' => $laptops->id,
                'is_featured' => true,
                'weight' => 2.1,
                'dimensions' => '355.7 x 248.1 x 16.8 mm',
            ],
            [
                'name' => 'Dell XPS 13',
                'description' => 'Ultrabook with stunning display and long battery life.',
                'short_description' => 'Premium ultrabook for productivity.',
                'sku' => 'DXPS13001',
                'price' => 1299.99,
                'sale_price' => 1199.99,
                'stock_quantity' => 25,
                'category_id' => $laptops->id,
                'weight' => 1.2,
                'dimensions' => '295.7 x 198.5 x 14.8 mm',
            ],
            [
                'name' => 'Classic Cotton T-Shirt',
                'description' => 'Comfortable 100% cotton t-shirt in various colors.',
                'short_description' => 'Classic fit cotton t-shirt.',
                'sku' => 'TSHIRT001',
                'price' => 29.99,
                'sale_price' => 24.99,
                'stock_quantity' => 100,
                'category_id' => $mensClothing->id,
                'weight' => 0.2,
            ],
        ];

        foreach ($products as $productData) {
            $product = Product::create($productData);

            // Create a sample product image
            ProductImage::create([
                'product_id' => $product->id,
                'image_path' => 'products/sample-' . $product->id . '.jpg',
                'alt_text' => $product->name,
                'is_primary' => true,
                'sort_order' => 1,
            ]);
        }
    }
}
