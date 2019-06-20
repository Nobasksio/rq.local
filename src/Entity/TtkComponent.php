<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TtkComponentRepository")
 */
class TtkComponent
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Ttk", inversedBy="ttkComponents")
     * @ORM\JoinColumn(nullable=false)
     */
    private $Ttk;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Component", inversedBy="ttkComponents")
     */
    private $component;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Measure", inversedBy="ttkComponents")
     * @ORM\JoinColumn(nullable=false)
     */
    private $measure;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $count;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $iiko_id;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTtk(): ?Ttk
    {
        return $this->Ttk;
    }

    public function setTtk(?Ttk $Ttk): self
    {
        $this->Ttk = $Ttk;

        return $this;
    }

    public function getComponent(): ?Component
    {
        return $this->component;
    }

    public function setComponent(?Component $component): self
    {
        $this->component = $component;

        return $this;
    }

    public function getMeasure(): ?Measure
    {
        return $this->measure;
    }

    public function setMeasure(?Measure $measure): self
    {
        $this->measure = $measure;

        return $this;
    }

    public function getCount(): ?string
    {
        return $this->count;
    }

    public function setCount(string $count): self
    {
        $this->count = $count;

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
