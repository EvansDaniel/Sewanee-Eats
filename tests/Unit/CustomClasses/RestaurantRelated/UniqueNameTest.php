<?php

namespace Tests\Unit\CustomClasses\RestaurantRelated;

use App\CustomClasses\Helpers\UniqueName;
use Tests\TestCase;

class UniqueNameTest extends TestCase
{
    /**
     * Tests that UniqueName gives correct output when
     * given a name of "string", with no number to right.
     * We should get the original name as output since it is unique
     * @test
     */
    public function correctWhenNoNumberToTheRight()
    {
        $name = "name";
        $names = ["blah", 'blah1', 'name'];
        $unique_name = new UniqueName($names, $name);
        self::assertEquals($name . '.1', $unique_name->getUniqueName());
    }

    /**
     * Make sure we get correct output when there is a lot
     * of elements with the same start name as the one we want
     * @test
     */
    public function correctWhenContainsManySameNames()
    {
        $name = "name";
        $names = [];
        $amount_names = 100;
        for ($i = 0; $i < $amount_names; $i++) {
            $names[] = $name . "." . $i;
        }
        $unique_name = new UniqueName($names, $name);
        self::assertEquals($name . "." . ($amount_names), $unique_name->getUniqueName());
    }

    /**
     * Make sure we get correct output when there is an array of stuff
     * in it that do not have the same name and some that do have the same name
     * @test
     */
    public function correctWhenContainsManyManyAndSomeDifferentSameNames()
    {
        $name = "name";
        $names = [];
        // building test data
        $start_names = ['hello', 'this', 'blahblah', 'hello world', 'lksdjflkd', $name];
        $amount_names = 100;
        foreach ($start_names as $start_name) {
            for ($i = 0; $i < $amount_names; $i++) {
                $names[] = $start_name . "." . $i;
            }
        }
        // done building
        $unique_name = new UniqueName($names, $name);
        self::assertEquals($name . "." . ($amount_names), $unique_name->getUniqueName());
    }

    /**
     * Make sure we get correct output when there is an array of stuff
     * that does not have the same start name. We should get the original
     * name as output b/c it is unique
     * @test
     */
    public function correctWhenContainsOnlyDifferentNames()
    {
        $name = "name";
        $names = [];
        // building test data
        $start_names = ['hello', 'this', 'blahblah', 'hello world', 'lksdjflkd'];
        $amount_names = 100;
        foreach ($start_names as $start_name) {
            for ($i = 0; $i < $amount_names; $i++) {
                $names[] = $start_name . "." . $i;
            }
        }
        // done building
        $unique_name = new UniqueName($names, $name);
        self::assertEquals($name, $unique_name->getUniqueName());
    }
}
