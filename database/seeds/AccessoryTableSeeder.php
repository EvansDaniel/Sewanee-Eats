<?php

use App\Models\Accessory;
use Illuminate\Database\Seeder;

class AccessoryTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(Accessory::class, 10)->create();
    }
}
