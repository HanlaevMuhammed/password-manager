<?php

namespace App\Tests\Entity;

use App\Entity\User;
use App\Entity\Account;
use App\Entity\AccountGroup;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    public function testEmailAndPassword(): void
    {
        $user = new User();

        $email = 'user@example.com';
        $password = 'secret';

        $user->setEmail($email);
        $user->setPassword($password);

        $this->assertEquals($email, $user->getEmail());
        $this->assertEquals($email, $user->getUserIdentifier());
        $this->assertEquals($email, $user->getUsername());
        $this->assertEquals($password, $user->getPassword());
    }

    public function testRoles(): void
    {
        $user = new User();

        
        $this->assertContains('ROLE_USER', $user->getRoles());

        $user->setRoles(['ROLE_ADMIN']);
        $roles = $user->getRoles();

        $this->assertContains('ROLE_ADMIN', $roles);
        $this->assertNotContains('ROLE_USER', $roles);
    }

    public function testAccountGroupsCollection(): void
    {
        $user = new User();
        $group = new AccountGroup();

        $this->assertCount(0, $user->getAccountGroups());

        $user->addAccountGroup($group);
        $this->assertCount(1, $user->getAccountGroups());
        $this->assertSame($user, $group->getUser());

        $user->removeAccountGroup($group);
        $this->assertCount(0, $user->getAccountGroups());
        $this->assertNull($group->getUser());
    }

    public function testAccountsCollection(): void
    {
        $user = new User();
        $account = new Account();

        $this->assertCount(0, $user->getAccounts());

        $user->addAccount($account);
        $this->assertCount(1, $user->getAccounts());
        $this->assertSame($user, $account->getUser());

        $user->removeAccount($account);
        $this->assertCount(0, $user->getAccounts());
        $this->assertNull($account->getUser());
    }
}
