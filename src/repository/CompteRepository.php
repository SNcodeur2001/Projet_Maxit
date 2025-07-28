<?php

namespace App\Repository;

use App\Core\Abstract\Database;
use App\Entity\Compte;
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
     * Crée un compte secondaire pour un utilisateur
     */
    public function createCompteSecondaire(int $utilisateurId, string $telephone): array
    {
        try {
            $numeroCompte = $this->generateNumeroCompte();
            $sql = 'INSERT INTO compte (utilisateur_id, numero, solde, statut, telephone_secondaire) 
                    VALUES (:utilisateur_id, :numero, :solde, :statut, :telephone)';
            $stmt = $this->pdo->prepare($sql);
            $result = $stmt->execute([
                'utilisateur_id' => $utilisateurId,
                'numero' => $numeroCompte,
                'solde' => 0.00,
                'statut' => 'COMPTE_SECONDAIRE',
                'telephone' => $telephone
            ]);
            if ($result) {
                return [
                    'success' => true,
                    'compte_id' => $this->pdo->lastInsertId(),
                    'numero' => $numeroCompte
                ];
            }
            return ['success' => false, 'message' => 'Erreur lors de la création'];
        } catch (\PDOException $e) {
            error_log("Erreur création compte secondaire: " . $e->getMessage());
            return ['success' => false, 'message' => $e->getMessage()];
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
        $sql = "SELECT * FROM compte WHERE numero = :numero";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['numero' => $numeroCompte]);
        return $stmt->fetch(\PDO::FETCH_ASSOC) ?: null;
    }

    /**
     * Trouve un compte par son téléphone secondaire
     */
    public function findByTelephoneSecondaire(string $telephone): ?array
    {
        $sql = "SELECT * FROM compte WHERE telephone_secondaire = :telephone";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['telephone' => $telephone]);
        return $stmt->fetch(\PDO::FETCH_ASSOC) ?: null;
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

   
    public function selectAll(): array
{
    $sql = '
        SELECT 
            c.id,
            c.numero,
            c.solde,
            c.statut,
            u.nom AS nom_proprietaire,
            u.prenom AS prenom_proprietaire
        FROM compte c
        JOIN utilisateur u ON u.id = c.utilisateur_id
    ';
    
    $stmt = $this->pdo->query($sql);
    return $stmt->fetchAll(\PDO::FETCH_ASSOC);
}

    /**
     * Trouve tous les comptes par l'ID utilisateur
     */
    public function findAllByUserId(int $utilisateurId): array
    {
        $sql = "SELECT * FROM compte WHERE utilisateur_id = :utilisateur_id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['utilisateur_id' => $utilisateurId]);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * Trouve un compte par son ID
     */
    public function findById(int $id): ?array
    {
        $sql = "SELECT * FROM compte WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(\PDO::FETCH_ASSOC) ?: null;
    }

public function getPaginatedComptes(int $limit, int $offset): array
{
    $sql = '
        SELECT 
            c.id,
            c.numero,
            c.solde,
            c.statut,
            u.nom AS nom_proprietaire,
            u.prenom AS prenom_proprietaire,
            u.telephone
        FROM compte c
        JOIN utilisateur u ON u.id = c.utilisateur_id
        ORDER BY c.id DESC
        LIMIT :limit OFFSET :offset
    ';
    $stmt = $this->pdo->prepare($sql);
    $stmt->bindValue(':limit', $limit, \PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, \PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(\PDO::FETCH_ASSOC);
}

public function countAllComptes(): int
{
    $sql = "SELECT COUNT(*) FROM compte";
    $stmt = $this->pdo->query($sql);
    return (int) $stmt->fetchColumn();
}
/**
 * Récupère le compte principal d'un utilisateur
 */
public function getComptePrincipalByUserId($userId)
{
    $stmt = $this->pdo->prepare("
        SELECT * FROM compte 
        WHERE utilisateur_id = :user_id 
        LIMIT 1
    ");
    $stmt->execute(['user_id' => $userId]);
    return $stmt->fetch();
}

/**
 * Change un compte secondaire en compte principal
 */
public function makeComptePrincipal($compteId, $userId)
{
    try {
        $this->pdo->beginTransaction();
        
        // 1. Vérifier que le compte appartient à l'utilisateur et qu'il est secondaire
        $stmt = $this->pdo->prepare("
            SELECT * FROM compte 
            WHERE id = :compte_id AND utilisateur_id = :user_id AND statut = 'COMPTE_SECONDAIRE'
        ");
        $stmt->execute([
            'compte_id' => $compteId,
            'user_id' => $userId
        ]);
        
        $compte = $stmt->fetch();
        if (!$compte) {
            throw new \Exception("Compte non trouvé ou déjà principal");
        }
        
        // 2. Changer l'ancien compte principal en secondaire
        $stmt = $this->pdo->prepare("
            UPDATE compte 
            SET statut = 'COMPTE_SECONDAIRE'
            WHERE utilisateur_id = :user_id AND statut = 'COMPTE_PRINCIPAL'
        ");
        $stmt->execute(['user_id' => $userId]);
        
        // 3. Changer le compte sélectionné en principal
        $stmt = $this->pdo->prepare("
            UPDATE compte 
            SET statut = 'COMPTE_PRINCIPAL'
            WHERE id = :compte_id
        ");
        $stmt->execute(['compte_id' => $compteId]);
        
        $this->pdo->commit();
        
        return [
            'success' => true,
            'message' => 'Compte principal changé avec succès'
        ];
        
    } catch (\Exception $e) {
        $this->pdo->rollBack();
        return [
            'success' => false,
            'message' => 'Erreur : ' . $e->getMessage()
        ];
    }
}

/**
 * Récupère tous les comptes d'un utilisateur avec leur statut
 */
public function getComptesByUserId($userId)
{
    $stmt = $this->pdo->prepare("
        SELECT c.*, 
               CASE 
                   WHEN c.statut = 'COMPTE_PRINCIPAL' THEN 'Principal' 
                   ELSE 'Secondaire' 
               END as type_compte
        FROM compte c 
        WHERE c.utilisateur_id = :user_id 
        ORDER BY 
            CASE WHEN c.statut = 'COMPTE_PRINCIPAL' THEN 0 ELSE 1 END,
            c.created_at ASC
    ");
    $stmt->execute(['user_id' => $userId]);
    return $stmt->fetchAll();
}

/**
 * Crée une transaction et met à jour le solde du compte
 */
public function createTransaction(int $compteId, string $type, float $montant, string $libelle = ''): array
{
    try {
        $this->pdo->beginTransaction();
        
        // 1. Vérifier que le compte existe
        $compte = $this->findById($compteId);
        if (!$compte) {
            throw new \Exception("Compte non trouvé");
        }
        
        // 2. Calculer le nouveau solde selon le type de transaction
        $nouveauSolde = $compte['solde'];
        switch ($type) {
            case 'DEPOT':
                $nouveauSolde += $montant;
                break;
            case 'RETRAIT':
                if ($compte['solde'] < $montant) {
                    throw new \Exception("Solde insuffisant");
                }
                $nouveauSolde -= $montant;
                break;
            case 'PAIEMENT':
                if ($compte['solde'] < $montant) {
                    throw new \Exception("Solde insuffisant");
                }
                $nouveauSolde -= $montant;
                break;
            default:
                throw new \Exception("Type de transaction invalide");
        }
        
        // 3. Enregistrer la transaction
        $stmt = $this->pdo->prepare("
            INSERT INTO transaction (compte_id, type, montant, libelle) 
            VALUES (:compte_id, :type, :montant, :libelle)
        ");
        $stmt->execute([
            'compte_id' => $compteId,
            'type' => $type,
            'montant' => $montant,
            'libelle' => $libelle
        ]);
        
        // 4. Mettre à jour le solde du compte
        $updateResult = $this->updateSolde($compteId, $nouveauSolde);
        if (!$updateResult) {
            throw new \Exception("Erreur lors de la mise à jour du solde");
        }
        
        $this->pdo->commit();
        
        return [
            'success' => true,
            'message' => 'Transaction créée avec succès',
            'transaction_id' => $this->pdo->lastInsertId(),
            'nouveau_solde' => $nouveauSolde
        ];
        
    } catch (\Exception $e) {
        $this->pdo->rollBack();
        return [
            'success' => false,
            'message' => 'Erreur : ' . $e->getMessage()
        ];
    }
}


}