<?php
namespace App\Entity;
use App\Repository\RecipeCondimentRepository;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Condiment;

#[ORM\Entity(repositoryClass: RecipeCondimentRepository::class)]
class RecipeCondiment
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?float $quantity = null;

    #[ORM\Column(length: 255)]
    private ?string $unit = null;

    #[ORM\ManyToOne(inversedBy: 'recipeCondiments')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Recipe $recipe = null;

    #[ORM\ManyToOne(inversedBy: 'recipeCondiments')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Condiment $condiment = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getQuantity(): ?float
    {
        return $this->quantity;
    }

    public function setQuantity(float $quantity): static
    {
        $this->quantity = $quantity;
        return $this;
    }

    public function getUnit(): ?string
    {
        return $this->unit;
    }

    public function setUnit(string $unit): static
    {
        $this->unit = $unit;
        return $this;
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

    public function getCondiment(): ?Condiment
    {
        return $this->condiment;
    }

    public function setCondiment(?Condiment $condiment): static
    {
        $this->condiment = $condiment;
        return $this;
    }
}