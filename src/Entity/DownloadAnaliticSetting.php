<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\DownloadAnaliticSettingRepository")
 */
class DownloadAnaliticSetting
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Analitics", cascade={"persist", "remove"})
     */
    private $analitics;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $main_iiko_category;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getMainIikoCategory(): ?string
    {
        return $this->main_iiko_category;
    }

    public function setMainIikoCategory(?string $main_iiko_category): self
    {
        $this->main_iiko_category = $main_iiko_category;

        return $this;
    }
}
