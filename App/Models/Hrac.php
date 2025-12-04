<?php

namespace App\Models;

use Framework\Core\Model;

class Hrac extends Model
{
    protected ?int $id = null;
    protected ?int $pouzivatel_id = null;
    protected ?string $meno = null;
    protected ?string $priezvisko = null;
    protected ?string $datum_narodenia = null;
    protected ?string $krajina = null;
    protected ?string $pozicia = null;
    protected ?string $preferovana_noha = null;
    protected ?string $foto_cesta = null;
    protected ?string $bio = null;

    /**
     * Názov tabuľky v DB.
     * Musí byť statická, aby bola kompatibilná s Framework\Core\Model.
     */
    public static function getTableName(): string
    {
        return 'hrac';
    }

    // --- GETTERY --- //
    public function getId(): ?int { return $this->id; }
    public function getPouzivatelId(): ?int { return $this->pouzivatel_id; }
    public function getMeno(): ?string { return $this->meno; }
    public function getPriezvisko(): ?string { return $this->priezvisko; }
    public function getDatumNarodenia(): ?string { return $this->datum_narodenia; }
    public function getKrajina(): ?string { return $this->krajina; }
    public function getPozicia(): ?string { return $this->pozicia; }
    public function getPreferovanaNoha(): ?string { return $this->preferovana_noha; }
    public function getFotoCesta(): ?string { return $this->foto_cesta; }
    public function getBio(): ?string { return $this->bio; }

    // --- SETTERY --- //
    public function setPouzivatelId(?int $id): void { $this->pouzivatel_id = $id; }
    public function setMeno(string $meno): void { $this->meno = $meno; }
    public function setPriezvisko(string $priezvisko): void { $this->priezvisko = $priezvisko; }
    public function setDatumNarodenia(string $datum): void { $this->datum_narodenia = $datum; }
    public function setKrajina(string $krajina): void { $this->krajina = $krajina; }
    public function setPozicia(string $pozicia): void { $this->pozicia = $pozicia; }
    public function setPreferovanaNoha(string $noha): void { $this->preferovana_noha = $noha; }
    public function setFotoCesta(?string $cesta): void { $this->foto_cesta = $cesta; }
    public function setBio(?string $bio): void { $this->bio = $bio; }
}
