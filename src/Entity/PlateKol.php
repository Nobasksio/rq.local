<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PlateKolRepository")
 */
class PlateKol
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Project", inversedBy="plateKols")
     */
    private $project;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Plate", inversedBy="plateKols")
     */
    private $plate;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $kol;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isActive;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date_update;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getPlate(): ?Plate
    {
        return $this->plate;
    }

    public function setPlate(?Plate $plate): self
    {
        $this->plate = $plate;

        return $this;
    }

    public function getKol(): ?string
    {
        return $this->kol;
    }

    public function setKol(?string $kol): self
    {
        $this->kol = $kol;

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

    public function getDateUpdate(): ?\DateTimeInterface
    {
        return $this->date_update;
    }

    public function setDateUpdate(\DateTimeInterface $date_update): self
    {
        $this->date_update = $date_update;

        return $this;
    }
}
