<?php
/**
 * Created by PhpStorm.
 * User: daniel
 * Date: 3/23/17
 * Time: 11:43 AM
 */

namespace App\CustomClasses\Helpers;


class HttpRequest
{
    protected $url_builder;

    public function __construct($url_builder)
    {
        $this->url_builder = $url_builder;
    }

    public function get()
    {
        // create curl resource
        $ch = curl_init();

        // set url
        curl_setopt($ch, CURLOPT_URL, $this->url_builder->getUrl());

        //return the transfer as a string
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        // $output contains the output string
        $output = curl_exec($ch);

        // close curl resource to free up system resources
        curl_close($ch);

        return $output;
    }
}