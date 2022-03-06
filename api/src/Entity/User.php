<?php

declare(strict_types=1);

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiProperty;
use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Doctrine\UuidGenerator;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\SerializedName;
use Ramsey\Uuid\UuidInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Controller\UserRegistration;

/**
 * @ORM\Entity
 * @ORM\Table(name="`user`")
 * 
 */
#[ApiResource(
    normalizationContext: ['groups' => ['user:read']],
    denormalizationContext: ['groups' => ['user:write']],
    collectionOperations: [
        "get" => ["security" => "is_granted('ROLE_ADMIN')"],
        "post" => ["security" => "is_granted('ROLE_ADMIN')"],
        'register' => [
            'method' => 'POST',
            'path' => '/users/register',
            'controller' => UserRegistration::class,
        ],
    ],
    itemOperations: [
        "get" => ["security" => "is_granted('ROLE_ADMIN') or object.getEmail() == user.getEmail()"],
        "put" => ["security" => "is_granted('ROLE_ADMIN') or object.getEmail() == user.getEmail()"],
    ],
)]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{

    /**
     * @var \Ramsey\Uuid\UuidInterface
     *
     * @ORM\Id
     * @ORM\Column(type="uuid", unique=true)
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\CustomIdGenerator(class=UuidGenerator::class)
     * @Groups("user:read")
     */
    private ?UuidInterface $id = null;


    /**
     * @ORM\Column(type="string", unique=true)
     * @Groups({"user:read", "user:write"})
     */
    private ?string $email = null;

    /**
     * The hashed password.
     *
     * @ORM\Column(type="string")
     */
    private ?string $password = null;

    /**
     * @Groups("user:write")
     * @SerializedName("password")
     */
    private $plainPassword;

    /**
     * Property viewable and writable only by users with ROLE_ADMIN 
     * @ORM\Column(type="json")
     * @Groups({"user:read", "user:write"})
     */
    #[ApiProperty(security: "is_granted('ROLE_ADMIN')")]
    private array $roles = [];

    public function getId(): ?UuidInterface
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    /**
     * {@inheritdoc}
     */
    public function eraseCredentials(): void
    {
        // if you store any temporary, sensitive data on the user, clear it here
        $this->plainPassword = null;
    }

    /**
     * {@inheritdoc}
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    /**
     * @return array<int, string>
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return \array_unique($roles);
    }

    /**
     * @param array<int, string> $roles
     */
    public function setRoles(array $roles): void
    {
        $this->roles = $roles;
    }

    /**
     * {@inheritdoc}
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    public function getPlainPassword(): ?string
    {
        return $this->plainPassword;
    }
    public function setPlainPassword(string $plainPassword): self
    {
        $this->plainPassword = $plainPassword;
        return $this;
    }
}
