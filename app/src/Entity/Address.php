<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\IdGenerator\UuidV4Generator;

/**
 * @ORM\Entity
 */
class Address
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
     * @ORM\Column(type="string", length=255)
     */
    private $line1 = '';

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255)
     */
    private $line2 = '';

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=5)
     */
    private $zip = '';

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255)
     */
    private $city = '';

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=3)
     */
    private $country = '';

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
    public function getLine1(): string
    {
        return $this->line1;
    }

    /**
     * @param string $line1
     */
    public function setLine1(string $line1): void
    {
        $this->line1 = $line1;
    }

    /**
     * @return string
     */
    public function getLine2(): string
    {
        return $this->line2;
    }

    /**
     * @param string $line2
     */
    public function setLine2(string $line2): void
    {
        $this->line2 = $line2;
    }

    /**
     * @return string
     */
    public function getZip(): string
    {
        return $this->zip;
    }

    /**
     * @param string $zip
     */
    public function setZip(string $zip): void
    {
        $this->zip = $zip;
    }

    /**
     * @return string
     */
    public function getCity(): string
    {
        return $this->city;
    }

    /**
     * @param string $city
     */
    public function setCity(string $city): void
    {
        $this->city = $city;
    }

    /**
     * @return string
     */
    public function getCountry(): string
    {
        return $this->country;
    }

    /**
     * @param string $country
     */
    public function setCountry(string $country): void
    {
        $this->country = $country;
    }
}
