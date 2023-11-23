<?php

namespace App\Tests\Util;

use App\Persistence\Entity\JobApplication;
use App\Persistence\Repository\JobApplicationRepository;

class JobApplicationGenerator
{
    public function __construct(
        private readonly JobApplicationRepository $jobApplicationRepository,
    ) {
    }

    public function aJobApplication(): JobApplication
    {
        $jobApplication = (new JobApplicationBuilder())->build();
        $this->jobApplicationRepository->save($jobApplication);
        return $jobApplication;
    }
}