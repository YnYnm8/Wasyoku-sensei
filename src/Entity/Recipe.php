<?php

namespace App\Entity;

use App\Repository\RecipeRepository;
use Doctrine\ORM\Mapping as ORM;
use App\Enum\RecipeLevel;
use App\Enum\RecipeSeason;


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
}
