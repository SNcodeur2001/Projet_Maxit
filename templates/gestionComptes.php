<?php
$user = $user ?? $_SESSION['user'];
$comptes = $comptes ?? [];
$errors = $errors ?? [];
$success = $success ?? '';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des comptes - MAXITSA</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#ff6500',
                        'primary-light': '#ff8533',
                        'primary-dark': '#e55700',
                    }
                }
            }
        }
    </script>
</head>
<body class="min-h-screen bg-gray-100">
    <div class="container mx-auto px-4 py-6 max-w-4xl">
        <!-- Header -->
        <div class="bg-gradient-to-r from-primary to-primary-light text-white rounded-2xl shadow-2xl mb-8 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold">Gestion de mes comptes</h1>
                    <p class="text-white/90 mt-2">Gérez vos comptes principal et secondaires</p>
                </div>
                <a href="/dashboard-client" class="bg-white/20 hover:bg-white/30 transition-all duration-300 px-6 py-3 rounded-lg backdrop-blur-sm font-medium">
                    ← Retour
                </a>
            </div>
        </div>

        <!-- Messages -->
        <?php if (!empty($errors)): ?>
            <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6 rounded-lg">
                <div class="flex items-center">
                    <div class="text-red-500 mr-3">⚠️</div>
                    <div>
                        <?php foreach ($errors as $error): ?>
                            <p class="text-red-700"><?= htmlspecialchars($error) ?></p>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <?php if ($success): ?>
            <div class="bg-green-50 border-l-4 border-green-500 p-4 mb-6 rounded-lg">
                <div class="flex items-center">
                    <div class="text-green-500 mr-3">✅</div>
                    <p class="text-green-700"><?= htmlspecialchars($success) ?></p>
                </div>
            </div>
        <?php endif; ?>

        <!-- Liste des comptes -->
        <div class="space-y-6">
            <?php foreach ($comptes as $compte): ?>
                <div class="bg-white rounded-2xl shadow-xl overflow-hidden border-2 <?= $compte['statut'] === 'COMPTE_PRINCIPAL' ? 'border-primary' : 'border-gray-200' ?>">
                    <div class="p-6">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-4">
                                <!-- Icône du compte -->
                                <div class="w-16 h-16 <?= $compte['statut'] === 'COMPTE_PRINCIPAL' ? 'bg-gradient-to-br from-primary to-primary-dark' : 'bg-gradient-to-br from-gray-400 to-gray-600' ?> rounded-2xl flex items-center justify-center shadow-lg">
                                    <?php if ($compte['statut'] === 'COMPTE_PRINCIPAL'): ?>
                                        <span class="text-white text-2xl">👑</span>
                                    <?php else: ?>
                                        <span class="text-white text-2xl">💳</span>
                                    <?php endif; ?>
                                </div>
                                
                                <!-- Informations du compte -->
                                <div>
                                    <div class="flex items-center space-x-3 mb-2">
                                        <h3 class="text-xl font-bold text-gray-800">
                                            <?= htmlspecialchars($compte['numero']) ?>
                                        </h3>
                                        <?php if ($compte['statut'] === 'COMPTE_PRINCIPAL'): ?>
                                            <span class="px-3 py-1 bg-gradient-to-r from-primary to-primary-dark text-white text-xs font-bold rounded-full">
                                                👑 PRINCIPAL
                                            </span>
                                        <?php else: ?>
                                            <span class="px-3 py-1 bg-gray-500 text-white text-xs font-bold rounded-full">
                                                💳 SECONDAIRE
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                    
                                    <div class="space-y-1">
                                        <p class="text-2xl font-bold text-green-600">
                                            <?= number_format($compte['solde'], 0, ',', ' ') ?> FCFA
                                        </p>
                                        <p class="text-sm text-gray-500">
                                            📅 Créé le <?= date('d/m/Y', strtotime($compte['created_at'])) ?>
                                        </p>
                                        <?php if (isset($compte['telephone_secondaire']) && $compte['telephone_secondaire']): ?>
                                            <p class="text-sm text-gray-500">
                                                📱 <?= htmlspecialchars($compte['telephone_secondaire']) ?>
                                            </p>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Actions -->
                            <div class="flex flex-col space-y-3">
                                <?php if ($compte['statut'] !== 'COMPTE_PRINCIPAL'): ?>
                                    <!-- Bouton pour faire principal -->
                                    <form method="POST" action="/make-compte-principal" class="inline">
                                        <input type="hidden" name="compte_id" value="<?= $compte['id'] ?>">
                                        <button type="submit" 
                                                onclick="return confirm('Êtes-vous sûr de vouloir faire de ce compte votre compte principal ? Votre compte principal actuel deviendra secondaire.')"
                                                class="bg-gradient-to-r from-primary to-primary-dark hover:from-primary-dark hover:to-red-600 text-white px-6 py-3 rounded-xl font-semibold transition-all duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-1">
                                            👑 Faire principal
                                        </button>
                                    </form>
                                <?php else: ?>
                                    <div class="text-center">
                                        <span class="text-primary font-semibold bg-orange-50 px-4 py-2 rounded-lg">
                                            ⭐ Compte actuel
                                        </span>
                                    </div>
                                <?php endif; ?>
                                
                                <!-- Bouton détails -->
                                <a href="/mes-transactions" 
                                   class="bg-gray-600 hover:bg-gray-700 text-white px-6 py-3 rounded-xl font-semibold transition-colors text-center">
                                    📊 Voir transactions
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Bouton pour ajouter un compte secondaire -->
        <div class="mt-8 text-center">
            <a href="/ajouter-compte-secondaire" 
               class="inline-flex items-center gap-2 bg-gradient-to-r from-blue-500 to-purple-600 hover:from-blue-600 hover:to-purple-700 text-white px-8 py-4 rounded-xl font-semibold transition-all duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-1">
                ➕ Ajouter un compte secondaire
            </a>
        </div>
    </div>
</body>
</html>
