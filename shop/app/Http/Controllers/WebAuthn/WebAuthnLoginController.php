<?php

namespace App\Http\Controllers\WebAuthn;

use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\Response;
use Laragear\WebAuthn\Http\Requests\AssertedRequest;
use Laragear\WebAuthn\Http\Requests\AssertionRequest;

class WebAuthnLoginController
{
    public function options(AssertionRequest $request): Responsable
    {
        return $request->toVerify(
            $request->validate(['email' => 'sometimes|email|string'])
        );
    }

    public function login(AssertedRequest $request): Response
    {
        return response()->noContent($request->login() ? 204 : 422);
    }
}
