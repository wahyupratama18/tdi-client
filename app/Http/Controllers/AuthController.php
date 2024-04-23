<?php

namespace App\Http\Controllers;

use App\Actions\Server\TDIConnection;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Pipeline;

class AuthController extends Controller
{
    public function index(): RedirectResponse
    {
        return TDIConnection::redirect('local/'.request()->getHost().'/'.request()->getPort());
    }

    /**
     * Handle the incoming request.
     */
    public function store(string $token)
    {
        $handle = Pipeline::send((object) ['token' => $token])
            ->through([
                \App\Actions\Authorization\Fetching::class,
                \App\Actions\Authorization\LocalAuthorize::class,
            ])
            ->thenReturn();

        return redirect()->route($handle->valid ? 'dashboard' : 'login');
    }

    public function logout(Request $request)
    {
        $request->user()->update(['token' => null, 'vaild_until' => null]);

        Auth::logout();

        return redirect()->route('login');
    }
}
