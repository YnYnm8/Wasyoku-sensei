<?php

namespace App\Entity;

use App\Repository\FavoriteRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FavoriteRepository::class)]
class Favorite
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'favorites')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Recipe $recipe = null;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    private ?\DateTimeImmutable $savedAt = null;


    #[ORM\ManyToOne(inversedBy: 'favorites')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\Column]
    private ?int $person = 1;

    // これは「カラムの代わり」ではなく、「カラムに自動で値を入れてくれる仕組み」です。
    /**
     * Stamps savedAt with the current time as soon as the favorite is created.
     */
    public function __construct()
    {
        $this->savedAt = new \DateTimeImmutable();
    }

    /**
     * Returns the favorite's id.
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Sets the favorite's id.
     */
    public function setId(int $id): static
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Returns the favorited recipe.
     */
    public function getRecipe(): ?Recipe
    {
        return $this->recipe;
    }

    /**
     * Sets the favorited recipe.
     */
    public function setRecipe(?Recipe $recipe): static
    {
        $this->recipe = $recipe;

        return $this;
    }

    /**
     * Returns when the favorite was saved.
     */
    public function getSavedAt(): ?\DateTimeImmutable
    {
        return $this->savedAt;
    }

    /**
     * Sets when the favorite was saved.
     */
    public function setSavedAt(\DateTimeImmutable $savedAt): static
    {
        $this->savedAt = $savedAt;
        return $this;
    }

    /**
     * Returns the user who favorited the recipe.
     */
    public function getUser(): ?User
    {
        return $this->user;
    }

    /**
     * Sets the user who favorited the recipe.
     */
    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Returns the person count chosen for this favorite (used to scale quantities).
     */
    public function getPerson(): ?int
    {
        return $this->person;
    }

    /**
     * Sets the person count chosen for this favorite (used to scale quantities).
     */
    public function setPerson(?int $person): static
    {
        $this->person = $person;
        return $this;
    }
 
}
