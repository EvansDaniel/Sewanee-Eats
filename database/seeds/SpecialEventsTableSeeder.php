<?php

use App\Models\SpecialEvent;
use Illuminate\Database\Seeder;

class SpecialEventsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(SpecialEvent::class, 1)->create();
    }
}
