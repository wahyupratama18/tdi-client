<?php

namespace App\Actions\Server;

use App\Enums\SyncList;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;

abstract class Launcher implements LaunchInterface
{
    protected string $token;

    protected SyncList $sync;

    public function __construct(protected $id = null) {}

    protected function connect(): PendingRequest
    {
        return Http::withToken($this->token);
    }

    public function setToken(string $token): static
    {
        $this->token = $token;

        return $this;
    }

    protected function replaceURL(): string
    {
        $route = $this->sync->apiRoute();

        if ($this->id) {
            $route = str_replace(':id', $this->id, $route);
        }

        return $route;
    }
}
