<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\DepartmentRepository")
 */
class Department
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $code;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $iiko_id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $iiko_parent_id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $type;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $taxpayer_id_num;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Project", mappedBy="iiko_department")
     */
    private $projects;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\IikoCategory", mappedBy="department")
     */
    private $iikoCategories;

    public function __construct()
    {
        $this->project = new ArrayCollection();
        $this->projects = new ArrayCollection();
        $this->iikoCategories = new ArrayCollection();

    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(?string $code): self
    {
        $this->code = $code;

        return $this;
    }

    public function getIikoId(): ?string
    {
        return $this->iiko_id;
    }

    public function setIikoId(string $iiko_id): self
    {
        $this->iiko_id = $iiko_id;

        return $this;
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

    public function getIikoParentId(): ?string
    {
        return $this->iiko_parent_id;
    }

    public function setIikoParentId(?string $iiko_parent_id): self
    {
        $this->iiko_parent_id = $iiko_parent_id;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getTaxpayerIdNum(): ?string
    {
        return $this->taxpayer_id_num;
    }

    public function setTaxpayerIdNum(?string $taxpayer_id_num): self
    {
        $this->taxpayer_id_num = $taxpayer_id_num;

        return $this;
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
     * @return Collection|Project[]
     */
    public function getProjects(): Collection
    {
        return $this->projects;
    }

    /**
     * @return Collection|IikoCategory[]
     */
    public function getIikoCategories(): Collection
    {
        return $this->iikoCategories;
    }

    public function addIikoCategory(IikoCategory $iikoCategory): self
    {
        if (!$this->iikoCategories->contains($iikoCategory)) {
            $this->iikoCategories[] = $iikoCategory;
            $iikoCategory->addDepartment($this);
        }

        return $this;
    }

    public function removeIikoCategory(IikoCategory $iikoCategory): self
    {
        if ($this->iikoCategories->contains($iikoCategory)) {
            $this->iikoCategories->removeElement($iikoCategory);
            $iikoCategory->removeDepartment($this);
        }

        return $this;
    }
}
