<?php
/**
 * Created by PhpStorm.
 * User: daniel
 * Date: 4/26/17
 * Time: 12:06 PM
 */

namespace App\CustomTraits;


use Carbon\Carbon;
use Illuminate\Support\HtmlString;

trait Timing
{
    public function timeSinceCreated()
    {
        $time_diff = $this->created_at->diffInMinutes(Carbon::now());
        return new HtmlString('<span class="time-since-created">' . $time_diff . '</span> minutes ago');
    }
}