<?php

namespace App\UI\HTTP\Resource;

use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use App\Persistence\Entity\ExperienceLevel;
use App\UI\HTTP\OperationName;
use App\UI\HTTP\DataProvider\JobApplicationProvider;
use App\UI\HTTP\StateProcessor\JobApplicationCreationProcessor;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ApiResource(
    operations: [
        new Post(
            processor: JobApplicationCreationProcessor::class,
        ),
        new GetCollection(
            security: "is_granted('ROLE_ADMIN')",
            name: OperationName::GET,
        ),
        new GetCollection(
            uriTemplate: 'job_applications/new',
            description: 'Lists job applications that have not been displayed yet',
            security: "is_granted('ROLE_ADMIN')",
            name: OperationName::GET_NEW_JOB_APPLICATIONS,
        ),
        new Get(
            security: "is_granted('ROLE_ADMIN')",
        ),
    ],
    normalizationContext: ['groups' => ['read']],
    denormalizationContext: ['groups' => ['write']],
    provider: JobApplicationProvider::class,
)]
class JobApplication
{
    public function __construct(
        #[ApiProperty(identifier: true)]
        #[Groups('read')]
        public ?int $id = null,
        #[ApiProperty]
        #[Groups(['read', 'write'])]
        #[Assert\NotBlank]
        public ?string $firstName = null,
        #[ApiProperty]
        #[Groups(['read', 'write'])]
        #[Assert\NotBlank]
        public ?string $lastName = null,
        #[ApiProperty]
        #[Groups(['read', 'write'])]
        #[Assert\Email]
        public ?string $email = null,
        #[ApiProperty]
        #[Groups(['read', 'write'])]
        #[Assert\NotBlank]
        #[Assert\Regex(
            pattern: '/^\+?[0-9]{3}-?[0-9]{6,12}$/',
            message: 'Invalid format. Enter pattern: +48999000999',
        )]
        public ?string $phoneNumber = null,
        #[ApiProperty]
        #[Groups(['read', 'write'])]
        #[Assert\NotBlank]
        #[Assert\GreaterThan(value: 0)]
        public ?int $expectedSalary = null,
        #[ApiProperty]
        #[Groups(['read'])]
        public ?ExperienceLevel $estimatedExperienceLevel = null,
    ) {
    }
}
