<?php
class Seeder {
    private PDO $pdo;
    
    public function __construct(string $host, string $user, string $pass, string $dbName) {
        $this->pdo = new PDO("pgsql:host=$host;dbname=$dbName", $user, $pass);
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
    
    public function clearData(): void {
        echo "🧹 Suppression des données existantes...\n";
        
        // PostgreSQL n'a pas FOREIGN_KEY_CHECKS, nous supprimons dans l'ordre correct
        $tables = ['transaction', 'compte', 'utilisateur'];
        foreach ($tables as $table) {
            $this->pdo->exec("TRUNCATE TABLE $table RESTART IDENTITY CASCADE");
        }
        
        echo "✅ Données supprimées.\n";
    }
    
    public function seedUtilisateurs(): void {
        echo "👥 Insertion des utilisateurs...\n";
        
        $utilisateurs = [
            [1, 'Ndiaye', 'Fatou', 'Dakar, Pikine', '771234567', 'CNI123456', 'fatou_recto.jpg', 'fatou_verso.jpg', 'CLIENT'],
            [2, 'Sow', 'Mamadou', NULL, '780001122', NULL, NULL, NULL, 'SERVICE_COMMERCIAL'],
            [3, 'Ndiaye', 'Mapathe', 'dakar', '771234565', 'CNI12432124', 'recto_686f58444a3ea_1752127556.png', 'verso_686f58444a5f7_1752127556.png', 'CLIENT'],
            [6, 'allou', 'Alassane', 'Dakar', '778965432', 'CNI12432128', 'recto_686f675d62476_1752131421.png', 'verso_686f675d62868_1752131421.png', 'CLIENT'],
            [13, 'Ndiaye', 'Mapathe', 'Dakar', '779874532', '1243212423455', 'recto_687026156807d_1752180245.png', 'verso_68702615681f6_1752180245.png', 'CLIENT'],
            [25, 'Ndiaye', 'Mapathe', 'sdds', '784620621', '1243212423459', 'recto_687033e8014b5_1752183784.png', 'verso_687033e8015a4_1752183784.png', 'CLIENT'],
            [26, 'Ndiaye', 'Mapathe', 'AZ', '771234568', '1243212423421', 'recto_6871023bdc190_1752236603.png', 'verso_6871023bdc34b_1752236603.png', 'CLIENT']
        ];
        
        $stmt = $this->pdo->prepare("INSERT INTO utilisateur (id, nom, prenom, adresse, telephone, numero_piece_identite, photo_recto, photo_verso, profil) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?::profil_type)");
        
        foreach ($utilisateurs as $user) {
            $stmt->execute($user);
        }
        
        echo "✅ " . count($utilisateurs) . " utilisateurs insérés.\n";
    }
    
    public function seedComptes(): void {
        echo "💳 Insertion des comptes...\n";
        
        $comptes = [
            [1, 1, 'CP001', 20000.00, 'COMPTE_SECONDAIRE', NULL],
            [2, 1, 'CS002', 5000.00, 'COMPTE_SECONDAIRE', NULL],
            [3, 6, 'MAX4962030386', 0.00, 'COMPTE_PRINCIPAL', NULL],
            [10, 13, 'MAX3169095625', 0.00, 'COMPTE_PRINCIPAL', NULL],
            [13, 25, 'MAX2778251456', 0.00, 'COMPTE_PRINCIPAL', NULL],
            [14, 26, 'MAX2604805805', 0.00, 'COMPTE_PRINCIPAL', NULL],
            [15, 1, 'MAX5172770335', 0.00, 'COMPTE_SECONDAIRE', NULL],
            [16, 1, 'MAX6464027935', 0.00, 'COMPTE_SECONDAIRE', '772202532'],
            [17, 1, 'MAX4655582819', 0.00, 'COMPTE_SECONDAIRE', '777777777'],
            [18, 1, 'MAX2109761945', 0.00, 'COMPTE_SECONDAIRE', '770009900']
        ];
        
        $stmt = $this->pdo->prepare("INSERT INTO compte (id, utilisateur_id, numero, solde, statut, telephone_secondaire) VALUES (?, ?, ?, ?, ?::statut_type, ?)");
        
        foreach ($comptes as $compte) {
            $stmt->execute($compte);
        }
        
        echo "✅ " . count($comptes) . " comptes insérés.\n";
    }
    
    public function seedTransactions(): void {
        echo "💰 Insertion des transactions...\n";
        
        $transactions = [
            [1, 1, 'DEPOT', 20000.00, 'Solde initial'],
            [2, 1, 'PAIEMENT', 5000.00, 'Paiement SENELEC'],
            [3, 2, 'DEPOT', 5000.00, 'Montant envoyé depuis compte principal'],
            [4, 1, 'DEPOT', 10000.00, 'Versement initial'],
            [5, 1, 'PAIEMENT', 2500.00, 'Paiement abonnement Orange'],
            [6, 1, 'RETRAIT', 2000.00, 'Retrait au guichet'],
            [7, 2, 'DEPOT', 3000.00, 'Transfert reçu'],
            [8, 2, 'PAIEMENT', 1500.00, 'Achat boutique Dakar'],
            [9, 1, 'PAIEMENT', 1800.00, 'Paiement Wari'],
            [10, 1, 'DEPOT', 5000.00, 'Solde rechargé'],
            [11, 2, 'RETRAIT', 1000.00, NULL],
            [12, 2, 'DEPOT', 4500.00, 'Versement depuis principal'],
            [13, 1, 'RETRAIT', 1200.00, 'Retrait distributeur automatique']
        ];
        
        $stmt = $this->pdo->prepare("INSERT INTO transaction (id, compte_id, type, montant, libelle) VALUES (?, ?, ?::transaction_type, ?, ?)");
        
        foreach ($transactions as $trans) {
            $stmt->execute($trans);
        }
        
        echo "✅ " . count($transactions) . " transactions insérées.\n";
    }
    
    public function resetSequences(): void {
        echo "🔢 Réinitialisation des séquences...\n";
        
        // Réinitialiser les séquences pour correspondre aux données
        $this->pdo->exec("SELECT setval('utilisateur_id_seq', 27, false)");
        $this->pdo->exec("SELECT setval('compte_id_seq', 19, false)");
        $this->pdo->exec("SELECT setval('transaction_id_seq', 14, false)");
        
        echo "✅ Séquences réinitialisées.\n";
    }
    
    public function showStats(): void {
        echo "\n📊 Statistiques de la base de données:\n";
        
        $tables = ['utilisateur', 'compte', 'transaction'];
        foreach ($tables as $table) {
            $stmt = $this->pdo->query("SELECT COUNT(*) FROM $table");
            $count = $stmt->fetchColumn();
            echo "- $table: $count enregistrements\n";
        }
        
        // Statistiques détaillées
        echo "\n📈 Détails:\n";
        
        // Utilisateurs par profil
        $stmt = $this->pdo->query("SELECT profil, COUNT(*) as count FROM utilisateur GROUP BY profil");
        echo "• Utilisateurs par profil:\n";
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo "  - {$row['profil']}: {$row['count']}\n";
        }
        
        // Comptes par statut
        $stmt = $this->pdo->query("SELECT statut, COUNT(*) as count FROM compte GROUP BY statut");
        echo "• Comptes par statut:\n";
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo "  - {$row['statut']}: {$row['count']}\n";
        }
        
