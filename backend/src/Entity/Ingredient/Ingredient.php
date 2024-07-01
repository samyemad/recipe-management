<?php

namespace App\Entity\Ingredient;

use App\Constants\Ingredient\IngredientSerializationGroups;
use App\Constants\Ingredient\RecipeSerializationGroups;
use App\Entity\Recipe\RecipeIngredient;
use App\Repository\Ingredient\IngredientRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: IngredientRepository::class)]
#[UniqueEntity(fields: ['name'], message: 'This ingredient name is already in use.')]
class Ingredient
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups([
        IngredientSerializationGroups::INGREDIENT_READ,
        IngredientSerializationGroups::INGREDIENT_WRITE,
    ])]
    private int $id;

    #[ORM\Column(type: 'string', length: 255, unique: true)]
    #[Groups([
        IngredientSerializationGroups::INGREDIENT_READ,
        IngredientSerializationGroups::INGREDIENT_WRITE,
        RecipeSerializationGroups::RECIPE_READ,
    ])]
    private string $name;

    #[ORM\Column(type: 'json', nullable: true)]
    #[Groups([
        IngredientSerializationGroups::INGREDIENT_READ,
        IngredientSerializationGroups::INGREDIENT_WRITE,
        RecipeSerializationGroups::RECIPE_READ,
    ])]
    private array $nutritionalInformation;

    /**
     * @var Collection<int, RecipeIngredient>
     */
    #[ORM\OneToMany(targetEntity: RecipeIngredient::class, mappedBy: 'ingredient', cascade: ['persist', 'remove'])]
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

    public function getNutritionalInformation(): ?array
    {
        return $this->nutritionalInformation;
    }

    public function setNutritionalInformation(?array $nutritionalInformation): self
    {
        $this->nutritionalInformation = $nutritionalInformation;

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
            $recipeIngredient->setIngredient($this);
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
