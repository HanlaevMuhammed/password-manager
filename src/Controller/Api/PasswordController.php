<?php

namespace App\Controller\Api;

use App\Service\PasswordGenerator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class PasswordController extends AbstractController
{
    #[Route('/api/password/generate', methods: ['GET'])]
    public function generate(Request $request, PasswordGenerator $generator): JsonResponse
    {
        $length = (int) $request->query->get('length', 12);
        $useNumbers = filter_var($request->query->get('numbers', 'true'), FILTER_VALIDATE_BOOLEAN);
        $useSymbols = filter_var($request->query->get('symbols', 'true'), FILTER_VALIDATE_BOOLEAN);

        // Я ограничил длину чтобы избежать перегрузок
        if ($length < 4) {
            $length = 4;
        } elseif ($length > 64) {
            $length = 64;
        }

        $password = $generator->generate($length, $useNumbers, $useSymbols);

        return $this->json(['password' => $password]);
    }
}
