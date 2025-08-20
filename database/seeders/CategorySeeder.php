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
        $categories = [
            [
                'name' => 'Electronics',
                'description' => 'Electronic devices and accessories',
                'is_active' => true,
                'sort_order' => 1,
                'children' => [
                    ['name' => 'Smartphones', 'description' => 'Mobile phones and accessories'],
                    ['name' => 'Laptops', 'description' => 'Laptop computers and accessories'],
                    ['name' => 'Tablets', 'description' => 'Tablet computers and accessories'],
                ]
            ],
            [
                'name' => 'Clothing',
                'description' => 'Fashion and apparel',
                'is_active' => true,
                'sort_order' => 2,
                'children' => [
                    ['name' => 'Men\'s Clothing', 'description' => 'Clothing for men'],
                    ['name' => 'Women\'s Clothing', 'description' => 'Clothing for women'],
                    ['name' => 'Accessories', 'description' => 'Fashion accessories'],
                ]
            ],
            [
                'name' => 'Home & Garden',
                'description' => 'Home improvement and garden supplies',
                'is_active' => true,
                'sort_order' => 3,
                'children' => [
                    ['name' => 'Furniture', 'description' => 'Home furniture'],
                    ['name' => 'Kitchen', 'description' => 'Kitchen appliances and tools'],
                    ['name' => 'Garden', 'description' => 'Garden tools and supplies'],
                ]
            ],
        ];

        foreach ($categories as $categoryData) {
            $children = $categoryData['children'] ?? [];
            unset($categoryData['children']);

            $category = Category::create($categoryData);

            foreach ($children as $childData) {
                $childData['parent_id'] = $category->id;
                $childData['is_active'] = true;
                $childData['sort_order'] = 1;
                Category::create($childData);
            }
        }
    }
}
