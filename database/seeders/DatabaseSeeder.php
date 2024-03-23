<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Category;
use App\Models\Currency;
use App\Models\SubCategory;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Currency
        Currency::query()->create([
            'title' => 'dollar',
            'symbol' => '$',
            'rate' => 12600.00
        ]);
        Currency::query()->create([
            'title' => 'sum',
            'symbol' => 'сўм',
            'rate' => 1
        ]);
        Currency::query()->create([
            'title' => 'euro',
            'symbol' => '€',
            'rate' => 13619.11,
        ]);

        // Category
        Category::query()->create([
            'name' => 'Income',
        ]);
        Category::query()->create([
            'name' => 'Expense',
        ]);

        // Subcategory
        //income
        SubCategory::query()->create([
            'name' => 'Business',
            'category_id' => 1,
            'is_default' => true
        ]);
        SubCategory::query()->create([
            'name' => 'Extra Income',
            'category_id' => 1,
            'is_default' => true

        ]);
        SubCategory::query()->create([
            'name' => 'Gifts',
            'category_id' => 1,
            'is_default' => true

        ]);
        SubCategory::query()->create([
            'name' => 'Loan',
            'category_id' => 1,
            'is_default' => true

        ]);
        SubCategory::query()->create([
            'name' => 'Salary',
            'category_id' => 1,
            'is_default' => true

        ]);

        //expense
        SubCategory::query()->create([
            'name' => 'Food & Drink',
            'category_id' => 2,
            'is_default' => true
        ]);
        SubCategory::query()->create([
            'name' => 'Gifts',
            'category_id' => 2,
            'is_default' => true
        ]);
        SubCategory::query()->create([
            'name' => 'Shopping',
            'category_id' => 2,
            'is_default' => true
        ]);
        SubCategory::query()->create([
            'name' => 'Home',
            'category_id' => 2,
            'is_default' => true
        ]);
        SubCategory::query()->create([
            'name' => 'Travel',
            'category_id' => 2,
            'is_default' => true
        ]);
        SubCategory::query()->create([
            'name' => 'Transport',
            'category_id' => 2,
            'is_default' => true
        ]);
        SubCategory::query()->create([
            'name' => 'Work',
            'category_id' => 2,
            'is_default' => true
        ]);
        SubCategory::query()->create([
            'name' => 'Family & Personal',
            'category_id' => 2,
            'is_default' => true
        ]);
        SubCategory::query()->create([
            'name' => 'Education',
            'category_id' => 2,
            'is_default' => true
        ]);
        SubCategory::query()->create([
            'name' => 'Car',
            'category_id' => 2,
            'is_default' => true
        ]);
        SubCategory::query()->create([
            'name' => 'Entertainment',
            'category_id' => 2,
            'is_default' => true
        ]);
        SubCategory::query()->create([
            'name' => 'Healthcare',
            'category_id' => 2,
            'is_default' => true
        ]);
        SubCategory::query()->create([
            'name' => 'Bills & Fees',
            'category_id' => 2,
            'is_default' => true
        ]);
        SubCategory::query()->create([
            'name' => 'Other',
            'category_id' => 2,
            'is_default' => true
        ]);
    }
}
