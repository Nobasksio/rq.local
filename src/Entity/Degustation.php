<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints\DateTime;

/**
 * @ORM\Entity(repositoryClass="App\Repository\DegustationRepository")
 */
class Degustation
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Project", inversedBy="degustations")
     * @ORM\JoinColumn(nullable=false)
     */
    private $project;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date;

    private $date_str;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $place;

    /**
     * @ORM\Column(type="integer")
     */
    private $status;

    private $status_arr = [0=>'Отмена',
        1=>'Черновик',
        2=>'Ожидание',
        3=>'Идёт',
        4=>'Завершена',
        5=>'Блюда выбранны'];

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Product", inversedBy="degustations")
     */
    private $products;

    private $count_products;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $hash;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\DegustationScore", mappedBy="degustation")
     */
    private $scores;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $old_id;

    public function __construct() {
        $this->setDate(New \DateTime('now'));
        $this->setStatus(1);
        $this->setHash(md5(uniqid()));
        $this->products = new ArrayCollection();
        $this->scores = new ArrayCollection();

    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getproject(): ?Project
    {
        return $this->project;
    }

    public function setproject(?Project $project): self
    {
        $this->project = $project;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getPlace(): ?string
    {
        return $this->place;
    }

    public function setPlace(?string $place): self
    {
        $this->place = $place;

        return $this;
    }

    public function getStatus(): ?int
    {
        return $this->status;
    }

    public function setStatus(int $status): self
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @return Collection|Product[]
     */
    public function getProducts(): Collection
    {
        return $this->products;
    }

    public function addProduct(Product $product): self
    {
        if (!$this->products->contains($product)) {
            $this->products[] = $product;
        }

        return $this;
    }

    public function removeProduct(Product $product): self
    {
        if ($this->products->contains($product)) {
            $this->products->removeElement($product);
        }

        return $this;
    }

    /**
     * @return mixed
     */
    public function getCountProducts()
    {

        return $this->count_products;
    }

    /**
     * @param mixed $count_products
     */
    public function setCountProducts()
    {
        $count = count($this->getProducts());
        $this->count_products = $count;
    }

    /**
     * @return mixed
     */
    public function getDateStr()
    {
        return $this->date_str;
    }

    /**
     * @param mixed $date_str
     */
    public function setDateStr()
    {
        $this->date_str = $this->date->format('H:i d-m-Y');
    }




    public function getArrayParam($array = ['id','name','place','count_products','status','date_str','hash']){

        $array_param = [];

        $this->setCountProducts();
        $this->setDateStr();
        $status_name = $this->status_arr[$this->getStatus()];
        $array_param['status_name'] = $status_name;
        $array_param['copied'] = false;
        $array_param['link'] = 'http://'.$_SERVER['SERVER_NAME'].'/degustation/'.$this->getHash();

        foreach ($array as $name_property){
            if (isset($this->$name_property)){
                $array_param[$name_property] = $this->$name_property;
            } else {

            }
        }
        $array_param['_rowVariant']='';
        return $array_param;


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

    /**
     * @return Collection|DegustationScore[]
     */
    public function getScores(): Collection
    {
        return $this->scores;
    }

    public function addScore(DegustationScore $score): self
    {
        if (!$this->scores->contains($score)) {
            $this->scores[] = $score;
            $score->setDegustation($this);
        }

        return $this;
    }

    public function removeScore(DegustationScore $score): self
    {
        if ($this->scores->contains($score)) {
            $this->scores->removeElement($score);
            // set the owning side to null (unless already changed)
            if ($score->getDegustation() === $this) {
                $score->setDegustation(null);
            }
        }

        return $this;
    }

    public function getOldId(): ?int
    {
        return $this->old_id;
    }

    public function setOldId(?int $old_id): self
    {
        $this->old_id = $old_id;

        return $this;
    }
}
