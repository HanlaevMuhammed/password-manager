<?php

namespace App\Repository;

use App\Entity\AccountGroup;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<AccountGroup>
 */
class AccountGroupRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AccountGroup::class);
    }

    // Здесь можно добавлять свои методы выборки, если нужно
}
