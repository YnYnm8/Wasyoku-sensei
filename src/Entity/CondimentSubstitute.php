<?php

namespace App\Entity;

use App\Repository\CondimentSubstituteRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CondimentSubstituteRepository::class)]
class CondimentSubstitute
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column]
    private ?float $quantity = null;

    #[ORM\Column(length: 255)]
    private ?string $unit = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $url = null;

    #[ORM\ManyToOne(inversedBy: 'condimentSubstitutes')]
    #[ORM\JoinColumn(nullable: false)]
    private ?CondimentSubstituteGroup $condimentSubstituteGroup = null;

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

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(?string $url): static
    {
        $this->url = $url;

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
}
