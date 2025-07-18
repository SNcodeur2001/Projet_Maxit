<?php
namespace App\Entity;

use App\Core\Abstract\AbstractEntity;

class Compte extends AbstractEntity
{
    private int $id;
    private string $numero;
    private float $solde;
    private Utilisateur $utilisateur; 
    private StatutEnum $statut;
    private ?array $transactions; 

    public function __construct(
        string $numero,
        float $solde,
        Utilisateur $utilisateur,
        ?array $transactions = null,
        StatutEnum $statut = StatutEnum::Secondaire
    ) {
        $this->numero = $numero;
        $this->solde = $solde;
        $this->utilisateur = $utilisateur; 
        $this->transactions = $transactions ?? []; 
        $this->statut = $statut; 
    }

    public function getNumero(): string
    {
        return $this->numero;
    }
    public function getSolde(): float
    {
        return $this->solde;
    }
    public function getUtilisateur(): Utilisateur
    {
        return $this->utilisateur;
    }
    public function getTransactions(): array

    {
        return $this->transactions;
    }
    public function getStatut(): StatutEnum
    {
        return $this->statut;
    }
    public function setNumero(string $numero): void
    {
        $this->numero = $numero;
    }
    public function setSolde(float $solde): void
    {
        $this->solde = $solde;
    }
    public function setUtilisateur(Utilisateur $utilisateur): void
    {
        $this->utilisateur = $utilisateur;
    }
    public function setTransactions(array $transactions): void
    {
        $this->transactions = $transactions;
    }
    public function setStatut(StatutEnum $statut): void
    {
        $this->statut = $statut;
    }
    public function ajouterTransaction(Transaction $transaction): void
    {
        $this->transactions[] = $transaction;
    }
          public static function toObject(array $tableau): static
    {
      return new static(
        $tableau['numero'],
        $tableau['solde'],
        $tableau['utilisateur'],
        $tableau['transactions'],
        $tableau['statut']
      );
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'numero' => $this->numero,
            'solde' => $this->solde,
            'utilisateur' => $this->utilisateur,
            'transactions' => $this->transactions,
            'statut' => $this->statut,
        ];
    }
}
