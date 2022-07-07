<?php

namespace App\Model\User\Helpers;

class AuthView
{
    public string $id;
    public ?string $email;
    public ?string $password_hash;
    public string $role;
    public string $status;

    public function fetchData(array $fetchResult): self
    {
        $this->id            = $fetchResult['id'];
        $this->email         = $fetchResult['email'];
        $this->password_hash = $fetchResult['password_hash'];
        $this->role          = $fetchResult['role'];
        $this->status        = $fetchResult['status'];

        return $this;
    }
}