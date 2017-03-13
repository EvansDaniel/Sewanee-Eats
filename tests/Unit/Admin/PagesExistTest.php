<?php

namespace Tests\Unit\Admin;

use App\Models\Role;
use App\User;
use Tests\TestCase;

class PagesExistTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function dashboardPageTest()
    {
        $user = factory(User::class)->make();
        $user->save();
        $user->roles()->attach(Role::where('name', 'admin')->first());
        $this->actingAs($user)
            ->visit('/')
            ->see('DASHBOARD');
    }
}
