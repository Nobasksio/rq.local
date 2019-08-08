<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\OldProductRepository")
 */
class OldProduct
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
     * @ORM\Column(type="float")
     */
    private $sale;

    /**
     * @ORM\Column(type="float")
     */
    private $price;

    /**
     * @ORM\Column(type="float")
     */
    private $cost_price;

    /**
     * @ORM\Column(type="boolean")
     */
    private $status;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date_last_change;

    /**
     * @ORM\Column(type="boolean")
     */
    private $disregard;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\OldCategory", inversedBy="oldProducts")
     */
    private $category;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Product", cascade={"persist", "remove"}, mappedBy="old_product")
     * @ORM\JoinColumn(name="product_id", referencedColumnName="id")
     */
    private $product;

    private $vir;

    private $marj;

    private $abc_sale;

    private $abc_vir;

    private $abc_marj;

    private $product_cat;

    private $per_cost_price;

    private $first_otch_name;

    private $first_otch_comment;

    private $second_otch_name;

    private $second_otch_comment;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $m_id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $iiko_code;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $base_category;

    public function __construct() {

        $this->setStatus(true);
        $this->setPrice(0);
        $this->setDateLastChange(new \DateTime('now'));
        $this->setDisregard(false);

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

    public function getSale(): ?float
    {
        return $this->sale;
    }

    public function setSale(float $sale): self
    {
        $this->sale = $sale;

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

    public function getcostPrice(): ?float
    {
        return $this->cost_price;
    }

    public function setcostPrice(float $cost_price): self
    {
        $this->cost_price = $cost_price;

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

    public function getDateLastChange(): ?\DateTimeInterface
    {
        return $this->date_last_change;
    }

    public function setDateLastChange(\DateTimeInterface $date_last_change): self
    {
        $this->date_last_change = $date_last_change;

        return $this;
    }

    public function getDisregard(): ?bool
    {
        return $this->disregard;
    }

    public function setDisregard(bool $disregard): self
    {
        $this->disregard = $disregard;

        return $this;
    }

    public function getCategory(): ?OldCategory
    {
        return $this->category;
    }

    public function setCategory(?OldCategory $category): self
    {
        $this->category = $category;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getAbcSale()
    {
        return $this->abc_sale;
    }

    /**
     * @param mixed $abc_sale
     */
    public function setAbcSale($abc_sale)
    {
        $this->abc_sale = $abc_sale;
    }

    /**
     * @return mixed
     */
    public function getAbcVir()
    {
        return $this->abc_vir;
    }

    /**
     * @param mixed $abc_vir
     */
    public function setAbcVir($abc_vir)
    {
        $this->abc_vir = $abc_vir;
    }

    /**
     * @return mixed
     */
    public function getAbcMarj()
    {
        return $this->abc_marj;
    }

    /**
     * @param mixed $abc_marj
     */
    public function setAbcMarj($abc_cost_price)
    {
        $this->abc_marj = $abc_cost_price;
    }

    /**
     * @return mixed
     */
    public function getVir()
    {
        if(isset($this->vir)) {
            return $this->vir;
        } else {
            return $this->getSale()*$this->getPrice();
        }
    }

    /**
     * @param mixed $vir
     */
    public function setVir($vir)
    {
        $this->vir = $vir;
    }

    /**
     * @return mixed
     */
    public function getMarj()
    {
        return $this->marj;
    }

    /**
     * @param mixed $marj
     */
    public function setMarj($marj)
    {
        $this->marj = $marj;
    }

    /**
     * @return mixed
     */
    public function getProductCat()
    {
        return $this->product_cat;
    }

    /**
     * @param mixed $product_cat
     */
    public function setProductCat($product_cat)
    {
        $this->product_cat = $product_cat;
    }

    /**
     * @return mixed
     */
    public function getPerCostPrice()
    {
        return $this->per_cost_price;
    }

    /**
     * @param mixed $perCostPrice
     */
    public function setPerCostPrice($per_cost_price)
    {
        $this->per_cost_price = $per_cost_price;
    }

    /**
     * @return mixed
     */
    public function getFirstOtchName()
    {
        return $this->first_otch_name;
    }

    /**
     * @param mixed $first_otch_name
     */
    public function setFirstOtchName($first_otch_name)
    {
        $this->first_otch_name = $first_otch_name;
    }

    /**
     * @return mixed
     */
    public function getFirstOtchComment()
    {
        return $this->first_otch_comment;
    }

    /**
     * @param mixed $first_otch_comment
     */
    public function setFirstOtchComment($first_otch_comment)
    {
        $this->first_otch_comment = $first_otch_comment;
    }

    /**
     * @return mixed
     */
    public function getSecondOtchName()
    {
        return $this->second_otch_name;
    }

    /**
     * @param mixed $second_otch_name
     */
    public function setSecondOtchName($second_otch_name)
    {
        $this->second_otch_name = $second_otch_name;
    }

    /**
     * @return mixed
     */
    public function getSecondOtchComment()
    {
        return $this->second_otch_comment;
    }

    /**
     * @param mixed $second_otch_comment
     */
    public function setSecondOtchComment($second_otch_comment)
    {
        $this->second_otch_comment = $second_otch_comment;
    }


    public function getProduct(): ?Product
    {
        return $this->product;
    }

    public function setProduct(?Product $product): self
    {
        $this->product = $product;

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

    public function getIikoCode(): ?string
    {
        return $this->iiko_code;
    }

    public function setIikoCode(?string $iiko_code): self
    {
        $this->iiko_code = $iiko_code;

        return $this;
    }

    public function getArrayParam($array = ['id','name','sale','price','cost_price','vir','marj']){

        $array_param = [];

        $array_param['category_edit'] = false;
        $array_param['loading2'] = false;
        $array_param['abc_sale'] = $this->getAbcSale();
        $array_param['abc_vir'] = $this->getAbcVir();
        $array_param['abc_marj'] = $this->getAbcMarj();
        $array_param['product_cat'] = $this->getProductCat();
        $array_param['per_cost_price'] = $this->getPerCostPrice();
        $array_param['first_otch_name'] = $this->getFirstOtchName();
        $array_param['first_otch_comment'] = $this->getFirstOtchComment();
        $array_param['second_otch_name'] = $this->getSecondOtchName();
        $array_param['second_otch_comment'] = $this->getSecondOtchComment();
        $array_param['category'] = ['id' => $this->getCategory()->getId(),
            'name' => $this->getCategory()->getName()];

        foreach ($array as $name_property){
            if (isset($this->$name_property)){

                $array_param[$name_property] = $this->$name_property;
            }
        }
        return $array_param;

    }

    public function getBaseCategory(): ?int
    {
        return $this->base_category;
    }

    public function setBaseCategory(?int $base_category): self
    {
        $this->base_category = $base_category;

        return $this;
    }


}
