<?php

namespace Package\EmployeeDto;

use DateTime;

class Employee
{
    /**
     * @var string|null
     */
    private $id;

    /**
     * @var string|null
     */
    private $firstname;

    /**
     * @var string|null
     */
    private $lastname;

    /**
     * @var DateTime|null
     */
    private $birthdate;

    /**
     * @var DateTime|null
     */
    private $employmentDate;

    /**
     * @var string|null
     */
    private $bossId;

    /**
     * @var string|null
     */
    private $homeAddressLine1;

    /**
     * @var string|null
     */
    private $homeAddressLine2;

    /**
     * @var string|null
     */
    private $homeAddressZip;

    /**
     * @var string|null
     */
    private $homeAddressCity;

    /**
     * @var string|null
     */
    private $homeAddressCountry;

    /**
     * @var string|null
     */
    private $roleName;

    /**
     * @return string|null
     */
    public function getId(): ?string
    {
        return $this->id;
    }

    /**
     * @param string|null $id
     */
    public function setId(?string $id): void
    {
        $this->id = $id;
    }

    /**
     * @return string|null
     */
    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    /**
     * @param string|null $firstname
     */
    public function setFirstname(?string $firstname): void
    {
        $this->firstname = $firstname;
    }

    /**
     * @return string|null
     */
    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    /**
     * @param string|null $lastname
     */
    public function setLastname(?string $lastname): void
    {
        $this->lastname = $lastname;
    }

    /**
     * @return DateTime|null
     */
    public function getBirthdate(): ?DateTime
    {
        return $this->birthdate;
    }

    /**
     * @param DateTime|null $birthdate
     */
    public function setBirthdate(?DateTime $birthdate): void
    {
        $this->birthdate = $birthdate;
    }

    /**
     * @return DateTime|null
     */
    public function getEmploymentDate(): ?DateTime
    {
        return $this->employmentDate;
    }

    /**
     * @param DateTime|null $employmentDate
     */
    public function setEmploymentDate(?DateTime $employmentDate): void
    {
        $this->employmentDate = $employmentDate;
    }

    /**
     * @return string|null
     */
    public function getBossId(): ?string
    {
        return $this->bossId;
    }

    /**
     * @param string|null $bossId
     */
    public function setBossId(?string $bossId): void
    {
        $this->bossId = $bossId;
    }

    /**
     * @return string|null
     */
    public function getHomeAddressLine1(): ?string
    {
        return $this->homeAddressLine1;
    }

    /**
     * @param string|null $homeAddressLine1
     */
    public function setHomeAddressLine1(?string $homeAddressLine1): void
    {
        $this->homeAddressLine1 = $homeAddressLine1;
    }

    /**
     * @return string|null
     */
    public function getHomeAddressLine2(): ?string
    {
        return $this->homeAddressLine2;
    }

    /**
     * @param string|null $homeAddressLine2
     */
    public function setHomeAddressLine2(?string $homeAddressLine2): void
    {
        $this->homeAddressLine2 = $homeAddressLine2;
    }

    /**
     * @return string|null
     */
    public function getHomeAddressZip(): ?string
    {
        return $this->homeAddressZip;
    }

    /**
     * @param string|null $homeAddressZip
     */
    public function setHomeAddressZip(?string $homeAddressZip): void
    {
        $this->homeAddressZip = $homeAddressZip;
    }

    /**
     * @return string|null
     */
    public function getHomeAddressCity(): ?string
    {
        return $this->homeAddressCity;
    }

    /**
     * @param string|null $homeAddressCity
     */
    public function setHomeAddressCity(?string $homeAddressCity): void
    {
        $this->homeAddressCity = $homeAddressCity;
    }

    /**
     * @return string|null
     */
    public function getHomeAddressCountry(): ?string
    {
        return $this->homeAddressCountry;
    }

    /**
     * @param string|null $homeAddressCountry
     */
    public function setHomeAddressCountry(?string $homeAddressCountry): void
    {
        $this->homeAddressCountry = $homeAddressCountry;
    }

    /**
     * @return string|null
     */
    public function getRoleName(): ?string
    {
        return $this->roleName;
    }

    /**
     * @param string|null $roleName
     */
    public function setRoleName(?string $roleName): void
    {
        $this->roleName = $roleName;
    }
}
