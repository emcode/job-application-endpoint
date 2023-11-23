<?php

namespace App\Security;

use ParagonIE\Paseto\Builder;
use ParagonIE\Paseto\Keys\Version4\AsymmetricSecretKey;
use ParagonIE\Paseto\Keys\Version4\SymmetricKey;
use ParagonIE\Paseto\Keys\Base\SymmetricKey as BaseSymmetricKey;
use ParagonIE\Paseto\Parser;
use ParagonIE\Paseto\Protocol\Version4;
use ParagonIE\Paseto\ProtocolCollection;
use ParagonIE\Paseto\ProtocolInterface;
use ParagonIE\Paseto\Purpose;
use ParagonIE\Paseto\Rules\IssuedBy;
use ParagonIE\Paseto\Rules\ValidAt;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use SensitiveParameter;
use DateTimeImmutable;
use DateInterval;
use Webmozart\Assert\Assert;

readonly class PasetoHelper
{
    public function __construct(
        #[SensitiveParameter]
        #[Autowire('%env(ACCESS_TOKEN_SIGNING_KEY)%')]
        private string $accessTokenSigningKey,
        #[Autowire('%env(ACCESS_TOKEN_ISSUER)%')]
        private string $accessTokenIssuer,
    ) {
        Assert::notEmpty(
            $this->accessTokenIssuer,
            'Please setup ACCESS_TOKEN_ISSUER to non empty value',
        );
        Assert::notEmpty(
            $this->accessTokenSigningKey,
            'Please setup ACCESS_TOKEN_SIGNING_KEY to non empty value',
        );
    }

    public function createAccessTokenParser(): Parser
    {
        return (new Parser())
            ->setKey($this->getAccessTokenSigningKey()->getPublicKey())
            ->addRule(new ValidAt())
            ->addRule(new IssuedBy($this->accessTokenIssuer))
            ->setPurpose(Purpose::public())
            ->setAllowedVersions(new ProtocolCollection($this->version()))
        ;
    }

    public function createAccessTokenBuilder(): Builder
    {
        return (new Builder())
            ->setKey($this->getAccessTokenSigningKey())
            ->setVersion($this->version())
            ->setPurpose(Purpose::public())
            ->setIssuedAt()
            ->setNotBefore()
            ->setExpiration(
                (new DateTimeImmutable())->add(new DateInterval('P01D'))
            )
            ->setIssuer($this->accessTokenIssuer)
            ->setClaims([]);
    }

    public function getAccessTokenSigningKey(): AsymmetricSecretKey
    {
        return AsymmetricSecretKey::fromEncodedString($this->accessTokenSigningKey, $this->version());
    }

    public function generateRandomAccessTokenSigningKey(): string
    {
        return AsymmetricSecretKey::generate($this->version())->encode();
    }

    private function version(): ProtocolInterface
    {
        return new Version4();
    }
}