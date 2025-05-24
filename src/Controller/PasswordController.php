<?php

namespace App\Controller;

use App\Entity\Password;
use App\Entity\User;
use App\Repository\PasswordRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/api/passwords')]
class PasswordController extends AbstractController
{
    #[Route('', name: 'get_passwords', methods: ['GET'])]
    public function index(PasswordRepository $repository): JsonResponse
    {
        $passwords = $repository->findAll();

        $data = array_map(fn($p) => [
            'id' => $p->getId(),
            'title' => $p->getTitle(),
            'login' => $p->getLogin(),
            'password' => $p->getPassword(),
            'createdAt' => $p->getCreatedAt()->format('Y-m-d H:i:s'),
        ], $passwords);

        return $this->json($data);
    }

    #[Route('/{id}', name: 'get_password', methods: ['GET'])]
    public function show(Password $password): JsonResponse
    {
        return $this->json([
            'id' => $password->getId(),
            'title' => $password->getTitle(),
            'login' => $password->getLogin(),
            'password' => $password->getPassword(),
            'createdAt' => $password->getCreatedAt()->format('Y-m-d H:i:s'),
        ]);
    }

    #[Route('', name: 'create_password', methods: ['POST'])]
    public function create(Request $request, EntityManagerInterface $em): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $password = new Password();
        $password->setTitle($data['title']);
        $password->setLogin($data['login']);
        $password->setPassword($data['password']);

        // 💡 Пока без аутентификации — просто берём первого пользователя
        $user = $em->getRepository(User::class)->find(1);
        $password->setUser($user);

        $em->persist($password);
        $em->flush();

        return $this->json(['status' => 'Password created'], 201);
    }

    #[Route('/{id}', name: 'update_password', methods: ['PUT'])]
    public function update(Request $request, Password $password, EntityManagerInterface $em): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $password->setTitle($data['title'] ?? $password->getTitle());
        $password->setLogin($data['login'] ?? $password->getLogin());
        $password->setPassword($data['password'] ?? $password->getPassword());

        $em->flush();

        return $this->json(['status' => 'Password updated']);
    }

    #[Route('/{id}', name: 'delete_password', methods: ['DELETE'])]
    public function delete(Password $password, EntityManagerInterface $em): JsonResponse
    {
        $em->remove($password);
        $em->flush();

        return $this->json(['status' => 'Password deleted']);
    }
}
