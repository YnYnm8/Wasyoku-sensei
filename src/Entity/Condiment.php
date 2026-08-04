<?php

namespace App\Entity;

use App\Repository\CondimentRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CondimentRepository::class)]
class Condiment
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $explanation = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $composition = null;

    #[ORM\Column(name: '`use`', length: 255)]
    private ?string $use = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $note = null;

    /**
     * @var Collection<int, RecipeCondiment>
     */
    #[ORM\OneToMany(targetEntity: RecipeCondiment::class, mappedBy: 'condiment', orphanRemoval: true)]
    private Collection $recipeCondiments;

    /**
     * @var Collection<int, CondimentSubstituteGroup>
     */
    #[ORM\OneToMany(targetEntity: CondimentSubstituteGroup::class, mappedBy: 'condiment', orphanRemoval: true)]
    private Collection $condimentSubstituteGroups;

    /**
     * @var Collection<int, ShoppingListSubstitute>
     */
    #[ORM\OneToMany(targetEntity: ShoppingListSubstitute::class, mappedBy: 'condiment', orphanRemoval: true)]
    private Collection $shoppingListSubstitutes;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $url = null;

    #[ORM\Column(length: 255)]
    private ?string $imageUrl = null;

    public function __construct()
    {
        $this->recipeCondiments = new ArrayCollection();
        $this->condimentSubstituteGroups = new ArrayCollection();
        $this->shoppingListSubstitutes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getExplanation(): ?string
    {
        return $this->explanation;
    }

    public function setExplanation(string $explanation): static
    {
        $this->explanation = $explanation;

        return $this;
    }

    public function getComposition(): ?string
    {
        return $this->composition;
    }

    public function setComposition(?string $composition): static
    {
        $this->composition = $composition;

        return $this;
    }

    public function getUse(): ?string
    {
        return $this->use;
    }

    public function setUse(string $use): static
    {
        $this->use = $use;

        return $this;
    }

    public function getNote(): ?string
    {
        return $this->note;
    }

    public function setNote(?string $note): static
    {
        $this->note = $note;

        return $this;
    }

    /**
     * @return Collection<int, RecipeCondiment>
     */
    public function getRecipeCondiments(): Collection
    {
        return $this->recipeCondiments;
    }

    public function addRecipeCondiment(RecipeCondiment $recipeCondiment): static
    {
        if (!$this->recipeCondiments->contains($recipeCondiment)) {
            $this->recipeCondiments->add($recipeCondiment);
            $recipeCondiment->setCondiment($this);
        }

        return $this;
    }

    public function removeRecipeCondiment(RecipeCondiment $recipeCondiment): static
    {
        if ($this->recipeCondiments->removeElement($recipeCondiment)) {
            // set the owning side to null (unless already changed)
            if ($recipeCondiment->getCondiment() === $this) {
                $recipeCondiment->setCondiment(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, CondimentSubstituteGroup>
     */
    public function getCondimentSubstituteGroups(): Collection
    {
        return $this->condimentSubstituteGroups;
    }

    public function addCondimentSubstituteGroup(CondimentSubstituteGroup $condimentSubstituteGroup): static
    {
        if (!$this->condimentSubstituteGroups->contains($condimentSubstituteGroup)) {
            $this->condimentSubstituteGroups->add($condimentSubstituteGroup);
            $condimentSubstituteGroup->setCondiment($this);
        }

        return $this;
    }

    public function removeCondimentSubstituteGroup(CondimentSubstituteGroup $condimentSubstituteGroup): static
    {
        if ($this->condimentSubstituteGroups->removeElement($condimentSubstituteGroup)) {
            // set the owning side to null (unless already changed)
            if ($condimentSubstituteGroup->getCondiment() === $this) {
                $condimentSubstituteGroup->setCondiment(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, ShoppingListSubstitute>
     */
    public function getShoppingListSubstitutes(): Collection
    {
        return $this->shoppingListSubstitutes;
    }

    public function addShoppingListSubstitute(ShoppingListSubstitute $shoppingListSubstitute): static
    {
        if (!$this->shoppingListSubstitutes->contains($shoppingListSubstitute)) {
            $this->shoppingListSubstitutes->add($shoppingListSubstitute);
            $shoppingListSubstitute->setCondiment($this);
        }

        return $this;
    }

    public function removeShoppingListSubstitute(ShoppingListSubstitute $shoppingListSubstitute): static
    {
        if ($this->shoppingListSubstitutes->removeElement($shoppingListSubstitute)) {
            // set the owning side to null (unless already changed)
            if ($shoppingListSubstitute->getCondiment() === $this) {
                $shoppingListSubstitute->setCondiment(null);
            }
        }

        return $this;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(?string $url): static
    {
        $this->url = $url;

        return $this;
    }

    public function getImageUrl(): ?string
    {
        return $this->imageUrl;
    }

    public function setImageUrl(string $imageUrl): static
    {
        $this->imageUrl = $imageUrl;

        return $this;
    }
}