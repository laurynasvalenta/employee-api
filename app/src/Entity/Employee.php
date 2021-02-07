<?php

namespace App\Entity;

use App\Repository\EmployeeRepository;
use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\IdGenerator\UuidV4Generator;

/**
 * @ORM\Entity(repositoryClass=EmployeeRepository::class)
 */
class Employee
{
    /**
     * @var string
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\CustomIdGenerator(class=UuidV4Generator::class)
     * @ORM\Column(type="uuid")
     */
    private $id = '';

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=50)
     */
    private $firstname = '';

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=50)
     */
    private $lastname = '';

    /**
     * @var DateTime
     *
     * @ORM\Column(type="date")
     */
    private $birthdate;

    /**
     * @var DateTime
     *
     * @ORM\Column(type="date")
     */
    private $employmentDate;

    /**
     * @var Employee|null
     *
     * @ORM\ManyToOne(targetEntity=Employee::class)
     */
    private $boss;

    /**
     * @var Address
     *
     * @ORM\OneToOne(targetEntity=Address::class, cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $homeAddress;

    /**
     * @var Role
     *
     * @ORM\ManyToOne(targetEntity=Role::class, cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $role;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean")
     */
    private $isDeleted = false;

    public function __construct()
    {
        $this->birthdate = new DateTime();
        $this->employmentDate = new DateTime();
        $this->homeAddress = new Address();
        $this->role = new Role();
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @param string $id
     */
    public function setId(string $id): void
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getFirstname(): string
    {
        return $this->firstname;
    }

    /**
     * @param string $firstname
     */
    public function setFirstname(string $firstname): void
    {
        $this->firstname = $firstname;
    }

    /**
     * @return string
     */
    public function getLastname(): string
    {
        return $this->lastname;
    }

    /**
     * @param string $lastname
     */
    public function setLastname(string $lastname): void
    {
        $this->lastname = $lastname;
    }

    /**
     * @return DateTime
     */
    public function getBirthdate(): DateTime
    {
        return $this->birthdate;
    }

    /**
     * @param DateTime $birthdate
     */
    public function setBirthdate(DateTime $birthdate): void
    {
        $this->birthdate = $birthdate;
    }

    /**
     * @return DateTime
     */
    public function getEmploymentDate(): DateTime
    {
        return $this->employmentDate;
    }

    /**
     * @param DateTime $employmentDate
     */
    public function setEmploymentDate(DateTime $employmentDate): void
    {
        $this->employmentDate = $employmentDate;
    }

    /**
     * @return Employee|null
     */
    public function getBoss(): ?Employee
    {
        return $this->boss;
    }

    /**
     * @param Employee|null $boss
     */
    public function setBoss(?Employee $boss): void
    {
        $this->boss = $boss;
    }

    /**
     * @return Address
     */
    public function getHomeAddress(): Address
    {
        return $this->homeAddress;
    }

    /**
     * @param Address $homeAddress
     */
    public function setHomeAddress(Address $homeAddress): void
    {
        $this->homeAddress = $homeAddress;
    }

    /**
     * @return Role
     */
    public function getRole(): Role
    {
        return $this->role;
    }

    /**
     * @param Role $role
     */
    public function setRole(Role $role): void
    {
        $this->role = $role;
    }

    /**
     * @return bool
     */
    public function isDeleted(): bool
    {
        return $this->isDeleted;
    }

    /**
     * @param bool $isDeleted
     */
    public function setIsDeleted(bool $isDeleted): void
    {
        $this->isDeleted = $isDeleted;
    }
}
