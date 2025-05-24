<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use App\Entity\User;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class PasswordControllerTest extends WebTestCase
{
    private \Symfony\Bundle\FrameworkBundle\KernelBrowser $client;
    private ?string $jwtToken = null;

    protected function setUp(): void
    {
        parent::setUp();

        $this->client = static::createClient();

        $container = static::getContainer();
        $entityManager = $container->get('doctrine')->getManager();
        $passwordHasher = $container->get(UserPasswordHasherInterface::class);

        $userRepo = $entityManager->getRepository(User::class);
        $user = $userRepo->findOneBy(['email' => 'test@example.com']);

        if (!$user) {
            $user = new User();
            $user->setEmail('test@example.com');
        }

        $hashedPassword = $passwordHasher->hashPassword($user, 'testpassword');
        $user->setPassword($hashedPassword);

        $entityManager->persist($user);
        $entityManager->flush();

        $this->client->request(
            'POST',
            '/api/login',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode([
                'email' => 'test@example.com',
                'password' => 'testpassword',
            ])
        );

        $response = $this->client->getResponse();
        $this->assertResponseIsSuccessful();

        $data = json_decode($response->getContent(), true);
        $this->assertArrayHasKey('token', $data);

        $this->jwtToken = $data['token'];
    }

    public function testGeneratePasswordDefault(): void
    {
        $this->client->request(
            'GET',
            '/api/password/generate?length=12&numbers=true&symbols=true',
            [],
            [],
            [
                'HTTP_Authorization' => 'Bearer ' . $this->jwtToken,
            ]
        );

        $this->assertResponseIsSuccessful();

        $response = json_decode($this->client->getResponse()->getContent(), true);

        $this->assertArrayHasKey('password', $response);
        $this->assertEquals(12, strlen($response['password']));
    }
}
