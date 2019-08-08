<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PlateMatrixRepository")
 */
class PlateMatrix
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Project", inversedBy="plateMatrices")
     */
    private $project;

    /**
     * @ORM\Column(type="integer")
     */
    private $entity;

    /**
     * @ORM\Column(type="integer")
     */
    private $id_entity;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Plate", inversedBy="plateMatrices")
     */
    private $plate;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isActive;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date_update;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="plateMatrices")
     */
    private $user;

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

    public function getEntity(): ?int
    {
        return $this->entity;
    }

    public function setEntity(int $entity): self
    {
        $this->entity = $entity;

        return $this;
    }

    public function getIdEntity(): ?int
    {
        return $this->id_entity;
    }

    public function setIdEntity(int $id_entity): self
    {
        $this->id_entity = $id_entity;

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

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }
}
