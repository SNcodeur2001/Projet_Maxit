<?php
class Migration {
    private PDO $pdo;
    private string $driver;
    
    public function __construct() {
        $this->loadEnv();
        $this->driver = $_ENV['DB_DRIVER'] ?? 'pgsql';
        // ⚠️ NE PAS initialiser la connexion ici
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
    
    // 🔧 NOUVELLE MÉTHODE : Connexion à la base par défaut
    private function connectToDefaultDatabase(): PDO {
        $host = $_ENV['DB_HOST'] ?? 'localhost';
        $user = $_ENV['DB_USER'] ?? ($this->driver === 'pgsql' ? 'postgres' : 'root');
        $pass = $_ENV['DB_PASSWORD'] ?? '';
        
        if ($this->driver === 'pgsql') {
            $pdo = new PDO("pgsql:host=$host;dbname=postgres", $user, $pass);
        } else {
            $pdo = new PDO("mysql:host=$host", $user, $pass);
        }
        
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
    }
    
    // 🔧 MODIFIER : Méthode initializeConnection
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
    
    // 🔧 MODIFIER : Méthode createDatabase
    public function createDatabase(string $dbName): void {
        echo "🔧 Création de la base de données '$dbName' ($this->driver)...\n";
        
        try {
            if ($this->driver === 'pgsql') {
                $this->createPostgreSQLDatabase($dbName);
            } else {
                $this->createMySQLDatabase($dbName);
            }
            
            // Maintenant se connecter à la base créée
            $this->initializeConnection();
            echo "✅ Connexion à la base '$dbName' établie.\n";
            
        } catch (PDOException $e) {
            throw new Exception("Erreur lors de la création/connexion à la base: " . $e->getMessage());
        }
    }
    
    // 🔧 MODIFIER : Méthode createPostgreSQLDatabase
    private function createPostgreSQLDatabase(string $dbName): void {
        $defaultPdo = $this->connectToDefaultDatabase();
        
        // Vérifier si la base existe
        $stmt = $defaultPdo->prepare("SELECT 1 FROM pg_database WHERE datname = ?");
        $stmt->execute([$dbName]);
        
        if (!$stmt->fetch()) {
            $defaultPdo->exec("CREATE DATABASE \"$dbName\"");
            echo "✅ Base de données PostgreSQL '$dbName' créée.\n";
        } else {
            echo "ℹ️ Base de données PostgreSQL '$dbName' existe déjà.\n";
        }
    }
    
    // 🔧 MODIFIER : Méthode createMySQLDatabase
    private function createMySQLDatabase(string $dbName): void {
        $defaultPdo = $this->connectToDefaultDatabase();
        
        $defaultPdo->exec("CREATE DATABASE IF NOT EXISTS `$dbName` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
        echo "✅ Base de données MySQL '$dbName' créée ou existe déjà.\n";
    }
    
    public function createTypes(): void {
        if ($this->driver === 'pgsql') {
            echo "🔧 Création des types personnalisés PostgreSQL...\n";
            
            $types = [
                "CREATE TYPE profil_type AS ENUM ('CLIENT', 'SERVICE_COMMERCIAL')",
                "CREATE TYPE statut_type AS ENUM ('COMPTE_PRINCIPAL', 'COMPTE_SECONDAIRE')",
                "CREATE TYPE transaction_type AS ENUM ('DEPOT', 'RETRAIT', 'PAIEMENT')"
            ];
            
            foreach ($types as $sql) {
                try {
                    $this->pdo->exec($sql);
                } catch (PDOException $e) {
                    if (strpos($e->getMessage(), 'already exists') === false) {
                        throw $e;
                    }
                }
            }
            echo "✅ Types personnalisés PostgreSQL créés.\n";
        } else {
            echo "ℹ️ MySQL utilise des ENUM intégrés, pas de types personnalisés à créer.\n";
        }
    }
    
    public function createTables(): void {
        echo "📋 Création des tables ($this->driver)...\n";
        
        if ($this->driver === 'pgsql') {
            $this->createPostgreSQLTables();
        } else {
            $this->createMySQLTables();
        }
        
        echo "✅ Tables créées avec succès.\n";
    }
    
    private function createPostgreSQLTables(): void {
        $tables = [
            "CREATE TABLE IF NOT EXISTS utilisateur (
                id SERIAL PRIMARY KEY,
                nom VARCHAR(100) NOT NULL,
                prenom VARCHAR(100) NOT NULL,
                adresse VARCHAR(255),
                telephone VARCHAR(20) NOT NULL UNIQUE,
                numero_piece_identite VARCHAR(50) UNIQUE,
                photo_recto VARCHAR(255),
                photo_verso VARCHAR(255),
                profil profil_type DEFAULT 'CLIENT' NOT NULL,
                created_at TIMESTAMP WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP
            )",
            
            "CREATE TABLE IF NOT EXISTS compte (
                id SERIAL PRIMARY KEY,
                utilisateur_id INTEGER NOT NULL,
                numero VARCHAR(20) NOT NULL UNIQUE,
                solde NUMERIC(10,2) DEFAULT 0,
                created_at TIMESTAMP WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP,
                statut statut_type DEFAULT 'COMPTE_SECONDAIRE' NOT NULL,
                telephone_secondaire VARCHAR(20),
                FOREIGN KEY (utilisateur_id) REFERENCES utilisateur(id) ON DELETE CASCADE
            )",
            
            "CREATE TABLE IF NOT EXISTS transaction (
                id SERIAL PRIMARY KEY,
                compte_id INTEGER NOT NULL,
                type transaction_type NOT NULL,
                montant NUMERIC(10,2) NOT NULL,
                libelle VARCHAR(255),
                created_at TIMESTAMP WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (compte_id) REFERENCES compte(id) ON DELETE CASCADE
            )"
        ];
        
