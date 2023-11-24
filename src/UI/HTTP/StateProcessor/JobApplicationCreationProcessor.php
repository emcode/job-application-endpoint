<?php

namespace App\UI\HTTP\StateProcessor;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\Metadata\Post;
use ApiPlatform\State\ProcessorInterface;
use App\Persistence\Entity\ExperienceLevel;
use App\Persistence\Repository\JobApplicationRepository;
use App\UI\HTTP\Resource\JobApplication as JobApplicationResource;
use App\Persistence\Entity\JobApplication as JobApplicationEntity;
use InvalidArgumentException;

class JobApplicationCreationProcessor implements ProcessorInterface
{
    public function __construct(
        private readonly JobApplicationRepository $jobApplicationRepository,
    )
    {
    }

    public function process(
        mixed $data,
        Operation $operation,
        array $uriVariables = [],
        array $context = [],
    ): JobApplicationResource
    {
        if (!$operation instanceof Post) {
            throw new InvalidArgumentException(sprintf(
                "Unsupported operation received: %s",
                $operation::class,
            ));
        }

        if (!$data instanceof JobApplicationResource) {
            throw new InvalidArgumentException(sprintf(
                "Unsupported input data type received: %s",
                get_debug_type($data),
            ));
        }

        $entity = new JobApplicationEntity(
            $data->firstName,
            $data->lastName,
            $data->email,
            $data->phoneNumber,
            $data->expectedSalary,
            ExperienceLevel::estimateBaseOnSalary($data->expectedSalary),
        );

        $this->jobApplicationRepository->save($entity);
        return new JobApplicationResource(
            ... $entity->exposeDataForHttpApi()
        );
    }
}