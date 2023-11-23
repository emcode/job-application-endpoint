<?php

namespace App\Tests\Functional\UI\HTTP;

use App\Tests\Util\UserGenerator;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class AuthenticationEndpointTest extends WebTestCase
{
    public function testInvalidCredentialsResultIn401WithErrorMessage(): void
    {
        $client = static::createClient();
        $requestData = [
            'username' => 'non-existing-user',
            'password' => 'secret',
        ];
        $client->jsonRequest(
            Request::METHOD_POST,
            '/api/login',
            $requestData,
        );
        $this->assertResponseStatusCodeSame(Response::HTTP_UNAUTHORIZED);

        // TODO: find out nicer way to decode the json response (helpers from api platform?)
        $responseData = json_decode(
            $client->getResponse()->getContent(),
            true,
            flags: JSON_THROW_ON_ERROR
        );

        $this->assertArrayHasKey('error', $responseData);
        $this->assertEquals('Invalid credentials.', $responseData['error']);
    }

    public function testValidCredentialsResultInGeneratingAccessToken(): void
    {
        $client = static::createClient();
        /** @var UserGenerator $userGenerator */
        $userGenerator = $client->getContainer()->get(UserGenerator::class);
        $user = $userGenerator->aUserWithPassword('secret');
        $requestData = [
            'username' => $user->getUserIdentifier(),
            'password' => 'secret',
        ];
        $client->jsonRequest(
            Request::METHOD_POST,
            '/api/login',
            $requestData,
        );
        $this->assertResponseIsSuccessful();

        // TODO: find out nicer way to decode the json response (helpers from api platform?)
        $responseData = json_decode(
            $client->getResponse()->getContent(),
            true,
            flags: JSON_THROW_ON_ERROR
        );

        $this->assertArrayHasKey('username', $responseData);
        $this->assertArrayHasKey('token', $responseData);
        $this->assertEquals(
            $user->getUserIdentifier(),
            $responseData['username'],
        );
        $this->assertNotEmpty(
            $responseData['token'],
        );
    }
}