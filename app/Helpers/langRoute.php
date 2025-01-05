<?php

if ( ! function_exists('langRoute')) {
    function langRoute($name, $parameters = [], $absolute = true): string
    {
        $parameters['lang'] = app()->getLocale();
        return route($name, $parameters, $absolute);
    }
}