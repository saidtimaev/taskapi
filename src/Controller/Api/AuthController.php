<?php

namespace  App\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

final class AuthController extends AbstractController
{
    #[Route('/api/register', methods: ['POST'])]
    public function register(): JsonResponse
    {
        // Логика регистрации пользователя
        return new JsonResponse(['message' => 'User registered successfully']);
    }
}
