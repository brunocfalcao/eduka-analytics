<?php

if (! function_exists('my_ip')) {
    function ip2()
    {
        return request()->ip() == '127.0.0.1' ?
        file_get_contents('https://ipinfo.io/ip') :
        request()->ip();
    }
}
