<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\DegustationScoreRepository")
 */
class DegustationScore
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Product", inversedBy="degustationScores")
     */
    private $product;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $taste_score;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $view_score;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $concept_score;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $price_score;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $comment;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date_update;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="degustationScores")
     * @ORM\JoinColumn(nullable=true)
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Degustation", inversedBy="scores")
     */
    private $degustation;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $degustation_user_hash;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getTasteScore(): ?int
    {
        return $this->taste_score;
    }

    public function setTasteScore(int $taste_score): self
    {
        $this->taste_score = $taste_score;

        return $this;
    }

    public function getViewScore(): ?int
    {
        return $this->view_score;
    }

    public function setViewScore(int $view_score): self
    {
        $this->view_score = $view_score;

        return $this;
    }

    public function getConceptScore(): ?int
    {
        return $this->concept_score;
    }

    public function setConceptScore(int $concept_score): self
    {
        $this->concept_score = $concept_score;

        return $this;
    }

    public function getPriceScore(): ?float
    {
        return $this->price_score;
    }

    public function setPriceScore(float $price_score): self
    {
        $this->price_score = $price_score;

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

    public function getDateUpdate(): ?\DateTimeInterface
    {
        return $this->date_update;
    }

    public function setDateUpdate(\DateTimeInterface $date_update): self
    {
        $this->date_update = $date_update;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getDegustation(): ?Degustation
    {
        return $this->degustation;
    }

    public function setDegustation(?Degustation $degustation): self
    {
        $this->degustation = $degustation;

        return $this;
    }

    public function getDegustationUserHash(): ?string
    {
        return $this->degustation_user_hash;
    }

    public function setDegustationUserHash(string $degustation_user_hash): self
    {
        $this->degustation_user_hash = $degustation_user_hash;

        return $this;
    }
}
