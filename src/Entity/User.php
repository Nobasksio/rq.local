<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @UniqueEntity(fields="email", message="Пользователь с таким эмэилом уже существует")
 */
class User implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     * @Assert\NotBlank()
     * @Assert\Email()
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @Assert\NotBlank()
     * @Assert\Length(min=3,max=300)
     */
    private $plainPassword;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     * @Assert\Length(min=3,max=300)
     */

    private $Name;

    /**
     * @ORM\Column(type="datetime")
     *
     */
    private $date_create;

    /**
     * @ORM\Column(type="boolean")
     */
    private $status;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Project", inversedBy="users")
     */
    private $projects;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\DegustationScore", mappedBy="user")
     */
    private $degustationScores;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\NameMenu", mappedBy="User")
     */
    private $namesMenu;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\DescriptionMenu", mappedBy="user")
     */
    private $descriptionsMenu;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\UserProjectRole", mappedBy="User")
     */
    private $userProjectRoles;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Company", mappedBy="created_by")
     */
    private $created_companies;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Comment", mappedBy="user")
     */
    private $comments;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Plate", mappedBy="user_create")
     */
    private $plates;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\PlateMatrix", mappedBy="user")
     */
    private $plateMatrices;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\PlatePatern", mappedBy="user")
     */
    private $platePaterns;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\PlatePaternMatrix", mappedBy="user")
     */
    private $platePaternMatrices;

    public function __construct()
    {
        $this->projects = new ArrayCollection();
        $this->degustationScores = new ArrayCollection();
        $this->namesMenu = new ArrayCollection();
        $this->descriptionsMenu = new ArrayCollection();
        $this->userProjectRoles = new ArrayCollection();
        $this->created_companies = new ArrayCollection();
        $this->comments = new ArrayCollection();
        $this->plates = new ArrayCollection();
        $this->plateMatrices = new ArrayCollection();
        $this->platePaterns = new ArrayCollection();
        $this->platePaternMatrices = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getName(): ?string
    {
        return $this->Name;
    }

    public function setName(string $Name): self
    {
        $this->Name = $Name;

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

    public function getStatus(): ?bool
    {
        return $this->status;
    }

    public function setStatus(bool $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getPlainPassword(): ?string
    {
        return $this->plainPassword;
    }


    public function setPlainPassword($plainPassword)
    {
        $this->plainPassword = $plainPassword;
    }

    /**
     * @return Collection|Project[]
     */
    public function getProjects(): Collection
    {
        return $this->projects;
    }

    public function addProject(Project $project): self
    {
        if (!$this->projects->contains($project)) {
            $this->projects[] = $project;
        }

        return $this;
    }

    public function removeProject(Project $project): self
    {
        if ($this->projects->contains($project)) {
            $this->projects->removeElement($project);
        }

        return $this;
    }

    /**
     * @return Collection|DegustationScore[]
     */
    public function getDegustationScores(): Collection
    {
        return $this->degustationScores;
    }

    public function addDegustationScore(DegustationScore $degustationScore): self
    {
        if (!$this->degustationScores->contains($degustationScore)) {
            $this->degustationScores[] = $degustationScore;
            $degustationScore->setUser($this);
        }

        return $this;
    }

    public function removeDegustationScore(DegustationScore $degustationScore): self
    {
        if ($this->degustationScores->contains($degustationScore)) {
            $this->degustationScores->removeElement($degustationScore);
            // set the owning side to null (unless already changed)
            if ($degustationScore->getUser() === $this) {
                $degustationScore->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|NameMenu[]
     */
    public function getNamesMenu(): Collection
    {
        return $this->namesMenu;
    }

    public function addNamesMenu(NameMenu $namesMenu): self
    {
        if (!$this->namesMenu->contains($namesMenu)) {
            $this->namesMenu[] = $namesMenu;
            $namesMenu->setUser($this);
        }

        return $this;
    }

    public function removeNamesMenu(NameMenu $namesMenu): self
    {
        if ($this->namesMenu->contains($namesMenu)) {
            $this->namesMenu->removeElement($namesMenu);
            // set the owning side to null (unless already changed)
            if ($namesMenu->getUser() === $this) {
                $namesMenu->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|DescriptionMenu[]
     */
    public function getDescriptionsMenu(): Collection
    {
        return $this->descriptionsMenu;
    }

    public function addDescriptionsMenu(DescriptionMenu $descriptionsMenu): self
    {
        if (!$this->descriptionsMenu->contains($descriptionsMenu)) {
            $this->descriptionsMenu[] = $descriptionsMenu;
            $descriptionsMenu->setUser($this);
        }

        return $this;
    }

    public function removeDescriptionsMenu(DescriptionMenu $descriptionsMenu): self
    {
        if ($this->descriptionsMenu->contains($descriptionsMenu)) {
            $this->descriptionsMenu->removeElement($descriptionsMenu);
            // set the owning side to null (unless already changed)
            if ($descriptionsMenu->getUser() === $this) {
                $descriptionsMenu->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|UserProjectRole[]
     */
    public function getUserProjectRoles(): Collection
    {
        return $this->userProjectRoles;
    }

    public function addUserProjectRole(UserProjectRole $userProjectRole): self
    {
        if (!$this->userProjectRoles->contains($userProjectRole)) {
            $this->userProjectRoles[] = $userProjectRole;
            $userProjectRole->setUser($this);
        }

        return $this;
    }

    public function removeUserProjectRole(UserProjectRole $userProjectRole): self
    {
        if ($this->userProjectRoles->contains($userProjectRole)) {
            $this->userProjectRoles->removeElement($userProjectRole);
            // set the owning side to null (unless already changed)
            if ($userProjectRole->getUser() === $this) {
                $userProjectRole->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Company[]
     */
    public function getCreatedCompanies(): Collection
    {
        return $this->created_companies;
    }

    public function addCreatedCompany(Company $createdCompany): self
    {
        if (!$this->created_companies->contains($createdCompany)) {
            $this->created_companies[] = $createdCompany;
            $createdCompany->setCreatedBy($this);
        }

        return $this;
    }

    public function removeCreatedCompany(Company $createdCompany): self
    {
        if ($this->created_companies->contains($createdCompany)) {
            $this->created_companies->removeElement($createdCompany);
            // set the owning side to null (unless already changed)
            if ($createdCompany->getCreatedBy() === $this) {
                $createdCompany->setCreatedBy(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Comment[]
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comment $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments[] = $comment;
            $comment->setUser($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        if ($this->comments->contains($comment)) {
            $this->comments->removeElement($comment);
            // set the owning side to null (unless already changed)
            if ($comment->getUser() === $this) {
                $comment->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Plate[]
     */
    public function getPlates(): Collection
    {
        return $this->plates;
    }

    public function addPlate(Plate $plate): self
    {
        if (!$this->plates->contains($plate)) {
            $this->plates[] = $plate;
            $plate->setUserCreate($this);
        }

        return $this;
    }

    public function removePlate(Plate $plate): self
    {
        if ($this->plates->contains($plate)) {
            $this->plates->removeElement($plate);
            // set the owning side to null (unless already changed)
            if ($plate->getUserCreate() === $this) {
                $plate->setUserCreate(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|PlateMatrix[]
     */
    public function getPlateMatrices(): Collection
    {
        return $this->plateMatrices;
    }

    public function addPlateMatrix(PlateMatrix $plateMatrix): self
    {
        if (!$this->plateMatrices->contains($plateMatrix)) {
            $this->plateMatrices[] = $plateMatrix;
            $plateMatrix->setUser($this);
        }

        return $this;
    }

    public function removePlateMatrix(PlateMatrix $plateMatrix): self
    {
        if ($this->plateMatrices->contains($plateMatrix)) {
            $this->plateMatrices->removeElement($plateMatrix);
            // set the owning side to null (unless already changed)
            if ($plateMatrix->getUser() === $this) {
                $plateMatrix->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|PlatePatern[]
     */
    public function getPlatePaterns(): Collection
    {
        return $this->platePaterns;
    }

    public function addPlatePatern(PlatePatern $platePatern): self
    {
        if (!$this->platePaterns->contains($platePatern)) {
            $this->platePaterns[] = $platePatern;
            $platePatern->setUser($this);
        }

        return $this;
    }

    public function removePlatePatern(PlatePatern $platePatern): self
    {
        if ($this->platePaterns->contains($platePatern)) {
            $this->platePaterns->removeElement($platePatern);
            // set the owning side to null (unless already changed)
            if ($platePatern->getUser() === $this) {
                $platePatern->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|PlatePaternMatrix[]
     */
    public function getPlatePaternMatrices(): Collection
    {
        return $this->platePaternMatrices;
    }

    public function addPlatePaternMatrix(PlatePaternMatrix $platePaternMatrix): self
    {
        if (!$this->platePaternMatrices->contains($platePaternMatrix)) {
            $this->platePaternMatrices[] = $platePaternMatrix;
            $platePaternMatrix->setUser($this);
        }

        return $this;
    }

    public function removePlatePaternMatrix(PlatePaternMatrix $platePaternMatrix): self
    {
        if ($this->platePaternMatrices->contains($platePaternMatrix)) {
            $this->platePaternMatrices->removeElement($platePaternMatrix);
            // set the owning side to null (unless already changed)
            if ($platePaternMatrix->getUser() === $this) {
                $platePaternMatrix->setUser(null);
            }
        }

        return $this;
    }


}
