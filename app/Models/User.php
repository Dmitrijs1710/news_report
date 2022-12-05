<?php

namespace App\Models;

class User
{

    private string $name;
    private ?string $password;
    private string $eMail;
    private ?int $id;

    public function __construct(string $eMail, string $name, ?string $password = null, ?int $id = null)
    {
        $this->name = $name;
        $this->password = $password;
        $this->eMail = $eMail;
        $this->id = $id;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function getName(): string
    {
        return $this->name;
    }


    public function getEMail(): string
    {
        return $this->eMail;
    }


    public function getId(): ?int
    {
        return $this->id;
    }
}