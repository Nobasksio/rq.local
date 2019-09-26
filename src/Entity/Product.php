<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Utility\EntityHelper;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ProductRepository")
 */
class Product extends EntityHelper
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
     * @ORM\Column(type="string")
     */
    private $cost_price;

    /**
     * @ORM\Column(type="string")
     */
    private $weight;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $consist;

    /**
     * @ORM\Column(type="boolean")
     */
    private $status;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $povar;

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

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\NameMenu", mappedBy="product")
     */
    private $namesMenu;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\DescriptionMenu", mappedBy="Product")
     */
    private $DescritionsMenu;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\TypeProduct", inversedBy="products")
     */
    private $type;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Degustation", mappedBy="products")
     */
    private $degustations;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $old_price;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Comment", mappedBy="product")
     */
    private $comments;

    public function __construct() {
        $this->setPrice(0);
        $this->setStatus(true);
        $this->setCostPrice(0);
        $this->setWeight(0);
        $this->setOldProduct(null);
        $this->setSubcategory(null);
        $this->setConsist('');
        $this->degustationScores = new ArrayCollection();
        $this->active = new ArrayCollection();
        $this->photos = new ArrayCollection();
        $this->namesMenu = new ArrayCollection();
        $this->DescritionsMenu = new ArrayCollection();
        $this->degustations = new ArrayCollection();
        $this->comments = new ArrayCollection();
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

    public function getCostPrice(): ?string
    {
        return $this->cost_price;
    }

    public function setCostPrice(string $cost_price): self
    {
        $this->cost_price = $cost_price;

        return $this;
    }

    public function getWeight(): ?string
    {
        return $this->weight;
    }

    public function setWeight(string $weight): self
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

    /**
     * @return Collection|NameMenu[]
     */
    public function getNamesMenu(): Collection
    {
        return $this->namesMenu;
    }

    public function addNamesMenu(NameMenu $namesMenu): self
    {
        if (!$this->namesMenu->contains($namesMenu)) {
            $this->namesMenu[] = $namesMenu;
            $namesMenu->setProduct($this);
        }

        return $this;
    }

    public function removeNamesMenu(NameMenu $namesMenu): self
    {
        if ($this->namesMenu->contains($namesMenu)) {
            $this->namesMenu->removeElement($namesMenu);
            // set the owning side to null (unless already changed)
            if ($namesMenu->getProduct() === $this) {
                $namesMenu->setProduct(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|DescriptionMenu[]
     */
    public function getDescritionsMenu(): Collection
    {
        return $this->DescritionsMenu;
    }

    public function addDescritionsMenu(DescriptionMenu $descritionsMenu): self
    {
        if (!$this->DescritionsMenu->contains($descritionsMenu)) {
            $this->DescritionsMenu[] = $descritionsMenu;
            $descritionsMenu->setProduct($this);
        }

        return $this;
    }

    public function removeDescritionsMenu(DescriptionMenu $descritionsMenu): self
    {
        if ($this->DescritionsMenu->contains($descritionsMenu)) {
            $this->DescritionsMenu->removeElement($descritionsMenu);
            // set the owning side to null (unless already changed)
            if ($descritionsMenu->getProduct() === $this) {
                $descritionsMenu->setProduct(null);
            }
        }

        return $this;
    }

    public function getType(): ?TypeProduct
    {
        return $this->type;
    }

    public function setType(?TypeProduct $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getArrayParam($array = ['id','name_work']){

        $array_param = [];

        foreach ($array as $name_property){
            if (isset($this->$name_property)){
                $array_param[$name_property] = $this->$name_property;
            }
        }
        return $array_param;

    }

    public function getArrayParamDegust($array = ['id','name_work','consist','category','weight','cost_price','povar']){

        $array_param = [];

        $array_param['edit'] = false;

        $array_param['povar'] = null;
        $array_param['scores'] = $this->getDegustationScores();
        foreach ($array as $name_property){
            if (isset($this->$name_property)){
                if ($name_property == 'category'){
                    $array_param[$name_property] = $this->$name_property->getArrayParam();
                } else {
                    $array_param[$name_property] = $this->$name_property;
                }
            }
        }
        return $array_param;

    }

    /**
     * @return mixed
     */
    public function getConsist()
    {
        return $this->consist;
    }

    /**
     * @param mixed $consist
     */
    public function setConsist($consist): void
    {
        $this->consist = $consist;
    }

    /**
     * @return mixed
     */
    public function getPovar()
    {
        return $this->povar;
    }

    /**
     * @param mixed $povar
     */
    public function setPovar($povar): void
    {
        $this->povar = $povar;
    }

    /**
     * @return Collection|Degustation[]
     */
    public function getDegustations(): Collection
    {
        return $this->degustations;
    }

    public function addDegustation(Degustation $degustation): self
    {
        if (!$this->degustations->contains($degustation)) {
            $this->degustations[] = $degustation;
            $degustation->addProduct($this);
        }

        return $this;
    }

    public function removeDegustation(Degustation $degustation): self
    {
        if ($this->degustations->contains($degustation)) {
            $this->degustations->removeElement($degustation);
            $degustation->removeProduct($this);
        }

        return $this;
    }

    public function getOldPrice(): ?string
    {
        return $this->old_price;
    }

    public function setOldPrice(?string $old_price): self
    {
        $this->old_price = $old_price;

        return $this;
    }

    /**
     * @return Collection|Comment[]
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comment $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments[] = $comment;
            $comment->setProduct($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        if ($this->comments->contains($comment)) {
            $this->comments->removeElement($comment);
            // set the owning side to null (unless already changed)
            if ($comment->getProduct() === $this) {
                $comment->setProduct(null);
            }
        }

        return $this;
    }



}
