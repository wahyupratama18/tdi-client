<?php

namespace App\Actions\Server;

use Illuminate\Http\Client\Response;

interface LaunchInterface
{
    public function launch(): Response;

    public function setToken(string $token): static;
}
