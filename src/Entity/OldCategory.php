<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\MaxDepth;

/**
 * @ORM\Entity(repositoryClass="App\Repository\OldCategoryRepository")
 */
class OldCategory
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
    private $date_last_change;

    /**
     * @ORM\Column(type="boolean")
     */
    private $status;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\OldProduct", mappedBy="category")
     * @MaxDepth(1)
     */
    private $oldProducts;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Analitics", inversedBy="oldCategories")
     * @ORM\JoinColumn(nullable=false)
     * @MaxDepth(1)
     */
    private $analitics;

    /**
     * @ORM\Column(type="smallint")
     */
    private $category_subdivision;

    private $val_marj;

    private $val_ss;

    private $val_vir;

    private $per_vir;

    private $val_sale;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $m_id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $iiko_code;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $alias;

    /**
     * @ORM\Column(type="smallint", nullable=true)
     */
    private $type;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $combine;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Category", mappedBy="old_category", cascade={"persist", "remove"})
     */
    private $category;

    public function __construct()
    {
        $this->oldProducts = new ArrayCollection();
        $this->setDateLastChange(new \DateTime('now'));
        $this->setStatus(true);
        $this->setCategorySubdivision(1);
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

    public function getDateLastChange(): ?\DateTimeInterface
    {
        return $this->date_last_change;
    }

    public function setDateLastChange(\DateTimeInterface $date_last_change): self
    {
        $this->date_last_change = $date_last_change;

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
     * @return Collection|OldProduct[]
     */
    public function getOldProducts(): Collection
    {
        return $this->oldProducts;
    }

    public function addOldProduct(OldProduct $oldProduct): self
    {
        if (!$this->oldProducts->contains($oldProduct)) {
            $this->oldProducts[] = $oldProduct;
            $oldProduct->setCategory($this);
        }

        return $this;
    }

    public function removeOldProduct(OldProduct $oldProduct): self
    {
        if ($this->oldProducts->contains($oldProduct)) {
            $this->oldProducts->removeElement($oldProduct);
            // set the owning side to null (unless already changed)
            if ($oldProduct->getCategory() === $this) {
                $oldProduct->setCategory(null);
            }
        }

        return $this;
    }

    public function getAnalitics(): ?Analitics
    {
        return $this->analitics;
    }

    public function setAnalitics(?Analitics $analitics): self
    {
        $this->analitics = $analitics;

        return $this;
    }

    public function getCategorySubdivision(): ?int
    {
        return $this->category_subdivision;
    }

    public function setCategorySubdivision(int $category_subdivision): self
    {
        $this->category_subdivision = $category_subdivision;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getValMarj()
    {
        return $this->val_marj;
    }

    /**
     * @param mixed $val_marj
     */
    public function setValMarj($val_marj)
    {
        $this->val_marj = $val_marj;
    }

    /**
     * @return mixed
     */
    public function getValSs()
    {
        return $this->val_ss;
    }

    /**
     * @param mixed $val_ss
     */
    public function setValSs($val_ss)
    {
        $this->val_ss = $val_ss;
    }

    /**
     * @return mixed
     */
    public function getValVir()
    {
        return $this->val_vir;
    }

    /**
     * @param mixed $val_vir
     */
    public function setValVir($val_vir)
    {
        $this->val_vir = $val_vir;
    }

    /**
     * @return mixed
     */
    public function getPerVir()
    {
        return $this->per_vir;
    }

    /**
     * @param mixed $per_vir
     */
    public function setPerVir($per_vir)
    {
        $this->per_vir = $per_vir;
    }

    /**
     * @return mixed
     */
    public function getValSale()
    {
        return $this->val_sale;
    }

    /**
     * @param mixed $val_sale
     */
    public function setValSale($val_sale)
    {
        $this->val_sale = $val_sale;
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

    public function getIikoCode(): ?string
    {
        return $this->iiko_code;
    }

    public function setIikoCode(?string $iiko_code): self
    {
        $this->iiko_code = $iiko_code;

        return $this;
    }

    public function getAlias(): ?string
    {
        return $this->alias;
    }

    public function setAlias(?string $alias): self
    {
        $this->alias = $alias;

        return $this;
    }

    public function getArrayParam($array = ['id', 'alias', 'status', 'name', 'type', 'combine'])
    {

        $array_param = [];

        $array_param['typek'] = $this->getType();
        $array_param['val_marj'] = $this->getValMarj();
        $array_param['val_ss'] = $this->getValSs();
        $array_param['val_sale'] = $this->getValSale();
        $array_param['val_vir'] = $this->getValVir();
        $array_param['combine'] = null;
        $array_param['text'] = $this->getName();

        $all_summ = 0;
        foreach ($this->getOldProducts() as $old_product) {
            $all_summ += $old_product->getPrice() * $old_product->getSale();
        }
        $all_summ = round($all_summ);

        $array_param['sum'] = $all_summ;
        $array_param['combine'] = false;

        foreach ($array as $name_property) {
            if (isset($this->$name_property)) {

                $array_param[$name_property] = $this->$name_property;
            }
        }


        return $array_param;

    }

    public function getType(): ?int
    {
        return $this->type;
    }

    public function setType(?int $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getCombine(): ?int
    {
        return $this->combine;
    }

    public function setCombine(?int $combine): self
    {
        $this->combine = $combine;

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): self
    {
        $this->category = $category;

        // set (or unset) the owning side of the relation if necessary
        $newOld_category = $category === null ? null : $this;
        if ($newOld_category !== $category->getOldCategory()) {
            $category->setOldCategory($newOld_category);
        }

        return $this;
    }
}
