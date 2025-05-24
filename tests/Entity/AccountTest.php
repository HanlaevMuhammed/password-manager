<?php

namespace App\Tests\Entity;

use App\Entity\Account;
use App\Entity\User;
use App\Entity\AccountGroup;
use PHPUnit\Framework\TestCase;

class AccountTest extends TestCase
{
    public function testGettersAndSetters(): void
    {
        $account = new Account();

        $serviceName = 'My Service';
        $login = 'mylogin';
        $password = 'secret1234';
        $user = new User();
        $group = new AccountGroup();

        $account->setServiceName($serviceName);
        $account->setLogin($login);
        $account->setPassword($password, false);
        $account->setUser($user);
        $account->setGroup($group);

        $this->assertEquals($serviceName, $account->getServiceName());
        $this->assertEquals($login, $account->getLogin());
        $this->assertEquals($password, $account->getPassword());
        $this->assertSame($user, $account->getUser());
        $this->assertSame($group, $account->getGroup());
    }

    public function testSetPasswordHashesByDefault(): void
    {
        $account = new Account();
        $rawPassword = 'mypassword';

        $account->setPassword($rawPassword);
        $hashedPassword = $account->getPassword();

        $this->assertNotEquals($rawPassword, $hashedPassword);
        $this->assertTrue(password_verify($rawPassword, $hashedPassword));
    }
}
