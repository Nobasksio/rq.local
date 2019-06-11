<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ProductRepository")
 */
class Product
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
    private $name_work;

    /**
     * @ORM\Column(type="float")
     */
    private $price;

    /**
     * @ORM\Column(type="float")
     */
    private $cost_price;

    /**
     * @ORM\Column(type="integer")
     */
    private $weight;

    /**
     * @ORM\Column(type="boolean")
     */
    private $status;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\OldProduct", cascade={"persist", "remove"},inversedBy="product")
     */
    private $old_product;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Project", inversedBy="products")
     * @ORM\JoinColumn(nullable=false)
     */
    private $project;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Category", inversedBy="products")
     */
    private $category;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\DegustationScore", mappedBy="product")
     */
    private $degustationScores;


    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Ttk", mappedBy="product")
     */
    private $ttk;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Photo", mappedBy="product")
     */
    private $photos;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Subcategory", inversedBy="products")
     * @ORM\JoinColumn(nullable=true)
     */
    private $subcategory;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $m_id;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $old_status;

    public function __construct() {
        $this->setPrice(0);
        $this->setStatus(true);
        $this->setCostPrice(0);
        $this->setWeight(0);
        $this->setOldProduct(null);
        $this->setSubcategory(null);
        $this->degustationScores = new ArrayCollection();
        $this->active = new ArrayCollection();
        $this->photos = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNameWork(): ?string
    {
        return $this->name_work;
    }

    public function setNameWork(string $name_work): self
    {
        $this->name_work = $name_work;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getCostPrice(): ?float
    {
        return $this->cost_price;
    }

    public function setCostPrice(float $cost_price): self
    {
        $this->cost_price = $cost_price;

        return $this;
    }

    public function getWeight(): ?int
    {
        return $this->weight;
    }

    public function setWeight(int $weight): self
    {
        $this->weight = $weight;

        return $this;
    }

    public function getStatus(): ?bool
    {
        return $this->status;
    }

    public function setStatus(bool $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getOldProduct(): ?OldProduct
    {
        return $this->old_product;
    }

    public function setOldProduct(?OldProduct $old_product): self
    {
        $this->old_product = $old_product;

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

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): self
    {
        $this->category = $category;

        return $this;
    }

    /**
     * @return Collection|DegustationScore[]
     */
    public function getDegustationScores(): Collection
    {
        return $this->degustationScores;
    }

    public function addDegustationScore(DegustationScore $degustationScore): self
    {
        if (!$this->degustationScores->contains($degustationScore)) {
            $this->degustationScores[] = $degustationScore;
            $degustationScore->setProduct($this);
        }

        return $this;
    }

    public function removeDegustationScore(DegustationScore $degustationScore): self
    {
        if ($this->degustationScores->contains($degustationScore)) {
            $this->degustationScores->removeElement($degustationScore);
            // set the owning side to null (unless already changed)
            if ($degustationScore->getProduct() === $this) {
                $degustationScore->setProduct(null);
            }
        }

        return $this;
    }


    /**
     * @return Collection|Ttk[]
     */
    public function getTtk(): Collection
    {
        return $this->ttk;
    }

    public function addTtk(Ttk $ttk): self
    {
        if (!$this->ttk->contains($ttk)) {
            $this->ttk[] = $ttk;
            $ttk->setProduct($this);
        }

        return $this;
    }

    public function removeTtk(Ttk $ttk): self
    {
        if ($this->ttk->contains($ttk)) {
            $this->ttk->removeElement($ttk);
            // set the owning side to null (unless already changed)
            if ($ttk->getProduct() === $this) {
                $ttk->setProduct(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Photo[]
     */
    public function getPhotos(): Collection
    {
        return $this->photos;
    }

    public function addPhoto(Photo $photo): self
    {
        if (!$this->photos->contains($photo)) {
            $this->photos[] = $photo;
            $photo->setProduct($this);
        }

        return $this;
    }

    public function removePhoto(Photo $photo): self
    {
        if ($this->photos->contains($photo)) {
            $this->photos->removeElement($photo);
            // set the owning side to null (unless already changed)
            if ($photo->getProduct() === $this) {
                $photo->setProduct(null);
            }
        }

        return $this;
    }

    public function getSubcategory(): ?Subcategory
    {
        return $this->subcategory;
    }

    public function setSubcategory(?Subcategory $subcategory): self
    {
        $this->subcategory = $subcategory;

        return $this;
    }

    public function getMId(): ?int
    {
        return $this->m_id;
    }

    public function setMId(?int $m_id): self
    {
        $this->m_id = $m_id;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getOldStatus()
    {
        return $this->old_status;
    }

    /**
     * @param mixed $old_status
     */
    public function setOldStatus($old_status)
    {
        $this->old_status = $old_status;
    }
}
