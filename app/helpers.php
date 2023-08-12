<?php

if (! function_exists('tdiRoute')) {
    function tdiRoute(string $route): string
    {
        return env('TDI_URL').'/'.$route;
    }
}
