<?php

function toTwoDecimals(float $num)
{
    if (((int)$num) == $num) {
        return number_format((float)$num, 2, '.', '');
    }
    return $num;
}