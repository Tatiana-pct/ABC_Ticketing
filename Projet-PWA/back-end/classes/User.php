<?php
class User
{
    /* Instance Attributes */
    private int $id;
    private string $lastname;
    private string $firstname;
    private string $email;
    private string $phone;
    private string $password;
    private string $secret;
    private int $admin;
    private int $blocked;
    private DateTime $dateCreated;

    /* Association */
    private Enterprise $enterprise;

    /**
     * User Object Builder
     * @param string $lastname
     * @param string $firstname
     * @param Enterprise $enterprise
     * @param string $email
     * @param string $phone
     * @param string $password
     */
    public function __construct(string $lastname,
                                string $firstname,
                                Enterprise $enterprise,
                                string $email,
                                string $phone,
                                string $password)
    {
        $this->lastname = trim($lastname);
        $this->firstname = trim($firstname);
        $this->enterprise = $enterprise;
        $this->email = trim($email);
        $this->phone = trim($phone);
        $this->password = trim($password);
        $this->setAdmin(0);
        $this->setBlocked(0);
    }

    /**
     * Function checking if the required parameters are set
     * @return bool
     */
    public function isRequiredComplete(): bool {
        if ($this->getLastname()
            && $this->getFirstname()
            && !empty($this->getEnterprise())
            && $this->getEmail()
            && $this->getPhone()
            && $this->getPassword()) {
            return true;
        } else {
            return false;
        }
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
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    /**
     * @return string
     */
    public function getPhone(): string
    {
        return $this->phone;
    }

    /**
     * @param string $phone
     */
    public function setPhone(string $phone): void
    {
        $this->phone = $phone;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @param string $password
     */
    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    /**
     * @return string
     */
    public function getSecret(): string
    {
        return $this->secret;
    }

    /**
     * @param string $secret
     */
    public function setSecret(string $secret): void
    {
        $this->secret = $secret;
    }

    /**
     * @return Enterprise
     */
    public function getEnterprise(): Enterprise
    {
        return $this->enterprise;
    }

    /**
     * @param Enterprise $enterprise
     */
    public function setEnterprise(Enterprise $enterprise): void
    {
        $this->enterprise = $enterprise;
    }

    /**
     * @return int
     */
    public function getAdmin(): int
    {
        return $this->admin;
    }

    /**
     * @param int $admin
     */
    public function setAdmin(int $admin): void
    {
        $this->admin = $admin;
    }

    /**
     * @return int
     */
    public function getBlocked(): int
    {
        return $this->blocked;
    }

    /**
     * @param int $blocked
     */
    public function setBlocked(int $blocked): void
    {
        $this->blocked = $blocked;
    }

    /**
     * @return DateTime
     */
    public function getDateCreated(): DateTime
    {
        return $this->dateCreated;
    }

    /**
     * @param DateTime $dateCreated
     */
    public function setDateCreated(DateTime $dateCreated): void
    {
        $this->dateCreated = $dateCreated;
    }
}