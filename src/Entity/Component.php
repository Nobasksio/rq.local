<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ComponentRepository")
 */
class Component
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
     * @ORM\ManyToMany(targetEntity="App\Entity\Ttk", mappedBy="components")
     */
    private $ttks;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\TtkComponent", mappedBy="component")
     */
    private $ttkComponents;

    public function __construct()
    {
        $this->setActive(true);
        $this->ttks = new ArrayCollection();
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
     * @return Collection|Ttk[]
     */
    public function getTtks(): Collection
    {
        return $this->ttks;
    }

    public function addTtk(Ttk $ttk): self
    {
        if (!$this->ttks->contains($ttk)) {
            $this->ttks[] = $ttk;
            $ttk->addComponent($this);
        }

        return $this;
    }

    public function removeTtk(Ttk $ttk): self
    {
        if ($this->ttks->contains($ttk)) {
            $this->ttks->removeElement($ttk);
            $ttk->removeComponent($this);
        }

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
            $ttkComponent->setComponent($this);
        }

        return $this;
    }

    public function removeTtkComponent(TtkComponent $ttkComponent): self
    {
        if ($this->ttkComponents->contains($ttkComponent)) {
            $this->ttkComponents->removeElement($ttkComponent);
            // set the owning side to null (unless already changed)
            if ($ttkComponent->getComponent() === $this) {
                $ttkComponent->setComponent(null);
            }
        }

        return $this;
    }
}
