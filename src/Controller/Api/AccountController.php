<?php

namespace App\Controller\Api;

use App\Entity\Account;
use App\Repository\AccountRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

#[Route('/api/account')]
final class AccountController extends AbstractController
{
    #[Route('/me', name: 'api_account_me', methods: ['GET'])]
    public function me(AccountRepository $accountRepository): JsonResponse
    {
        $user = $this->getUser();
        if (!$user) {
            throw new AccessDeniedException('Unauthorized');
        }

        $accounts = $accountRepository->findBy(['user' => $user]);

        $data = [];
        foreach ($accounts as $account) {
            $data[] = [
                'id' => $account->getId(),
                'serviceName' => $account->getServiceName(),
                'login' => $account->getLogin(),
            ];
        }

        return $this->json($data);
    }

    #[Route('', name: 'api_account_list', methods: ['GET'])]
    public function list(AccountRepository $accountRepository): JsonResponse
    {
        $user = $this->getUser();
        if (!$user) {
            throw new AccessDeniedException('Unauthorized');
        }

        $accounts = $accountRepository->findBy(['user' => $user]);

        $data = [];
        foreach ($accounts as $account) {
            $data[] = [
                'id' => $account->getId(),
                'serviceName' => $account->getServiceName(),
                'login' => $account->getLogin(),
            ];
        }

        return $this->json($data);
    }

    #[Route('/{id}', name: 'api_account_show', methods: ['GET'], requirements: ['id' => '\d+'])]
    public function show(int $id, AccountRepository $accountRepository): JsonResponse
    {
        $user = $this->getUser();
        if (!$user) {
            throw new AccessDeniedException('Unauthorized');
        }

        $account = $accountRepository->find($id);

        if (!$account || $account->getUser() !== $user) {
            return $this->json(['error' => 'Account not found or access denied'], 404);
        }

        return $this->json([
            'id' => $account->getId(),
            'serviceName' => $account->getServiceName(),
            'login' => $account->getLogin(),
            'password' => $account->getPassword(),
        ]);
    }

    #[Route('', name: 'api_account_create', methods: ['POST'])]
    public function create(Request $request, EntityManagerInterface $em): JsonResponse
    {
        $user = $this->getUser();
        if (!$user) {
            throw new AccessDeniedException('Unauthorized');
        }

        $data = json_decode($request->getContent(), true);
        if (!$data || empty($data['serviceName']) || empty($data['login']) || empty($data['password'])) {
            return $this->json(['error' => 'Missing data'], 400);
        }

        $account = new Account();
        $account->setUser($user);
        $account->setServiceName($data['serviceName']);
        $account->setLogin($data['login']);
        $account->setPassword($data['password']);

        $em->persist($account);
        $em->flush();

        return $this->json([
            'message' => 'Account created',
            'id' => $account->getId(),
        ], 201);
    }

    #[Route('/{id}', name: 'api_account_update', methods: ['PUT'], requirements: ['id' => '\d+'])]
    public function update(int $id, Request $request, AccountRepository $accountRepository, EntityManagerInterface $em): JsonResponse
    {
        $user = $this->getUser();
        if (!$user) {
            throw new AccessDeniedException('Unauthorized');
        }

        $account = $accountRepository->find($id);

        if (!$account || $account->getUser() !== $user) {
            return $this->json(['error' => 'Account not found or access denied'], 404);
        }

        $data = json_decode($request->getContent(), true);

        if (!$data) {
            return $this->json(['error' => 'Invalid JSON'], 400);
        }

        if (isset($data['serviceName'])) {
            $account->setServiceName($data['serviceName']);
        }
        if (isset($data['login'])) {
            $account->setLogin($data['login']);
        }
        if (isset($data['password'])) {
            $account->setPassword($data['password']);
        }

        $em->flush();

        return $this->json(['message' => 'Account updated']);
    }

    #[Route('/{id}', name: 'api_account_delete', methods: ['DELETE'], requirements: ['id' => '\d+'])]
    public function delete(int $id, AccountRepository $accountRepository, EntityManagerInterface $em): JsonResponse
    {
        $user = $this->getUser();
        if (!$user) {
            throw new AccessDeniedException('Unauthorized');
        }

        $account = $accountRepository->find($id);

        if (!$account || $account->getUser() !== $user) {
            return $this->json(['error' => 'Account not found or access denied'], 404);
        }

        $em->remove($account);
        $em->flush();

        return $this->json(null, 204);
    }
}
