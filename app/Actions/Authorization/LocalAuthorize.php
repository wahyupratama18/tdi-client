<?php

namespace App\Actions\Authorization;

use App\Models\User;
use Closure;
use Illuminate\Support\Facades\Auth;

final class LocalAuthorize
{
    public function handle(object $handle, Closure $next)
    {
        if ($handle->valid) {
            Auth::login(
                User::updateOrCreate(
                    ['email' => $handle->http->json('email')],
                    [
                        'name' => $handle->http->json('name'),
                        'token' => $handle->token,
                        'valid_until' => $handle->previous?->valid_until ?? now()->addDays(6),
                        'profile_photo_url' => $handle->http->json('profile_photo_url'),
                    ],
                ),
                true,
            );
        }

        return $next($handle);
    }
}
