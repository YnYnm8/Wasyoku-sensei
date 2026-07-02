<?php

namespace App\Entity;

use App\Repository\CondimentSubstituteGroupRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CondimentSubstituteGroupRepository::class)]
class CondimentSubstituteGroup
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $label = null;

    #[ORM\ManyToOne(inversedBy: 'condimentSubstituteGroups')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Condiment $condiment = null;

    /**
     * @var Collection<int, CondimentSubstitute>
     */
    #[ORM\OneToMany(targetEntity: CondimentSubstitute::class, mappedBy: 'condimentSubstituteGroup', orphanRemoval: true)]
    private Collection $condimentSubstitutes;

    /**
     * @var Collection<int, ShoppingListSubstitute>
     */
    #[ORM\OneToMany(targetEntity: ShoppingListSubstitute::class, mappedBy: 'condimentSubstituteGroup', orphanRemoval: true)]
    private Collection $shoppingListSubstitutes;

    public function __construct()
    {
        $this->condimentSubstitutes = new ArrayCollection();
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

    public function getLabel(): ?string
    {
        return $this->label;
    }

    public function setLabel(string $label): static
    {
        $this->label = $label;

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

    /**
     * @return Collection<int, CondimentSubstitute>
     */
    public function getCondimentSubstitutes(): Collection
    {
        return $this->condimentSubstitutes;
    }

    public function addCondimentSubstitute(CondimentSubstitute $condimentSubstitute): static
    {
        if (!$this->condimentSubstitutes->contains($condimentSubstitute)) {
            $this->condimentSubstitutes->add($condimentSubstitute);
            $condimentSubstitute->setCondimentSubstituteGroup($this);
        }

        return $this;
    }

    public function removeCondimentSubstitute(CondimentSubstitute $condimentSubstitute): static
    {
        if ($this->condimentSubstitutes->removeElement($condimentSubstitute)) {
            // set the owning side to null (unless already changed)
            if ($condimentSubstitute->getCondimentSubstituteGroup() === $this) {
                $condimentSubstitute->setCondimentSubstituteGroup(null);
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
            $shoppingListSubstitute->setCondimentSubstituteGroup($this);
        }

        return $this;
    }

    public function removeShoppingListSubstitute(ShoppingListSubstitute $shoppingListSubstitute): static
    {
        if ($this->shoppingListSubstitutes->removeElement($shoppingListSubstitute)) {
            // set the owning side to null (unless already changed)
            if ($shoppingListSubstitute->getCondimentSubstituteGroup() === $this) {
                $shoppingListSubstitute->setCondimentSubstituteGroup(null);
            }
        }

        return $this;
    }
}
