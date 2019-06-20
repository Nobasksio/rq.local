<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\IikoProductRepository")
 */
class IikoProduct
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, unique = true)
     */
    private $iiko_id;

    /**
     * @ORM\Column(type="boolean")
     */
    private $deleted;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $num;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $code;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $parent;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $modifiers;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $category;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $frontImageId;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $position;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $main_unit;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $excluded_sections;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $placeType;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $type;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $unit_weight;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $unit_capacity;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Project", inversedBy="iikoProducts")
     */
    private $project;

    public function __construct()
    {
        $this->project = new ArrayCollection();
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

    public function getDeleted(): ?bool
    {
        return $this->deleted;
    }

    public function setDeleted(bool $deleted): self
    {
        $this->deleted = $deleted;

        return $this;
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getNum(): ?string
    {
        return $this->num;
    }

    public function setNum(?string $num): self
    {
        $this->num = $num;

        return $this;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(?string $code): self
    {
        $this->code = $code;

        return $this;
    }

    public function getParent(): ?string
    {
        return $this->parent;
    }

    public function setParent(?string $parent): self
    {
        $this->parent = $parent;

        return $this;
    }

    public function getModifiers(): ?string
    {
        return $this->modifiers;
    }

    public function setModifiers(?string $modifiers): self
    {
        $this->modifiers = $modifiers;

        return $this;
    }

    public function getCategory(): ?string
    {
        return $this->category;
    }

    public function setCategory(?string $category): self
    {
        $this->category = $category;

        return $this;
    }

    public function getFrontImageId(): ?string
    {
        return $this->frontImageId;
    }

    public function setFrontImageId(?string $frontImageId): self
    {
        $this->frontImageId = $frontImageId;

        return $this;
    }

    public function getPosition(): ?string
    {
        return $this->position;
    }

    public function setPosition(?string $position): self
    {
        $this->position = $position;

        return $this;
    }

    public function getMainUnit(): ?string
    {
        return $this->main_unit;
    }

    public function setMainUnit(?string $main_unit): self
    {
        $this->main_unit = $main_unit;

        return $this;
    }

    public function getExcludedSections(): ?string
    {
        return $this->excluded_sections;
    }

    public function setExcludedSections(?string $excluded_sections): self
    {
        $this->excluded_sections = $excluded_sections;

        return $this;
    }

    public function getPlaceType(): ?string
    {
        return $this->placeType;
    }

    public function setPlaceType(?string $placeType): self
    {
        $this->placeType = $placeType;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(?string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getUnitWeight(): ?string
    {
        return $this->unit_weight;
    }

    public function setUnitWeight(?string $unit_weight): self
    {
        $this->unit_weight = $unit_weight;

        return $this;
    }

    public function getUnitCapacity(): ?string
    {
        return $this->unit_capacity;
    }

    public function setUnitCapacity(?string $unit_capacity): self
    {
        $this->unit_capacity = $unit_capacity;

        return $this;
    }

    /**
     * @return Collection|Project[]
     */
    public function getProject(): Collection
    {
        return $this->project;
    }

    public function addProject(Project $project): self
    {
        if (!$this->project->contains($project)) {
            $this->project[] = $project;
        }

        return $this;
    }

    public function removeProject(Project $project): self
    {
        if ($this->project->contains($project)) {
            $this->project->removeElement($project);
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
}
