<?php

namespace App\Tests\Util;

use App\Persistence\Entity\ExperienceLevel;
use App\Persistence\Entity\JobApplication;
use Faker\Factory as Faker;

class JobApplicationBuilder
{
    private string $firstName;
    private string $lastName;
    private string $email;
    private string $phoneNumber;
    private int $expectedSalary;
    private ExperienceLevel $experienceLevel;

    public function __construct() {
        $faker = Faker::create();
        $this->firstName = $faker->firstName();
        $this->lastName = $faker->lastName();
        $this->email = $faker->email();
        // TODO: generate phone number in valid shape randomly
        $this->phoneNumber = '+48999000999';
        $this->expectedSalary = $faker->numberBetween(1, 15_000_00);
        $this->experienceLevel = ExperienceLevel::estimateBaseOnSalary($this->expectedSalary);
    }

    public function build(): JobApplication
    {
        return new JobApplication(
            $this->firstName,
            $this->lastName,
            $this->email,
            $this->phoneNumber,
            $this->expectedSalary,
            $this->experienceLevel,
        );
    }
}