<?php

namespace App\Controller\Api;

use App\Entity\AccountGroup;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

#[Route('/api/account/group', name: 'api_account_group_')]
final class AccountGroupController extends AbstractController
{
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    #[Route('', name: 'index', methods: ['GET'])]
    public function index(): JsonResponse
    {
        $user = $this->getUser();
        if (!$user) {
            throw new AccessDeniedException('Unauthorized');
        }

        $groups = $this->em->getRepository(AccountGroup::class)
            ->findBy(['user' => $user]);

        $data = array_map(fn(AccountGroup $group) => [
            'id' => $group->getId(),
            'name' => $group->getName(),
        ], $groups);

        return $this->json($data);
    }

    #[Route('/{id}', name: 'show', methods: ['GET'])]
    public function show(int $id): JsonResponse
    {
        $user = $this->getUser();
        if (!$user) {
            throw new AccessDeniedException('Unauthorized');
        }

        $group = $this->em->getRepository(AccountGroup::class)->find($id);

        if (!$group || $group->getUser()->getId() !== $user->getId()) {
            return $this->json(['error' => 'Group not found'], 404);
        }

        return $this->json([
            'id' => $group->getId(),
            'name' => $group->getName(),
        ]);
    }

    #[Route('', name: 'create', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        $user = $this->getUser();
        if (!$user) {
            throw new AccessDeniedException('Unauthorized');
        }

        $data = json_decode($request->getContent(), true);
        if (empty($data['name'])) {
            return $this->json(['error' => 'Name is required'], 400);
        }

        $group = new AccountGroup();
        $group->setName($data['name']);
        $group->setUser($user);

        $this->em->persist($group);
        $this->em->flush();

        return $this->json([
            'id' => $group->getId(),
            'name' => $group->getName(),
        ], 201);
    }

    #[Route('/{id}', name: 'update', methods: ['PUT', 'PATCH'])]
    public function update(int $id, Request $request): JsonResponse
    {
        $user = $this->getUser();
        if (!$user) {
            throw new AccessDeniedException('Unauthorized');
        }

        $group = $this->em->getRepository(AccountGroup::class)->find($id);

        if (!$group || $group->getUser()->getId() !== $user->getId()) {
            return $this->json(['error' => 'Group not found'], 404);
        }

        $data = json_decode($request->getContent(), true);
        if (!empty($data['name'])) {
            $group->setName($data['name']);
        }

        $this->em->flush();

        return $this->json([
            'id' => $group->getId(),
            'name' => $group->getName(),
        ]);
    }

    #[Route('/{id}', name: 'delete', methods: ['DELETE'])]
    public function delete(int $id): JsonResponse
    {
        $user = $this->getUser();
        if (!$user) {
            throw new AccessDeniedException('Unauthorized');
        }

        $group = $this->em->getRepository(AccountGroup::class)->find($id);

        if (!$group || $group->getUser()->getId() !== $user->getId()) {
            return $this->json(['error' => 'Group not found'], 404);
        }

        $this->em->remove($group);
        $this->em->flush();

        return $this->json(null, 204);
    }
}
