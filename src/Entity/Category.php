<?php

namespace App\Entity;

use App\Utility\EntityHelper;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\MaxDepth;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CategoryRepository")
 */
class Category extends EntityHelper
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"default"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"default"})
     */
    private $name;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date_create;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Project", inversedBy="categories")
     * @ORM\JoinColumn(nullable=false)
     * @MaxDepth(1)
     */
    private $project;

    /**
     * @ORM\Column(type="smallint")
     */
    private $type;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Product", mappedBy="category")
     * @MaxDepth(1)
     */
    private $products;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $old_category;

    /**
     * @ORM\Column(type="boolean")
     */
    private $status;



    private $old_amount;

    private $moved_amount;



    /**
     * @ORM\Column(type="integer")
     */
    private $amount_plan;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $m_id;

    public function __construct()
    {
        $this->products = new ArrayCollection();
        $this->setDateCreate(new \DateTime('now'));
        $this->setType(1);
        $this->setOldCategory(0);
        $this->setStatus(true);
        $this->setOldAmount(0);
        $this->setMovedAmount(0);
        $this->setAmountPlan(0);

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

    public function getDateCreate(): ?\DateTimeInterface
    {
        return $this->date_create;
    }

    public function setDateCreate(\DateTimeInterface $date_create): self
    {
        $this->date_create = $date_create;

        return $this;
    }

    public function getProjectId(): ?Project
    {
        return $this->project;
    }

    public function setProjectId(?Project $project): self
    {
        $this->project = $project;

        return $this;
    }

    public function getType(): ?int
    {
        return $this->type;
    }

    public function setType(int $type): self
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return Collection|Product[]
     */
    public function getProducts(): Collection
    {
        return $this->products;
    }

    public function addProduct(Product $product): self
    {
        if (!$this->products->contains($product)) {
            $this->products[] = $product;
            $product->setCategory($this);
        }

        return $this;
    }

    public function removeProduct(Product $product): self
    {
        if ($this->products->contains($product)) {
            $this->products->removeElement($product);
            // set the owning side to null (unless already changed)
            if ($product->getCategory() === $this) {
                $product->setCategory(null);
            }
        }

        return $this;
    }

    public function getOldCategory(): ?int
    {
        return $this->old_category;
    }

    public function setOldCategory(?int $old_category): self
    {
        $this->old_category = $old_category;

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
    /**
     * @return mixed
     */
    public function getOldAmount()
    {
        return $this->old_amount;
    }

    /**
     * @param mixed $old_amount
     */
    public function setOldAmount($old_amount)
    {
        $this->old_amount = $old_amount;
    }

    /**
     * @return mixed
     */
    public function getMovedAmount()
    {
        return $this->moved_amount;
    }

    /**
     * @param mixed $moved_amount
     */
    public function setMovedAmount($moved_amount)
    {
        $this->moved_amount = $moved_amount;
    }


    public function getAmountPlan(): ?int
    {
        return $this->amount_plan;
    }

    public function setAmountPlan(int $amount_plan): self
    {
        $this->amount_plan = $amount_plan;

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
    public function getArrayParam($array = ['id','name','type']){

        $array_param = [];

        foreach ($array as $name_property){
            if (isset($this->$name_property)){

                $array_param[$name_property] = $this->$name_property;
            }
        }
        return $array_param;

    }
}
