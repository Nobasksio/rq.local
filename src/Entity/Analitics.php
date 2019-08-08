<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\AnaliticsRepository")
 */
class Analitics
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $name;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date_create;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date_start;

    private $date_start_str;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date_finish;

    private $date_finish_str;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $hash;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Project", inversedBy="analitics")
     * @ORM\JoinColumn(nullable=false)
     */
    private $project;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $file;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\OldCategory", mappedBy="analitics")
     */
    private $oldCategories;

    /**
     * @ORM\Column(type="boolean")
     */
    private $status;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $m_id;

    /**
     * @ORM\Column(type="smallint", nullable=true)
     */
    private $type;

    public function __construct() {
        $this->setDateCreate(new \DateTime('now'));
        $this->setHash(md5(rand(0,123232332)));
        $this->oldCategories = new ArrayCollection();
        $this->setFile('');
        $this->setStatus(true);


    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getDateCreate(): ?\DateTimeInterface
    {
        return $this->date_create;
    }

    public function setDateCreate(\DateTimeInterface $date_create): self
    {
        $this->date_create = $date_create;

        return $this;
    }

    public function getDateStart(): ?\DateTimeInterface
    {
        return $this->date_start;
    }

    public function setDateStart(\DateTimeInterface $date_start): self
    {
        $this->date_start = $date_start;

        return $this;
    }

    public function getDateFinish(): ?\DateTimeInterface
    {
        return $this->date_finish;
    }

    public function setDateFinish(\DateTimeInterface $date_finish): self
    {
        $this->date_finish = $date_finish;

        return $this;
    }

    public function getHash(): ?string
    {
        return $this->hash;
    }

    public function setHash(string $hash): self
    {
        $this->hash = $hash;

        return $this;
    }

    public function getProject(): ?Project
    {
        return $this->project;
    }

    public function setProject(?Project $project): self
    {
        $this->project = $project;

        return $this;
    }

    public function getFile(): ?string
    {
        return $this->file;
    }

    public function setFile(string $file): self
    {
        $this->file = $file;

        return $this;
    }

    /**
     * @return string
     */
    public function getDateStartStr()
    {
        return $this->date_start_str;
    }

    /**
     * @param string $date_start_str
     */
    public function setDateStartStr($date_start_str)
    {
        $this->date_start_str = $date_start_str;
    }

    /**
     * @return string
     */
    public function getDateFinishStr()
    {
        return $this->date_finish_str;
    }

    /**
     * @param string $date_finish_str
     */
    public function setDateFinishStr($date_finish_str)
    {
        $this->date_finish_str = $date_finish_str;
    }

    /**
     * @return Collection|OldCategory[]
     */
    public function getOldCategories(): Collection
    {
        return $this->oldCategories;
    }

    public function addOldCategory(OldCategory $oldCategory): self
    {
        if (!$this->oldCategories->contains($oldCategory)) {
            $this->oldCategories[] = $oldCategory;
            $oldCategory->setAnalitics($this);
        }

        return $this;
    }

    public function removeOldCategory(OldCategory $oldCategory): self
    {
        if ($this->oldCategories->contains($oldCategory)) {
            $this->oldCategories->removeElement($oldCategory);
            // set the owning side to null (unless already changed)
            if ($oldCategory->getAnalitics() === $this) {
                $oldCategory->setAnalitics(null);
            }
        }

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

    public function getMId(): ?int
    {
        return $this->m_id;
    }

    public function setMId(?int $m_id): self
    {
        $this->m_id = $m_id;

        return $this;
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
}
