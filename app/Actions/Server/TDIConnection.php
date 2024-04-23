<?php

namespace App\Actions\Server;

use Illuminate\Http\RedirectResponse;

final class TDIConnection
{
    public static function redirect(string $route): RedirectResponse
    {
        return redirect()->away(self::path($route));
    }

    public static function path(string $route): string
    {
        return env('TDI_URL').'/'.$route;
    }
}
