<?php

namespace App\Entity\Recipe;

use App\Constants\Ingredient\RecipeSerializationGroups;
use App\Repository\Recipe\RecipeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: RecipeRepository::class)]
#[UniqueEntity(fields: ['name'], message: 'This recipe name is already in use.')]
class Recipe
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups([
        RecipeSerializationGroups::RECIPE_READ,
        RecipeSerializationGroups::RECIPE_WRITE,
    ])]
    private int $id;

    #[ORM\Column(type: 'string', length: 255, unique: true)]
    #[Assert\NotBlank(message: 'The recipe name must not be blank.')]
    #[Groups([
        RecipeSerializationGroups::RECIPE_READ,
        RecipeSerializationGroups::RECIPE_WRITE,
    ])]
    private string $name;

    #[ORM\Column(type: 'text', nullable: true)]
    #[Groups([
        RecipeSerializationGroups::RECIPE_READ,
        RecipeSerializationGroups::RECIPE_WRITE,
    ])]
    private ?string $description = null;

    /**
     * @var Collection<int, RecipeIngredient>
     */
    #[ORM\OneToMany(targetEntity: RecipeIngredient::class, mappedBy: 'recipe', cascade: ['persist', 'remove'], orphanRemoval: true)]
    #[Groups([
        RecipeSerializationGroups::RECIPE_READ,
        RecipeSerializationGroups::RECIPE_WRITE,
    ])]
    private Collection $recipeIngredients;

    public function __construct()
    {
        $this->recipeIngredients = new ArrayCollection();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return Collection<int, RecipeIngredient>
     */
    public function getRecipeIngredients(): Collection
    {
        return $this->recipeIngredients;
    }

    public function addRecipeIngredient(RecipeIngredient $recipeIngredient): self
    {
        if (! $this->recipeIngredients->contains($recipeIngredient)) {
            $this->recipeIngredients[] = $recipeIngredient;
            $recipeIngredient->setRecipe($this);
        }

        return $this;
    }

    public function removeRecipeIngredient(RecipeIngredient $recipeIngredient): self
    {
        if ($this->recipeIngredients->contains($recipeIngredient)) {
            $this->recipeIngredients->removeElement($recipeIngredient);
        }

        return $this;
    }
}
