<?php

namespace App\Repository;

use App\Core\Abstract\Database;
use PDO;

class CompteRepository
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = Database::getConnection();
    }

    /**
     * Crée un compte primaire pour un utilisateur
     */
    public function createComptePrimaire(int $utilisateurId): array
    {
        try {
            // Générer un numéro de compte unique
            $numeroCompte = $this->generateNumeroCompte();

            $sql = 'INSERT INTO compte (utilisateur_id, numero, solde, statut) 
                    VALUES (:utilisateur_id, :numero, :solde, :statut)';

            $stmt = $this->pdo->prepare($sql);
            $result = $stmt->execute([
                'utilisateur_id' => $utilisateurId,
                'numero' => $numeroCompte,
                'solde' => 0.00,
                'statut' => 'COMPTE_PRINCIPAL'
            ]);

            if ($result) {
                return [
                    'success' => true,
                    'message' => 'Compte primaire créé avec succès',
                    'compte_id' => $this->pdo->lastInsertId(),
                    'numero' => $numeroCompte
                ];
            }

            return [
                'success' => false,
                'message' => 'Erreur lors de la création du compte'
            ];

        } catch (\PDOException $e) {
            error_log("Erreur création compte: " . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Erreur de base de données : ' . $e->getMessage()
            ];
        }
    }

    /**
     * Trouve un compte par l'ID utilisateur
     */
    public function findByUserId(int $utilisateurId): ?array
    {
        try {
            $stmt = $this->pdo->prepare('SELECT * FROM compte WHERE utilisateur_id = :utilisateur_id AND statut = "COMPTE_PRINCIPAL"');
            $stmt->execute(['utilisateur_id' => $utilisateurId]);
            $compte = $stmt->fetch(PDO::FETCH_ASSOC);

            return $compte ?: null;
        } catch (\PDOException $e) {
            error_log("Erreur findByUserId: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Trouve un compte par son numéro
     */
    public function findByNumeroCompte(string $numeroCompte): ?array
    {
        try {
            $stmt = $this->pdo->prepare('SELECT * FROM compte WHERE numero = :numero');
            $stmt->execute(['numero' => $numeroCompte]);
            $compte = $stmt->fetch(PDO::FETCH_ASSOC);

            return $compte ?: null;
        } catch (\PDOException $e) {
            error_log("Erreur findByNumeroCompte: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Génère un numéro de compte unique
     */
    private function generateNumeroCompte(): string
    {
        do {
            // Format: MAX + 10 chiffres aléatoires
            $numeroCompte = 'MAX' . str_pad(mt_rand(1, 9999999999), 10, '0', STR_PAD_LEFT);
            
            // Vérifier l'unicité
            $existing = $this->findByNumeroCompte($numeroCompte);
        } while ($existing !== null);

        return $numeroCompte;
    }

    /**
     * Met à jour le solde d'un compte
     */
    public function updateSolde(int $compteId, float $nouveauSolde): bool
    {
        try {
            $stmt = $this->pdo->prepare('UPDATE compte SET solde = :solde WHERE id = :id');
            return $stmt->execute([
                'solde' => $nouveauSolde,
                'id' => $compteId
            ]);
        } catch (\PDOException $e) {
            error_log("Erreur updateSolde: " . $e->getMessage());
            return false;
        }
    }
}