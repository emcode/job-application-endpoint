<?php

namespace App\Security;

use ParagonIE\Paseto\Exception\PasetoException;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;
use Symfony\Component\Security\Http\AccessToken\AccessTokenHandlerInterface;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;

readonly class AccessTokenHandler implements AccessTokenHandlerInterface
{
    public function __construct(
        private PasetoHelper $pasetoHelper,
    ) {
    }

    public function getUserBadgeFrom(string $accessToken): UserBadge
    {
        $parser = $this->pasetoHelper->createAccessTokenParser();
        try {
            $token = $parser->parse($accessToken);
        } catch (PasetoException $ex) {
            throw new BadCredentialsException('Invalid credentials.', previous: $ex);
        }
        return new UserBadge($token->getSubject());
    }
}