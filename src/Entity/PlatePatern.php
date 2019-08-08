<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PlatePaternRepository")
 */
class PlatePatern
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
     * @ORM\ManyToOne(targetEntity="App\Entity\Project", inversedBy="platePaterns")
     */
    private $project;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Plate", inversedBy="platePaterns")
     */
    private $plate;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isActive;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="platePaterns")
     */
    private $user;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\PlatePaternMatrix", mappedBy="plate_pattern")
     */
    private $platePaternMatrices;

    public function __construct()
    {
        $this->plate = new ArrayCollection();
        $this->platePaternMatrices = new ArrayCollection();
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

    public function getProject(): ?Project
    {
        return $this->project;
    }

    public function setProject(?Project $project): self
    {
        $this->project = $project;

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

    public function getIsActive(): ?bool
    {
        return $this->isActive;
    }

    public function setIsActive(bool $isActive): self
    {
        $this->isActive = $isActive;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return Collection|PlatePaternMatrix[]
     */
    public function getPlatePaternMatrices(): Collection
    {
        return $this->platePaternMatrices;
    }

    public function addPlatePaternMatrix(PlatePaternMatrix $platePaternMatrix): self
    {
        if (!$this->platePaternMatrices->contains($platePaternMatrix)) {
            $this->platePaternMatrices[] = $platePaternMatrix;
            $platePaternMatrix->setPlatePattern($this);
        }

        return $this;
    }

    public function removePlatePaternMatrix(PlatePaternMatrix $platePaternMatrix): self
    {
        if ($this->platePaternMatrices->contains($platePaternMatrix)) {
            $this->platePaternMatrices->removeElement($platePaternMatrix);
            // set the owning side to null (unless already changed)
            if ($platePaternMatrix->getPlatePattern() === $this) {
                $platePaternMatrix->setPlatePattern(null);
            }
        }

        return $this;
    }
}