        foreach ($tables as $sql) {
            $this->pdo->exec($sql);
        }
    }
    
    private function createMySQLTables(): void {
        $tables = [
            "CREATE TABLE IF NOT EXISTS utilisateur (
                id INT AUTO_INCREMENT PRIMARY KEY,
                nom VARCHAR(100) NOT NULL,
                prenom VARCHAR(100) NOT NULL,
                adresse VARCHAR(255),
                telephone VARCHAR(20) NOT NULL UNIQUE,
                numero_piece_identite VARCHAR(50) UNIQUE,
                photo_recto VARCHAR(255),
                photo_verso VARCHAR(255),
                profil ENUM('CLIENT', 'SERVICE_COMMERCIAL') DEFAULT 'CLIENT' NOT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",
            
            "CREATE TABLE IF NOT EXISTS compte (
                id INT AUTO_INCREMENT PRIMARY KEY,
                utilisateur_id INT NOT NULL,
                numero VARCHAR(20) NOT NULL UNIQUE,
                solde DECIMAL(10,2) DEFAULT 0,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                statut ENUM('COMPTE_PRINCIPAL', 'COMPTE_SECONDAIRE') DEFAULT 'COMPTE_SECONDAIRE' NOT NULL,
                telephone_secondaire VARCHAR(20),
                FOREIGN KEY (utilisateur_id) REFERENCES utilisateur(id) ON DELETE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",
            
            "CREATE TABLE IF NOT EXISTS transaction (
                id INT AUTO_INCREMENT PRIMARY KEY,
                compte_id INT NOT NULL,
                type ENUM('DEPOT', 'RETRAIT', 'PAIEMENT') NOT NULL,
                montant DECIMAL(10,2) NOT NULL,
                libelle VARCHAR(255),
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (compte_id) REFERENCES compte(id) ON DELETE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci"
        ];
        
        foreach ($tables as $sql) {
            $this->pdo->exec($sql);
        }
    }
    
    public function dropTables(): void {
        echo "🗑️ Suppression des tables existantes...\n";
        
        if ($this->driver === 'mysql') {
            $this->pdo->exec("SET FOREIGN_KEY_CHECKS = 0");
        }
        
        $tables = ['transaction', 'compte', 'utilisateur'];
        foreach ($tables as $table) {
            if ($this->driver === 'pgsql') {
                $this->pdo->exec("DROP TABLE IF EXISTS $table CASCADE");
            } else {
                $this->pdo->exec("DROP TABLE IF EXISTS $table");
            }
        }
        
        if ($this->driver === 'mysql') {
            $this->pdo->exec("SET FOREIGN_KEY_CHECKS = 1");
        }
        
        echo "✅ Tables supprimées.\n";
    }
    
    public function dropTypes(): void {
        if ($this->driver === 'pgsql') {
            echo "🗑️ Suppression des types personnalisés PostgreSQL...\n";
            
            $types = ['transaction_type', 'statut_type', 'profil_type'];
            foreach ($types as $type) {
                try {
                    $this->pdo->exec("DROP TYPE IF EXISTS $type CASCADE");
                } catch (PDOException $e) {
                    // Ignorer les erreurs
                }
            }
            
            echo "✅ Types personnalisés supprimés.\n";
        } else {
            echo "ℹ️ MySQL n'utilise pas de types personnalisés à supprimer.\n";
        }
    }
    
    public function showTables(): void {
        echo "\n📋 Tables créées ($this->driver):\n";
        
        if ($this->driver === 'pgsql') {
            $stmt = $this->pdo->query("
                SELECT tablename 
                FROM pg_tables 
                WHERE schemaname = 'public' 
                AND tablename IN ('utilisateur', 'compte', 'transaction')
                ORDER BY tablename
            ");
        } else {
            $stmt = $this->pdo->query("SHOW TABLES");
        }
        
        while ($row = $stmt->fetch()) {
            echo "- " . ($row[0] ?? $row['tablename']) . "\n";
        }
        
        if ($this->driver === 'pgsql') {
            echo "\n🔧 Types personnalisés:\n";
            $stmt = $this->pdo->query("
                SELECT typname 
                FROM pg_type 
                WHERE typname IN ('profil_type', 'statut_type', 'transaction_type')
                ORDER BY typname
            ");
            while ($row = $stmt->fetch()) {
                echo "- " . $row['typname'] . "\n";
            }
        }
    }
    
    public function resetSequences(): void {
        echo "🔢 Réinitialisation des séquences...\n";
        
        if ($this->driver === 'pgsql') {
            $sequences = [
                'utilisateur_id_seq' => 1,
                'compte_id_seq' => 1,
                'transaction_id_seq' => 1
            ];
            
            foreach ($sequences as $sequence => $value) {
                $this->pdo->exec("SELECT setval('$sequence', $value, false)");
            }
        } else {
            // MySQL : Réinitialiser AUTO_INCREMENT
            $this->pdo->exec("ALTER TABLE utilisateur AUTO_INCREMENT = 1");
            $this->pdo->exec("ALTER TABLE compte AUTO_INCREMENT = 1");
            $this->pdo->exec("ALTER TABLE transaction AUTO_INCREMENT = 1");
        }
        
        echo "✅ Séquences/AUTO_INCREMENT réinitialisés.\n";
    }
    
    public function reset(): void {
        echo "🔄 Réinitialisation complète de la base ($this->driver)...\n";
        
        $dbName = $_ENV['DB_NAME'] ?? 'maxitsa_db';
        
        // Créer la base si elle n'existe pas
        $this->createDatabase($dbName);
        
        // Supprimer et recréer
        $this->dropTables();
        $this->dropTypes();
        $this->createTypes();
        $this->createTables();
        
        echo "✅ Base réinitialisée.\n";
    }
}

// 🚀 MODIFIER : Exécution
try {
    $driver = $_ENV['DB_DRIVER'] ?? 'pgsql';
    $dbName = $_ENV['DB_NAME'] ?? 'maxitsa_db';
    
    echo "🚀 Démarrage de la migration ($driver)...\n\n";
    
    $migration = new Migration();
    
    if (isset($argv[1]) && $argv[1] === '--reset') {
        $migration->reset();
        $migration->resetSequences();
    } else {
        $migration->createDatabase($dbName);
        $migration->createTypes();
        $migration->createTables();
    }
    
    $migration->showTables();
    
    echo "\n🎉 Migration ($driver) terminée avec succès.\n";
    echo "💡 Utilisez maintenant le seeder pour remplir la base de données.\n";
    echo "💡 Pour réinitialiser : php migration.php --reset\n";
    echo "💡 Base de données: $dbName ($driver)\n";
    
} catch (PDOException $e) {
    echo "❌ Erreur : " . $e->getMessage() . "\n";
    echo "💡 Vérifiez que votre SGBD est démarré et que les identifiants sont corrects.\n";
    echo "💡 Driver configuré : " . ($_ENV['DB_DRIVER'] ?? 'pgsql') . "\n";
} catch (Exception $e) {
    echo "❌ Erreur : " . $e->getMessage() . "\n";
}