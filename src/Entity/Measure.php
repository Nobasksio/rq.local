<?php

namespace App\Entity;

use App\Utility\EntityHelper;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\MeasureRepository")
 */
class Measure extends EntityHelper
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
     * @ORM\Column(type="boolean")
     */
    private $active;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\TtkComponent", mappedBy="measure")
     */
    private $ttkComponents;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $iiko_id;

    public function __construct() {
        $this->setActive(true);
        $this->ttkComponents = new ArrayCollection();
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

    public function getActive(): ?bool
    {
        return $this->active;
    }

    public function setActive(bool $active): self
    {
        $this->active = $active;

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
            $ttkComponent->setMeasure($this);
        }

        return $this;
    }

    public function removeTtkComponent(TtkComponent $ttkComponent): self
    {
        if ($this->ttkComponents->contains($ttkComponent)) {
            $this->ttkComponents->removeElement($ttkComponent);
            // set the owning side to null (unless already changed)
            if ($ttkComponent->getMeasure() === $this) {
                $ttkComponent->setMeasure(null);
            }
        }

        return $this;
    }
    public function getArrayParam($array = ['id','name']){

        $array_param = [];

        foreach ($array as $name_property){
            if (isset($this->$name_property)){

                $array_param[$name_property] = $this->$name_property;
            }
        }
        return $array_param;


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
