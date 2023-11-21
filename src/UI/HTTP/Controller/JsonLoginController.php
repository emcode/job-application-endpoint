<?php

namespace App\UI\HTTP\Controller;


use App\Persistence\Entity\User;
use App\Security\PasetoHelper;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

#[AsController]
class JsonLoginController extends AbstractController
{
    #[Route('/api/login', name: 'api_json_login')]
    public function onSuccess(#[CurrentUser] ?User $user, PasetoHelper $pasetoHelper): Response
    {
        if (null === $user) {
            return $this->json([
                   'message' => 'missing credentials',
             ], Response::HTTP_UNAUTHORIZED);
       }

        $token = $pasetoHelper->createAccessTokenBuilder()
                              ->setSubject($user->getUserIdentifier())
                              ->toString();

        return $this->json([
            'username' => $user->getUserIdentifier(),
            'token' => $token,
        ]);
    }
}