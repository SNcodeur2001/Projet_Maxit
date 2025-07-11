<?php
if (!isset($_SESSION['user'])) {
    header('Location: /');
    exit;
}

$user = $_SESSION['user'];
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mes Transactions - MAXITSA</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
            background: rgb(237, 235, 235);
            min-height: 100vh;
        }

        .container {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            width: 100%;
            max-width: 1200px;
            margin: 20px auto;
        }

        .header {
            background: #ff6500;
            color: white;
            padding: 20px 40px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .back-btn {
            background: rgba(255, 255, 255, 0.2);
            border: none;
            color: white;
            padding: 8px 16px;
            border-radius: 6px;
            cursor: pointer;
            text-decoration: none;
            transition: background 0.3s ease;
        }

        .back-btn:hover {
            background: rgba(255, 255, 255, 0.3);
        }

        .content {
            padding: 30px 40px;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: linear-gradient(135deg, #f8f9fa, #e9ecef);
            border-radius: 12px;
            padding: 20px;
            text-align: center;
            border: 1px solid #dee2e6;
        }

        .stat-card.credit {
            background: linear-gradient(135deg, #d4edda, #c3e6cb);
            border-color: #28a745;
        }

        .stat-card.debit {
            background: linear-gradient(135deg, #f8d7da, #f5c6cb);
            border-color: #dc3545;
        }

        .stat-value {
            font-size: 1.5em;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .stat-label {
            color: #666;
            font-size: 0.9em;
        }

        .filters {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 12px;
            margin-bottom: 20px;
            display: flex;
            gap: 15px;
            align-items: center;
            flex-wrap: wrap;
        }

        .filter-group {
            display: flex;
            flex-direction: column;
            gap: 5px;
        }

        .filter-group label {
            font-size: 0.9em;
            color: #666;
            font-weight: 600;
        }

        .filter-group select,
        .filter-group input {
            padding: 8px 12px;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 0.9em;
        }

        .transactions-table {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .table-header {
            background: #ff6500;
            color: white;
            padding: 15px 20px;
            font-weight: 600;
        }

        .table-row {
            display: grid;
            grid-template-columns: 1fr 2fr 1fr 1fr 1fr;
            gap: 20px;
            padding: 15px 20px;
            border-bottom: 1px solid #f0f0f0;
            align-items: center;
        }

        .table-row:hover {
            background: #f9f9f9;
        }

        .transaction-type-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.8em;
            font-weight: 600;
            text-transform: uppercase;
        }

        .type-credit {
            background: #d4edda;
            color: #155724;
        }

        .type-debit {
            background: #f8d7da;
            color: #721c24;
        }

        .amount-credit {
            color: #28a745;
            font-weight: bold;
        }

        .amount-debit {
            color: #dc3545;
            font-weight: bold;
        }

        .no-transactions {
            text-align: center;
            padding: 60px 20px;
            color: #666;
        }

        .no-transactions-icon {
            font-size: 4em;
            margin-bottom: 20px;
            opacity: 0.5;
        }

        .pagination {
            display: flex;
            justify-content: center;
            gap: 10px;
            margin-top: 30px;
        }

        .pagination button {
            padding: 8px 16px;
            border: 1px solid #ddd;
            background: white;
            border-radius: 6px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .pagination button:hover {
            background: #ff6500;
            color: white;
            border-color: #ff6500;
        }

        .pagination button.active {
            background: #ff6500;
            color: white;
            border-color: #ff6500;
        }

        @media (max-width: 768px) {
            .container {
                margin: 10px;
            }
            
            .header {
                padding: 15px 20px;
            }
            
            .content {
                padding: 20px;
            }
            
            .table-row {
                grid-template-columns: 1fr;
                gap: 10px;
                text-align: left;
            }
            
            .filters {
                flex-direction: column;
                align-items: stretch;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div>
                <h1>📊 Mes Transactions</h1>
                <p>Historique complet de vos opérations</p>
            </div>
            <a href="/dashboard" class="back-btn">← Retour au Dashboard</a>
        </div>

        <div class="content">
            <!-- Statistiques -->
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-value"><?= $stats['total_transactions'] ?></div>
                    <div class="stat-label">Total Transactions</div>
                </div>
                <div class="stat-card credit">
                    <div class="stat-value"><?= $stats['total_credit'] ?></div>
                    <div class="stat-label">Total Crédits</div>
                </div>
                <div class="stat-card debit">
                    <div class="stat-value"><?= $stats['total_debit'] ?></div>
                    <div class="stat-label">Total Débits</div>
                </div>
            </div>

            <!-- Filtres -->
            <div class="filters">
                <div class="filter-group">
                    <label>Type de transaction</label>
                    <select id="typeFilter">
                        <option value="">Tous les types</option>
                        <option value="credit">Crédits</option>
                        <option value="debit">Débits</option>
                    </select>
                </div>
                <div class="filter-group">
                    <label>Date de début</label>
                    <input type="date" id="dateStart">
                </div>
                <div class="filter-group">
                    <label>Date de fin</label>
                    <input type="date" id="dateEnd">
                </div>
                <div class="filter-group">
                    <label>&nbsp;</label>
                    <button onclick="applyFilters()" style="padding: 8px 16px; background: #ff6500; color: white; border: none; border-radius: 6px; cursor: pointer;">
                        Filtrer
                    </button>
                </div>
            </div>

            <!-- Tableau des transactions -->
            <div class="transactions-table">
                <div class="table-header">
                    <div class="table-row">
                        <div>Type</div>
                        <div>Description</div>
                        <div>Date</div>
                        <div>Montant</div>
                        <div>Statut</div>
                    </div>
                </div>

                <div id="transactionsContainer">
                    <?php if (empty($transactions)): ?>
                        <div class="no-transactions">
                            <div class="no-transactions-icon">💳</div>
                            <h3>Aucune transaction trouvée</h3>
                            <p>Vous n'avez effectué aucune transaction pour le moment</p>
                        </div>
                    <?php else: ?>
                        <?php foreach ($transactions as $transaction): ?>
                            <div class="table-row transaction-item" 
                                 data-type="<?= $transaction['type'] ?>" 
                                 data-date="<?= $transaction['date_transaction'] ?>">
                                <div>
                                    <span class="transaction-type-badge type-<?= $transaction['type'] ?>">
                                        <?= $transaction['type'] === 'credit' ? 'Crédit' : 'Débit' ?>
                                    </span>
                                </div>
                                <div>
                                    <strong><?= htmlspecialchars($transaction['description']) ?></strong>
                                    <br>
                                    <small style="color: #666;"><?= htmlspecialchars($transaction['type_transaction']) ?></small>
                                </div>
                                <div><?= $transaction['date_formatted'] ?></div>
                                <div class="amount-<?= $transaction['type'] ?>">
                                    <?= $transaction['montant_formatted'] ?>
                                </div>
                                <div>
                                    <span style="color: #28a745; font-weight: 600;">✓ Terminé</span>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Pagination -->
            <div class="pagination" id="pagination">
                <!-- La pagination sera générée par JavaScript -->
            </div>
        </div>
    </div>

    <script>
        let currentPage = 1;
        const itemsPerPage = 10;
        let filteredTransactions = [];

        // Initialiser les transactions filtrées
        document.addEventListener('DOMContentLoaded', function() {
            filteredTransactions = Array.from(document.querySelectorAll('.transaction-item'));
            updatePagination();
            showPage(1);
        });

        function applyFilters() {
            const typeFilter = document.getElementById('typeFilter').value;
            const dateStart = document.getElementById('dateStart').value;
            const dateEnd = document.getElementById('dateEnd').value;
            
            const allTransactions = document.querySelectorAll('.transaction-item');
            filteredTransactions = [];
            
            allTransactions.forEach(transaction => {
                let show = true;
                
                // Filtre par type
                if (typeFilter && transaction.dataset.type !== typeFilter) {
                    show = false;
                }
                
                // Filtre par date
                const transactionDate = new Date(transaction.dataset.date);
                if (dateStart && transactionDate < new Date(dateStart)) {
                    show = false;
                }
                if (dateEnd && transactionDate > new Date(dateEnd + ' 23:59:59')) {
                    show = false;
                }
                
                if (show) {
                    filteredTransactions.push(transaction);
                }
            });
            
            currentPage = 1;
            updatePagination();
            showPage(1);
        }

        function showPage(page) {
            currentPage = page;
            const startIndex = (page - 1) * itemsPerPage;            const endIndex = startIndex + itemsPerPage;
            
            // Cacher toutes les transactions
            document.querySelectorAll('.transaction-item').forEach(item => {
                item.style.display = 'none';
            });
            
            // Afficher les transactions de la page courante
            filteredTransactions.slice(startIndex, endIndex).forEach(item => {
                item.style.display = 'grid';
            });
            
            // Mettre à jour les boutons de pagination
            updatePaginationButtons();
        }

        function updatePagination() {
            const totalPages = Math.ceil(filteredTransactions.length / itemsPerPage);
            const paginationContainer = document.getElementById('pagination');
            
            if (totalPages <= 1) {
                paginationContainer.style.display = 'none';
                return;
            }
            
            paginationContainer.style.display = 'flex';
            paginationContainer.innerHTML = '';
            
            // Bouton précédent
            if (currentPage > 1) {
                const prevBtn = document.createElement('button');
                prevBtn.textContent = '← Précédent';
                prevBtn.onclick = () => showPage(currentPage - 1);
                paginationContainer.appendChild(prevBtn);
            }
            
            // Numéros de page
            for (let i = 1; i <= totalPages; i++) {
                if (i === 1 || i === totalPages || (i >= currentPage - 2 && i <= currentPage + 2)) {
                    const pageBtn = document.createElement('button');
                    pageBtn.textContent = i;
                    pageBtn.onclick = () => showPage(i);
                    if (i === currentPage) {
                        pageBtn.classList.add('active');
                    }
                    paginationContainer.appendChild(pageBtn);
                } else if (i === currentPage - 3 || i === currentPage + 3) {
                    const dots = document.createElement('span');
                    dots.textContent = '...';
                    dots.style.padding = '8px';
                    paginationContainer.appendChild(dots);
                }
            }
            
            // Bouton suivant
            if (currentPage < totalPages) {
                const nextBtn = document.createElement('button');
                nextBtn.textContent = 'Suivant →';
                nextBtn.onclick = () => showPage(currentPage + 1);
                paginationContainer.appendChild(nextBtn);
            }
        }

        function updatePaginationButtons() {
            const buttons = document.querySelectorAll('.pagination button');
            buttons.forEach(btn => btn.classList.remove('active'));
            
            const activeBtn = Array.from(buttons).find(btn => 
                btn.textContent == currentPage
            );
            if (activeBtn) {
                activeBtn.classList.add('active');
            }
        }

        // Fonction pour exporter les transactions
        function exportTransactions() {
            const transactions = filteredTransactions.map(item => {
                const cells = item.querySelectorAll('div');
                return {
                    type: cells[0].textContent.trim(),
                    description: cells[1].textContent.trim(),
                    date: cells[2].textContent.trim(),
                    montant: cells[3].textContent.trim()
                };
            });
            
            console.log('Export des transactions:', transactions);
            // Ici vous pouvez ajouter la logique d'export (CSV, PDF, etc.)
        }
    </script>
</body>
</html>
