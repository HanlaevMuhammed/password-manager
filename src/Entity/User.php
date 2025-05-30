<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

#[ORM\Entity]
#[ORM\Table(name: "users")]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    private ?int $id = null;

    #[ORM\Column(type: "string", length: 180, unique: true)]
    private ?string $email = null;

    #[ORM\Column(type: "string")]
    private ?string $password = null;

    #[ORM\Column(type: "json")]
    private array $roles = [];

    #[ORM\OneToMany(mappedBy: "user", targetEntity: AccountGroup::class, cascade: ["remove"])]
    private Collection $accountGroups;

    #[ORM\OneToMany(mappedBy: "user", targetEntity: Account::class, cascade: ["remove"])]
    private Collection $accounts;

    public function __construct()
    {
        $this->accountGroups = new ArrayCollection();
        $this->accounts = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;
        return $this;
    }

    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    public function getUsername(): string
    {
        return $this->getUserIdentifier();
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;
        return $this;
    }

    public function getRoles(): array
    {
        $roles = $this->roles;
        if (empty($roles)) {
            $roles[] = 'ROLE_USER';
        }
        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;
        return $this;
    }

    public function eraseCredentials(): void{}

    /**
     * @return Collection|AccountGroup[]
     */
    public function getAccountGroups(): Collection
    {
        return $this->accountGroups;
    }

    public function addAccountGroup(AccountGroup $accountGroup): self
    {
        if (!$this->accountGroups->contains($accountGroup)) {
            $this->accountGroups[] = $accountGroup;
            $accountGroup->setUser($this);
        }
        return $this;
    }

    public function removeAccountGroup(AccountGroup $accountGroup): self
    {
        if ($this->accountGroups->removeElement($accountGroup)) {
            if ($accountGroup->getUser() === $this) {
                $accountGroup->setUser(null);
            }
        }
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
            $account->setUser($this);
        }
        return $this;
    }

    public function removeAccount(Account $account): self
    {
        if ($this->accounts->removeElement($account)) {
            if ($account->getUser() === $this) {
                $account->setUser(null);
            }
        }
        return $this;
    }
}
