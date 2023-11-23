<?php

namespace App\Persistence\Entity;

use App\Persistence\Repository\JobApplicationRepository;
use Doctrine\ORM\Mapping as ORM;
use Webmozart\Assert\Assert;

#[ORM\Entity(repositoryClass: JobApplicationRepository::class)]
class JobApplication
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    public function __construct(
        #[ORM\Column(length: 64)]
        private string $firstName,
        #[ORM\Column(length: 64)]
        private string $lastName,
        #[ORM\Column(length: 255)]
        private string $email,
        #[ORM\Column(length: 12)]
        private string $phoneNumber,
        #[ORM\Column]
        private int $expectedSalary,
        #[ORM\Column]
        private ExperienceLevel $estimatedExperienceLevel,
        #[ORM\Column(nullable: true)]
        private ?\DateTimeImmutable $firstDisplayDateTime = null,
        #[ORM\Column]
        private \DateTimeImmutable $created = new \DateTimeImmutable()
    ) {
        Assert::notEmpty($this->firstName);
        Assert::maxLength($this->firstName, 64);
        Assert::notEmpty($this->lastName);
        Assert::maxLength($this->lastName, 64);
        Assert::notEmpty($this->email);
        Assert::notEmpty($this->phoneNumber);
        Assert::notEmpty($this->expectedSalary);
        Assert::greaterThan($this->expectedSalary, 0);
    }

    /**
     * @return array<string,mixed>
     */
    public function exposeDataForHttpApi(): array
    {
        return [
            'id' => $this->id,
            'firstName' => $this->firstName,
            'lastName' => $this->lastName,
            'email' => $this->email,
            'phoneNumber' => $this->phoneNumber,
            'expectedSalary' => $this->expectedSalary,
            'estimatedExperienceLevel' => $this->estimatedExperienceLevel,
        ];
    }

    public function getId(): ?int
    {
        return $this->id;
    }
}
