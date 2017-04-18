<?php

function toTwoDecimals(float $num)
{
    $string_num = "$num";
    if (((int)$num) == $num) {
        return number_format((float)$num, 2, '.', '');
    }
    return $num;
}