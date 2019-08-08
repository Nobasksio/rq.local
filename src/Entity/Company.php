<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CompanyRepository")
 */
class Company
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
     * @ORM\Column(type="datetime")
     */
    private $created_date;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Project", mappedBy="company")
     */
    private $Projects;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="created_companies")
     */
    private $created_by;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\IikoProduct", mappedBy="company")
     */
    private $iikoProducts;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\IikoCategory", mappedBy="company")
     */
    private $iikoCategories;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $iiko_address;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $iiko_user;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $iiko_pass_hash;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isDepartment;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isProduct;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isTtk;

    public function __construct()
    {
        $this->Projects = new ArrayCollection();
        $this->iikoProducts = new ArrayCollection();
        $this->iikoCategories = new ArrayCollection();
        $this->setCreatedDate(new \DateTime('now'));
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

    public function getCreatedDate(): ?\DateTimeInterface
    {
        return $this->created_date;
    }

    public function setCreatedDate(\DateTimeInterface $created_date): self
    {
        $this->created_date = $created_date;

        return $this;
    }

    /**
     * @return Collection|Project[]
     */
    public function getProjects(): Collection
    {
        return $this->Projects;
    }

    public function addProject(Project $project): self
    {
        if (!$this->Projects->contains($project)) {
            $this->Projects[] = $project;
            $project->setCompany($this);
        }

        return $this;
    }

    public function removeProject(Project $project): self
    {
        if ($this->Projects->contains($project)) {
            $this->Projects->removeElement($project);
            // set the owning side to null (unless already changed)
            if ($project->getCompany() === $this) {
                $project->setCompany(null);
            }
        }

        return $this;
    }

    public function getCreatedBy(): ?User
    {
        return $this->created_by;
    }

    public function setCreatedBy(?User $created_by): self
    {
        $this->created_by = $created_by;

        return $this;
    }

    /**
     * @return Collection|IikoProduct[]
     */
    public function getIikoProducts(): Collection
    {
        return $this->iikoProducts;
    }

    public function addIikoProduct(IikoProduct $iikoProduct): self
    {
        if (!$this->iikoProducts->contains($iikoProduct)) {
            $this->iikoProducts[] = $iikoProduct;
            $iikoProduct->setCompany($this);
        }

        return $this;
    }

    public function removeIikoProduct(IikoProduct $iikoProduct): self
    {
        if ($this->iikoProducts->contains($iikoProduct)) {
            $this->iikoProducts->removeElement($iikoProduct);
            // set the owning side to null (unless already changed)
            if ($iikoProduct->getCompany() === $this) {
                $iikoProduct->setCompany(null);
            }
        }

        return $this;
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
            $iikoCategory->setCompany($this);
        }

        return $this;
    }

    public function removeIikoCategory(IikoCategory $iikoCategory): self
    {
        if ($this->iikoCategories->contains($iikoCategory)) {
            $this->iikoCategories->removeElement($iikoCategory);
            // set the owning side to null (unless already changed)
            if ($iikoCategory->getCompany() === $this) {
                $iikoCategory->setCompany(null);
            }
        }

        return $this;
    }

    public function getIikoAddress(): ?string
    {
        return $this->iiko_address;
    }

    public function setIikoAddress(?string $iiko_address): self
    {
        $this->iiko_address = $iiko_address;

        return $this;
    }

    public function getIikoUser(): ?string
    {
        return $this->iiko_user;
    }

    public function setIikoUser(?string $iiko_user): self
    {
        $this->iiko_user = $iiko_user;

        return $this;
    }

    public function getIikoPassHash(): ?string
    {
        return $this->iiko_pass_hash;
    }

    public function setIikoPassHash(?string $iiko_pass_hash): self
    {
        $this->iiko_pass_hash = $iiko_pass_hash;

        return $this;
    }

    public function getIsDepartment(): ?bool
    {
        return $this->isDepartment;
    }

    public function setIsDepartment(bool $isDepartment): self
    {
        $this->isDepartment = $isDepartment;

        return $this;
    }

    public function getIsProduct(): ?bool
    {
        return $this->isProduct;
    }

    public function setIsProduct(bool $isProduct): self
    {
        $this->isProduct = $isProduct;

        return $this;
    }

    public function getIsTtk(): ?bool
    {
        return $this->isTtk;
    }

    public function setIsTtk(bool $isTtk): self
    {
        $this->isTtk = $isTtk;

        return $this;
    }
}
