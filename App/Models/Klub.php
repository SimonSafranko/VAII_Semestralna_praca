<?php

namespace App\Models;

use Framework\Core\Model;

class Klub extends Model
{
    protected ?int $id = null;
    protected ?int $pouzivatel_id = null;
    protected ?string $nazov = null;
    protected ?string $region = null;
    protected ?string $kontakt_email = null;
    protected ?string $logo_cesta = null;
    protected ?string $created_at = null;

    public static function getTableName(): string
    {
        return 'klub';
    }

    // --- GETTERY --- //
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPouzivatelId(): ?int
    {
        return $this->pouzivatel_id;
    }

    public function getNazov(): ?string
    {
        return $this->nazov;
    }

    public function getRegion(): ?string
    {
        return $this->region;
    }

    public function getKontaktEmail(): ?string
    {
        return $this->kontakt_email;
    }

    public function getLogoCesta(): ?string
    {
        return $this->logo_cesta;
    }

    public function getCreatedAt(): ?string
    {
        return $this->created_at;
    }

    // --- SETTERY --- //
    public function setPouzivatelId(?int $id): void
    {
        $this->pouzivatel_id = $id;
    }

    public function setNazov(string $nazov): void
    {
        $this->nazov = $nazov;
    }

    public function setRegion(string $region): void
    {
        $this->region = $region;
    }

    public function setKontaktEmail(string $email): void
    {
        $this->kontakt_email = $email;
    }

    public function setLogoCesta(?string $cesta): void
    {
        $this->logo_cesta = $cesta;
    }

    public function setCreatedAt(string $datetime): void
    {
        $this->created_at = $datetime;
    }
}
