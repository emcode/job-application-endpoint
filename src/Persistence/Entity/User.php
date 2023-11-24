<?php

namespace App\Persistence\Entity;

use App\Persistence\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Webmozart\Assert\Assert;
use SensitiveParameter;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: 'app_user')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    public function __construct(
        #[ORM\Column(length: 64, unique: true)]
        public string $username,
        #[SensitiveParameter]
        #[ORM\Column(length: 255)]
        public string $password,
    )
    {
        Assert::notEmpty($this->username, 'Username field cannot be empty');
        Assert::notEmpty($this->password, 'Password field cannot be empty');
    }

    public function getRoles(): array
    {
        return ['ROLE_ADMIN'];
    }

    public function eraseCredentials(): void
    {
    }

    public function getUserIdentifier(): string
    {
        return $this->username;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public static function createExample(
        string $username = 'example',
        string $password = 'example',
    ): User
    {
        return new User(
            $username,
            $password,
        );
    }
}
