<?php

if ( ! function_exists('langRoute')) {
    function langRoute($name, $parameters = [], $absolute = true)
    {
        $parameters['lang'] = app()->getLocale();
        return route($name, $parameters, $absolute);
    }
}