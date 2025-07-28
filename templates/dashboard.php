<?php
if (!isset($_SESSION['user'])) {
    header('Location: /');
    exit;
}

$user = $_SESSION['user'];
$success = $_SESSION['success'] ?? '';
unset($_SESSION['success']);

$compteRepo = \App\Core\App::getDependency('compteRepository');
$comptes = $compteRepo->findAllByUserId($user['id']);
$compteActif = $_SESSION['compte_actif'] ?? $comptes[0]; // Par défaut, le premier compte

// Limite le nombre de transactions affichées pour éviter le scroll
$recentTransactions = $recentTransactions ?? [];
$recentTransactions = array_slice($recentTransactions, 0, 5);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - MAXITSA</title>
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
    <style>
        body {
            background: linear-gradient(135deg, #f8fafc 0%, #e0e7ff 100%);
        }
        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }
        .no-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
    </style>
</head>
<body class="min-h-screen bg-gray-100">
    <div class="container mx-auto px-4 py-6 max-w-5xl">
        <!-- Header -->
        <div class="bg-gradient-to-r from-primary to-primary-light text-white rounded-2xl shadow-2xl mb-8 overflow-hidden">
            <div class="p-8 flex flex-col md:flex-row justify-between items-center gap-6">
                <div class="flex items-center gap-6">
                    <div class="w-16 h-16 bg-white/20 rounded-full flex items-center justify-center text-2xl font-bold backdrop-blur-sm">
                        <?= strtoupper(substr($user['prenom'], 0, 1) . substr($user['nom'], 0, 1)) ?>
                    </div>
                    <div>
                        <div class="text-xl font-bold"><?= htmlspecialchars($user['prenom'] . ' ' . $user['nom']) ?></div>
                        <div class="text-white/90 text-sm">
                            Compte <?= ucfirst($user['profil']) ?> • <?= htmlspecialchars($user['telephone']) ?>
                        </div>
                    </div>
                </div>
                <a href="/logout" class="bg-white/20 hover:bg-white/30 transition-all duration-300 px-6 py-3 rounded-lg backdrop-blur-sm font-medium">
                    Déconnexion
                </a>
            </div>
        </div>

        <!-- Success Message -->
        <?php if ($success): ?>
            <div class="bg-green-50 border border-green-200 text-green-800 px-6 py-4 rounded-xl mb-8 shadow-sm">
                <div class="flex items-center gap-3">
                    <div class="text-green-500 text-xl">✓</div>
                    <div><?= htmlspecialchars($success) ?></div>
                </div>
            </div>
        <?php endif; ?>

        <!-- Account Info & Solde -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
            <div class="bg-white rounded-2xl shadow-xl p-8 border border-gray-100 flex flex-col justify-between">
                <h3 class="text-2xl font-bold text-primary mb-4 flex items-center gap-3">
                    <span class="text-2xl">📋</span>
                    Informations du compte
                </h3>
                <div class="space-y-3">
                    <div class="flex justify-between items-center">
                        <span class="font-semibold text-gray-700">Numéro de compte :</span>
                        <span class="font-mono text-gray-600 bg-gray-50 px-3 py-1 rounded-lg"><?= htmlspecialchars($compteActif['numero']) ?></span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="font-semibold text-gray-700">Statut du compte :</span>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider <?= strtolower($compteActif['statut'] ?? 'actif') === 'actif' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' ?>">
                            <?= htmlspecialchars($compteActif['statut'] ?? 'ACTIF') ?>
                        </span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="font-semibold text-gray-700">Type de compte :</span>
                        <span class="font-mono text-gray-600 bg-gray-50 px-3 py-1 rounded-lg"><?= htmlspecialchars($compteActif['statut'] === 'COMPTE_SECONDAIRE' ? 'Secondaire' : 'Primaire') ?></span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="font-semibold text-gray-700">Date de création :</span>
                        <span class="font-mono text-gray-600 bg-gray-50 px-3 py-1 rounded-lg"><?= date('d/m/Y', strtotime($compteActif['created_at'] ?? 'now')) ?></span>
                    </div>
                </div>
            </div>
            <div class="bg-gradient-to-br from-primary to-primary-light text-white rounded-2xl shadow-2xl p-8 text-center flex flex-col justify-center">
                <div class="text-xl font-medium mb-2 text-white/90">Solde du compte</div>
                <div class="text-5xl md:text-6xl font-bold mb-2 tracking-tight">
                    <?= number_format($compteActif['solde'] ?? 0, 0, ',', ' ') ?> FCFA
                </div>
            </div>
        </div>

        <!-- Actions Grid -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 p-6 text-center cursor-pointer hover:-translate-y-2 border border-gray-100 group" onclick="showAction('transfer')">
                <div class="text-5xl mb-4 group-hover:scale-110 transition-transform duration-300">💸</div>
                <div class="text-lg font-bold text-gray-800 mb-2">Effectuer un transfert</div>
                <div class="text-sm text-gray-600">Envoyer de l'argent rapidement et en sécurité</div>
            </div>
            <div class="bg-white rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 p-6 text-center cursor-pointer hover:-translate-y-2 border border-gray-100 group">
                <a href="/woyofal">
                <div class="text-5xl mb-4 group-hover:scale-110 transition-transform duration-300">💳</div>
                <div class="text-lg font-bold text-gray-800 mb-2">Woyofal</div>
                <div class="text-sm text-gray-600">Payer vos factures et services</div>
                </a>
            </div>
            <div class="bg-white rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 p-6 text-center cursor-pointer hover:-translate-y-2 border border-gray-100 group" onclick="showAction('history')">
                <div class="text-5xl mb-4 group-hover:scale-110 transition-transform duration-300">📊</div>
                <div class="text-lg font-bold text-gray-800 mb-2">Historique</div>
                <div class="text-sm text-gray-600">Consulter vos transactions</div>
            </div>
            <div class="bg-white rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 p-6 text-center cursor-pointer hover:-translate-y-2 border border-gray-100 group" onclick="showAction('deposit')">
                <div class="text-5xl mb-4 group-hover:scale-110 transition-transform duration-300">💰</div>
                <div class="text-lg font-bold text-gray-800 mb-2">Dépôt</div>
                <div class="text-sm text-gray-600">Alimenter votre compte</div>
            </div>
            <!-- Ajouter cette carte dans la section des actions rapides -->
            <div class="bg-white rounded-2xl shadow-xl p-6 hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-1">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800">Gestion des comptes</h3>
                        <p class="text-gray-600 text-sm">Gérer mes comptes</p>
                    </div>
                    <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-pink-600 rounded-xl flex items-center justify-center">
                        <span class="text-white text-xl">⚙️</span>
                    </div>
                </div>
                <a href="/gestion-comptes" class="inline-flex items-center gap-2 text-primary hover:text-primary-dark font-semibold">
                    Gérer mes comptes
                    <span class="text-lg">→</span>
                </a>
            </div>
        </div>

        <!-- Mes comptes -->
        <div class="mb-8">
            <h3 class="text-lg font-bold mb-2">Mes comptes</h3>
            <div class="flex flex-wrap gap-4">
                <?php foreach ($comptes as $compte): ?>
                    <form method="post" action="/basculer-compte">
                        <input type="hidden" name="compte_id" value="<?= $compte['id'] ?>">
                        <button type="submit"
                            class="px-4 py-2 rounded-xl border <?= $compteActif['id'] == $compte['id'] ? 'bg-blue-600 text-white' : 'bg-white text-blue-600 border-blue-600' ?>">
                            <?= htmlspecialchars($compte['numero']) ?> (<?= $compte['statut'] ?>)
                        </button>
                    </form>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Add Secondary Account Button -->
        <div class="text-center mb-8">
            <a href="/ajouter-compte-secondaire" class="bg-primary text-white px-4 py-2 rounded hover:bg-primary-dark">
                Ajouter un compte secondaire
            </a>
        </div>

        <!-- Recent Transactions (max 5, pas de scroll) -->
        <div class="bg-white rounded-2xl shadow-xl p-8 border border-gray-100">
            <h3 class="text-2xl font-bold text-primary mb-6 flex items-center gap-3">
                <span class="text-2xl">📊</span>
                Dernières transactions
            </h3>
            
            <?php if (empty($recentTransactions)): ?>
                <div class="text-center py-8">
                    <div class="text-6xl mb-6 opacity-50">💳</div>
                    <p class="text-xl text-gray-600 mb-2">Aucune transaction pour le moment</p>
                    <p class="text-gray-500">Vos futures transactions apparaîtront ici</p>
                </div>
            <?php else: ?>
                <div class="space-y-4">
                    <?php foreach ($recentTransactions as $transaction): ?>
                        <div class="flex items-center justify-between p-4 hover:bg-gray-50 rounded-xl transition-colors duration-200">
                            <div class="flex items-center gap-4">
                                <?php if ($transaction['type'] === 'credit'): ?>
                                    <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center text-xl">💰</div>
                                <?php else: ?>
                                    <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center text-xl">💸</div>
                                <?php endif; ?>
                                <div>
                                    <div class="font-semibold text-gray-800">
                                        <?= htmlspecialchars($transaction['description'] ?? 'Transaction') ?>
                                    </div>
                                    <div class="text-sm text-gray-500">
                                        <?= $transaction['date_formatted'] ?>
                                    </div>
                                </div>
                            </div>
                            <div class="font-bold text-lg font-mono <?= $transaction['type'] === 'credit' ? 'text-green-600' : 'text-red-600' ?>">
                                <?= $transaction['montant_formatted'] ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                <div class="mt-6 pt-6 border-t border-gray-200 text-center">
                    <a href="/mes-transactions" class="inline-flex items-center gap-2 text-primary hover:text-primary-dark font-semibold px-6 py-3 rounded-lg hover:bg-orange-50 transition-colors duration-200">
                        Voir toutes les transactions
                        <span class="text-lg">→</span>
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <script>
        function showAction(action) {
            alert('Action "' + action + '" sera implémentée dans les prochaines versions.');
        }
    </script>
</body>
</html>