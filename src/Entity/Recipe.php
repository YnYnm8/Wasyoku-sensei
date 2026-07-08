<?php

namespace App\Entity;

use App\Repository\RecipeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Enum\RecipeLevel;
use App\Enum\RecipeSeason;
use App\Entity\RecipeIngredient;
use App\Entity\RecipeCondiment;
use App\Entity\Media;
use App\Entity\RecipeShoppingList;
use App\Enum\RecipeMainCategory;
use App\Entity\Favorite;

#[ORM\Entity(repositoryClass: RecipeRepository::class)]
class Recipe
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $step = null;

    #[ORM\Column(enumType: RecipeSeason::class)]
    private ?RecipeSeason $season = null;

    #[ORM\Column(length: 255)]
    private ?string $time = null;

    #[ORM\Column(enumType: RecipeLevel::class)]
    private ?RecipeLevel $level = null;

    #[ORM\Column(enumType: RecipeMainCategory::class)]
    private ?RecipeMainCategory $mainCategory = null;

    /**
     * @var Collection<int, RecipeIngredient>
     */
    #[ORM\OneToMany(targetEntity: RecipeIngredient::class, mappedBy: 'recipe', orphanRemoval: true)]
    private Collection $recipeIngredients;

    /**
     * @var Collection<int, RecipeCondiment>
     */
    #[ORM\OneToMany(targetEntity: RecipeCondiment::class, mappedBy: 'recipe', orphanRemoval: true)]
    private Collection $recipeCondiments;

    /**
     * @var Collection<int, RecipeShoppingList>
     */
    #[ORM\OneToMany(targetEntity: RecipeShoppingList::class, mappedBy: 'recipe', orphanRemoval: true)]
    private Collection $recipeShoppingLists;
    /**
     * @var Collection<int, Media>
     */
    #[ORM\OneToMany(targetEntity: Media::class, mappedBy: 'recipe', orphanRemoval: true)]
    private Collection $media;

    /**
     * @var Collection<int, ShoppingListSubstitute>
     */
    #[ORM\OneToMany(targetEntity: ShoppingListSubstitute::class, mappedBy: 'recipe', orphanRemoval: true)]
    private Collection $shoppingListSubstitutes;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    /**
     * @var Collection<int, Favorite>
     */
    #[ORM\OneToMany(targetEntity: Favorite::class, mappedBy: 'recipe', orphanRemoval: true)]
    private Collection $favorites;


    public function __construct()
    {
        $this->recipeIngredients = new ArrayCollection();
        $this->recipeCondiments = new ArrayCollection();
        $this->media = new ArrayCollection();
        $this->recipeShoppingLists = new ArrayCollection();
        $this->shoppingListSubstitutes = new ArrayCollection();
        $this->favorites = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): static
    {
        $this->id = $id;
        return $this;
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

    public function getStep(): ?string
    {
        return $this->step;
    }

    public function setStep(string $step): static
    {
        $this->step = $step;
        return $this;
    }

    public function getSeason(): ?RecipeSeason
    {
        return $this->season;
    }

    public function setSeason(RecipeSeason $season): static
    {
        $this->season = $season;
        return $this;
    }

    public function getTime(): ?string
    {
        return $this->time;
    }

    public function setTime(string $time): static
    {
        $this->time = $time;
        return $this;
    }

    public function getLevel(): ?RecipeLevel
    {
        return $this->level;
    }

    public function setLevel(RecipeLevel $level): static
    {
        $this->level = $level;
        return $this;
    }

    /**
     * @return Collection<int, RecipeIngredient>
     */
    public function getRecipeIngredients(): Collection
    {
        return $this->recipeIngredients;
    }

    public function addRecipeIngredient(RecipeIngredient $recipeIngredient): static
    {
        if (!$this->recipeIngredients->contains($recipeIngredient)) {
            $this->recipeIngredients->add($recipeIngredient);
            $recipeIngredient->setRecipe($this);
        }
        return $this;
    }

    public function removeRecipeIngredient(RecipeIngredient $recipeIngredient): static
    {
        if ($this->recipeIngredients->removeElement($recipeIngredient)) {
            if ($recipeIngredient->getRecipe() === $this) {
                $recipeIngredient->setRecipe(null);
            }
        }
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
            $recipeCondiment->setRecipe($this);
        }
        return $this;
    }

    public function removeRecipeCondiment(RecipeCondiment $recipeCondiment): static
    {
        if ($this->recipeCondiments->removeElement($recipeCondiment)) {
            if ($recipeCondiment->getRecipe() === $this) {
                $recipeCondiment->setRecipe(null);
            }
        }
        return $this;
    }
    /**
     * @return Collection<int, Media>
     */
    public function getMedia(): Collection
    {
        return $this->media;
    }

    public function addMedia(Media $media): static
    {
        if (!$this->media->contains($media)) {
            $this->media->add($media);
            $media->setRecipe($this);
        }
        return $this;
    }

    public function removeMedia(Media $media): static
    {
        if ($this->media->removeElement($media)) {
            if ($media->getRecipe() === $this) {
                $media->setRecipe(null);
            }
        }
        return $this;
    }

    /**
     * @return Collection<int, RecipeShoppingList>
     */
    public function getRecipeShoppingLists(): Collection
    {
        return $this->recipeShoppingLists;
    }

    public function addRecipeShoppingList(RecipeShoppingList $recipeShoppingList): static
    {
        if (!$this->recipeShoppingLists->contains($recipeShoppingList)) {
            $this->recipeShoppingLists->add($recipeShoppingList);
            $recipeShoppingList->setRecipe($this);
        }

        return $this;
    }

    public function removeRecipeShoppingList(RecipeShoppingList $recipeShoppingList): static
    {
        if ($this->recipeShoppingLists->removeElement($recipeShoppingList)) {
            // set the owning side to null (unless already changed)
            if ($recipeShoppingList->getRecipe() === $this) {
                $recipeShoppingList->setRecipe(null);
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
            $shoppingListSubstitute->setRecipe($this);
        }

        return $this;
    }

    public function removeShoppingListSubstitute(ShoppingListSubstitute $shoppingListSubstitute): static
    {
        if ($this->shoppingListSubstitutes->removeElement($shoppingListSubstitute)) {
            // set the owning side to null (unless already changed)
            if ($shoppingListSubstitute->getRecipe() === $this) {
                $shoppingListSubstitute->setRecipe(null);
            }
        }

        return $this;
    }

    public function getMainCategory(): ?RecipeMainCategory
    {
        return $this->mainCategory;
    }
    public function setMainCategory(RecipeMainCategory $mainCategory): static
    {
        $this->mainCategory = $mainCategory;
        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }
    /**
     * @return Collection<int, Favorite>
     */
    public function getFavorites(): Collection
    {
        return $this->favorites;
    }
}
