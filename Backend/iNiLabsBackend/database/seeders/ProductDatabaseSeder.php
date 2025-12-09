<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductDatabaseSeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();

        if ($users->isEmpty()) {
            $this->command->info('No users found, please seed users first.');

            return;
        }

        $products = [
            [
                'name' => 'Product 1',
                'description' => 'This is the description for product 1',
                'price' => 99.99,
                'stock' => 10,
            ],
            [
                'name' => 'Product 2',
                'description' => 'This is the description for product 2',
                'price' => 199.99,
                'stock' => 5,
            ],
            [
                'name' => 'Product 3',
                'description' => 'This is the description for product 3',
                'price' => 49.99,
                'stock' => 20,
            ],
        ];

        foreach ($products as $product) {
            $product['user_id'] = $users->random()->id;
            $product['created_at'] = now();
            $product['updated_at'] = now();
            DB::table('products')->insert($product);
        }

        $this->command->info('Products seeded successfully.');
    }
}
