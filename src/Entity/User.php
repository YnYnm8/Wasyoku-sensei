<?php

namespace App\Entity;

use App\Repository\UserRepository;
use App\Enum\RoleEnum;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_EMAIL', fields: ['email'])]
#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180)]
    private ?string $email = null;

    #[ORM\Column(length: 20, enumType: RoleEnum::class)]
    private RoleEnum $role = RoleEnum::USER;

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column(length: 255)]
    private ?string $username = null;

    /**
     * @var Collection<int, Favorite>
     */
    #[ORM\OneToMany(targetEntity: Favorite::class, mappedBy: 'user', orphanRemoval: true)]
    private Collection $favorites;

    /**
     * @var Collection<int, ShoppingList>
     */
    #[ORM\OneToMany(targetEntity: ShoppingList::class, mappedBy: 'user', orphanRemoval: true)]
    private Collection $shoppingLists;

    /**
     * Initializes the favorites and shopping lists collections.
     */
    public function __construct()
    {
        $this->favorites = new ArrayCollection();
        $this->shoppingLists = new ArrayCollection();
    }

    /**
     * Returns the user's id.
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Returns the user's email.
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * Sets the user's email.
     */
    public function setEmail(string $email): static
    {
        $this->email = $email;
        return $this;
    }

    /**
     * Returns the identifier Symfony's security layer uses for this user (the email).
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * Returns the user's role.
     */
    public function getRole(): RoleEnum
    {
        return $this->role;
    }

    /**
     * Sets the user's role.
     */
    public function setRole(RoleEnum $role): static
    {
        $this->role = $role;
        return $this;
    }

    /**
     * Fulfils Symfony's UserInterface contract, which requires an array of role strings.
     *
     * SymfonyのUserInterface契約を満たすためのメソッド。
     * 戻り値は必ずarrayでなければならない（フレームワーク側の仕様）。
     * 実体は $role という単一のEnum値だが、ここでSymfonyが期待する配列形式に変換する。
     *
     * @return list<string>
     */
    public function getRoles(): array
    {
        $roles = [$this->role->value];
        $roles[] = RoleEnum::USER->value;

        return array_unique($roles);
    }

    /**
     * Checks whether the user has the ROLE_ADMIN role.
     *
     * ROLE_ADMINかどうかを判定する
     */
    public function isAdmin(): bool
    {
        return $this->role === RoleEnum::ADMIN;
    }

    /**
     * Returns the hashed password.
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    /**
     * Sets the hashed password.
     */
    public function setPassword(string $password): static
    {
        $this->password = $password;
        return $this;
    }

    /**
     * Customizes serialization so the raw hashed password is never stored as-is
     * (a crc32c checksum of it is kept instead, e.g. for session invalidation checks).
     */
    public function __serialize(): array
    {
        $data = (array) $this;
        $data["\0" . self::class . "\0password"] = hash('crc32c', $this->password);
        return $data;
    }

    /**
     * Returns the user's username.
     */
    public function getUsername(): ?string
    {
        return $this->username;
    }

    /**
     * Sets the user's username.
     */
    public function setUsername(string $username): static
    {
        $this->username = $username;
        return $this;
    }

    /**
     * Returns the user's favorites.
     *
     * @return Collection<int, Favorite>
     */
    public function getFavorites(): Collection
    {
        return $this->favorites;
    }

    /**
     * Adds a favorite to the user, keeping both sides of the relation in sync.
     */
    public function addFavorite(Favorite $favorite): static
    {
        if (!$this->favorites->contains($favorite)) {
            $this->favorites->add($favorite);
            $favorite->setUser($this);
        }
        return $this;
    }

    /**
     * Removes a favorite from the user, keeping both sides of the relation in sync.
     */
    public function removeFavorite(Favorite $favorite): static
    {
        if ($this->favorites->removeElement($favorite)) {
            if ($favorite->getUser() === $this) {
                $favorite->setUser(null);
            }
        }
        return $this;
    }

    /**
     * Returns the user's shopping lists.
     *
     * @return Collection<int, ShoppingList>
     */
    public function getShoppingLists(): Collection
    {
        return $this->shoppingLists;
    }

    /**
     * Adds a shopping list to the user, keeping both sides of the relation in sync.
     */
    public function addShoppingList(ShoppingList $shoppingList): static
    {
        if (!$this->shoppingLists->contains($shoppingList)) {
            $this->shoppingLists->add($shoppingList);
            $shoppingList->setUser($this);
        }
        return $this;
    }

    /**
     * Removes a shopping list from the user, keeping both sides of the relation in sync.
     */
    public function removeShoppingList(ShoppingList $shoppingList): static
    {
        if ($this->shoppingLists->removeElement($shoppingList)) {
            if ($shoppingList->getUser() === $this) {
                $shoppingList->setUser(null);
            }
        }
        return $this;
    }
}