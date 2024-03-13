<?php

namespace App\Entity;

use App\Repository\LegoCollectionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LegoCollectionRepository::class)]
class LegoCollection
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\OneToMany(targetEntity: Lego::class, mappedBy: 'category')]
    private Collection $legos;

    public function __construct()
    {
        $this->legos = new ArrayCollection();
    }

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

    /**
     * @return Collection<int, Lego>
     */
    public function getLegos(): Collection
    {
        return $this->legos;
    }

    public function addLego(Lego $lego): static
    {
        if (!$this->legos->contains($lego)) {
            $this->legos->add($lego);
            $lego->setCategory($this);
        }

        return $this;
    }

    public function removeLego(Lego $lego): static
    {
        if ($this->legos->removeElement($lego)) {
            // set the owning side to null (unless already changed)
            if ($lego->getCategory() === $this) {
                $lego->setCategory(null);
            }
        }

        return $this;
    }
}
