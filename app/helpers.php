<?php

if (! function_exists('with_locale')) {
    function with_locale($locale, \Closure $callback)
    {
        $currentLocale = app()->getLocale();
        app()->setLocale($locale);

        $value = $callback();

        app()->setLocale($currentLocale);

        return $value;
    }
}

if (! function_exists('array_mirror')) {
    function array_mirror(array $array): array
    {
        return array_combine($array, $array);
    }
}
