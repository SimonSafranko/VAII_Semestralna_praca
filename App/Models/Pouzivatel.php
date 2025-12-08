<?php

namespace App\Models;

use Framework\Core\IIdentity;
use Framework\Core\Model;

class Pouzivatel extends Model implements IIdentity
{
    protected ?int $id = null;
    protected ?string $email = null;
    protected ?string $heslo = null;
    protected ?int $je_admin = 0;

    public const IDENTITY_KEY = 'id';

    public static function getTableName(): string
    {
        return 'pouzivatel';
    }

    public function getIdentityKey(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->email ?? '';
    }

    public function isAdmin(): bool
    {
        return (int)$this->je_admin === 1;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function getHeslo(): ?string
    {
        return $this->heslo;
    }

    public function getJeAdmin(): ?int
    {
        return $this->je_admin;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function setHeslo(string $heslo): void
    {
        $this->heslo = $heslo;
    }

    public function setJeAdmin(bool|int $jeAdmin): void
    {
        $this->je_admin = $jeAdmin ? 1 : 0;
    }

    public static function getOneByEmail(string $email): ?self
    {
        $rows = self::getAll('email = ?', [$email]);
        return $rows[0] ?? null;
    }
}
