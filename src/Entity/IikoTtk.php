<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\IikoTtkRepository")
 */
class IikoTtk
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
     * @ORM\Column(type="string", length=255)
     */
    private $iiko_product_id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $date_from;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $date_to;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $assembled_amount;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $product_size_assembly_strategy;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $apperance;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description_second;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $output_comment;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\IikoTtkComponent", mappedBy="iiko_ttk")
     */
    private $iikoTtkComponents;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $netto_ttk;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $date_update;

    public function __construct()
    {
        $this->iikoTtkComponents = new ArrayCollection();
    }

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

    public function getIikoProductId(): ?string
    {
        return $this->iiko_product_id;
    }

    public function setIikoProductId(string $iiko_product_id): self
    {
        $this->iiko_product_id = $iiko_product_id;

        return $this;
    }

    public function getDateFrom(): ?string
    {
        return $this->date_from;
    }

    public function setDateFrom(?string $date_from): self
    {
        $this->date_from = $date_from;

        return $this;
    }

    public function getDateTo(): ?string
    {
        return $this->date_to;
    }

    public function setDateTo(?string $date_to): self
    {
        $this->date_to = $date_to;

        return $this;
    }

    public function getAssembledAmount(): ?string
    {
        return $this->assembled_amount;
    }

    public function setAssembledAmount(?string $assembled_amount): self
    {
        $this->assembled_amount = $assembled_amount;

        return $this;
    }

    public function getProductSizeAssemblyStrategy(): ?string
    {
        return $this->product_size_assembly_strategy;
    }

    public function setProductSizeAssemblyStrategy(?string $product_size_assembly_strategy): self
    {
        $this->product_size_assembly_strategy = $product_size_assembly_strategy;

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

    public function getApperance(): ?string
    {
        return $this->apperance;
    }

    public function setApperance(?string $apperance): self
    {
        $this->apperance = $apperance;

        return $this;
    }

    public function getDescriptionSecond(): ?string
    {
        return $this->description_second;
    }

    public function setDescriptionSecond(?string $description_second): self
    {
        $this->description_second = $description_second;

        return $this;
    }

    public function getOutputComment(): ?string
    {
        return $this->output_comment;
    }

    public function setOutputComment(?string $output_comment): self
    {
        $this->output_comment = $output_comment;

        return $this;
    }

    /**
     * @return Collection|IikoTtkComponent[]
     */
    public function getIikoTtkComponents(): Collection
    {
        return $this->iikoTtkComponents;
    }

    public function addIikoTtkComponent(IikoTtkComponent $iikoTtkComponent): self
    {
        if (!$this->iikoTtkComponents->contains($iikoTtkComponent)) {
            $this->iikoTtkComponents[] = $iikoTtkComponent;
            $iikoTtkComponent->setIikoTtk($this);
        }

        return $this;
    }

    public function removeIikoTtkComponent(IikoTtkComponent $iikoTtkComponent): self
    {
        if ($this->iikoTtkComponents->contains($iikoTtkComponent)) {
            $this->iikoTtkComponents->removeElement($iikoTtkComponent);
            // set the owning side to null (unless already changed)
            if ($iikoTtkComponent->getIikoTtk() === $this) {
                $iikoTtkComponent->setIikoTtk(null);
            }
        }

        return $this;
    }

    public function getNettoTtk(): ?string
    {
        return $this->netto_ttk;
    }

    public function setNettoTtk(?string $netto_ttk): self
    {
        $this->netto_ttk = $netto_ttk;

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
}
