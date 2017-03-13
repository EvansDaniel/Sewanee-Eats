<?php

use App\Models\EventItem;
use Illuminate\Database\Seeder;

class EventItemTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(EventItem::class, 10)->create();
    }
}
