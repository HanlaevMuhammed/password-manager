<?php

namespace App\Tests\Entity;

use App\Entity\AccountGroup;
use App\Entity\User;
use PHPUnit\Framework\TestCase;

class AccountGroupTest extends TestCase
{
    public function testSetNameAndGetName()
    {
        $group = new AccountGroup();
        $group->setName('Рабочие аккаунты');
        $this->assertEquals('Рабочие аккаунты', $group->getName());
    }

    public function testSetUserAndGetUser()
    {
        $user = new User();
        $group = new AccountGroup();
        $group->setUser($user);
        $this->assertSame($user, $group->getUser());
    }

    public function testAddAndRemoveAccount()
    {
        $group = new AccountGroup();

        $account = $this->createMock(\App\Entity\Account::class);

        $calls = [];

        $account->expects($this->any())
            ->method('setGroup')
            ->willReturnCallback(function ($arg) use (&$calls, $account) {
                $calls[] = $arg;
                return $account;
            });

        $account->expects($this->any())
            ->method('getGroup')
            ->willReturn($group);

        $group->addAccount($account);
        $this->assertTrue($group->getAccounts()->contains($account));

        $this->assertContains($group, $calls);

        $group->removeAccount($account);
        $this->assertFalse($group->getAccounts()->contains($account));

        $this->assertContains(null, $calls);
    }


}
