<?php
if (!isset($_SESSION['user'])) {
    header('Location: /');
    exit;
}

$user = $_SESSION['user'];
$success = $_SESSION['success'] ?? '';
unset($_SESSION['success']);
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
</head>
<body class="min-h-screen bg-gray-100">
    <div class="container mx-auto px-4 py-6 max-w-6xl">
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
                            Compte <?= ucfirst($user['type']) ?> • <?= htmlspecialchars($user['telephone']) ?>
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

        <!-- Account Info -->
        <div class="bg-white rounded-2xl shadow-xl p-8 mb-8 border border-gray-100">
            <h3 class="text-2xl font-bold text-primary mb-6 flex items-center gap-3">
                <span class="text-2xl">📋</span>
                Informations du compte
            </h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="flex justify-between items-center p-4 bg-gray-50 rounded-xl">
                    <span class="font-semibold text-gray-700">Numéro de compte :</span>
                    <span class="font-mono text-gray-600 bg-white px-3 py-1 rounded-lg"><?= htmlspecialchars($user['numero_compte'] ?? 'Non défini') ?></span>
                </div>
                
                <div class="flex justify-between items-center p-4 bg-gray-50 rounded-xl">
                    <span class="font-semibold text-gray-700">Statut du compte :</span>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider <?= strtolower($user['statut_compte'] ?? 'actif') === 'actif' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' ?>">
                        <?= htmlspecialchars($user['statut_compte'] ?? 'ACTIF') ?>
                    </span>
                </div>
                
                <div class="flex justify-between items-center p-4 bg-gray-50 rounded-xl">
                    <span class="font-semibold text-gray-700">Type de compte :</span>
                    <span class="font-mono text-gray-600 bg-white px-3 py-1 rounded-lg">COMPTE PRIMAIRE</span>
                </div>
                
                <div class="flex justify-between items-center p-4 bg-gray-50 rounded-xl">
                    <span class="font-semibold text-gray-700">Date de création :</span>
                    <span class="font-mono text-gray-600 bg-white px-3 py-1 rounded-lg"><?= date('d/m/Y') ?></span>
                </div>
            </div>
        </div>

        <!-- Balance Card -->
        <div class="bg-gradient-to-br from-primary to-primary-light text-white rounded-2xl shadow-2xl p-8 mb-8 text-center relative overflow-hidden">
            <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -mr-16 -mt-16"></div>
            <div class="absolute bottom-0 left-0 w-24 h-24 bg-white/10 rounded-full -ml-12 -mb-12"></div>
            
            <div class="relative z-10">
                <div class="text-xl font-medium mb-4 text-white/90">Solde du compte principal</div>
                <div class="text-5xl md:text-6xl font-bold mb-6 tracking-tight">
                    <?= number_format($user['solde'] ?? 0, 0, ',', ' ') ?> FCFA
                </div>
                <?php //if ($user['numero']): ?>
                    <!-- <div class="bg-white/20 backdrop-blur-sm px-6 py-3 rounded-xl font-mono text-lg tracking-wider inline-block">
                        N° <?= htmlspecialchars($user['numero']) ?>
                    </div> -->
                <?php //endif; ?>
            </div>
        </div>

        <!-- Actions Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 p-6 text-center cursor-pointer hover:-translate-y-2 border border-gray-100 group" onclick="showAction('transfer')">
                <div class="text-5xl mb-4 group-hover:scale-110 transition-transform duration-300">💸</div>
                <div class="text-lg font-bold text-gray-800 mb-2">Effectuer un transfert</div>
                <div class="text-sm text-gray-600">Envoyer de l'argent rapidement et en sécurité</div>
            </div>

            <div class="bg-white rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 p-6 text-center cursor-pointer hover:-translate-y-2 border border-gray-100 group" onclick="showAction('payment')">
                <div class="text-5xl mb-4 group-hover:scale-110 transition-transform duration-300">💳</div>
                <div class="text-lg font-bold text-gray-800 mb-2">Paiement</div>
                <div class="text-sm text-gray-600">Payer vos factures et services</div>
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
        </div>

        <!-- Recent Transactions -->
        <div class="bg-white rounded-2xl shadow-xl p-8 border border-gray-100">
            <h3 class="text-2xl font-bold text-primary mb-6 flex items-center gap-3">
                <span class="text-2xl">📊</span>
                Dernières transactions
            </h3>
            
            <?php if (empty($recentTransactions)): ?>
                <div class="text-center py-16">
                    <div class="text-6xl mb-6 opacity-50">💳</div>
                    <p class="text-xl text-gray-600 mb-2">Aucune transaction pour le moment</p>
                    <p class="text-gray-500">Vos futures transactions apparaîtront ici</p>
                </div>
            <?php else: ?>
                <div class="space-y-4 max-h-96 overflow-y-auto">
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
                    <a href="/transactions" class="inline-flex items-center gap-2 text-primary hover:text-primary-dark font-semibold px-6 py-3 rounded-lg hover:bg-orange-50 transition-colors duration-200">
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