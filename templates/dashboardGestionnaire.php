<?php
$comptes = $comptes ?? [];
$user = $user ?? $_SESSION['user'];
$currentPage = $currentPage ?? 1;
$totalPages = $totalPages ?? 1;
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lister comptes</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        
        body {
            font-family: 'Inter', sans-serif;
        }

        body, html {
            height: 100%;
            margin: 0;
            padding: 0;
            overflow: hidden; /* Empêche tout scroll vertical ET horizontal */
        }
        
        .glass-effect {
            backdrop-filter: blur(20px);
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        
        .gradient-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        
        .card-shadow {
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        }
        
        .hover-lift {
            transition: all 0.3s ease;
        }
        
        .hover-lift:hover {
            transform: translateY(-2px);
            box-shadow: 0 30px 60px -12px rgba(0, 0, 0, 0.3);
        }
        
        .sidebar-gradient {
            background: linear-gradient(145deg, #ea580c 0%, #f97316 100%);
        }
        
        .status-active {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        }
        
        .status-inactive {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        }
        
        .search-focus {
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
        }
        
        .table-row-hover {
            transition: all 0.2s ease;
        }
        
        .table-row-hover:hover {
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
            transform: scale(1.002);
        }
    </style>
</head>
<body class="bg-gradient-to-br from-slate-50 to-blue-50 font-sans h-screen w-full overflow-hidden">
    <div class="flex h-screen">
        <!-- Sidebar -->
        <div class="w-72 sidebar-gradient text-white shadow-2xl flex flex-col ">
            <!-- Header utilisateur -->
            <div class="p-8 border-b border-orange-600/50">
                <div class="flex items-center space-x-4">
                    <div class="w-16 h-16 bg-gradient-to-br from-orange-400 to-red-500 rounded-2xl flex items-center justify-center shadow-lg">
                        <img src="https://via.placeholder.com/64x64/ea580c/ffffff?text=<?= substr($user['nom'], 0, 1) ?>" alt="Profile" class="w-14 h-14 rounded-xl">
                    </div>
                    <div>
                        <h3 class="font-bold text-lg"><?php echo $user['nom'] . ' ' . $user['prenom']; ?></h3>
                        <p class="text-sm text-orange-200 font-medium"><?php echo $user['profil']; ?></p>
                    </div>
                </div>
            </div>
            
            <!-- Menu navigation -->
            <nav class="flex-1 mt-8 overflow-y-auto">
                <div class="px-6 space-y-3">
                    <a href="/dashboard-gestionnaire" class="flex items-center space-x-4 px-4 py-3 bg-gradient-to-r from-orange-500 to-red-600 text-white rounded-xl shadow-lg hover-lift">
                        <i class="fas fa-list text-lg"></i>
                        <span class="font-semibold">Lister comptes</span>
                    </a>
                    <a href="/recherche-compte" class="flex items-center space-x-4 px-4 py-3 text-orange-200 hover:bg-orange-600/50 rounded-xl transition-all duration-300 hover:text-white">
                        <i class="fas fa-search text-lg"></i>
                        <span class="font-semibold">Rechercher un compte</span>
                    </a>
                </div>
            </nav>
            
            <!-- Logout button -->
            <div class="absolute bottom-8 left-6">
                <a href="/logout" class="flex items-center space-x-3 text-orange-200 hover:text-white transition-all duration-300 hover:bg-orange-600/50 px-4 py-2 rounded-lg">
                    <i class="fas fa-sign-out-alt text-lg"></i>
                    <span class="font-semibold">Déconnexion</span>
                </a>
            </div>
        </div>
        
        <!-- Main content -->
        <div class="flex-1 flex flex-col min-h-0">
            <!-- Header -->
            <header class="bg-slate-700/90 backdrop-blur-lg border-b border-slate-600/50 text-white px-0 py-4 flex items-center justify-between shadow-sm">
                <div>
                    <h1 class="text-2xl font-bold text-white">Lister comptes</h1>
                    <p class="text-slate-300 mt-1">Gérez vos comptes clients</p>
                </div>
                <div class="w-12 h-12 bg-gradient-to-br from-orange-500 to-red-600 rounded-2xl shadow-lg"></div>
            </header>
            
            <!-- Content -->
            <main class="flex-1 bg-gradient-to-br from-slate-50 to-blue-50 h-full p-0 m-0 w-full overflow-y-auto">
                <!-- Search and filters -->
                <div class="mb-8 flex items-center justify-between">
                    <div class="flex items-center space-x-4">
                        <div class="relative">
                            <input type="text" placeholder="Rechercher..." class="pl-12 pr-6 py-4 border-0 bg-white/80 backdrop-blur-lg rounded-2xl w-80 focus:outline-none focus:ring-4 focus:ring-indigo-500/20 shadow-lg font-medium text-slate-700 placeholder-slate-400">
                            <i class="fas fa-search absolute left-4 top-5 text-slate-400"></i>
                        </div>
                        <button class="bg-slate-700 text-white px-8 py-4 rounded-2xl hover:bg-slate-600 transition-all duration-300 shadow-lg hover-lift font-semibold">
                            Rechercher
                        </button>
                    </div>
                    
                    <div class="flex items-center space-x-6">
                        <span class="text-slate-600 font-medium">Filtrer par</span>
                        <div class="flex items-center space-x-2 bg-white/80 backdrop-blur-lg px-4 py-2 rounded-xl shadow-sm cursor-pointer hover:bg-white transition-all duration-300">
                            <span class="text-slate-600 font-medium">Type</span>
                            <i class="fas fa-chevron-down text-slate-400"></i>
                        </div>
                        <div class="flex items-center space-x-2 bg-white/80 backdrop-blur-lg px-4 py-2 rounded-xl shadow-sm cursor-pointer hover:bg-white transition-all duration-300">
                            <span class="text-slate-600 font-medium">Date</span>
                            <i class="fas fa-chevron-down text-slate-400"></i>
                        </div>
                    </div>
                </div>
                
                <!-- Table -->
                <div class="bg-white/90 backdrop-blur-lg shadow-2xl overflow-hidden border border-white/20 w-full h-full m-0 p-0 rounded-none">
                    <table class="w-full">
                        <thead class="bg-gradient-to-r from-slate-50 to-blue-50">
                            <tr>
                                <th class="px-8 py-6 text-left text-xs font-bold text-slate-600 uppercase tracking-wider">Proprietaire</th>
                                <th class="px-8 py-6 text-left text-xs font-bold text-slate-600 uppercase tracking-wider">Téléphone</th>
                                <th class="px-8 py-6 text-left text-xs font-bold text-slate-600 uppercase tracking-wider">Solde</th>
                                <th class="px-8 py-6 text-left text-xs font-bold text-slate-600 uppercase tracking-wider">Statut</th>
                                <th class="px-8 py-6 text-left text-xs font-bold text-slate-600 uppercase tracking-wider">Status</th>
                                <th class="px-8 py-6 text-left text-xs font-bold text-slate-600 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            <?php foreach ($comptes as $compte): ?>
                                <tr class="table-row-hover">
                                    <td class="px-8 py-6 whitespace-nowrap">
                                        <div class="flex items-center space-x-3">
                                            <div class="w-10 h-10 bg-gradient-to-br from-orange-400 to-red-500 rounded-full flex items-center justify-center text-white font-bold text-sm shadow-lg">
                                                <?= substr($compte['nom_proprietaire'], 0, 1) ?>
                                            </div>
                                            <div>
                                                <div class="text-sm font-semibold text-slate-900">
                                                    <?= htmlspecialchars($compte['nom_proprietaire'] . ' ' . $compte['prenom_proprietaire']) ?>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-8 py-6 whitespace-nowrap text-sm font-medium text-slate-700">
                                        <?= htmlspecialchars($compte['numero']) ?>
                                    </td>
                                    <td class="px-8 py-6 whitespace-nowrap text-sm font-bold text-slate-900">
                                        <?= number_format($compte['solde'], 0, ',', ' ') ?> <span class="text-slate-500">FCFA</span>
                                    </td>
                                    <td class="px-8 py-6 whitespace-nowrap text-sm font-medium text-slate-700">
                                        <?= ucfirst(strtolower($compte['statut'])) ?>
                                    </td>
                                    <td class="px-8 py-6 whitespace-nowrap">
                                        <?php if (strtoupper($compte['statut']) === 'ACTIF'): ?>
                                            <span class="px-4 py-2 inline-flex text-xs leading-5 font-bold rounded-full status-active text-white shadow-lg">
                                                <i class="fas fa-check-circle mr-1"></i> Actif
                                            </span>
                                        <?php else: ?>
                                            <span class="px-4 py-2 inline-flex text-xs leading-5 font-bold rounded-full status-inactive text-white shadow-lg">
                                                <i class="fas fa-times-circle mr-1"></i> Inactif
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="px-8 py-6 whitespace-nowrap">
                                        <a href="/compte/<?= $compte['id'] ?>/detail" class="inline-flex items-center px-4 py-2 bg-slate-700 text-white font-semibold rounded-xl hover:bg-slate-600 transition-all duration-300 shadow-lg hover-lift">
                                            <i class="fas fa-eye mr-2"></i> Détail
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination -->
                <div class="mt-4 flex justify-center w-full">
                    <div class="flex space-x-2">
                        <?php if ($currentPage > 1): ?>
                            <a href="?page=<?= $currentPage - 1 ?>" class="px-4 py-2 bg-white/80 border border-slate-200 rounded-xl hover:bg-white shadow-sm transition-all duration-300 text-slate-600 font-medium">‹</a>
                        <?php endif; ?>
                        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                            <a href="?page=<?= $i ?>" class="px-4 py-2 <?= $i == $currentPage ? 'bg-gradient-to-r from-indigo-500 to-purple-600 text-white' : 'bg-white/80 border border-slate-200 text-slate-600' ?> rounded-xl shadow-lg font-semibold"><?= $i ?></a>
                        <?php endfor; ?>
                        <?php if ($currentPage < $totalPages): ?>
                            <a href="?page=<?= $currentPage + 1 ?>" class="px-4 py-2 bg-white/80 border border-slate-200 rounded-xl hover:bg-white shadow-sm transition-all duration-300 text-slate-600 font-medium">›</a>
                        <?php endif; ?>
                    </div>
                </div>
            </main>
        </div>
    </div>
</body>
</html>