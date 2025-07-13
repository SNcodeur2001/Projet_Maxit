<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lister comptes</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 font-sans">
    <!-- Container principal -->
    <div class="flex min-h-screen">
        <!-- Sidebar -->
        <div class="w-64 bg-orange-500 text-white">
            <!-- Header utilisateur -->
            <div class="p-6 border-b border-orange-600">
                <div class="flex items-center space-x-3">
                    <div class="w-12 h-12 bg-white rounded-full flex items-center justify-center">
                        <img src="https://via.placeholder.com/48x48/666666/ffffff?text=M" alt="Profile" class="w-10 h-10 rounded-full">
                    </div>
                    <div>
                        <h3 class="font-semibold"><?php echo $user['nom'] . ' ' . $user['prenom']; ?></h3>
                        <p class="text-sm text-orange-200"><?php echo $user['profil']; ?></p>
                    </div>
                </div>
            </div>
            
            <!-- Menu navigation -->
            <nav class="mt-6">
                <div class="px-4 space-y-2">
                    <a href="#" class="flex items-center space-x-3 px-3 py-2 text-orange-200 hover:bg-orange-600 rounded">
                        <i class="fas fa-plus-circle"></i>
                        <span>Ajouter categorie</span>
                    </a>
                    <a href="#" class="flex items-center space-x-3 px-3 py-2 bg-orange-600 text-white rounded">
                        <i class="fas fa-list"></i>
                        <span>Lister comptes</span>
                    </a>
                    <a href="#" class="flex items-center space-x-3 px-3 py-2 text-orange-200 hover:bg-orange-600 rounded">
                        <i class="fas fa-plus-circle"></i>
                        <span>Ajouter Article</span>
                    </a>
                    <a href="#" class="flex items-center space-x-3 px-3 py-2 text-orange-200 hover:bg-orange-600 rounded">
                        <i class="fas fa-list"></i>
                        <span>Lister Article</span>
                    </a>
                </div>
            </nav>
            
            <!-- Logout button -->
           <div class="absolute bottom-6 left-6">
    <a href="/logout" class="flex items-center space-x-2 text-orange-200 hover:text-white">
        <i class="fas fa-sign-out-alt"></i>
        <span>Déconnexion</span>
    </a>
</div>

        </div>
        
        <!-- Main content -->
        <div class="flex-1 flex flex-col">
            <!-- Header -->
            <header class="bg-slate-700 text-white p-4 flex items-center justify-between">
                <h1 class="text-xl font-semibold">Lister comptes</h1>
                <div class="w-10 h-10 bg-gray-300 rounded-full"></div>
            </header>
            
            <!-- Content -->
            <main class="flex-1 p-6 bg-gray-50">
                <!-- Search and filters -->
                <div class="mb-6 flex items-center justify-between">
                    <div class="flex items-center space-x-4">
                        <div class="relative">
                            <input type="text" placeholder="Rechercher..." class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg w-64 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                        </div>
                        <button class="bg-slate-700 text-white px-4 py-2 rounded-lg hover:bg-slate-600">
                            Rechercher
                        </button>
                    </div>
                    
                    <div class="flex items-center space-x-4">
                        <span class="text-gray-600">Filter par</span>
                        <div class="flex items-center space-x-2">
                            <span class="text-gray-600">Type</span>
                            <i class="fas fa-chevron-up text-gray-600"></i>
                        </div>
                        <div class="flex items-center space-x-2">
                            <span class="text-gray-600">Date</span>
                            <i class="fas fa-chevron-up text-gray-600"></i>
                        </div>
                    </div>
                </div>
                
                <!-- Table -->
                <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                    <table class="w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Proprietaire</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">telephone</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Country</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Jane Cooper</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">(225) 555-0118</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">jane@microsoft.com</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">United States</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Principal</span>
                                </td>
                            </tr>
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Floyd Miles</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">(205) 555-0100</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">floyd@yahoo.com</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Kiribati</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Secondaire</span>
                                </td>
                            </tr>
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Ronald Richards</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">(302) 555-0107</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">ronald@adobe.com</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Israel</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Secondaire</span>
                                </td>
                            </tr>
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Marvin McKinney</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">(252) 555-0126</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">marvin@tesla.com</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Iran</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Principal</span>
                                </td>
                            </tr>
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Jerome Bell</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">(629) 555-0129</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">jerome@google.com</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Réunion</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Principal</span>
                                </td>
                            </tr>
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Kathryn Murphy</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">(406) 555-0120</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">kathryn@microsoft.com</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Curaçao</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Principal</span>
                                </td>
                            </tr>
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Jacob Jones</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">(208) 555-0112</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">jacob@yahoo.com</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Brazil</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Principale</span>
                                </td>
                            </tr>
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Kristin Watson</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">(704) 555-0127</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">kristin@facebook.com</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Åland Islands</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Secondaire</span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination -->
                <div class="mt-6 flex justify-center">
                    <div class="flex space-x-2">
                        <button class="px-3 py-1 border border-gray-300 rounded hover:bg-gray-50">‹</button>
                        <button class="px-3 py-1 bg-blue-500 text-white rounded">1</button>
                        <button class="px-3 py-1 border border-gray-300 rounded hover:bg-gray-50">2</button>
                        <button class="px-3 py-1 border border-gray-300 rounded hover:bg-gray-50">3</button>
                        <button class="px-3 py-1 border border-gray-300 rounded hover:bg-gray-50">›</button>
                    </div>
                </div>
            </main>
        </div>
    </div>
</body>
</html>