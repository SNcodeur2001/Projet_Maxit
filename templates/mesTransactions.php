<?php
$user = $user ?? $_SESSION['user'];
$transactionsFormatees = $transactionsFormatees ?? [];
$type = $_GET['type'] ?? '';
$dateStart = $_GET['date_start'] ?? '';
$dateEnd = $_GET['date_end'] ?? '';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mes Transactions - MAXITSA</title>
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
        <div class="bg-gradient-to-r from-primary to-primary-light text-white rounded-2xl shadow-2xl mb-8 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold">Mes Transactions</h1>
                    <p class="text-white/90 mt-2">Historique complet de vos transactions</p>
                </div>
                <a href="/dashboard-client" class="bg-white/20 hover:bg-white/30 transition-all duration-300 px-6 py-3 rounded-lg backdrop-blur-sm font-medium">
                    ← Retour
                </a>
            </div>
        </div>

        <!-- Filtres -->
        <div class="bg-white rounded-2xl shadow-xl p-6 mb-8">
            <h3 class="text-xl font-bold text-gray-800 mb-4">Filtrer les transactions</h3>
            <form method="GET" action="/mes-transactions" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <!-- Filtre par type -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Type de transaction</label>
                    <select name="type" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                        <option value="">Tous les types</option>
                        <option value="DEPOT" <?= $type === 'DEPOT' ? 'selected' : '' ?>>Dépôt</option>
                        <option value="RETRAIT" <?= $type === 'RETRAIT' ? 'selected' : '' ?>>Retrait</option>
                        <option value="TRANSFERT_ENVOYE" <?= $type === 'TRANSFERT_ENVOYE' ? 'selected' : '' ?>>Transfert envoyé</option>
                        <option value="TRANSFERT_RECU" <?= $type === 'TRANSFERT_RECU' ? 'selected' : '' ?>>Transfert reçu</option>
                    </select>
                </div>

                <!-- Date de début -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Date de début</label>
                    <input type="date" name="date_start" value="<?= htmlspecialchars($dateStart) ?>" 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                </div>

                <!-- Date de fin -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Date de fin</label>
                    <input type="date" name="date_end" value="<?= htmlspecialchars($dateEnd) ?>" 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                </div>

                <!-- Bouton de recherche -->
                <div class="flex items-end">
                    <button type="submit" class="w-full bg-primary hover:bg-primary-dark text-white px-6 py-2 rounded-lg font-semibold transition-colors">
                        Filtrer
                    </button>
                </div>
            </form>
        </div>

        <!-- Liste des transactions -->
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
            <div class="p-6 border-b border-gray-200">
                <h3 class="text-xl font-bold text-gray-800">
                    Historique des transactions (<?= count($transactionsFormatees) ?> résultat<?= count($transactionsFormatees) > 1 ? 's' : '' ?>)
                </h3>
            </div>

            <?php if (empty($transactionsFormatees)): ?>
                <div class="text-center py-12">
                    <div class="text-6xl mb-4 opacity-50">💳</div>
                    <p class="text-xl text-gray-600 mb-2">Aucune transaction trouvée</p>
                    <p class="text-gray-500">Essayez de modifier vos critères de recherche</p>
                </div>
            <?php else: ?>
                <div class="divide-y divide-gray-200">
                    <?php foreach ($transactionsFormatees as $transaction): ?>
                        <div class="p-6 hover:bg-gray-50 transition-colors">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-4">
                                    <!-- Icône selon le type -->
                                    <div class="w-12 h-12 rounded-full flex items-center justify-center
                                        <?= in_array($transaction['type'], ['DEPOT', 'TRANSFERT_RECU']) ? 'bg-green-100' : 'bg-red-100' ?>">
                                        <?php if (in_array($transaction['type'], ['DEPOT', 'TRANSFERT_RECU'])): ?>
                                            <span class="text-green-600 text-xl">↓</span>
                                        <?php else: ?>
                                            <span class="text-red-600 text-xl">↑</span>
                                        <?php endif; ?>
                                    </div>

                                    <!-- Détails de la transaction -->
                                    <div>
                                        <h4 class="font-semibold text-gray-800">
                                            <?= htmlspecialchars($transaction['type_display']) ?>
                                        </h4>
                                        <p class="text-sm text-gray-500">
                                            <?= $transaction['date_formatted'] ?>
                                        </p>
                                        <?php if (!empty($transaction['libelle'])): ?>
                                            <p class="text-sm text-gray-600 mt-1">
                                                <?= htmlspecialchars($transaction['libelle']) ?>
                                            </p>
                                        <?php endif; ?>
                                    </div>
                                </div>

                                <!-- Montant -->
                                <div class="text-right">
                                    <div class="text-lg font-bold <?= $transaction['montant_formatted']['color'] ?>">
                                        <?= $transaction['montant_formatted']['formatted'] ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
