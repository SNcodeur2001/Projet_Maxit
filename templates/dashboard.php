<?php
if (!isset($_SESSION['user'])) {
    header('Location: /');
    exit;
}

$user = $_SESSION['user'];
$success = $_SESSION['success'] ?? '';
unset($_SESSION['success']);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - MAXITSA</title>
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
            max-width: 900px;
            margin: 20px auto;
            position: relative;
        }

        .dashboard-header {
            background: #ff6500;
            color: white;
            padding: 20px 40px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .user-avatar {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.2);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5em;
            font-weight: bold;
        }

        .logout-btn {
            background: rgba(255, 255, 255, 0.2);
            border: none;
            color: white;
            padding: 8px 16px;
            border-radius: 6px;
            cursor: pointer;
            transition: background 0.3s ease;
            text-decoration: none;
        }

        .logout-btn:hover {
            background: rgba(255, 255, 255, 0.3);
        }

        .form-container {
            padding: 30px 40px;
        }

        .success-message {
            background-color: #d4edda;
            color: #155724;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            border: 1px solid #c3e6cb;
        }

        .account-info {
            background: linear-gradient(135deg, #f8f9fa, #e9ecef);
            border: 1px solid #dee2e6;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 20px;
        }

        .account-info h3 {
            color: #ff6500;
            margin-bottom: 15px;
            font-size: 1.3em;
        }

        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 15px;
        }

        .info-item {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px solid #e9ecef;
        }

        .info-label {
            font-weight: 600;
            color: #495057;
        }

        .info-value {
            color: #6c757d;
            font-family: monospace;
        }

        .balance-card {
            background: linear-gradient(135deg, #ff6500, #ff8533);
            color: white;
            padding: 30px;
            border-radius: 15px;
            margin-bottom: 30px;
            text-align: center;
            box-shadow: 0 10px 20px rgba(255, 101, 0, 0.3);
        }

        .balance-amount {
            font-size: 3em;
            font-weight: bold;
            margin: 10px 0;
        }

        .balance-label {
            font-size: 1.2em;
            opacity: 0.9;
        }

        .account-number {
            background: rgba(255, 255, 255, 0.2);
            padding: 10px 15px;
            border-radius: 8px;
            margin-top: 15px;
            font-family: monospace;
            font-size: 1.1em;
            letter-spacing: 1px;
        }

        .actions-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-top: 30px;
        }

        .action-card {
            background: white;
            border: 2px solid #f0f0f0;
            border-radius: 12px;
            padding: 25px;
            text-align: center;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .action-card:hover {
            border-color: #ff6b35;
            transform: translateY(-3px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }

        .action-icon {
            font-size: 2.5em;
            color: #ff6b35;
            margin-bottom: 10px;
        }

        .action-title {
            font-size: 1.2em;
            font-weight: 600;
            color: #333;
            margin-bottom: 5px;
        }

        .action-desc {
            color: #666;
            font-size: 0.9em;
        }

        .status-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.8em;
            font-weight: 600;
            text-transform: uppercase;
        }

        .status-actif {
            background-color: #d4edda;
            color: #155724;
        }

        .status-inactif {
            background-color: #f8d7da;
            color: #721c24;
        }

        @media (max-width: 768px) {
            .container {
                margin: 10px;
            }
            
            .dashboard-header {
                padding: 15px 20px;
                flex-direction: column;
                gap: 15px;
            }
            
            .form-container {
                padding: 20px;
            }
            
            .info-grid {
                grid-template-columns: 1fr;
            }
            
            .actions-grid {
                grid-template-columns: 1fr;
            }
        }

        /* AJOUT : Styles pour les transactions */
        .transactions-section {
            background: white;
            border-radius: 15px;
            padding: 25px;
            margin-bottom: 30px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
        }

        .transactions-section h3 {
            color: #ff6500;
            margin-bottom: 20px;
            font-size: 1.3em;
            border-bottom: 2px solid #f0f0f0;
            padding-bottom: 10px;
        }

        .transactions-list {
            max-height: 400px;
            overflow-y: auto;
        }

        .transaction-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 0;
            border-bottom: 1px solid #f5f5f5;
            transition: background-color 0.2s ease;
        }

        .transaction-item:hover {
            background-color: #f9f9f9;
            border-radius: 8px;
            padding-left: 10px;
            padding-right: 10px;
        }

        .transaction-item:last-child {
            border-bottom: none;
        }

        .transaction-type {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .transaction-icon {
            font-size: 1.5em;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .transaction-icon.credit {
            background: rgba(40, 167, 69, 0.1);
        }

        .transaction-icon.debit {
            background: rgba(220, 53, 69, 0.1);
        }

        .transaction-details {
            flex: 1;
        }

        .transaction-title {
            font-weight: 600;
            color: #333;
            margin-bottom: 4px;
        }

        .transaction-date {
            font-size: 0.85em;
            color: #666;
        }

        .transaction-amount {
            font-weight: bold;
            font-size: 1.1em;
            font-family: monospace;
        }

        .transaction-amount.credit {
            color: #28a745;
        }

        .transaction-amount.debit {
            color: #dc3545;
        }

        .no-transactions {
            text-align: center;
            padding: 40px 20px;
            color: #666;
        }

        .no-transactions-icon {
            font-size: 3em;
            margin-bottom: 15px;
            opacity: 0.5;
        }

        .view-all-transactions {
            text-align: center;
            margin-top: 20px;
            padding-top: 15px;
            border-top: 1px solid #f0f0f0;
        }

        .view-all-btn {
            color: #ff6500;
            text-decoration: none;
            font-weight: 600;
            padding: 8px 16px;
            border-radius: 6px;
            transition: all 0.3s ease;
        }

        .view-all-btn:hover {
            background-color: #ff6500;
            color: white;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="dashboard-header">
            <div class="user-info">
                <div class="user-avatar">
                    <?= strtoupper(substr($user['prenom'], 0, 1) . substr($user['nom'], 0, 1)) ?>
                </div>
                <div>
                    <div style="font-weight: 600"><?= htmlspecialchars($user['prenom'] . ' ' . $user['nom']) ?></div>
                    <div style="opacity: 0.8; font-size: 0.9em">
                        Compte <?= ucfirst($user['type']) ?> • <?= htmlspecialchars($user['telephone']) ?>
                    </div>
                </div>
            </div>
            <a href="/logout" class="logout-btn">Déconnexion</a>
        </div>

        <div class="form-container">
            <?php if ($success): ?>
                <div class="success-message">
                    <?= htmlspecialchars($success) ?>
                </div>
            <?php endif; ?>

            <div class="account-info">
                <h3>📋 Informations du compte</h3>
                <div class="info-grid">
                    <div class="info-item">
                        <span class="info-label">Numéro de compte :</span>
                        <span class="info-value"><?= htmlspecialchars($user['numero_compte'] ?? 'Non défini') ?></span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Statut du compte :</span>
                        <span class="status-badge status-<?= strtolower($user['statut_compte'] ?? 'actif') ?>">
                            <?= htmlspecialchars($user['statut_compte'] ?? 'ACTIF') ?>
                        </span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Type de compte :</span>
                        <span class="info-value">COMPTE PRIMAIRE</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Date de création :</span>
                        <span class="info-value"><?= date('d/m/Y') ?></span>
                    </div>
                </div>
            </div>

            <div class="balance-card">
                <div class="balance-label">Solde du compte principal</div>
                <div class="balance-amount"><?= number_format($user['solde'] ?? 0, 0, ',', ' ') ?> FCFA</div>
                <?php if ($user['numero']): ?>
                    <div class="account-number">
                        N° <?= htmlspecialchars($user['numero']) ?>
                    </div>
                <?php endif; ?>
            </div>

            <div class="actions-grid">
                <div class="action-card" onclick="showAction('transfer')">
                    <div class="action-icon">💸</div>
                    <div class="action-title">Effectuer un transfert</div>
                    <div class="action-desc">
                        Envoyer de l'argent rapidement et en sécurité
                    </div>
                </div>

                <div class="action-card" onclick="showAction('payment')">
                    <div class="action-icon">💳</div>
                    <div class="action-title">Paiement</div>
                    <div class="action-desc">Payer vos factures et services</div>
                </div>

                <div class="action-card" onclick="showAction('history')">
                    <div class="action-icon">📊</div>
                    <div class="action-title">Historique</div>
                    <div class="action-desc">Consulter vos transactions</div>
                </div>

                <div class="action-card" onclick="showAction('deposit')">
                    <div class="action-icon">💰</div>
                    <div class="action-title">Dépôt</div>
                    <div class="action-desc">Alimenter votre compte</div>
                </div>
            </div>

            <!-- AJOUT : Section des transactions récentes -->
            <div class="transactions-section">
                <h3>📊 Dernières transactions</h3>
                
                <?php if (empty($recentTransactions)): ?>
                    <div class="no-transactions">
                        <div class="no-transactions-icon">💳</div>
                        <p>Aucune transaction pour le moment</p>
                        <small>Vos futures transactions apparaîtront ici</small>
                    </div>
                <?php else: ?>
                    <div class="transactions-list">
                        <?php foreach ($recentTransactions as $transaction): ?>
                            <div class="transaction-item">
                                <div class="transaction-info">
                                    <div class="transaction-type">
                                        <?php if ($transaction['type'] === 'credit'): ?>
                                            <span class="transaction-icon credit">💰</span>
                                        <?php else: ?>
                                            <span class="transaction-icon debit">💸</span>
                                        <?php endif; ?>
                                        <div class="transaction-details">
                                            <div class="transaction-title">
                                                <?= htmlspecialchars($transaction['description'] ?? 'Transaction') ?>
                                            </div>
                                            <div class="transaction-date">
                                                <?= $transaction['date_formatted'] ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="transaction-amount <?= $transaction['type'] ?>">
                                    <?= $transaction['montant_formatted'] ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    
                    <div class="view-all-transactions">
                        <a href="/transactions" class="view-all-btn">
                            Voir toutes les transactions →
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script>
        function showAction(action) {
            alert('Action "' + action + '" sera implémentée dans les prochaines versions.');
        }
    </script>
</body>
</html>