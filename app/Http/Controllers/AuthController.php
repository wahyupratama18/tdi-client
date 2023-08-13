<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Client\Response;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class AuthController extends Controller
{
    public function index(Request $request): RedirectResponse
    {
        /* $expectedUser = User::query()->whereNotNull('token')->latest('updated_at')->first();

        $http = $this->fetch($expectedUser->token);

        if ($expectedUser && $this->validateAuthServer($http)) {
            $this->login($http, $expectedUser->token, $expectedUser);

            redirect()->to(RouteServiceProvider::HOME);
        } */

        return redirect()->away(tdiRoute('local/'.request()->getHost().'/'.request()->getPort()));
    }

    protected function fetch(string $token): Response
    {
        return Http::withToken($token)->get(tdiRoute('api/user'));
    }

    protected function validateAuthServer(Response $http): bool
    {
        return is_array($http->json());
    }

    protected function login(Response $http, string $token, User $previous = null)
    {
        Auth::login(
            User::updateOrCreate(
                ['email' => $http->json('email')],
                [
                    'name' => $http->json('name'),
                    'token' => $token,
                    'valid_until' => $previous?->valid_until ?? now()->addDays(6),
                    'profile_photo_url' => $http->json('profile_photo_url'),
                ],
            ),
            true,
        );
    }

    /**
     * Handle the incoming request.
     */
    public function store(Request $request, string $token)
    {
        $http = $this->fetch($token);

        if (! $this->validateAuthServer($http)) {
            return redirect()->route('login');
        }

        $this->login($http, $token);

        return redirect()->to(RouteServiceProvider::HOME);
    }

    public function logout(Request $request)
    {
        $request->user()->update(['token' => null, 'vaild_until' => null]);

        Auth::logout();

        return redirect()->route('login');
    }
}
