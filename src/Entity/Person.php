<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PersonRepository")
 */
class Person implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     * @Assert\NotBlank(message="Vul een loginnaam in")
     */
    private $loginname;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Vul een wachtwoord in")
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Vul een voornaam in")
     * @Assert\Regex(
     *     pattern     = "/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[?!-‌​/_/=:;§]).{8,20}+$/i",
     *     htmlPattern="/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[?!-‌​/_/=:;§]).{8,20}$/",
     *     match=true,
     *     message="message error ")
     * @var string
     */
    private $firstname;

    /**
     * @ORM\Column(type="string", nullable=true ,length=255)
     */
    private $preprovision;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Vul een achternaam in")
     */
    private $lastname;

    /**
     * @ORM\Column(type="datetime")
     * @Assert\NotBlank(message="Vul een geboortedatum in")
     */
    private $dateofbirth;

    /**
     * @ORM\Column(type="string", length=10)
     * @Assert\NotBlank(message="Dit veld mag niet leeg blijven")
     */
    private $gender;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     * @Assert\NotBlank(message="Vul een email in")
     * @Assert\Email(message="Vul een geldige email in")
     */
    private $emailaddress;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Instructor", mappedBy="person")
     */
    private $instructors;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Member", mappedBy="person")
     */
    private $members;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    public function __construct()
    {
        $this->instructors = new ArrayCollection();
        $this->members = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLoginname(): ?string
    {
        return $this->loginname;
    }

    public function setLoginname(string $loginname): self
    {
        $this->loginname = $loginname;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getPreprovision(): ?string
    {
        return $this->preprovision;
    }

    public function setPreprovision(string $preprovision): self
    {
        $this->preprovision = $preprovision;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getDateofbirth(): ?\DateTimeInterface
    {
        return $this->dateofbirth;
    }

    public function setDateofbirth(\DateTimeInterface $dateofbirth): self
    {
        $this->dateofbirth = $dateofbirth;

        return $this;
    }

    public function getGender(): ?string
    {
        return $this->gender;
    }

    public function setGender(string $gender): self
    {
        $this->gender = $gender;

        return $this;
    }

    public function getEmailaddress(): ?string
    {
        return $this->emailaddress;
    }

    public function setEmailaddress(string $emailaddress): self
    {
        $this->emailaddress = $emailaddress;

        return $this;
    }

    /**
     * @return Collection|Instructor[]
     */
    public function getInstructors(): Collection
    {
        return $this->instructors;
    }

    public function addInstructor(Instructor $instructor): self
    {
        if (!$this->instructors->contains($instructor)) {
            $this->instructors[] = $instructor;
            $instructor->setRelation($this);
        }

        return $this;
    }

    public function removeInstructor(Instructor $instructor): self
    {
        if ($this->instructors->contains($instructor)) {
            $this->instructors->removeElement($instructor);
            // set the owning side to null (unless already changed)
            if ($instructor->getRelation() === $this) {
                $instructor->setRelation(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Member[]
     */
    public function getMembers(): Collection
    {
        return $this->members;
    }

    public function addMember(Member $member): self
    {
        if (!$this->members->contains($member)) {
            $this->members[] = $member;
            $member->setPerson($this);
        }

        return $this;
    }

    public function removeMember(Member $member): self
    {
        if ($this->members->contains($member)) {
            $this->members->removeElement($member);
            // set the owning side to null (unless already changed)
            if ($member->getPerson() === $this) {
                $member->setPerson(null);
            }
        }

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->loginname;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_MEMBER
        $roles[] = 'ROLE_MEMBER';

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
    public function getSalt()
    {
        // not needed for apps that do not check user passwords
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }
}