        // Transactions par type
        $stmt = $this->pdo->query("SELECT type, COUNT(*) as count FROM transaction GROUP BY type");
        echo "• Transactions par type:\n";
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo "  - {$row['type']}: {$row['count']}\n";
        }
        
        // Solde total
        $stmt = $this->pdo->query("SELECT SUM(solde) as total FROM compte");
        $total = $stmt->fetchColumn();
        echo "• Solde total: " . number_format($total, 2) . " FCFA\n";
        
        // Informations sur les séquences
        echo "\n🔢 État des séquences:\n";
        $sequences = ['utilisateur_id_seq', 'compte_id_seq', 'transaction_id_seq'];
        foreach ($sequences as $seq) {
            $stmt = $this->pdo->query("SELECT last_value FROM $seq");
            $value = $stmt->fetchColumn();
            echo "  - $seq: $value\n";
        }
    }
    
    public function runAll(): void {
        echo "🚀 Exécution complète du seeder PostgreSQL...\n\n";
        
        $this->clearData();
        $this->seedUtilisateurs();
        $this->seedComptes();
        $this->seedTransactions();
        $this->resetSequences();
        $this->showStats();
        
        echo "\n🎉 Seeding PostgreSQL terminé avec succès.\n";
    }
    
    public function updateCompteSoldes(): void {
        echo "💰 Mise à jour des soldes des comptes...\n";
        
        // Calculer et mettre à jour les soldes basés sur les transactions
        $sql = "
            UPDATE compte 
            SET solde = COALESCE(
                (SELECT SUM(CASE 
                    WHEN type = 'DEPOT' THEN montant 
                    WHEN type = 'RETRAIT' OR type = 'PAIEMENT' THEN -montant 
                    ELSE 0 
                END)
                FROM transaction 
                WHERE transaction.compte_id = compte.id), 
                0
            )
        ";
        
        $this->pdo->exec($sql);
        echo "✅ Soldes des comptes mis à jour.\n";
    }
}

// 🔧 Configuration
$host = 'localhost';
$user = 'postgres';
$pass = 'passer123';
$dbName = 'maxitsa_db1';

// 🚀 Exécution
try {
    echo "🌱 Démarrage du seeder PostgreSQL...\n\n";
    
    $seeder = new Seeder($host, $user, $pass, $dbName);
    
    // Vérifier les arguments de ligne de commande
    if (isset($argv[1])) {
        switch ($argv[1]) {
            case '--clear':
                $seeder->clearData();
                break;
            case '--users':
                $seeder->seedUtilisateurs();
                break;
            case '--comptes':
                $seeder->seedComptes();
                break;
            case '--transactions':
                $seeder->seedTransactions();
                break;
            case '--stats':
                $seeder->showStats();
                break;
            case '--update-soldes':
                $seeder->updateCompteSoldes();
                break;
            default:
                $seeder->runAll();
                $seeder->updateCompteSoldes();
        }
    } else {
        $seeder->runAll();
        $seeder->updateCompteSoldes();
    }
    
    echo "\n💡 Options disponibles:\n";
    echo "  php seeder.php --clear           # Vider la base\n";
    echo "  php seeder.php --users           # Insérer seulement les utilisateurs\n";
    echo "  php seeder.php --comptes         # Insérer seulement les comptes\n";
    echo "  php seeder.php --transactions    # Insérer seulement les transactions\n";
    echo "  php seeder.php --stats           # Afficher les statistiques\n";
    echo "  php seeder.php --update-soldes   # Mettre à jour les soldes\n";
    echo "\n💡 Base de données: $dbName\n";
    echo "💡 Utilisez d'abord le migration.php puis ce seeder.php\n";
    
} catch (PDOException $e) {
    echo "❌ Erreur : " . $e->getMessage() . "\n";
    echo "💡 Vérifiez que la base de données '$dbName' existe et que les identifiants sont corrects.\n";
    echo "💡 Assurez-vous d'avoir d'abord exécuté le script de migration.\n";
    echo "💡 Commande: php migration.php\n";
}