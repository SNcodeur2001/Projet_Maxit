<?php
class Migration {
    private PDO $pdo;
    
    public function __construct(string $host, string $user, string $pass, string $dbName) {
        $this->pdo = new PDO("pgsql:host=$host;dbname=$dbName", $user, $pass);
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
    
    public function createDatabase(string $dbName): void {
        // Pour PostgreSQL, nous devons nous connecter à une base existante pour créer une nouvelle base
        $tempPdo = new PDO("pgsql:host=localhost;dbname=postgres", "postgres", "passer123");
        $tempPdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        // Vérifier si la base existe déjà
        $stmt = $tempPdo->prepare("SELECT 1 FROM pg_database WHERE datname = ?");
        $stmt->execute([$dbName]);
        
        if (!$stmt->fetch()) {
            $tempPdo->exec("CREATE DATABASE \"$dbName\"");
            echo "✅ Base de données '$dbName' créée.\n";
        } else {
            echo "✅ Base de données '$dbName' existe déjà.\n";
        }
        
        // Reconnexion à la base nouvellement créée
        $this->pdo = new PDO("pgsql:host=localhost;dbname=$dbName", "postgres", "passer123");
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
    
    public function createTypes(): void {
        echo "🔧 Création des types personnalisés...\n";
        
        $types = [
            "CREATE TYPE profil_type AS ENUM ('CLIENT', 'SERVICE_COMMERCIAL')",
            "CREATE TYPE statut_type AS ENUM ('COMPTE_PRINCIPAL', 'COMPTE_SECONDAIRE')",
            "CREATE TYPE transaction_type AS ENUM ('DEPOT', 'RETRAIT', 'PAIEMENT')"
        ];
        
        foreach ($types as $sql) {
            try {
                $this->pdo->exec($sql);
            } catch (PDOException $e) {
                // Ignorer si le type existe déjà
                if (strpos($e->getMessage(), 'already exists') === false) {
                    throw $e;
                }
            }
        }
        echo "✅ Types personnalisés créés.\n";
    }
    
    public function createTables(): void {
        echo "📋 Création des tables...\n";
        
        $tables = [
            // Table utilisateur
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
            
            // Table compte
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
            
            // Table transaction
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
        echo "✅ Tables créées avec succès.\n";
    }
    
    public function dropTables(): void {
        echo "🗑️ Suppression des tables existantes...\n";
        
        $tables = ['transaction', 'compte', 'utilisateur'];
        foreach ($tables as $table) {
            $this->pdo->exec("DROP TABLE IF EXISTS $table CASCADE");
        }
        
        echo "✅ Tables supprimées.\n";
    }
    
    public function dropTypes(): void {
        echo "🗑️ Suppression des types personnalisés...\n";
        
        $types = ['transaction_type', 'statut_type', 'profil_type'];
        foreach ($types as $type) {
            try {
                $this->pdo->exec("DROP TYPE IF EXISTS $type CASCADE");
            } catch (PDOException $e) {
                // Ignorer les erreurs si le type n'existe pas ou est utilisé
            }
        }
        
        echo "✅ Types personnalisés supprimés.\n";
    }
    
    public function showTables(): void {
        echo "\n📋 Tables créées:\n";
        $stmt = $this->pdo->query("
            SELECT tablename 
            FROM pg_tables 
            WHERE schemaname = 'public' 
            AND tablename IN ('utilisateur', 'compte', 'transaction')
            ORDER BY tablename
        ");
        while ($row = $stmt->fetch()) {
            echo "- " . $row['tablename'] . "\n";
        }
        
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
        
        echo "\n📊 Structure des tables:\n";
        $tables = ['utilisateur', 'compte', 'transaction'];
        foreach ($tables as $table) {
            echo "\n🔹 Table: $table\n";
            $stmt = $this->pdo->query("
                SELECT 
                    column_name,
                    data_type,
                    is_nullable,
                    column_default,
                    character_maximum_length
                FROM information_schema.columns 
                WHERE table_name = '$table' 
                AND table_schema = 'public'
                ORDER BY ordinal_position
            ");
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $length = $row['character_maximum_length'] ? "({$row['character_maximum_length']})" : '';
                $nullable = $row['is_nullable'] === 'YES' ? 'NULL' : 'NOT NULL';
                $default = $row['column_default'] ? "DEFAULT {$row['column_default']}" : '';
                echo "  - {$row['column_name']} {$row['data_type']}{$length} {$nullable} {$default}\n";
            }
        }
        
        echo "\n🔗 Contraintes et index:\n";
        $stmt = $this->pdo->query("
            SELECT 
                tc.table_name,
                tc.constraint_name,
                tc.constraint_type
            FROM information_schema.table_constraints tc
            WHERE tc.table_schema = 'public' 
            AND tc.table_name IN ('utilisateur', 'compte', 'transaction')
            ORDER BY tc.table_name, tc.constraint_type
        ");
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo "  - {$row['table_name']}: {$row['constraint_name']} ({$row['constraint_type']})\n";
        }
    }
    
    public function reset(): void {
        echo "🔄 Réinitialisation complète de la base...\n";
        $this->dropTables();
        $this->dropTypes();
        $this->createTypes();
        $this->createTables();
        echo "✅ Base réinitialisée.\n";
    }
    
    public function resetSequences(): void {
        echo "🔢 Réinitialisation des séquences...\n";
        
        $sequences = [
            'utilisateur_id_seq' => 1,
            'compte_id_seq' => 1,
            'transaction_id_seq' => 1
        ];
        
        foreach ($sequences as $sequence => $value) {
            $this->pdo->exec("SELECT setval('$sequence', $value, false)");
        }
        
        echo "✅ Séquences réinitialisées.\n";
    }
}

// 🔧 Configuration
$host = 'localhost';
$user = 'postgres';
$pass = 'passer123';
$dbName = 'maxitsa_db1';

// 🚀 Exécution
try {
    echo "🚀 Démarrage de la migration PostgreSQL (structure)...\n\n";
    
    $migration = new Migration($host, $user, $pass, 'postgres');
    $migration->createDatabase($dbName);
    
    // Reconnexion à la nouvelle base
    $migration = new Migration($host, $user, $pass, $dbName);
    
    // Vérifier s'il faut réinitialiser ou créer
    if (isset($argv[1]) && $argv[1] === '--reset') {
        $migration->reset();
        $migration->resetSequences();
    } else {
        $migration->createTypes();
        $migration->createTables();
    }
    
    $migration->showTables();
    
    echo "\n🎉 Migration de structure PostgreSQL terminée avec succès.\n";
    echo "💡 Utilisez maintenant le seeder pour remplir la base de données.\n";
    echo "💡 Pour réinitialiser : php migration.php --reset\n";
    echo "💡 Base de données: $dbName\n";
    echo "💡 Types personnalisés: profil_type, statut_type, transaction_type\n";
    
} catch (PDOException $e) {
    echo "❌ Erreur : " . $e->getMessage() . "\n";
    echo "💡 Vérifiez que PostgreSQL est démarré et que les identifiants sont corrects.\n";
    echo "💡 Assurez-vous que l'utilisateur 'postgres' a les droits de création de base.\n";
}