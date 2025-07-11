<?php
namespace App\Entity;
use App\Core\Abstract\AbstractEntity;

class Transaction extends AbstractEntity
{
    private int $id;
    private TypeEnum $type; 
    private float $montant;
    private ?string $libelle; 
    private Compte $compte;   

    public function __construct(
        TypeEnum $type,
        float $montant,
        Compte $compte,
        ?string $libelle = null
    ) {
        $this->type = $type;
        $this->montant = $montant;
        $this->compte = $compte;
        $this->libelle = $libelle;
    }

    public function getId(): int
    {
        return $this->id;
    }
    public function getType(): TypeEnum
    {
        return $this->type;
    }
    public function getMontant(): float
    {
        return $this->montant;
    }
    public function getLibelle(): ?string
    {
        return $this->libelle;
    }
   public function getCompte(): Compte
   {
        return $this->compte;
    }
    public function setId(int $id): void
    {
        $this->id = $id;
    }
    public function setType(TypeEnum $type): void
    {
        $this->type = $type;
    }
    public function setMontant(float $montant): void
    {
        $this->montant = $montant;
    }
    public function setLibelle(?string $libelle): void
    {
        $this->libelle = $libelle;
    }
    public function setCompte(Compte $compte): void
    {
        $this->compte = $compte;
    }
        public static function toObject(array $tableau): static{
          return new static(
            $tableau['type'],
            $tableau['montant'],
            $tableau['compte'],
            $tableau['libelle']
          );
         }

        public function toArray(Object $object): array{
         return [
            'id' => $this->id,
            'type' => $this->type,
            'montant' => $this->montant,
            'libelle' => $this->libelle,
            'compte' => $this->compte,
        ];
        }

}