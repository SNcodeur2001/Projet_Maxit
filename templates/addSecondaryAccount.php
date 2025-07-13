<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Ajouter un compte secondaire</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            background: linear-gradient(135deg, #f8fafc 0%, #e0e7ff 100%);
        }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center">
<?php
$errors = $_SESSION['errors'] ?? [];
unset($_SESSION['errors']);
?>
<div class="w-full max-w-lg mx-auto">
    <div class="bg-white rounded-3xl shadow-2xl p-10 md:p-12 border border-gray-100">
        <div class="flex items-center justify-center mb-8">
            <div class="bg-blue-100 rounded-full p-4">
                <svg class="w-8 h-8 text-blue-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 11c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v3h16v-3c0-2.66-5.33-4-8-4z"/>
                </svg>
            </div>
        </div>
        <h2 class="text-2xl font-extrabold text-center text-blue-700 mb-6">Ajouter un compte secondaire</h2>
        <?php if (!empty($errors)): ?>
            <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6 rounded-lg">
                <ul class="text-sm text-red-700 space-y-1">
                    <?php foreach ($errors as $error): ?>
                        <li><?= htmlspecialchars($error) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>
        <form method="post" action="/ajouter-compte-secondaire" class="space-y-6">
            <div>
                <label for="telephone" class="block text-sm font-semibold text-gray-700 mb-2">
                    Téléphone du compte secondaire <span class="text-red-500">*</span>
                </label>
                <input
                    type="tel"
                    id="telephone"
                    name="telephone"
                    required
                    placeholder="Ex: +221701234567 ou 701234567"
                    class="w-full px-4 py-3 border-2 border-blue-200 rounded-xl focus:ring-2 focus:ring-blue-400 focus:border-transparent transition-all duration-300 bg-blue-50"
                />
                <p class="mt-2 text-xs text-gray-500">Format sénégalais requis</p>
            </div>
            <button type="submit" class="w-full bg-gradient-to-r from-blue-600 to-blue-400 text-white py-3 rounded-xl font-semibold text-lg shadow-lg hover:from-blue-700 hover:to-blue-500 transition-all duration-300">
                <span class="flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                    </svg>
                    Créer le compte secondaire
                </span>
            </button>
        </form>
    </div>
</div>
</body>
</html>