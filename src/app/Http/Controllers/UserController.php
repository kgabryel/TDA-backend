<?php

namespace App\Http\Controllers;

use App\Http\Requests\User\LoginRequest;
use App\Http\Requests\User\RefreshTokenRequest;
use App\Http\Requests\User\RegistrationRequest;
use App\Services\AuthRequestFactory;
use App\Services\AuthService;
use App\Models\User;
use League\OAuth2\Server\AuthorizationServer;
use Nyholm\Psr7\Response;

class UserController extends Controller
{
    private AuthorizationServer $server;

    public function __construct(AuthorizationServer $server)
    {
        $this->server = $server;
    }

    public function register(
        RegistrationRequest $request, AuthRequestFactory $factory, AuthService $authService,
        Response $response
    )
    {
        User::create(
            [
                'email' => $request->get('email'),
                'password' => bcrypt($request->get('password'))
            ]
        );
        return $authService->withErrorHandling(
            function() use ($request, $factory, $response) {
                return $this->server->respondToAccessTokenRequest(
                    $factory->getPasswordGrant($request->get('email'), $request->get('password')),
                    $response
                );
            }
        );
    }

    public function login(
        LoginRequest $request, AuthRequestFactory $factory, AuthService $authService,
        Response $response
    )
    {
        return $authService->withErrorHandling(
            function() use ($request, $factory, $response) {
                return $this->server->respondToAccessTokenRequest(
                    $factory->getPasswordGrant($request->get('email'), $request->get('password')),
                    $response
                );
            }
        );
    }

    public function refreshToken(
        RefreshTokenRequest $request, AuthRequestFactory $factory, AuthService $authService,
        Response $response
    )
    {
        return $authService->withErrorHandling(
            function() use ($request, $factory, $response) {
                return $this->server->respondToAccessTokenRequest(
                    $factory->getRefreshTokenGrant($request->get('refresh_token')),
                    $response
                );
            }
        );
    }
}
