<?php

namespace App\Actions\Authorization;

use App\Actions\Server\TDIConnection;
use Closure;
use Illuminate\Support\Facades\Http;

final class Fetching
{
    public function handle(object $handle, Closure $next)
    {
        $handle->http = Http::withToken($handle->token)->get(
            TDIConnection::path('api/user')
        );

        $handle->valid = is_array($handle->http->json());

        return $next($handle);
    }
}
