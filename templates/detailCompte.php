<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Détail du compte</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">
    <div class="max-w-xl w-full bg-white rounded-xl shadow p-8 space-y-6">
        <h2 class="text-2xl font-bold text-primary mb-4">Détail du compte</h2>
        <div class="mb-4">
            <div class="font-semibold">Numéro de compte :</div>
            <div class="font-mono"><?= htmlspecialchars($compte['numero']) ?></div>
        </div>
        <div class="mb-4">
            <div class="font-semibold">Solde :</div>
            <div class="text-2xl font-bold text-green-600"><?= number_format($compte['solde'], 0, ',', ' ') ?> FCFA</div>
        </div>
        <div>
            <div class="font-semibold mb-2">10 dernières transactions :</div>
            <?php if (empty($transactions)): ?>
                <div class="text-gray-500">Aucune transaction récente.</div>
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
        </div>
        <div class="text-center mt-6">
            <a href="/compte/<?= $compte['id'] ?>/transactions" class="text-orange-600 hover:underline font-semibold">Voir plus</a>
        </div>
    </div>
</body>
</html>