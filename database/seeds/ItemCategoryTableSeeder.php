<?php

use Illuminate\Database\Seeder;
use App\Models\ItemCategory;

class ItemCategoryTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(ItemCategory::class,10)->create();
    }
}
