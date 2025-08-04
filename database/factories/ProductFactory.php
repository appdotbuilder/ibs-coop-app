<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $categories = ['Food & Beverages', 'Household', 'Electronics', 'Health & Beauty', 'Clothing'];
        $purchasePrice = fake()->numberBetween(10000, 500000);
        $sellingPrice = $purchasePrice * fake()->randomFloat(2, 1.2, 2.0);
        $memberPrice = $sellingPrice * fake()->randomFloat(2, 0.85, 0.95);

        return [
            'sku' => fake()->unique()->regexify('[A-Z]{2}[0-9]{3}'),
            'name' => fake()->words(3, true),
            'description' => fake()->sentence(),
            'category' => fake()->randomElement($categories),
            'purchase_price' => $purchasePrice,
            'selling_price' => $sellingPrice,
            'member_price' => $memberPrice,
            'stock_quantity' => fake()->numberBetween(0, 200),
            'minimum_stock' => fake()->numberBetween(5, 20),
            'unit' => fake()->randomElement(['pcs', 'pack', 'box', 'bottle', 'kg', 'liter']),
            'is_active' => fake()->boolean(90),
            'allow_installment' => fake()->boolean(30),
            'points_earned' => fake()->numberBetween(1, 10),
        ];
    }

    /**
     * Indicate that the product is active.
     */
    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => true,
        ]);
    }

    /**
     * Indicate that the product has low stock.
     */
    public function lowStock(): static
    {
        return $this->state(function (array $attributes) {
            $minStock = fake()->numberBetween(10, 20);
            return [
                'minimum_stock' => $minStock,
                'stock_quantity' => fake()->numberBetween(0, $minStock - 1),
            ];
        });
    }
}