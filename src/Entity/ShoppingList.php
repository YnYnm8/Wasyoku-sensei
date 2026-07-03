<?php
namespace App\Entity;
use App\Repository\ShoppingListRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\RecipeShoppingList;
use App\Entity\ShoppingListSubstitute;


#[ORM\Entity(repositoryClass: ShoppingListRepository::class)]
class ShoppingList
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $memo = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    /**
     * @var Collection<int, RecipeShoppingList>
     */
    #[ORM\OneToMany(targetEntity: RecipeShoppingList::class, mappedBy: 'shoppingList', orphanRemoval: true)]
    private Collection $recipeShoppingLists;

    /**
     * @var Collection<int, ShoppingListSubstitute>
     */
    #[ORM\OneToMany(targetEntity: ShoppingListSubstitute::class, mappedBy: 'shoppingList', orphanRemoval: true)]
    private Collection $shoppingListSubstitutes;

    #[ORM\ManyToOne(inversedBy: 'shoppingLists')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
        $this->recipeShoppingLists = new ArrayCollection();
        $this->shoppingListSubstitutes = new ArrayCollection();
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

    public function getMemo(): ?string
    {
        return $this->memo;
    }

    public function setMemo(?string $memo): static
    {
        $this->memo = $memo;
        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;
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
            $recipeShoppingList->setShoppingList($this);
        }
        return $this;
    }

    public function removeRecipeShoppingList(RecipeShoppingList $recipeShoppingList): static
    {
        if ($this->recipeShoppingLists->removeElement($recipeShoppingList)) {
            if ($recipeShoppingList->getShoppingList() === $this) {
                $recipeShoppingList->setShoppingList(null);
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
            $shoppingListSubstitute->setShoppingList($this);
        }
        return $this;
    }

    public function removeShoppingListSubstitute(ShoppingListSubstitute $shoppingListSubstitute): static
    {
        if ($this->shoppingListSubstitutes->removeElement($shoppingListSubstitute)) {
            if ($shoppingListSubstitute->getShoppingList() === $this) {
                $shoppingListSubstitute->setShoppingList(null);
            }
        }
        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }
}