<?php

namespace App\UI\HTTP\DataProvider;

use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\Persistence\Repository\JobApplicationRepository;
use App\UI\HTTP\OperationName;
use App\Persistence\Entity\JobApplication as JobApplicationEntity;
use App\UI\HTTP\Resource\JobApplication as JobApplicationResource;
use InvalidArgumentException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class JobApplicationProvider implements ProviderInterface
{
    public function __construct(
        private readonly JobApplicationRepository $jobApplicationRepository,
    )
    {
    }

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): object|array|null
    {
        if ($operation instanceof Get) {
            $id = (int) $uriVariables['id'];
            $entity = $this->jobApplicationRepository->find($id);
            if (!$entity) {
                throw new NotFoundHttpException(sprintf(
                    "Job application with id %s was not found in DB",
                    $id
                ));
            }
            return new JobApplicationResource(
                ... $entity->exposeDataForHttpApi()
            );
        }

        if ($operation instanceof GetCollection) {

            if (OperationName::GET_NEW_JOB_APPLICATIONS === $operation->getName()) {
                // TODO: support for pagination / sorting / filtering
                $unseenIds = $this->jobApplicationRepository->findIdsNotDisplayedYet();
                $this->jobApplicationRepository->markIdsAsAlreadyDisplayed($unseenIds);

                return array_map(
                    fn(JobApplicationEntity $e) => new JobApplicationResource(... $e->exposeDataForHttpApi()),
                    $this->jobApplicationRepository->findBy(['id' => $unseenIds]),
                );
            }

            if (OperationName::GET === $operation->getName()) {
                // TODO: support for pagination / sorting / filtering
                return array_map(
                    fn(JobApplicationEntity $e) => new JobApplicationResource(... $e->exposeDataForHttpApi()),
                    $this->jobApplicationRepository->findAll(),
                );
            }
        }

        throw new InvalidArgumentException(sprintf(
            "Unsupported operation received %s with type: %s",
            $operation->getName(),
            $operation::class,
        ));
    }
}
