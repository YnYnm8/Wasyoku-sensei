<?php
namespace App\Entity;
use App\Repository\ShoppingListSubstituteRepository;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Condiment;
use App\Entity\CondimentSubstituteGroup;
use App\Entity\ShoppingList;
use App\Entity\Recipe;

#[ORM\Entity(repositoryClass: ShoppingListSubstituteRepository::class)]
class ShoppingListSubstitute
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'shoppingListSubstitutes')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Recipe $recipe = null;

    #[ORM\ManyToOne(inversedBy: 'shoppingListSubstitutes')]
    #[ORM\JoinColumn(nullable: false)]
    private ?CondimentSubstituteGroup $condimentSubstituteGroup = null;

    #[ORM\ManyToOne(inversedBy: 'shoppingListSubstitutes')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Condiment $condiment = null;

    #[ORM\ManyToOne(inversedBy: 'shoppingListSubstitutes')]
    #[ORM\JoinColumn(nullable: false)]
    private ?ShoppingList $shoppingList = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRecipe(): ?Recipe
    {
        return $this->recipe;
    }

    public function setRecipe(?Recipe $recipe): static
    {
        $this->recipe = $recipe;
        return $this;
    }

    public function getCondimentSubstituteGroup(): ?CondimentSubstituteGroup
    {
        return $this->condimentSubstituteGroup;
    }

    public function setCondimentSubstituteGroup(?CondimentSubstituteGroup $condimentSubstituteGroup): static
    {
        $this->condimentSubstituteGroup = $condimentSubstituteGroup;
        return $this;
    }

    public function getCondiment(): ?Condiment
    {
        return $this->condiment;
    }

    public function setCondiment(?Condiment $condiment): static
    {
        $this->condiment = $condiment;
        return $this;
    }

    public function getShoppingList(): ?ShoppingList
    {
        return $this->shoppingList;
    }

    public function setShoppingList(?ShoppingList $shoppingList): static
    {
        $this->shoppingList = $shoppingList;
        return $this;
    }
}