<?php

namespace App\Entity;

use App\Repository\AccountRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: AccountRepository::class)]
#[ORM\Table(name: "accounts")]
class Account
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type:"integer")]
    private ?int $id = null;

    #[ORM\Column(type:"string", length: 255)]
    #[Assert\NotBlank(message: "Service name cannot be blank.")]
    #[Assert\Length(max: 255)]
    private string $serviceName;

    #[ORM\Column(type:"string", length: 255)]
    #[Assert\NotBlank(message: "Login cannot be blank.")]
    #[Assert\Length(max: 255)]
    private string $login;

    #[ORM\Column(type:"string", length: 255)]
    #[Assert\NotBlank(message: "Password cannot be blank.")]
    #[Assert\Length(min: 8, max: 255, minMessage: "Password must be at least {{ limit }} characters long.")]
    private string $password;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: "accounts")]
    #[ORM\JoinColumn(nullable: true)]
    private ?User $user = null;


    #[ORM\ManyToOne(targetEntity: AccountGroup::class, inversedBy: "accounts")]
    #[ORM\JoinColumn(nullable: true)]
    private ?AccountGroup $group;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getServiceName(): string
    {
        return $this->serviceName;
    }

    public function setServiceName(string $serviceName): self
    {
        $this->serviceName = $serviceName;
        return $this;
    }

    public function getLogin(): string
    {
        return $this->login;
    }

    public function setLogin(string $login): self
    {
        $this->login = $login;
        return $this;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    //Решил что буду хэшировать пароль перед его установкой
    public function setPassword(string $password, bool $hash = true): self
    {
        if ($hash) {
            $this->password = password_hash($password, PASSWORD_DEFAULT);
        } else {
            $this->password = $password;
        }
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

    public function getGroup(): ?AccountGroup
    {
        return $this->group;
    }

    public function setGroup(?AccountGroup $group): self
    {
        $this->group = $group;
        return $this;
    }
}
