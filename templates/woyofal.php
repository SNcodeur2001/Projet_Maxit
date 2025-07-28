<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Achat Woyofal - MAXITSA</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'brand-orange': '#ff6500',
                        'brand-light': '#ff6b35',
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-gradient-to-br from-gray-100 via-gray-50 to-blue-50 min-h-screen flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl shadow-2xl overflow-hidden w-full max-w-4xl relative animate-fade-in">
        <!-- Header avec gradient et animations -->
        <div class="bg-gradient-to-r from-brand-orange to-brand-light text-white text-center py-12 px-6 relative overflow-hidden">
            <div class="absolute inset-0 bg-black/5"></div>
            <div class="absolute top-0 left-0 w-full h-full">
                <div class="absolute top-4 left-4 w-8 h-8 bg-white/20 rounded-full animate-pulse-soft"></div>
                <div class="absolute top-8 right-8 w-4 h-4 bg-white/30 rounded-full animate-pulse-soft" style="animation-delay: 0.5s"></div>
                <div class="absolute bottom-6 left-1/3 w-6 h-6 bg-white/15 rounded-full animate-pulse-soft" style="animation-delay: 1s"></div>
            </div>
            <div class="relative z-10">
                <h1 class="text-4xl md:text-5xl font-bold mb-3 tracking-wide">Achat Woyofal</h1>
                <p class="text-xl opacity-90 font-light">Rechargez votre compteur électrique</p>
            </div>
        </div>

        <div class="p-8 md:p-12">
            <?php if (isset($_SESSION['errors'])): ?>
                <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6 rounded-lg animate-slide-up">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="w-5 h-5 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <?php foreach ($_SESSION['errors'] as $error): ?>
                                <p class="text-sm text-red-700"><?= htmlspecialchars($error) ?></p>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
                <?php unset($_SESSION['errors']); ?>
            <?php endif; ?>

            <form action="/woyofal/payer" method="POST" class="space-y-6">
                <div class="form-group">
                    <label for="numero_compteur" class="block text-sm font-semibold text-gray-700 mb-2">
                        Numéro du compteur <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           id="numero_compteur" 
                           name="numero_compteur" 
                           class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-brand-light focus:border-transparent transition-all duration-300"
                           placeholder="Ex: CPT123456" 
                           required>
                </div>

                <div class="form-group">
                    <label for="montant" class="block text-sm font-semibold text-gray-700 mb-2">
                        Montant (FCFA) <span class="text-red-500">*</span>
                    </label>
                    <input type="number" 
                           id="montant" 
                           name="montant" 
                           min="100" 
                           step="100"
                           class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-brand-light focus:border-transparent transition-all duration-300"
                           placeholder="Minimum 100 FCFA" 
                           required>
                </div>

                <button type="submit" 
                        class="w-full bg-gradient-to-r from-brand-orange to-brand-light text-white py-4 px-6 rounded-xl font-semibold text-lg hover:from-brand-light hover:to-brand-orange transform hover:scale-[1.02] transition-all duration-300 shadow-lg hover:shadow-xl">
                    Payer maintenant
                </button>

                <div class="text-center">
                    <a href="/dashboard-client" class="text-brand-light hover:text-brand-orange font-semibold">
                        Retour au tableau de bord
                    </a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>