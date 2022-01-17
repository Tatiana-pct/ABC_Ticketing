<?php

class Mail
{
    /* Instance Attributes */
    private int $id;
    private string $website;
    private string $object;
    private string $message;
    private bool $speedCall;
    private ?string $speedCallDate;
    private ?string $speedCallHours;
    private DateTime $sendDate;

    /* Association */
    private User $user;

    /**
     * Enterprise Object Builder
     * @param User $user
     * @param string $object
     * @param string $message
     * @param bool $speedCall
     */
    public function __construct(User $user, string $website, string $object, string $message, bool $speedCall)
    {
        $this->user = $user;
        $this->setWebsite($website);
        $this->setObject($object);
        $this->setMessage($message);
        $this->setSpeedCall($speedCall);
        $this->message = wordwrap($message, 70, "\r\n");
        $this->speedCallDate = PDO::PARAM_NULL;
        $this->speedCallHours = PDO::PARAM_NULL;
    }

    /**
     * Function to send a contact email
     */
    public function sendContactMail() {
        $to = "support@abcconception.fr";
        $subject = $this->getObject();
        $mailMessage = "Nom : ".$this->getUser()->getLastname()."\r\n"
            ."Prénom: ".$this->getUser()->getFirstname()."\r\n"
            ."Mail : ".$this->getUser()->getEmail()."\r\n"
            ."Téléphone : ".$this->getUser()->getPhone()."\r\n"
            ."Site web : ".$this->getWebsite()."\r\n\r\n"
            ."Message : ".$this->getMessage()."\r\n\r\n"
            ."Demande de rappel : ".($this->isSpeedCall()?"Oui":"Non")."\r\n";
        if ($this->isSpeedCall()) {
            $mailMessage .= "Jour de rappel : ".$this->getSpeedCallDate()."\r\n"
                ."Heure de rappel : ".$this->getSpeedCallHours()."\r\n";
        }
        $headers = array(
            'Content-type' => 'text/html',
            'charset' => 'utf-8',
            'From' => $this->getUser()->getEmail()
        );

        mail($to, $subject, nl2br($mailMessage), $headers);
    }


    /**
     * Function checking if the required parameters are set
     * @return bool
     */
    public function isRequiredComplete(): bool {
        if ($this->getUser()
            && $this->getWebsite()
            && $this->getObject()
            && $this->getMessage()) {
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
    public function getWebsite(): string
    {
        return $this->website;
    }

    /**
     * @param string $website
     */
    public function setWebsite(string $website): void
    {
        $this->website = $website;
    }

    /**
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * @param User $user
     */
    public function setUser(User $user): void
    {
        $this->user = $user;
    }

    /**
     * @return string
     */
    public function getObject(): string
    {
        return $this->object;
    }

    /**
     * @param string $object
     */
    public function setObject(string $object): void
    {
        $this->object = $object;
    }

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message;
    }

    /**
     * @param string $message
     */
    public function setMessage(string $message): void
    {
        $this->message = $message;
    }

    /**
     * @return bool
     */
    public function isSpeedCall(): bool
    {
        return $this->speedCall;
    }

    /**
     * @param bool $speedCall
     */
    public function setSpeedCall(bool $speedCall): void
    {
        $this->speedCall = $speedCall;
    }

    /**
     * @return int|string|null
     */
    public function getSpeedCallDate()
    {
        return $this->speedCallDate;
    }

    /**
     * @param int|string|null $speedCallDate
     */
    public function setSpeedCallDate($speedCallDate): void
    {
        $this->speedCallDate = $speedCallDate;
    }

    /**
     * @return int|string|null
     */
    public function getSpeedCallHours()
    {
        return $this->speedCallHours;
    }

    /**
     * @param int|string|null $speedCallHours
     */
    public function setSpeedCallHours($speedCallHours): void
    {
        $this->speedCallHours = $speedCallHours;
    }

    /**
     * @return DateTime
     */
    public function getSendDate(): DateTime
    {
        return $this->sendDate;
    }

    /**
     * @param DateTime $sendDate
     */
    public function setSendDate(DateTime $sendDate): void
    {
        $this->sendDate = $sendDate;
    }
}