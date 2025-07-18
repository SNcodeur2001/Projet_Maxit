<?php
// Pour éviter l'erreur si $comptes n'est pas défini
$comptes = $comptes ?? [];
$user = $user ?? $_SESSION['user'];
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
<body class="bg-gradient-to-br from-slate-50 to-blue-50 font-sans">
    <!-- Container principal -->
    <div class="flex min-h-screen">
        <!-- Sidebar -->
        <div class="w-72 sidebar-gradient text-white shadow-2xl">
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
            <nav class="mt-8">
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
        <div class="flex-1 flex flex-col">
            <!-- Header -->
            <header class="bg-slate-700/90 backdrop-blur-lg border-b border-slate-600/50 text-white p-6 flex items-center justify-between shadow-sm">
                <div>
                    <h1 class="text-2xl font-bold text-white">Lister comptes</h1>
                    <p class="text-slate-300 mt-1">Gérez vos comptes clients</p>
                </div>
                <div class="w-12 h-12 bg-gradient-to-br from-orange-500 to-red-600 rounded-2xl shadow-lg"></div>
            </header>
            
            <!-- Content -->
            <main class="flex-1 p-8 bg-gradient-to-br from-slate-50 to-blue-50">
                <div class="bg-white/90 backdrop-blur-lg rounded-3xl shadow-2xl overflow-hidden border border-white/20 p-8">
                    <?= $contentForLayout ?>
                </div>
            </main>
        </div>
    </div>
</body>
</html>