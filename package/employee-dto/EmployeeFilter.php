<?php

namespace Package\EmployeeDto;

use DateTime;

class EmployeeFilter
{
    /**
     * @var string|null
     */
    private $employeeId;

    /**
     * @var string|null
     */
    private $bossId;

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
    private $birthdateFrom;

    /**
     * @var DateTime|null
     */
    private $birthdateTo;

    /**
     * @var string|null
     */
    private $role;

    /**
     * @return string|null
     */
    public function getEmployeeId(): ?string
    {
        return $this->employeeId;
    }

    /**
     * @param string|null $employeeId
     */
    public function setEmployeeId(?string $employeeId): void
    {
        $this->employeeId = $employeeId;
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
    public function getBirthdateFrom(): ?DateTime
    {
        return $this->birthdateFrom;
    }

    /**
     * @param DateTime|null $birthdateFrom
     */
    public function setBirthdateFrom(?DateTime $birthdateFrom): void
    {
        $this->birthdateFrom = $birthdateFrom;
    }

    /**
     * @return DateTime|null
     */
    public function getBirthdateTo(): ?DateTime
    {
        return $this->birthdateTo;
    }

    /**
     * @param DateTime|null $birthdateTo
     */
    public function setBirthdateTo(?DateTime $birthdateTo): void
    {
        $this->birthdateTo = $birthdateTo;
    }

    /**
     * @param string|null $role
     */
    public function setRole(?string $role): void
    {
        $this->role = $role;
    }

    /**
     * @return string|null
     */
    public function getRole(): ?string
    {
        return $this->role;
    }
}
