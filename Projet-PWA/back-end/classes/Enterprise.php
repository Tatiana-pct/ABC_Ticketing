<?php

class Enterprise
{
    /* Instance Attributes */
    private int $id;
    private string $clientNumber;
    private string $name;
    private string $siret;
    private string $webSite;
    private string $address;

    /**
     * Enterprise Object Builder
     *
     * @param string $name
     * @param string $siret
     * @param string $webSite
     * @param string $address
     */
    public function __construct(string $name,
                                string $siret,
                                string $webSite,
                                string $address)
    {
        $this->name = trim($name);
        $this->siret = trim($siret);
        $this->webSite = trim($webSite);
        $this->address = trim($address);
    }

    /**
     * Function checking if the required parameters are set
     *
     * @return bool
     */
    public function isRequiredComplete(): bool {
        if ($this->getName()
            && $this->getSiret()
            && $this->getWebSite()
            && $this->getAddress()) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Function to generate a unique clientNumber
     *
     */
    public function generateClientNumber(): void {
        $clientNumber = date("ym").mt_rand(1000, 9999);
        $this->clientNumber = $clientNumber;
    }


    /**
     * Function to generate array of website
     *
     */
    public function getWebsiteArray(): array {
        return preg_split("/[,;]/", $this->getWebSite());
    }


    /* Getters & Setters */
    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getClientNumber(): string
    {
        return $this->clientNumber;
    }

    /**
     * @param string $clientNumber
     */
    public function setClientNumber(string $clientNumber): void
    {
        $this->clientNumber = $clientNumber;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getSiret(): string
    {
        return $this->siret;
    }

    /**
     * @param string $siret
     */
    public function setSiret(string $siret): void
    {
        $this->siret = $siret;
    }

    /**
     * @return string
     */
    public function getWebSite(): string
    {
        return $this->webSite;
    }

    /**
     * @param string $webSite
     */
    public function setWebSite(string $webSite): void
    {
        $this->webSite = $webSite;
    }

    /**
     * @return string
     */
    public function getAddress(): string
    {
        return $this->address;
    }

    /**
     * @param string $address
     */
    public function setAddress(string $address): void
    {
        $this->address = $address;
    }
}