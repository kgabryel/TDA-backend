<?php

namespace App\Services;

use App\Models\OauthClient;
use Psr\Http\Message\ServerRequestInterface;

class AuthRequestFactory
{
    private ServerRequestInterface $request;
    private int $clientId;
    private string $clientSecret;

    public function __construct(ServerRequestInterface $request)
    {
        $this->request = $request;
        $client = OauthClient::where('name', '=', env('OAUTH_PASSWORD_CLIENT'))
            ->firstOrFail();
        $this->clientId = $client->id;
        $this->clientSecret = $client->secret;
    }

    public function getPasswordGrant(string $username, string $password): ServerRequestInterface
    {
        return $this->request->withParsedBody(
            [
                'username' => $username,
                'password' => $password,
                'grant_type' => 'password',
                'client_id' => $this->clientId,
                'client_secret' => $this->clientSecret
            ]
        );
    }

    public function getRefreshTokenGrant(string $refreshToken): ServerRequestInterface
    {
        return $this->request->withParsedBody(
            [
                'refresh_token' => $refreshToken,
                'grant_type' => 'refresh_token',
                'client_id' => $this->clientId,
                'client_secret' => $this->clientSecret
            ]
        );
    }
}
