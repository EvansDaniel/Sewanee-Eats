<?php
/**
 * Created by PhpStorm.
 * User: daniel
 * Date: 3/23/17
 * Time: 10:30 AM
 */

namespace App\CustomClasses\Helpers;


class UrlBuilder
{
    protected $base_url;

    /**
     * UrlBuilder constructor.
     * @param $base_url string must be a url containing the correct path already
     */
    public function __construct($base_url)
    {
        $this->base_url = $base_url;
    }

    public function getUrl()
    {
        return $this->base_url;
    }

    public function addParams($array)
    {
        $query = parse_url($this->base_url, PHP_URL_QUERY);
        // Returns a string if the URL has parameters or NULL if not
        $query_string = http_build_query($array);
        if ($query) {
            $this->base_url .= '&' . $query_string;
        } else {
            $this->base_url .= '?' . $query_string;
        }
        return $this;
    }

    // this method doesn't work
    public function addParam($key, $value)
    {
        $query = parse_url($this->base_url, PHP_URL_QUERY);
        // Returns a string if the URL has parameters or NULL if not
        if ($query) {
            $this->base_url .= '&' . $key . '=' . $value;
        } else {
            $this->base_url .= '?' . $key . '=' . $value;
        }
        return $this;
    }
}