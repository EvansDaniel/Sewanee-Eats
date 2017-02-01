<?php

use Illuminate\Database\Seeder;
use App\Models\MenuItem;

class MenuItemTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(MenuItem::class,50)->create();
    }
}
