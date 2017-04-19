<?php

use Doctrine\Instantiator\Exception\InvalidArgumentException;
use Illuminate\Support\HtmlString;

/**
 * @param float $num a float to format to have two decimal following decimal places
 * @return float|string the number formatted to two decimal places
 * Note: the number is not modified
 */
function toTwoDecimals(float $num)
{
    $string_num = "$num";
    if (((int)$num) == $num) {
        return number_format((float)$num, 2, '.', '');
    }
    return $num;
}

/**
 * @param string $url the url to secure
 * @param array $args the arguments to the url
 * @return string a secure url to $url with $args
 */
function formUrl(string $url, array $args = [])
{
    return url()->to(parse_url(route($url, $args), PHP_URL_PATH), [], env('APP_ENV') !== 'local');
}

/**
 * @param string $url the url to secure
 * @return string a secure url for assets
 */
function assetUrl(string $url)
{
    return asset($url, env('APP_ENV') !== 'local');
}

/**
 * @param $scroll_to_menu_item_id int|null the id of the thing to scroll to
 * @return HtmlString the html required for the function scrollToItem in helpers.js to work
 */
function generateScrollTo($scroll_to_menu_item_id)
{
    if (empty($scroll_to_menu_item_id))
        $scroll_to_menu_item_id = null;
    return new HtmlString('<input type="hidden" id="scroll-to-id" data-scroll-to="' . $scroll_to_menu_item_id . '">');
}

function convertToPhoneNumber($number)
{
    if (!is_int($number)) {
        throw new InvalidArgumentException('$number must be of type int');
    }
    $num_to_string = "$number";
    $num = "";
    if (strlen($num_to_string) == 10) {// (931)-555-5555
        $num .= "(";
        $num .= substr($num_to_string, 0, 3);
        $num .= ")-";
        $num .= substr($num_to_string, 4, 3);
        $num .= "-";
        $num .= substr($num_to_string, 7, 4);
    }
    return $num === "" ? $number : $num;
}

/**
 * @param $name string the name of the restaurant
 */
function cleanseRestName($name)
{
    return str_replace(' ', "-", $name);
}

function decleanseRestaurant($name)
{
    // hyphens are not allowed in the restaurant name
    // so this will work fine
    return str_replace('-', ' ', $name);
}

