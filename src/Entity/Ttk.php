<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TtkRepository")
 */
class Ttk
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $number;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Product", inversedBy="ttk")
     */
    private $product;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $technology;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $comment;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\TtkComponent", mappedBy="Ttk")
     */
    private $ttkComponents;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $iiko_id;


    public function __construct()
    {
        $this->ttkComponents = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumber(): ?string
    {
        return $this->number;
    }

    public function setNumber(string $number): self
    {
        $this->number = $number;

        return $this;
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

    public function getTechnology(): ?string
    {
        return $this->technology;
    }

    public function setTechnology(?string $technology): self
    {
        $this->technology = $technology;

        return $this;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(?string $comment): self
    {
        $this->comment = $comment;

        return $this;
    }

    /**
     * @return Collection|TtkComponent[]
     */
    public function getTtkComponents(): Collection
    {
        return $this->ttkComponents;
    }

    public function addTtkComponent(TtkComponent $ttkComponent): self
    {
        if (!$this->ttkComponents->contains($ttkComponent)) {
            $this->ttkComponents[] = $ttkComponent;
            $ttkComponent->setTtk($this);
        }

        return $this;
    }

    public function removeTtkComponent(TtkComponent $ttkComponent): self
    {
        if ($this->ttkComponents->contains($ttkComponent)) {
            $this->ttkComponents->removeElement($ttkComponent);
            // set the owning side to null (unless already changed)
            if ($ttkComponent->getTtk() === $this) {
                $ttkComponent->setTtk(null);
            }
        }

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getIikoId(): ?string
    {
        return $this->iiko_id;
    }

    public function setIikoId(?string $iiko_id): self
    {
        $this->iiko_id = $iiko_id;

        return $this;
    }



}
