<?php

namespace App\Entity;

use App\Repository\TortugaRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Entity(repositoryClass: TortugaRepository::class)]
#[UniqueEntity(fields: ['name'], message: 'All turtles are unique, man')]
class Tortuga
{
  #[ORM\Id]
  #[ORM\GeneratedValue]
  #[ORM\Column]
  private ?int $id = null;

  #[ORM\Column(length: 100)]
  #[Assert\NotBlank]
  #[Assert\Length(min: 2, minMessage: 'Please, use at least 2 characters')]
  #[Assert\Length(max: 100, maxMessage: 'Do you think you will remember his name?')]
  private ?string $name = null;

  #[ORM\OneToOne(mappedBy: 'adoptedTurtle', cascade: ['persist', 'remove'])]
  private ?Adoption $adoption = null;

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

  public function getAdoption(): ?Adoption
  {
      return $this->adoption;
  }

  public function setAdoption(Adoption $adoption): static
  {
      // set the owning side of the relation if necessary
      if ($adoption->getAdoptedTurtle() !== $this) {
          $adoption->setAdoptedTurtle($this);
      }

      $this->adoption = $adoption;

      return $this;
  }
}
