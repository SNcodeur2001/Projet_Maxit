<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Ajouter un compte secondaire</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            background: linear-gradient(135deg, #fff7ed 0%, #fed7aa 100%);
        }
        .orange-gradient {
            background: linear-gradient(135deg, #ea580c 0%, #f97316 100%);
        }
        .orange-gradient-hover:hover {
            background: linear-gradient(135deg, #c2410c 0%, #ea580c 100%);
        }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center p-4">
<?php
$errors = $_SESSION['errors'] ?? [];
unset($_SESSION['errors']);
?>
<div class="w-full max-w-lg mx-auto">
    <div class="bg-white rounded-3xl shadow-2xl p-8 md:p-10 border border-orange-100 relative overflow-hidden">
        <!-- Decorative elements -->
        <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-orange-100 to-orange-200 rounded-full -mr-16 -mt-16 opacity-50"></div>
        <div class="absolute bottom-0 left-0 w-24 h-24 bg-gradient-to-tr from-orange-100 to-orange-200 rounded-full -ml-12 -mb-12 opacity-50"></div>
        
        <!-- Back button -->
        <div class="flex items-center justify-between mb-6">
            <a href="/dashboard-client" class="flex items-center gap-2 text-orange-600 hover:text-orange-700 transition-colors duration-200 group">
                <svg class="w-5 h-5 transform group-hover:-translate-x-1 transition-transform duration-200" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                <span class="font-medium">Retour</span>
            </a>
        </div>
        
        <!-- Header -->
        <div class="flex items-center justify-center mb-8">
            <div class="bg-orange-100 rounded-full p-4 shadow-lg">
                <svg class="w-8 h-8 text-orange-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                </svg>
            </div>
        </div>
        
        <h2 class="text-3xl font-bold text-center text-gray-800 mb-2">Ajouter un compte</h2>
        <p class="text-center text-gray-600 mb-8">Créez un compte secondaire pour votre client</p>
        
        <!-- Error messages -->
        <?php if (!empty($errors)): ?>
            <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6 rounded-lg shadow-sm">
                <div class="flex items-center mb-2">
                    <svg class="w-5 h-5 text-red-500 mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span class="font-medium text-red-700">Erreur de validation</span>
                </div>
                <ul class="text-sm text-red-700 space-y-1 ml-7">
                    <?php foreach ($errors as $error): ?>
                        <li><?= htmlspecialchars($error) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>
        
        <!-- Form -->
        <form method="post" action="/ajouter-compte-secondaire" class="space-y-6">
            <div class="relative">
                <label for="telephone" class="block text-sm font-semibold text-gray-700 mb-3">
                    <span class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-orange-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                        </svg>
                        Numéro de téléphone
                        <span class="text-red-500">*</span>
                    </span>
                </label>
                <div class="relative">
                    <input
                        type="tel"
                        id="telephone"
                        name="telephone"
                        required
                        placeholder="Ex: +221701234567 ou 701234567"
                        class="w-full px-4 py-4 pl-12 border-2 border-orange-200 rounded-xl focus:ring-2 focus:ring-orange-400 focus:border-transparent transition-all duration-300 bg-orange-50 text-gray-800 placeholder-gray-500"
                    />
                    <div class="absolute left-4 top-1/2 transform -translate-y-1/2">
                        <svg class="w-5 h-5 text-orange-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                        </svg>
                    </div>
                </div>
                <p class="mt-2 text-xs text-gray-500 flex items-center gap-1">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Format sénégalais requis
                </p>
            </div>
            
            <button type="submit" class="w-full orange-gradient orange-gradient-hover text-white py-4 rounded-xl font-semibold text-lg shadow-lg hover:shadow-xl transition-all duration-300 transform hover:scale-[1.02] active:scale-[0.98]">
                <span class="flex items-center justify-center gap-3">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                    </svg>
                    Créer le compte secondaire
                </span>
            </button>
        </form>
        
        <!-- Additional info -->
        <div class="mt-8 p-4 bg-orange-50 rounded-xl border border-orange-200">
            <div class="flex items-start gap-3">
                <svg class="w-5 h-5 text-orange-600 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <div>
                    <h4 class="font-semibold text-orange-800 mb-1">Information importante</h4>
                    <p class="text-sm text-orange-700">Le compte secondaire sera automatiquement lié à votre compte principal et bénéficiera des mêmes services.</p>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>