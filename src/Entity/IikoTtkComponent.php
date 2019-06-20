<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\IikoTtkComponentRepository")
 */
class IikoTtkComponent
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
    private $iiko_id;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $sort;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $iiko_product_id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $product_size_specification;

    /**
     * @ORM\Column(type="string", length=2000, nullable=true)
     */
    private $store_specification;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $amount_in;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $netto;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $amount_out;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\IikoTtk", inversedBy="iikoTtkComponents")
     */
    private $iiko_ttk;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getSort(): ?int
    {
        return $this->sort;
    }

    public function setSort(?int $sort): self
    {
        $this->sort = $sort;

        return $this;
    }

    public function getIikoProductId(): ?string
    {
        return $this->iiko_product_id;
    }

    public function setIikoProductId(string $iiko_product_id): self
    {
        $this->iiko_product_id = $iiko_product_id;

        return $this;
    }

    public function getProductSizeSpecification(): ?string
    {
        return $this->product_size_specification;
    }

    public function setProductSizeSpecification(?string $product_size_specification): self
    {
        $this->product_size_specification = $product_size_specification;

        return $this;
    }

    public function getStoreSpecification(): ?string
    {
        return $this->store_specification;
    }

    public function setStoreSpecification(?string $store_specification): self
    {
        $this->store_specification = $store_specification;

        return $this;
    }

    public function getAmountIn(): ?string
    {
        return $this->amount_in;
    }

    public function setAmountIn(?string $amount_in): self
    {
        $this->amount_in = $amount_in;

        return $this;
    }

    public function getNetto(): ?string
    {
        return $this->netto;
    }

    public function setNetto(?string $netto): self
    {
        $this->netto = $netto;

        return $this;
    }

    public function getAmountOut(): ?string
    {
        return $this->amount_out;
    }

    public function setAmountOut(?string $amount_out): self
    {
        $this->amount_out = $amount_out;

        return $this;
    }

    public function getIikoTtk(): ?IikoTtk
    {
        return $this->iiko_ttk;
    }

    public function setIikoTtk(?IikoTtk $iiko_ttk): self
    {
        $this->iiko_ttk = $iiko_ttk;

        return $this;
    }
}
