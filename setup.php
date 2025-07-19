<?php
echo "🚀 Configuration du projet MAXITSA\n\n";

// Vérifier si .env existe
if (!file_exists('.env')) {
    echo "📝 Création du fichier .env...\n";
    copy('.env.exemple', '.env');
    echo "✅ Fichier .env créé. Veuillez le configurer avant de continuer.\n";
    exit;
}

echo "Quel SGBD voulez-vous utiliser ?\n";
echo "1. PostgreSQL (recommandé)\n";
echo "2. MySQL\n";
echo "Choix (1-2): ";

$choice = trim(fgets(STDIN));

$driver = $choice === '2' ? 'mysql' : 'pgsql';

// Mettre à jour le .env
$envContent = file_get_contents('.env');
$envContent = preg_replace('/^DB_DRIVER=.*/m', "DB_DRIVER=$driver", $envContent);
file_put_contents('.env', $envContent);

echo "✅ Driver configuré: $driver\n";

// Demander si on veut exécuter les migrations
echo "\nVoulez-vous exécuter les migrations maintenant ? (y/n): ";
$runMigration = trim(fgets(STDIN));

if (strtolower($runMigration) === 'y') {
    echo "\n🔧 Exécution des migrations...\n";
    system('php migrations/migration.php --reset');
    
    echo "\nVoulez-vous peupler la base avec des données de test ? (y/n): ";
    $runSeeder = trim(fgets(STDIN));
    
    if (strtolower($runSeeder) === 'y') {
        echo "\n🌱 Exécution du seeder...\n";
        system('php seeders/seeder.php all');
    }
}

echo "\n🎉 Configuration terminée !\n";
echo "💡 Vous pouvez maintenant démarrer votre serveur web.\n";