<?php
$title = "Transactions du compte";
$headerTitle = "Transactions du compte";
ob_start();
?>
<div class="max-w-2xl w-full bg-white rounded-xl shadow p-8 space-y-6 mx-auto">
    <h2 class="text-2xl font-bold text-primary mb-4">Toutes les transactions</h2>
    <!-- Filtres -->
    <form method="get" class="flex flex-wrap gap-4 mb-6">
        <input type="hidden" name="id" value="<?= htmlspecialchars($compte['id']) ?>">
        <select name="type" class="border rounded px-3 py-2">
            <option value="">Tous types</option>
            <option value="DEPOT" <?= ($_GET['type'] ?? '') === 'DEPOT' ? 'selected' : '' ?>>Dépôt</option>
            <option value="RETRAIT" <?= ($_GET['type'] ?? '') === 'RETRAIT' ? 'selected' : '' ?>>Retrait</option>
            <option value="TRANSFERT_ENVOYE" <?= ($_GET['type'] ?? '') === 'TRANSFERT_ENVOYE' ? 'selected' : '' ?>>Transfert envoyé</option>
            <option value="TRANSFERT_RECU" <?= ($_GET['type'] ?? '') === 'TRANSFERT_RECU' ? 'selected' : '' ?>>Transfert reçu</option>
        </select>
        <input type="date" name="dateStart" value="<?= htmlspecialchars($_GET['dateStart'] ?? '') ?>" class="border rounded px-3 py-2" />
        <input type="date" name="dateEnd" value="<?= htmlspecialchars($_GET['dateEnd'] ?? '') ?>" class="border rounded px-3 py-2" />
        <button type="submit" class="bg-orange-500 text-white px-4 py-2 rounded">Filtrer</button>
    </form>
    <!-- Liste des transactions -->
    <?php if (empty($transactions)): ?>
        <div class="text-gray-500 text-center">Aucune transaction trouvée.</div>
    <?php else: ?>
        <ul class="space-y-2">
            <?php foreach ($transactions as $t): ?>
                <li class="flex justify-between border-b pb-1">
                    <span><?= htmlspecialchars($t['type']) ?> - <?= htmlspecialchars($t['montant']) ?> FCFA</span>
                    <span class="text-xs text-gray-500"><?= date('d/m/Y', strtotime($t['created_at'])) ?></span>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
    <div class="text-center mt-6">
        <a href="/recherche-compte" class="text-blue-600 hover:underline font-semibold">Retour à la recherche</a>
    </div>
</div>
<?php
$contentForLayout = ob_get_clean();
require __DIR__ . '/layout/gestionnaire.layout.php';
?>