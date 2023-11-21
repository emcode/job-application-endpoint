<?php

namespace App\Persistence\Entity;

use App\Persistence\Repository\JobApplicationRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: JobApplicationRepository::class)]
class JobApplication
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 64)]
    private ?string $firstName = null;

    #[ORM\Column(length: 64)]
    private ?string $lastName = null;

    #[ORM\Column(length: 255)]
    private ?string $email = null;

    #[ORM\Column(length: 12)]
    private ?string $phoneNumber = null;

    #[ORM\Column]
    private ?int $expectedSalary = null;

    #[ORM\Column]
    private ?ExperienceLevel $estimatedExperienceLevel = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $firstDisplayDateTime = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $created = null;

}
