<?php
class Seeder {
    private PDO $pdo;
    private string $driver;
    
    public function __construct() {
        $this->loadEnv();
        $this->driver = $_ENV['DB_DRIVER'] ?? 'pgsql';
        $this->initializeConnection();
    }
    
    private function loadEnv(): void {
        $envFile = dirname(__DIR__) . '/.env';
        if (file_exists($envFile)) {
            $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            foreach ($lines as $line) {
                if (str_starts_with(trim($line), '#') || !str_contains($line, '=')) continue;
                [$key, $value] = explode('=', $line, 2);
                $_ENV[trim($key)] = trim($value);
            }
        }
    }
    
    private function initializeConnection(): void {
        $host = $_ENV['DB_HOST'] ?? 'localhost';
        $user = $_ENV['DB_USER'] ?? ($this->driver === 'pgsql' ? 'postgres' : 'root');
        $pass = $_ENV['DB_PASSWORD'] ?? '';
        $dbName = $_ENV['DB_NAME'] ?? 'maxitsa_db';
        
        if ($this->driver === 'pgsql') {
            $this->pdo = new PDO("pgsql:host=$host;dbname=$dbName", $user, $pass);
        } else {
            $this->pdo = new PDO("mysql:host=$host;dbname=$dbName;charset=utf8mb4", $user, $pass);
        }
        
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
    
    public function clearAll(): void {
        echo "🧹 Nettoyage de toutes les données...\n";
        
        if ($this->driver === 'mysql') {
            $this->pdo->exec("SET FOREIGN_KEY_CHECKS = 0");
        }
        
        $this->pdo->exec("DELETE FROM transaction");
        $this->pdo->exec("DELETE FROM compte");
        $this->pdo->exec("DELETE FROM utilisateur");
        
        if ($this->driver === 'mysql') {
            $this->pdo->exec("SET FOREIGN_KEY_CHECKS = 1");
        }
        
        // Réinitialiser les séquences/AUTO_INCREMENT
        if ($this->driver === 'pgsql') {
            $this->pdo->exec("SELECT setval('utilisateur_id_seq', 1, false)");
            $this->pdo->exec("SELECT setval('compte_id_seq', 1, false)");
            $this->pdo->exec("SELECT setval('transaction_id_seq', 1, false)");
        } else {
            $this->pdo->exec("ALTER TABLE utilisateur AUTO_INCREMENT = 1");
            $this->pdo->exec("ALTER TABLE compte AUTO_INCREMENT = 1");
            $this->pdo->exec("ALTER TABLE transaction AUTO_INCREMENT = 1");
        }
        
        echo "✅ Toutes les données supprimées et séquences réinitialisées.\n";
    }
    
    public function seedUtilisateurs(): void {
        echo "👥 Insertion des utilisateurs ($this->driver)...\n";
        
        // Vider la table d'abord
        $this->pdo->exec("DELETE FROM utilisateur");
        
        // Réinitialiser les séquences/AUTO_INCREMENT
        if ($this->driver === 'pgsql') {
            $this->pdo->exec("SELECT setval('utilisateur_id_seq', 1, false)");
        } else {
            $this->pdo->exec("ALTER TABLE utilisateur AUTO_INCREMENT = 1");
        }
        
        $utilisateurs = [
            [
                'nom' => 'Diop',
                'prenom' => 'Amadou',
                'adresse' => 'Dakar, Plateau',
                'telephone' => '+221701234567',
                'numero_piece_identite' => '1234567890123',
                'photo_recto' => 'uploads/recto_1.jpg',
                'photo_verso' => 'uploads/verso_1.jpg',
                'profil' => 'CLIENT'
            ],
            [
                'nom' => 'Fall',
                'prenom' => 'Fatou',
                'adresse' => 'Thiès, Centre-ville',
                'telephone' => '+221702345678',
                'numero_piece_identite' => '2345678901234',
                'photo_recto' => 'uploads/recto_2.jpg',
                'photo_verso' => 'uploads/verso_2.jpg',
                'profil' => 'CLIENT'
            ],
            [
                'nom' => 'Ndiaye',
                'prenom' => 'Moussa',
                'adresse' => 'Saint-Louis, Sor',
                'telephone' => '+221703456789',
                'numero_piece_identite' => '3456789012345',
                'photo_recto' => 'uploads/recto_3.jpg',
                'photo_verso' => 'uploads/verso_3.jpg',
                'profil' => 'CLIENT'
            ],
            [
                'nom' => 'Sow',
                'prenom' => 'Aissatou',
                'adresse' => 'Kaolack, Médina',
                'telephone' => '+221704567890',
                'numero_piece_identite' => '4567890123456',
                'photo_recto' => 'uploads/recto_4.jpg',
                'photo_verso' => 'uploads/verso_4.jpg',
                'profil' => 'SERVICE_COMMERCIAL'
            ],
            [
                'nom' => 'Ba',
                'prenom' => 'Ousmane',
                'adresse' => 'Ziguinchor, Centre',
                'telephone' => '+221705678901',
                'numero_piece_identite' => '5678901234567',
                'photo_recto' => 'uploads/recto_5.jpg',
                'photo_verso' => 'uploads/verso_5.jpg',
                'profil' => 'CLIENT'
            ]
        ];
        
        $sql = "INSERT INTO utilisateur (nom, prenom, adresse, telephone, numero_piece_identite, photo_recto, photo_verso, profil) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->pdo->prepare($sql);
        
        foreach ($utilisateurs as $user) {
            $stmt->execute([
                $user['nom'],
                $user['prenom'],
                $user['adresse'],
                $user['telephone'],
                $user['numero_piece_identite'],
                $user['photo_recto'],
                $user['photo_verso'],
                $user['profil']
            ]);
        }
        
        echo "✅ " . count($utilisateurs) . " utilisateurs insérés.\n";
    }
    
    public function seedComptes(): void {
        echo "💳 Insertion des comptes ($this->driver)...\n";
        
        // Vider la table d'abord
        $this->pdo->exec("DELETE FROM compte");
        
        // Réinitialiser les séquences/AUTO_INCREMENT
        if ($this->driver === 'pgsql') {
            $this->pdo->exec("SELECT setval('compte_id_seq', 1, false)");
        } else {
            $this->pdo->exec("ALTER TABLE compte AUTO_INCREMENT = 1");
        }
        
        $comptes = [
            // Comptes principaux
            [
                'utilisateur_id' => 1,
                'numero' => 'MAX001001',
                'solde' => 150000.00,
                'statut' => 'COMPTE_PRINCIPAL',
                'telephone_secondaire' => null
            ],
            [
                'utilisateur_id' => 2,
                'numero' => 'MAX002001',
                'solde' => 75000.00,
                'statut' => 'COMPTE_PRINCIPAL',
                'telephone_secondaire' => null
            ],
            [
                'utilisateur_id' => 3,
                'numero' => 'MAX003001',
                'solde' => 200000.00,
                'statut' => 'COMPTE_PRINCIPAL',
                'telephone_secondaire' => null
            ],
            [
                'utilisateur_id' => 5,
                'numero' => 'MAX005001',
                'solde' => 50000.00,
                'statut' => 'COMPTE_PRINCIPAL',
                'telephone_secondaire' => null
            ],
            
            // Comptes secondaires
            [
                'utilisateur_id' => 1,
                'numero' => 'MAX001002',
                'solde' => 25000.00,
                'statut' => 'COMPTE_SECONDAIRE',
                'telephone_secondaire' => '+221706111111'
            ],
            [
                'utilisateur_id' => 2,
                'numero' => 'MAX002002',
                'solde' => 30000.00,
                'statut' => 'COMPTE_SECONDAIRE',
                'telephone_secondaire' => '+221706222222'
            ],
            [
                'utilisateur_id' => 3,
                'numero' => 'MAX003002',
                'solde' => 45000.00,
                'statut' => 'COMPTE_SECONDAIRE',
                'telephone_secondaire' => '+221706333333'
            ]
        ];
        
        $sql = "INSERT INTO compte (utilisateur_id, numero, solde, statut, telephone_secondaire) 
                VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->pdo->prepare($sql);
        
        foreach ($comptes as $compte) {
            $stmt->execute([
                $compte['utilisateur_id'],
                $compte['numero'],
                $compte['solde'],
                $compte['statut'],
                $compte['telephone_secondaire']
            ]);
        }
        
        echo "✅ " . count($comptes) . " comptes insérés.\n";
    }
    
    public function seedTransactions(): void {
        echo "💰 Insertion des transactions ($this->driver)...\n";
        
        // Vider la table d'abord
        $this->pdo->exec("DELETE FROM transaction");
        
        // Réinitialiser les séquences/AUTO_INCREMENT
        if ($this->driver === 'pgsql') {
            $this->pdo->exec("SELECT setval('transaction_id_seq', 1, false)");
        } else {
            $this->pdo->exec("ALTER TABLE transaction AUTO_INCREMENT = 1");
        }
        
        $transactions = [
            // Transactions pour le compte 1 (MAX001001)
            [
                'compte_id' => 1,
                'type' => 'DEPOT',
                'montant' => 100000.00,
                'libelle' => 'Dépôt initial'
            ],
            [
                'compte_id' => 1,
                'type' => 'DEPOT',
                'montant' => 50000.00,
                'libelle' => 'Dépôt espèces'
            ],
            [
                'compte_id' => 1,
                'type' => 'RETRAIT',
                'montant' => 25000.00,
                'libelle' => 'Retrait DAB'
            ],
            
            // Transactions pour le compte 2 (MAX002001)
            [
                'compte_id' => 2,
                'type' => 'DEPOT',
                'montant' => 80000.00,
                'libelle' => 'Dépôt salaire'
            ],
            [
                'compte_id' => 2,
                'type' => 'PAIEMENT',
                'montant' => 15000.00,
                'libelle' => 'Paiement facture électricité'
            ],
            [
                'compte_id' => 2,
                'type' => 'RETRAIT',
                'montant' => 10000.00,
                'libelle' => 'Retrait espèces'
            ],
            
            // Transactions pour le compte 3 (MAX003001)
            [
                'compte_id' => 3,
                'type' => 'DEPOT',
                'montant' => 250000.00,
                'libelle' => 'Dépôt virement'
            ],
            [
                'compte_id' => 3,
                'type' => 'PAIEMENT',
                'montant' => 35000.00,
                'libelle' => 'Paiement loyer'
            ],
            [
                'compte_id' => 3,
                'type' => 'RETRAIT',
                'montant' => 15000.00,
                'libelle' => 'Retrait urgence'
            ],
            
            // Transactions pour le compte 4 (MAX005001)
            [
                'compte_id' => 4,
                'type' => 'DEPOT',
                'montant' => 60000.00,
                'libelle' => 'Dépôt initial'
            ],
            [
                'compte_id' => 4,
                'type' => 'RETRAIT',
                'montant' => 10000.00,
                'libelle' => 'Retrait quotidien'
            ],
            
            // Transactions pour les comptes secondaires
            [
                'compte_id' => 5,
                'type' => 'DEPOT',
                'montant' => 25000.00,
                'libelle' => 'Dépôt compte secondaire'
            ],
            [
                'compte_id' => 6,
                'type' => 'DEPOT',
                'montant' => 30000.00,
                'libelle' => 'Transfert interne'
            ],
            [
                'compte_id' => 7,
                'type' => 'DEPOT',
                'montant' => 45000.00,
                'libelle' => 'Dépôt mobile money'
            ]
        ];
        
        $sql = "INSERT INTO transaction (compte_id, type, montant, libelle) VALUES (?, ?, ?, ?)";
        $stmt = $this->pdo->prepare($sql);
        
        foreach ($transactions as $transaction) {
            $stmt->execute([
                $transaction['compte_id'],
                $transaction['type'],
                $transaction['montant'],
                $transaction['libelle']
            ]);
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
        echo "\n📊 Statistiques de la base ($this->driver):\n";
        
        // Compter les utilisateurs
        $stmt = $this->pdo->query("SELECT COUNT(*) as count FROM utilisateur");
        $userCount = $stmt->fetch()['count'];
        echo "👥 Utilisateurs: $userCount\n";
        
        // Compter les comptes par type
        $stmt = $this->pdo->query("
            SELECT statut, COUNT(*) as count 
            FROM compte 
            GROUP BY statut 
            ORDER BY statut
        ");
        while ($row = $stmt->fetch()) {
            $type = $row['statut'] === 'COMPTE_PRINCIPAL' ? 'Principaux' : 'Secondaires';
            echo "💳 Comptes $type: {$row['count']}\n";
        }
        
        // Compter les transactions par type
        $stmt = $this->pdo->query("
            SELECT type, COUNT(*) as count, SUM(montant) as total
            FROM transaction 
            GROUP BY type 
            ORDER BY type
        ");
        while ($row = $stmt->fetch()) {
            $total = number_format($row['total'], 0, ',', ' ');
            echo "💰 {$row['type']}: {$row['count']} transactions ({$total} FCFA)\n";
        }
        
        // Solde total
        $stmt = $this->pdo->query("SELECT SUM(solde) as total FROM compte");
        $totalSolde = $stmt->fetch()['total'];
        $totalFormatted = number_format($totalSolde, 0, ',', ' ');
        echo "💵 Solde total: $totalFormatted FCFA\n";
    }
    
    public function seedAll(): void {
        echo "🌱 Seeding complet de la base ($this->driver)...\n\n";
        
        $this->clearAll();
        $this->seedUtilisateurs();
        $this->seedComptes();
        $this->seedTransactions();
        
        echo "\n🎉 Seeding terminé avec succès !\n";
        $this->showStats();
    }
    
    public function createDirectories(): void {
        echo "📁 Création des dossiers d'upload...\n";
        
        $directories = [
            'uploads',
            'uploads/pieces_identite',
            'uploads/temp'
        ];
        
        foreach ($directories as $dir) {
            if (!is_dir($dir)) {
                mkdir($dir, 0755, true);
                echo "✅ Dossier créé: $dir\n";
            } else {
                echo "ℹ️ Dossier existe: $dir\n";
            }
        }
    }
    
    public function createSampleFiles(): void {
        echo "📄 Création des fichiers d'exemple...\n";
        
        $sampleFiles = [
            'uploads/recto_1.jpg',
            'uploads/verso_1.jpg',
            'uploads/recto_2.jpg',
            'uploads/verso_2.jpg',
            'uploads/recto_3.jpg',
            'uploads/verso_3.jpg',
            'uploads/recto_4.jpg',
            'uploads/verso_4.jpg',
            'uploads/recto_5.jpg',
            'uploads/verso_5.jpg'
        ];
        
        foreach ($sampleFiles as $file) {
            if (!file_exists($file)) {
                // Créer un fichier placeholder
                $content = "Fichier d'exemple pour " . basename($file);
                file_put_contents($file, $content);
                echo "✅ Fichier créé: $file\n";
            } else {
                echo "ℹ️ Fichier existe: $file\n";
            }
        }
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

// 🚀 Exécution
try {
    $driver = $_ENV['DB_DRIVER'] ?? 'pgsql';
    $dbName = $_ENV['DB_NAME'] ?? 'maxitsa_db';
    
    echo "🌱 Démarrage du seeder ($driver)...\n";
    echo "📊 Base de données: $dbName\n\n";
    
    $seeder = new Seeder();
    
    // Vérifier les arguments de ligne de commande
    $action = $argv[1] ?? 'all';
    
    switch ($action) {
        case 'users':
        case 'utilisateurs':
            $seeder->seedUtilisateurs();
            break;
            
        case 'comptes':
        case 'accounts':
            $seeder->seedComptes();
            break;
            
        case 'transactions':
            $seeder->seedTransactions();
            break;
            
        case 'clear':
        case 'clean':
            $seeder->clearAll();
            break;
            
        case 'stats':
            $seeder->showStats();
            break;
            
        case 'files':
            $seeder->createDirectories();
            $seeder->createSampleFiles();
            break;
            
        case 'all':
        default:
            $seeder->createDirectories();
            $seeder->createSampleFiles();
            $seeder->seedAll();
            break;
    }
    
    echo "\n💡 Commandes disponibles:\n";
    echo "  php seeder.php all          - Seeding complet (défaut)\n";
    echo "  php seeder.php users        - Seulement les utilisateurs\n";
    echo "  php seeder.php comptes      - Seulement les comptes\n";
    echo "  php seeder.php transactions - Seulement les transactions\n";
    echo "  php seeder.php clear        - Vider toutes les données\n";
    echo "  php seeder.php stats        - Afficher les statistiques\n";
    echo "  php seeder.php files        - Créer les dossiers et fichiers\n";
    echo "\n🎯 Driver utilisé: $driver\n";
    
} catch (PDOException $e) {
    echo "❌ Erreur de base de données: " . $e->getMessage() . "\n";
    echo "💡 Vérifiez que la migration a été exécutée et que la base existe.\n";
    echo "💡 Driver configuré: " . ($_ENV['DB_DRIVER'] ?? 'pgsql') . "\n";
} catch (Exception $e) {
    echo "❌ Erreur: " . $e->getMessage() . "\n";
}