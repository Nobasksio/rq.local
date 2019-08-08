<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PlatePaternMatrixRepository")
 */
class PlatePaternMatrix
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Project", inversedBy="platePaternMatrices")
     */
    private $project;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\PlatePatern", inversedBy="platePaternMatrices")
     */
    private $plate_pattern;

    /**
     * @ORM\Column(type="integer")
     */
    private $Entity;

    /**
     * @ORM\Column(type="integer")
     */
    private $entity_id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="platePaternMatrices")
     */
    private $user;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date_update;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isActive;

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

    public function getPlatePattern(): ?PlatePatern
    {
        return $this->plate_pattern;
    }

    public function setPlatePattern(?PlatePatern $plate_pattern): self
    {
        $this->plate_pattern = $plate_pattern;

        return $this;
    }

    public function getEntity(): ?int
    {
        return $this->Entity;
    }

    public function setEntity(int $Entity): self
    {
        $this->Entity = $Entity;

        return $this;
    }

    public function getEntityId(): ?int
    {
        return $this->entity_id;
    }

    public function setEntityId(int $entity_id): self
    {
        $this->entity_id = $entity_id;

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

    public function getDateUpdate(): ?\DateTimeInterface
    {
        return $this->date_update;
    }

    public function setDateUpdate(\DateTimeInterface $date_update): self
    {
        $this->date_update = $date_update;

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
}
