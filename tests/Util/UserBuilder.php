<?php

namespace App\Tests\Util;

use App\Persistence\Entity\User;
use Faker\Factory as Faker;
class UserBuilder
{
    public function __construct(
        private ?string $username = null,
        private ?string $password = null,
    ) {
        $faker = Faker::create();
        $this->username = $username ?? $faker->userName();
        $this->password = $password ?? $faker->password();
    }

    public function build(): User
    {
        return new User(
            $this->username,
            $this->password,
        );
    }
}