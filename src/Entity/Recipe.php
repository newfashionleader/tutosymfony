<?php

namespace App\Entity;

use App\Repository\RecipeRepository;
use App\Validator\InappropriateWords;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;
use App\Entity\Traits\Timestampable;
#[ORM\Entity(repositoryClass: RecipeRepository::class)]
#[ORM\Table(name: "recipes")]
#[UniqueEntity('title')]
class Recipe
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank()]#[Assert\NotBlank(message: "Votre titre ne peut pas être vide")]
    #[Assert\Length(min: 10,max: 50, minMessage: "Vous devez avoir un titre de minimum 10 caractères", maxMessage: "Vous ne pouvez pas avoir un titre de plus de 50 caractères")]
    #[InappropriateWords(listWords: ["putain", "chienne", "purée", "wesh" ])]
    private ?string $title = null;
  
    #[ORM\Column(length: 255)]
    private ?string $slug = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Assert\NotBlank(message: "Votre contenu ne peut pas être vide")]
    #[Assert\Length(min: 20, minMessage: "Vous devez avoir un contenu de minimum 20 caractères")]
    private ?string $content = null;

    use Timestampable;
   
    

   

    #[ORM\Column(nullable: true)]
    #[Assert\Positive(message: "La durée doit être positif")]
    #[Assert\LessThan(1440, message: "Vous ne pouvez pas introduire de recette de plus de 24h")]
    private ?int $duration = null;

    private ?string $imageName = 
"https://upload.wikimedia.org/wikipedia/commons/a/ac/No_image_available.svg";

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): static
    {
        $this->slug = $slug;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): static
    {
        $this->content = $content;

        return $this;
    }

    

   

    public function getDuration(): ?int
    {
        return $this->duration;
    }

    public function setDuration(?int $duration): static
    {
        $this->duration = $duration;

        return $this;
    }

   
}
