<?php

namespace App\Services;

use League\OAuth2\Server\Exception\OAuthServerException;

class AuthService
{
    public function withErrorHandling(\Closure $callback)
    {
        try {
            return $callback();
        } catch (OAuthServerException $e) {
            return response()->json(
                [
                    'message' => $e->getMessage()
                ]
            )->setStatusCode(401);
        }
    }
}
