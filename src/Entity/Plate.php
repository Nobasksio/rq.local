<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PlateRepository")
 */
class Plate
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
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $size;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $preview;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $full;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="plates")
     */
    private $user_create;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date_create;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isDelete;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Project", inversedBy="plates")
     */
    private $project;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\PlateMatrix", mappedBy="plate")
     */
    private $plateMatrices;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\PlateProvider", mappedBy="plate")
     */
    private $plateProviders;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\PlateKol", mappedBy="plate")
     */
    private $plateKols;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\PlatePatern", mappedBy="plate")
     */
    private $platePaterns;

    public function __construct()
    {
        $this->project = new ArrayCollection();
        $this->plateMatrices = new ArrayCollection();
        $this->plateProviders = new ArrayCollection();
        $this->plateKols = new ArrayCollection();
        $this->platePaterns = new ArrayCollection();
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

    public function getSize(): ?string
    {
        return $this->size;
    }

    public function setSize(?string $size): self
    {
        $this->size = $size;

        return $this;
    }

    public function getPreview(): ?string
    {
        return $this->preview;
    }

    public function setPreview(?string $preview): self
    {
        $this->preview = $preview;

        return $this;
    }

    public function getFull(): ?string
    {
        return $this->full;
    }

    public function setFull(?string $full): self
    {
        $this->full = $full;

        return $this;
    }

    public function getUserCreate(): ?User
    {
        return $this->user_create;
    }

    public function setUserCreate(?User $user_create): self
    {
        $this->user_create = $user_create;

        return $this;
    }

    public function getDateCreate(): ?\DateTimeInterface
    {
        return $this->date_create;
    }

    public function setDateCreate(\DateTimeInterface $date_create): self
    {
        $this->date_create = $date_create;

        return $this;
    }

    public function getIsDelete(): ?bool
    {
        return $this->isDelete;
    }

    public function setIsDelete(bool $isDelete): self
    {
        $this->isDelete = $isDelete;

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
     * @return Collection|Project[]
     */
    public function getProject(): Collection
    {
        return $this->project;
    }

    public function addProject(Project $project): self
    {
        if (!$this->project->contains($project)) {
            $this->project[] = $project;
        }

        return $this;
    }

    public function removeProject(Project $project): self
    {
        if ($this->project->contains($project)) {
            $this->project->removeElement($project);
        }

        return $this;
    }

    /**
     * @return Collection|PlateMatrix[]
     */
    public function getPlateMatrices(): Collection
    {
        return $this->plateMatrices;
    }

    public function addPlateMatrix(PlateMatrix $plateMatrix): self
    {
        if (!$this->plateMatrices->contains($plateMatrix)) {
            $this->plateMatrices[] = $plateMatrix;
            $plateMatrix->setPlate($this);
        }

        return $this;
    }

    public function removePlateMatrix(PlateMatrix $plateMatrix): self
    {
        if ($this->plateMatrices->contains($plateMatrix)) {
            $this->plateMatrices->removeElement($plateMatrix);
            // set the owning side to null (unless already changed)
            if ($plateMatrix->getPlate() === $this) {
                $plateMatrix->setPlate(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|PlateProvider[]
     */
    public function getPlateProviders(): Collection
    {
        return $this->plateProviders;
    }

    public function addPlateProvider(PlateProvider $plateProvider): self
    {
        if (!$this->plateProviders->contains($plateProvider)) {
            $this->plateProviders[] = $plateProvider;
            $plateProvider->addPlate($this);
        }

        return $this;
    }

    public function removePlateProvider(PlateProvider $plateProvider): self
    {
        if ($this->plateProviders->contains($plateProvider)) {
            $this->plateProviders->removeElement($plateProvider);
            $plateProvider->removePlate($this);
        }

        return $this;
    }

    /**
     * @return Collection|PlateKol[]
     */
    public function getPlateKols(): Collection
    {
        return $this->plateKols;
    }

    public function addPlateKol(PlateKol $plateKol): self
    {
        if (!$this->plateKols->contains($plateKol)) {
            $this->plateKols[] = $plateKol;
            $plateKol->setPlate($this);
        }

        return $this;
    }

    public function removePlateKol(PlateKol $plateKol): self
    {
        if ($this->plateKols->contains($plateKol)) {
            $this->plateKols->removeElement($plateKol);
            // set the owning side to null (unless already changed)
            if ($plateKol->getPlate() === $this) {
                $plateKol->setPlate(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|PlatePatern[]
     */
    public function getPlatePaterns(): Collection
    {
        return $this->platePaterns;
    }

    public function addPlatePatern(PlatePatern $platePatern): self
    {
        if (!$this->platePaterns->contains($platePatern)) {
            $this->platePaterns[] = $platePatern;
            $platePatern->addPlate($this);
        }

        return $this;
    }

    public function removePlatePatern(PlatePatern $platePatern): self
    {
        if ($this->platePaterns->contains($platePatern)) {
            $this->platePaterns->removeElement($platePatern);
            $platePatern->removePlate($this);
        }

        return $this;
    }
}
