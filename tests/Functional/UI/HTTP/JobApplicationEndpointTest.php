<?php

namespace App\Tests\Functional\UI\HTTP;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use App\Persistence\Entity\ExperienceLevel;
use App\Tests\Util\JobApplicationGenerator;
use App\Tests\Util\UserGenerator;
use Symfony\Component\HttpFoundation\Request;

class JobApplicationEndpointTest extends ApiTestCase
{
    public function testJobApplicationCanBeSavedByAnonUser(): void
    {
        $client = static::createClient();
        $response = $client->request(
            Request::METHOD_POST,
            '/api/job_applications',
            [
                'headers' => [
                    'Content-Type' => 'application/ld+json',
                    'Accept' => 'application/ld+json',
                ],
                'json' => [
                    "firstName" => "John",
                    "lastName" => "Smith",
                    "email" => "hello@example.com",
                    "phoneNumber" => "+48999000999",
                    "expectedSalary" => 10_000_00
                ],
            ],
        );
        $this->assertResponseIsSuccessful();
        $responseData = $response->toArray();

        // TODO: use sth better to assert the shape of response (JSON schema?)

        $this->assertArrayHasKey('@context', $responseData);
        $this->assertArrayHasKey('@id', $responseData);
        $this->assertArrayHasKey('@type', $responseData);
        $this->assertArrayHasKey('id', $responseData);
        $this->assertArrayHasKey('firstName', $responseData);
        $this->assertArrayHasKey('lastName', $responseData);
        $this->assertArrayHasKey('estimatedExperienceLevel', $responseData);

        $this->assertEquals(ExperienceLevel::Senior->value, $responseData['estimatedExperienceLevel']);
        $this->assertEquals('John', $responseData['firstName']);
        $this->assertEquals('Smith', $responseData['lastName']);
        $this->assertEquals('hello@example.com', $responseData['email']);
    }

    public function testJobApplicationsListCanBeLoadedByAdminUser(): void
    {
        $client = static::createClient();
        /** @var UserGenerator $userGenerator */
        $userGenerator = $client->getContainer()->get(UserGenerator::class);
        $user = $userGenerator->aUser();
        $client->loginUser($user);
        $response = $client->request(
            Request::METHOD_GET,
            '/api/job_applications',
        );
        $this->assertResponseIsSuccessful();
        $responseData = $response->toArray();

        // TODO: use sth better to assert the shape of response (JSON schema?)

        $this->assertArrayHasKey('@context', $responseData);
        $this->assertArrayHasKey('@id', $responseData);
        $this->assertArrayHasKey('@type', $responseData);
        $this->assertArrayHasKey('hydra:totalItems', $responseData);
        $this->assertArrayHasKey('hydra:member', $responseData);
    }

    public function testListOfNewJobApplicationChangesAfterLoadingByAdminUser(): void
    {
        $this->markTestIncomplete("Implement test for /api/job_applications/new");
        // TODO: write test that checks whether how behaves (applications
        //       disappear from results of this specific endpoint)
    }

    public function testSingularJobApplicationCanLoadedByAdminUser(): void
    {
        $client = static::createClient();
        $container = $client->getContainer();
        /** @var JobApplicationGenerator $jobAppGenerator */
        $jobAppGenerator = $container->get(JobApplicationGenerator::class);
        $jobApp = $jobAppGenerator->aJobApplication();
        /** @var UserGenerator $userGenerator */
        $userGenerator = $container->get(UserGenerator::class);
        $user = $userGenerator->aUser();

        $client->loginUser($user);
        $response = $client->request(
            Request::METHOD_GET,
            sprintf('/api/job_applications/%s', $jobApp->getId()),
        );
        $this->assertResponseIsSuccessful();
        $responseData = $response->toArray();

        $expectedId = $jobApp->getId();
        $this->assertEquals(
            $expectedId,
            $responseData['id']
        );

        // TODO: use sth better to assert the shape of response (JSON schema?)

        $actualData = $jobApp->exposeDataForHttpApi();
        $this->assertEquals($actualData['firstName'], $responseData['firstName']);
        $this->assertEquals($actualData['lastName'], $responseData['lastName']);
        $this->assertEquals($actualData['email'], $responseData['email']);
        $this->assertEquals($actualData['expectedSalary'], $responseData['expectedSalary']);
        $this->assertEquals($actualData['estimatedExperienceLevel']->value, $responseData['estimatedExperienceLevel']);
    }
}