<?php

namespace App\Controller;

use App\Core\Session;
use App\Core\App;

class WoyofalController 
{
    public function showWoyofalForm() 
    {
        Session::requireAuth();
        $user = $_SESSION['user'];
        
        if ($user['profil'] !== 'CLIENT') {
            header('Location: /unauthorized');
            exit;
        }
        
        require_once __DIR__ . '/../../templates/woyofal.php';
    }

    public function processWoyofalPayment() 
    {
        Session::requireAuth();
        $user = $_SESSION['user'];
        
        if ($user['profil'] !== 'CLIENT') {
            header('Location: /unauthorized');
            exit;
        }
        
        $compteur = $_POST['numero_compteur'] ?? null;
        $montant = $_POST['montant'] ?? null;
        
        if (!$compteur || !$montant) {
            $_SESSION['errors'] = ['Veuillez remplir tous les champs'];
            header('Location: /woyofal');
            exit;
        }
        
        // Validation du montant
        if (!is_numeric($montant) || $montant <= 0) {
            $_SESSION['errors'] = ['Le montant doit être un nombre positif'];
            header('Location: /woyofal');
            exit;
        }
        
        // ✅ Vérification si compte_id existe dans la session
        if (!isset($user['compte_id']) || empty($user['compte_id'])) {
            $_SESSION['errors'] = ['Erreur de session: compte non trouvé. Veuillez vous reconnecter.'];
            header('Location: /logout'); // Forcer une reconnexion
            exit;
        }
        
        $compteRepo = App::getDependency('compteRepository');
        
        // Vérifier le solde du compte
        $compte = $compteRepo->findById((int)$user['compte_id']);
        
        if (!$compte) {
            $_SESSION['errors'] = ['Compte non trouvé. Veuillez vous reconnecter.'];
            header('Location: /logout');
            exit;
        }
        
        if ($compte['solde'] < $montant) {
            $_SESSION['errors'] = ['Solde insuffisant pour effectuer cet achat'];
            header('Location: /woyofal');
            exit;
        }
        
        // Appel à l'API Woyofal
        $apiUrl = "https://appwoyofal-latest-5nkc.onrender.com/api/woyofal/acheter?numero_compteur=" . urlencode($compteur) . "&montant=" . urlencode($montant);
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $apiUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        if ($response === false) {
            $_SESSION['errors'] = ['Erreur lors de la communication avec le service Woyofal'];
            header('Location: /woyofal');
            exit;
        }
        
        // Décoder la réponse JSON
        $woyofalData = json_decode($response, true);
        
        if ($httpCode !== 200 || !$woyofalData || $woyofalData['statut'] !== 'success') {
            $errorMessage = $woyofalData['message'] ?? 'Erreur lors de l\'achat Woyofal';
            $_SESSION['errors'] = [$errorMessage];
            header('Location: /woyofal');
            exit;
        }
        
        // Sauvegarder la transaction uniquement si l'achat Woyofal a réussi
        $result = $compteRepo->createTransaction(
            (int)$user['compte_id'], 
            'PAIEMENT', 
            (float)$montant, 
            'Achat Woyofal: ' . $compteur
        );
        
        if (!$result['success']) {
            $_SESSION['errors'] = ['Erreur lors de l\'enregistrement de la transaction: ' . $result['message']];
            header('Location: /woyofal');
            exit;
        }
        
        // Mettre à jour le solde dans la session
        $_SESSION['user']['solde'] = $result['nouveau_solde'];
        
        $_SESSION['woyofal_receipt'] = $woyofalData;
        $_SESSION['success'] = 'Achat Woyofal effectué avec succès';
        header('Location: /woyofal/confirmation');
        exit;
    }

    public function showConfirmation() 
    {
        Session::requireAuth();
        require_once __DIR__ . '/../../templates/woyofal-confirmation.php';
    }
}