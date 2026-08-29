<?php


namespace App\Entity;


use App\Repository\MediaRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MediaRepository::class)]
class Media
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $url = null;

    #[ORM\Column(length: 20, options: ['default' => 'photo'])]
    private ?string $type = 'photo';

    #[ORM\Column(nullable: true)]
    private ?int $stepOrder = null;  // 追加：写真の場合のみ使う（動画では null のまま）
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $stepDescription = null;

    #[ORM\ManyToOne(inversedBy: 'media')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Recipe $recipe = null;

    /**
     * Returns the media's id.
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Sets the media's id.
     */
    public function setId(int $id): static
    {
        $this->id = $id;
        return $this;
    }

    /**
     * Returns the media's display name.
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * Sets the media's display name.
     */
    public function setName(string $name): static
    {
        $this->name = $name;
        return $this;
    }

    /**
     * Returns the media's URL.
     */
    public function getUrl(): ?string
    {
        return $this->url;
    }

    /**
     * Sets the media's URL.
     */
    public function setUrl(string $url): static
    {
        $this->url = $url;
        return $this;
    }

    /**
     * Returns the media type (e.g. "photo").
     */
    public function getType(): ?string
    {
        return $this->type;
    }

    /**
     * Sets the media type (e.g. "photo").
     */
    public function setType(string $type): static
    {
        $this->type = $type;
        return $this;
    }

    /**
     * Returns the preparation-step order, or null when this is not a step photo.
     */
    public function getStepOrder(): ?int
    {
        return $this->stepOrder;
    }

    /**
     * Sets the preparation-step order (null for a non-step photo, e.g. the main photo).
     */
    public function setStepOrder(?int $stepOrder): static
    {
        $this->stepOrder = $stepOrder;
        return $this;
    }

    /**
     * Returns the recipe this media belongs to.
     */
    public function getRecipe(): ?Recipe
    {
        return $this->recipe;
    }

    /**
     * Sets the recipe this media belongs to.
     */
    public function setRecipe(?Recipe $recipe): static
    {
        $this->recipe = $recipe;
        return $this;
    }

    /**
     * Returns the preparation-step description, or null when this is not a step photo.
     */
    public function getStepDescription(): ?string
    {
        return $this->stepDescription;
    }

    /**
     * Sets the preparation-step description.
     */
    public function setStepDescription(?string $stepDescription): static
    {
        $this->stepDescription = $stepDescription;
        return $this;
    }
}
