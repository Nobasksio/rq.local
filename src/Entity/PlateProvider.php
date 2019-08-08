<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PlateProviderRepository")
 */
class PlateProvider
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $phone;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $logo;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Plate", inversedBy="plateProviders")
     */
    private $plate;

    public function __construct()
    {
        $this->plate = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getphone(): ?string
    {
        return $this->phone;
    }

    public function setphone(string $phone): self
    {
        $this->phone = $phone;

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

    public function getLogo(): ?string
    {
        return $this->logo;
    }

    public function setLogo(?string $logo): self
    {
        $this->logo = $logo;

        return $this;
    }

    /**
     * @return Collection|Plate[]
     */
    public function getPlate(): Collection
    {
        return $this->plate;
    }

    public function addPlate(Plate $plate): self
    {
        if (!$this->plate->contains($plate)) {
            $this->plate[] = $plate;
        }

        return $this;
    }

    public function removePlate(Plate $plate): self
    {
        if ($this->plate->contains($plate)) {
            $this->plate->removeElement($plate);
        }

        return $this;
    }
}
