<?php

namespace App\Repository;

use App\Core\Abstract\AbstractRepository;
use App\Core\Abstract\Database;
use App\Entity\Transaction;

class TransactionRepository extends AbstractRepository
{

    
    /**
     * Récupère les dernières transactions d'un utilisateur
     * 
     * @param int $userId ID de l'utilisateur
     * @param int $limit Nombre de transactions à récupérer (défaut: 10)
     * @return array Liste des transactions
     */
    public function getRecentTransactionsByUser(int $userId, int $limit = 10): array
    {
        $pdo = Database::getConnection();
        
        // CORRECTION : Utilisation de TO_CHAR pour PostgreSQL au lieu de DATE_FORMAT
        $sql = "SELECT t.*, 
                       TO_CHAR(t.created_at, 'DD/MM/YYYY HH24:MI') as date_formatted,
                       CASE 
                           WHEN t.type = 'DEPOT' THEN CONCAT('+', t.montant, ' FCFA')
                           WHEN t.type = 'RETRAIT' THEN CONCAT('-', t.montant, ' FCFA')
                           ELSE CONCAT(t.montant, ' FCFA')
                       END as montant_formatted
                FROM transaction t 
                WHERE t.compte_id = :compte_id 
                ORDER BY t.created_at DESC 
                LIMIT :limit";
        
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':compte_id', $userId, \PDO::PARAM_INT);
        $stmt->bindValue(':limit', $limit, \PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * Calcule le solde total d'un utilisateur
     * 
     * @param int $userId ID de l'utilisateur
     * @return float Solde total
     */
    public function getTotalBalanceByUser(int $userId): float
    {
        $pdo = Database::getConnection();
        
        // CORRECTION : Utilisation de COALESCE compatible PostgreSQL
        $sql = "SELECT 
                    COALESCE(SUM(CASE WHEN type = 'DEPOT' THEN montant ELSE 0 END), 0) - 
                    COALESCE(SUM(CASE WHEN type = 'RETRAIT' THEN montant ELSE 0 END), 0) as balance
                FROM transaction
                WHERE compte_id = :compte_id";
        
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':compte_id', $userId, \PDO::PARAM_INT);
        $stmt->execute();
        
        $result = $stmt->fetch(\PDO::FETCH_ASSOC);
        return (float) ($result['balance'] ?? 0);
    }

    // Méthodes abstraites requises
    public function selectAll() 
    {
        $pdo = Database::getConnection();
        $stmt = $pdo->query("SELECT * FROM transaction ORDER BY created_at DESC");
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
    
    public function insert() { /* implémentation */ }
    public function update() { /* implémentation */ }
    public function delete() { /* implémentation */ }
    public function selectById() { /* implémentation */ }
    public function selectBy(array $filtre) { /* implémentation */ }
}