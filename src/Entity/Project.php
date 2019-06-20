<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use App\Entity\User;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\MaxDepth;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ProjectRepository")
 */
class Project
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"default"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"default"})
     */
    private $project_name;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $company_id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $kitchen_type;

    /**
     * @ORM\Column(type="integer")
     */
    private $type_menu;

    /**
     * @ORM\Column(type="integer")
     */
    private $old_menu_id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date_create;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $hash;

    private $type_category;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\User", mappedBy="projects")
     * @MaxDepth(1)
     */

    private $users;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Analitics", mappedBy="project")
     * @MaxDepth(1)
     */
    private $analitics;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Product", mappedBy="project")
     * @MaxDepth(1)
     */
    private $products;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Category", mappedBy="project")
     * @MaxDepth(1)
     */
    private $categories;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Degustation", mappedBy="project")
     * @MaxDepth(1)
     */
    private $degustations;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Subcategory", mappedBy="project")
     */
    private $subcategories;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $m_id;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\TypeProduct", mappedBy="project")
     */
    private $typeProducts;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Department", mappedBy="project")
     */
    private $departments;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\IikoProduct", mappedBy="project")
     */
    private $iikoProducts;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\IikoCategory", mappedBy="project")
     */
    private $iikoCategories;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Department", inversedBy="projects")
     */
    private $iiko_department;

    public function __construct()
    {
        $this->users = new ArrayCollection();
        $this->setDateCreate(new \DateTime('now'));
        $this->setCompanyId(0);
        $this->setOldMenuId(0);

        $this->setKitchenType('');
        $this->setTypeMenu(1);
        $this->setHash(md5(rand(0,123232332)));
        $this->analitics = new ArrayCollection();
        $this->products = new ArrayCollection();
        $this->categories = new ArrayCollection();
        $this->degustations = new ArrayCollection();
        $this->subcategories = new ArrayCollection();
        $this->typeProducts = new ArrayCollection();
        $this->departments = new ArrayCollection();
        $this->iikoProducts = new ArrayCollection();
        $this->iikoCategories = new ArrayCollection();
        $this->iiko_department = new ArrayCollection();

    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProjectName(): ?string
    {
        return $this->project_name;
    }

    public function setProjectName(string $project_name): self
    {
        $this->project_name = $project_name;

        return $this;
    }

    public function getCompanyId(): ?int
    {
        return $this->company_id;
    }

    public function setCompanyId(?int $company_id): self
    {
        $this->company_id = $company_id;

        return $this;
    }

    public function getKitchenType(): ?string
    {
        return $this->kitchen_type;
    }

    public function setKitchenType(string $kitchen_type): self
    {
        $this->kitchen_type = $kitchen_type;

        return $this;
    }

    public function getTypeMenu(): ?int
    {
        return $this->type_menu;
    }

    public function setTypeMenu(int $type_menu): self
    {
        $this->type_menu = $type_menu;

        return $this;
    }

    public function getOldMenuId(): ?int
    {
        return $this->old_menu_id;
    }

    public function setOldMenuId(int $old_menu_id): self
    {
        $this->old_menu_id = $old_menu_id;

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
     * @return Collection|User[]
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users[] = $user;
            $user->addProject($this);
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->users->contains($user)) {
            $this->users->removeElement($user);
            $user->removeProject($this);
        }

        return $this;
    }


    /**
     * @return mixed
     */
    public function getTypeCategory()
    {
        return $this->type_category;
    }

    /**
     * @param mixed $type_category
     */
    public function setTypeCategory($type_category)
    {
        $this->type_category = $type_category;
    }

    /**
     * @return Collection|Analitics[]
     */
    public function getAnalitics(): Collection
    {
        return $this->analitics;
    }

    public function addAnalitic(Analitics $analitic): self
    {
        if (!$this->analitics->contains($analitic)) {
            $this->analitics[] = $analitic;
            $analitic->setProject($this);
        }

        return $this;
    }

    public function removeAnalitic(Analitics $analitic): self
    {
        if ($this->analitics->contains($analitic)) {
            $this->analitics->removeElement($analitic);
            // set the owning side to null (unless already changed)
            if ($analitic->getProject() === $this) {
                $analitic->setProject(null);
            }
        }

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
            $product->setProjectId($this);
        }

        return $this;
    }

    public function removeProduct(Product $product): self
    {
        if ($this->products->contains($product)) {
            $this->products->removeElement($product);
            // set the owning side to null (unless already changed)
            if ($product->getProjectId() === $this) {
                $product->setProjectId(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Category[]
     */
    public function getCategories(): Collection
    {
        return $this->categories;
    }

    public function addCategory(Category $category): self
    {
        if (!$this->categories->contains($category)) {
            $this->categories[] = $category;
            $category->setProjectId($this);
        }

        return $this;
    }

    public function removeCategory(Category $category): self
    {
        if ($this->categories->contains($category)) {
            $this->categories->removeElement($category);
            // set the owning side to null (unless already changed)
            if ($category->getProjectId() === $this) {
                $category->setProjectId(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Degustation[]
     */
    public function getDegustations(): Collection
    {
        return $this->degustations;
    }

    public function addDegustation(Degustation $degustation): self
    {
        if (!$this->degustations->contains($degustation)) {
            $this->degustations[] = $degustation;
            $degustation->setproject($this);
        }

        return $this;
    }

    public function removeDegustation(Degustation $degustation): self
    {
        if ($this->degustations->contains($degustation)) {
            $this->degustations->removeElement($degustation);
            // set the owning side to null (unless already changed)
            if ($degustation->getз�project() === $this) {
                $degustation->setз�project(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Subcategory[]
     */
    public function getSubcategories(): Collection
    {
        return $this->subcategories;
    }

    public function addSubcategory(Subcategory $subcategory): self
    {
        if (!$this->subcategories->contains($subcategory)) {
            $this->subcategories[] = $subcategory;
            $subcategory->setProject($this);
        }

        return $this;
    }

    public function removeSubcategory(Subcategory $subcategory): self
    {
        if ($this->subcategories->contains($subcategory)) {
            $this->subcategories->removeElement($subcategory);
            // set the owning side to null (unless already changed)
            if ($subcategory->getProject() === $this) {
                $subcategory->setProject(null);
            }
        }

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

    /**
     * @return Collection|TypeProduct[]
     */
    public function getTypeProducts(): Collection
    {
        return $this->typeProducts;
    }

    public function addTypeProduct(TypeProduct $typeProduct): self
    {
        if (!$this->typeProducts->contains($typeProduct)) {
            $this->typeProducts[] = $typeProduct;
            $typeProduct->setProject($this);
        }

        return $this;
    }

    public function removeTypeProduct(TypeProduct $typeProduct): self
    {
        if ($this->typeProducts->contains($typeProduct)) {
            $this->typeProducts->removeElement($typeProduct);
            // set the owning side to null (unless already changed)
            if ($typeProduct->getProject() === $this) {
                $typeProduct->setProject(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Department[]
     */
    public function getDepartments(): Collection
    {
        return $this->departments;
    }

    public function addDepartment(Department $department): self
    {
        if (!$this->departments->contains($department)) {
            $this->departments[] = $department;
            $department->addProject($this);
        }

        return $this;
    }

    public function removeDepartment(Department $department): self
    {
        if ($this->departments->contains($department)) {
            $this->departments->removeElement($department);
            $department->removeProject($this);
        }

        return $this;
    }

    /**
     * @return Collection|IikoProduct[]
     */
    public function getIikoProducts(): Collection
    {
        return $this->iikoProducts;
    }

    public function addIikoProduct(IikoProduct $iikoProduct): self
    {
        if (!$this->iikoProducts->contains($iikoProduct)) {
            $this->iikoProducts[] = $iikoProduct;
            $iikoProduct->addProject($this);
        }

        return $this;
    }

    public function removeIikoProduct(IikoProduct $iikoProduct): self
    {
        if ($this->iikoProducts->contains($iikoProduct)) {
            $this->iikoProducts->removeElement($iikoProduct);
            $iikoProduct->removeProject($this);
        }

        return $this;
    }

    /**
     * @return Collection|IikoCategory[]
     */
    public function getIikoCategories(): Collection
    {
        return $this->iikoCategories;
    }

    public function addIikoCategory(IikoCategory $iikoCategory): self
    {
        if (!$this->iikoCategories->contains($iikoCategory)) {
            $this->iikoCategories[] = $iikoCategory;
            $iikoCategory->addProject($this);
        }

        return $this;
    }

    public function removeIikoCategory(IikoCategory $iikoCategory): self
    {
        if ($this->iikoCategories->contains($iikoCategory)) {
            $this->iikoCategories->removeElement($iikoCategory);
            $iikoCategory->removeProject($this);
        }

        return $this;
    }

    /**
     * @return Collection|Department[]
     */
    public function getIikoDepartment(): Collection
    {
        return $this->iiko_department;
    }

    public function addIikoDepartment(Department $iikoDepartment): self
    {
        if (!$this->iiko_department->contains($iikoDepartment)) {
            $this->iiko_department[] = $iikoDepartment;
        }

        return $this;
    }

    public function removeIikoDepartment(Department $iikoDepartment): self
    {
        if ($this->iiko_department->contains($iikoDepartment)) {
            $this->iiko_department->removeElement($iikoDepartment);
        }

        return $this;
    }

}
