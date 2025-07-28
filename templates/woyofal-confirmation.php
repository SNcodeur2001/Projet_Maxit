<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmation Woyofal - MAXITSA</title>
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
                <h1 class="text-4xl md:text-5xl font-bold mb-3 tracking-wide">Confirmation</h1>
                <p class="text-xl opacity-90 font-light">Achat Woyofal réussi</p>
            </div>
        </div>

        <div class="p-8 md:p-12">
            <?php if (isset($_SESSION['success'])): ?>
                <div class="bg-green-50 border-l-4 border-green-500 p-4 mb-6 rounded-lg animate-slide-up">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="w-5 h-5 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-green-700"><?= htmlspecialchars($_SESSION['success']) ?></p>
                        </div>
                    </div>
                </div>
                <?php unset($_SESSION['success']); ?>
            <?php endif; ?>

            <?php if (isset($_SESSION['woyofal_receipt'])): ?>
                <div class="bg-gray-50 p-6 rounded-xl shadow-inner mb-6">
                    <h2 class="text-2xl font-bold text-gray-800 mb-4">Détails de la transaction</h2>
                    <?php
                    $receipt = $_SESSION['woyofal_receipt'];
                    unset($_SESSION['woyofal_receipt']);
                    ?>
                    <div class="space-y-4">
                        <div class="grid grid-cols-2 gap-4">
                            <div class="text-gray-600">Numéro compteur:</div>
                            <div class="font-semibold"><?= htmlspecialchars($receipt['data']['compteur']) ?></div>
                            
                            <div class="text-gray-600">Code de recharge:</div>
                            <div class="font-semibold text-brand-orange text-lg"><?= htmlspecialchars($receipt['data']['code']) ?></div>
                            
                            <div class="text-gray-600">Montant:</div>
                            <div class="font-semibold"><?= number_format($receipt['data']['prix'], 0, ',', ' ') ?> FCFA</div>
                            
                            <div class="text-gray-600">Nombre de kWh:</div>
                            <div class="font-semibold"><?= htmlspecialchars($receipt['data']['nbreKwt']) ?> kWh</div>
                            
                            <div class="text-gray-600">Tranche:</div>
                            <div class="font-semibold"><?= htmlspecialchars($receipt['data']['tranche']) ?></div>
                            
                            <div class="text-gray-600">Client:</div>
                            <div class="font-semibold"><?= htmlspecialchars($receipt['data']['client']) ?></div>
                            
                            <div class="text-gray-600">Référence:</div>
                            <div class="font-semibold"><?= htmlspecialchars($receipt['data']['reference']) ?></div>
                            
                            <div class="text-gray-600">Date:</div>
                            <div class="font-semibold"><?= date('d/m/Y H:i', strtotime($receipt['data']['date'])) ?></div>
                        </div>
                        
                        <div class="mt-6 p-4 bg-green-50 border border-green-200 rounded-lg">
                            <h3 class="text-lg font-semibold text-green-800 mb-2">Code de recharge important :</h3>
                            <div class="text-2xl font-mono font-bold text-green-700 tracking-wider text-center p-4 bg-white rounded border-2 border-green-300">
                                <?= htmlspecialchars($receipt['data']['code']) ?>
                            </div>
                            <p class="text-sm text-green-600 mt-2 text-center">
                                Saisissez ce code sur votre compteur Woyofal pour recharger votre électricité
                            </p>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <div class="flex flex-col items-center space-y-4">
                <a href="/dashboard-client" 
                   class="bg-gradient-to-r from-brand-orange to-brand-light text-white py-4 px-8 rounded-xl font-semibold text-lg hover:from-brand-light hover:to-brand-orange transform hover:scale-[1.02] transition-all duration-300 shadow-lg hover:shadow-xl">
                    Retour au tableau de bord
                </a>
                
                <a href="/woyofal" 
                   class="text-brand-light hover:text-brand-orange font-semibold">
                    Effectuer un nouvel achat
                </a>
            </div>
        </div>
    </div>
</body>
</html>