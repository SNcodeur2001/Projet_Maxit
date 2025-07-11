<?php

namespace App\Repository;
use App\Core\App;
use App\Core\Abstract\Database;
use Exception;
use PDO;

class UserRepository
{
    private PDO $pdo;
    private CompteRepository $compteRepository;

    public function __construct()
    {
        $this->pdo = App::getDependency('database');
        $this->compteRepository = App::getDependency('compteRepository');
    }

    /**
     * Trouve un utilisateur par son numéro de téléphone
     */
    public function findByTelephone(string $telephone): ?array
    {
        try {
            $stmt = $this->pdo->prepare('SELECT * FROM utilisateur WHERE telephone = :telephone');
            $stmt->execute(['telephone' => $telephone]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            return $user ?: null;
        } catch (\PDOException $e) {
            error_log("Erreur findByTelephone: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Crée un nouvel utilisateur avec son compte primaire
     */
    public function create(array $userData): array
    {
        try {
            // Démarrer une transaction
            $this->pdo->beginTransaction();

            // Vérifier si le téléphone existe déjà
            if ($this->findByTelephone($userData['telephone'])) {
                $this->pdo->rollBack();
                return [
                    'success' => false,
                    'message' => 'Ce numéro de téléphone est déjà utilisé'
                ];
            }

            // Vérifier si le CNI existe déjà
            if (!$this->isCNIUnique($userData['numero_piece_identite'])) {
                throw new Exception("Ce numéro de CNI est déjà enregistré");
            }

            // 1. Créer l'utilisateur
            $sql = 'INSERT INTO utilisateur (nom, prenom, telephone, adresse, numero_piece_identite, photo_recto, photo_verso, profil) 
                    VALUES (:nom, :prenom, :telephone, :adresse, :numero_piece_identite, :photo_recto, :photo_verso, :profil)';

            $stmt = $this->pdo->prepare($sql);
            $userResult = $stmt->execute([
                'nom' => $userData['nom'],
                'prenom' => $userData['prenom'],
                'telephone' => $userData['telephone'],
                'adresse' => $userData['adresse'],
                'numero_piece_identite' => $userData['numero_piece_identite'],
                'photo_recto' => $userData['photo_recto'],
                'photo_verso' => $userData['photo_verso'],
                'profil' => 'CLIENT'
            ]);

            if (!$userResult) {
                $this->pdo->rollBack();
                return [
                    'success' => false,
                    'message' => 'Erreur lors de la création de l\'utilisateur'
                ];
            }

            $userId = $this->pdo->lastInsertId();

            // 2. Créer le compte primaire
            $compteResult = $this->compteRepository->createComptePrimaire($userId);

            if (!$compteResult['success']) {
                $this->pdo->rollBack();
                return [
                    'success' => false,
                    'message' => 'Erreur lors de la création du compte : ' . $compteResult['message']
                ];
            }

            // Valider la transaction
            $this->pdo->commit();

            return [
                'success' => true,
                'message' => 'Compte utilisateur et compte primaire créés avec succès',
                'user_id' => $userId,
                'compte_id' => $compteResult['compte_id'],
                'numero' => $compteResult['numero']
            ];

        } catch (Exception $e) {
            // ✨ Gestion spécifique des erreurs d'unicité PostgreSQL
            $errorMessage = $e->getMessage();
            
            if (strpos($errorMessage, 'utilisateur_piece_unique') !== false || 
                strpos($errorMessage, 'numero_piece_identite') !== false) {
                throw new Exception("Ce numéro de CNI est déjà utilisé par un autre compte");
            } elseif (strpos($errorMessage, 'telephone') !== false) {
                throw new Exception("Ce numéro de téléphone est déjà utilisé par un autre compte");
            } else {
                error_log("Erreur création utilisateur: " . $errorMessage);
                throw new Exception("Erreur lors de la création du compte");
            }
        }
    }

    /**
     * Trouve un utilisateur par son ID avec ses informations de compte
     */
    public function findById(int $id): ?array
    {
        try {
            $stmt = $this->pdo->prepare('
                SELECT u.*, c.numero, c.solde, c.statut as statut_compte
                FROM utilisateur u
                LEFT JOIN compte c ON u.id = c.utilisateur_id AND c.profil_compte = "COMPTE_PRINCIPAL"
                WHERE u.id = :id
            ');
            $stmt->execute(['id' => $id]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            return $user ?: null;
        } catch (\PDOException $e) {
            error_log("Erreur findById: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Trouve un utilisateur avec son compte par téléphone
     */
    public function findByTelephoneWithAccount(string $telephone): ?array
    {
        try {
            $stmt = $this->pdo->prepare('
                SELECT u.*, c.numero, c.solde, c.statut as statut_compte
                FROM utilisateur u
                LEFT JOIN compte c ON u.id = c.utilisateur_id AND c.profil_compte = "COMPTE_PRINCIPAL"
                WHERE u.telephone = :telephone
            ');
            $stmt->execute(['telephone' => $telephone]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            return $user ?: null;
        } catch (\PDOException $e) {
            error_log("Erreur findByTelephoneWithAccount: " . $e->getMessage());
            return null;
        }
    }

    // ✨ Méthode spécifique pour vérifier l'unicité du CNI
    public function isCNIUnique(string $cni, ?int $excludeUserId = null): bool
    {
        $sql = "SELECT COUNT(*) FROM utilisateur WHERE numero_piece_identite = $1";
        $params = [$cni];
        
        if ($excludeUserId) {
            $sql .= " AND id != $2";
            $params[] = $excludeUserId;
        }
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        
        return $stmt->fetchColumn() == 0;
    }
}