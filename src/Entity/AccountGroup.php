<?php

namespace App\Entity;

use App\Repository\AccountGroupRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AccountGroupRepository::class)]
#[ORM\Table(name: "account_groups")]
class AccountGroup
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type:"integer")]
    private ?int $id = null;

    #[ORM\Column(type:"string", length: 255)]
    private string $name;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: "accountGroups")]
    #[ORM\JoinColumn(nullable: true)]
    private ?User $user = null;


    #[ORM\OneToMany(mappedBy: "group", targetEntity: Account::class, cascade:["remove"])]
    private Collection $accounts;

    public function __construct()
    {
        $this->accounts = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;
        return $this;
    }

    /**
     * @return Collection|Account[]
     */
    public function getAccounts(): Collection
    {
        return $this->accounts;
    }

    public function addAccount(Account $account): self
    {
        if (!$this->accounts->contains($account)) {
            $this->accounts[] = $account;
            $account->setGroup($this);
        }
        return $this;
    }

    public function removeAccount(Account $account): self
    {
        if ($this->accounts->removeElement($account)) {
            if ($account->getGroup() === $this) {
                $account->setGroup(null);
            }
        }
        return $this;
    }
}
