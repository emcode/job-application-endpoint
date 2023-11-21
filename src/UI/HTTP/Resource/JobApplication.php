<?php

namespace App\UI\HTTP\Resource;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use App\UI\HTTP\ResourceProvider\JobApplicationProvider;

#[ApiResource(
    operations: [
        new Post(),
        new Get(
            security: "is_granted('ROLE_ADMIN')",
        ),
        new GetCollection(
            security: "is_granted('ROLE_ADMIN')",
        ),
        new GetCollection(
            uriTemplate: 'job_applications/new',
            description: 'Lists job applications that have not been displayed yet',
            security: "is_granted('ROLE_ADMIN')",
            name: 'new',
        ),
    ],
    provider: JobApplicationProvider::class
)]
class JobApplication
{
    #[ApiProperty(identifier: true)]
    private ?int $id = null;

    #[ApiProperty]
    private ?string $firstName = null;

    #[ApiProperty]
    private ?string $lastName = null;

    #[ApiProperty]
    private ?string $email = null;

    #[ApiProperty]
    private ?string $phoneNumber = null;

    #[ApiProperty]
    private ?int $expectedSalary = null;

}
