<?php

namespace App\Tests\Util;

use App\Persistence\Entity\User;
use App\Persistence\Repository\UserRepository;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserGenerator
{
    public function __construct(
        private readonly UserRepository $userRepository,
        private readonly UserPasswordHasherInterface $userPasswordHasher,
    ) {
    }

    public function aUser(): User
    {
        $user = (new UserBuilder())->build();
        $this->userRepository->save($user);
        return $user;
    }

    public function aUserWithPassword(string $password): User
    {
        $encodedPassword = $this->userPasswordHasher->hashPassword(
            User::createExample(),
            $password,
        );
        $user = (new UserBuilder(password: $encodedPassword))->build();
        $this->userRepository->save($user);
        return $user;
    }
}