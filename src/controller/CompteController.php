<?php

namespace App\Controller;

use App\Core\Abstract\AbstractController;
use App\Repository\UserRepository;
use App\Repository\CompteRepository;
use App\Service\FileUploadService;
use App\Service\SmsService;
use App\Repository\TransactionRepository;
use App\Core\Session;
use App\Core\App;
use App\Service\CompteService;
use Exception;

class CompteController extends AbstractController
{
    private UserRepository $userRepository;
    // private CompteRepository $compteRepository;
    private FileUploadService $fileUploadService;
    private SmsService $smsService;
    private CompteService $compteService;

    public function __construct()
    {
        $this->userRepository = App::getDependency('userRepository');
        // $this->compteRepository = App::getDependency('compteRepository');
        $this->fileUploadService = App::getDependency('fileUploadService');
        $this->smsService = App::getDependency('smsService');
        // $this->compteService = App::getDependency('compteService');
    }

    

public function showAllComptes()
{
    $page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
    $perPage = 5;
    $offset = ($page - 1) * $perPage;

    $compteRepo = \App\Core\App::getDependency('compteRepository');
    $comptes = $compteRepo->getPaginatedComptes($perPage, $offset);
    $totalComptes = $compteRepo->countAllComptes();
    $totalPages = ceil($totalComptes / $perPage);

    $this->renderHtml('dashboardGestionnaire.php', [
        'comptes' => $comptes,
        'totalPages' => $totalPages,
        'currentPage' => $page,
        'user' => $_SESSION['user']
    ]);
}


    /**
     * Affiche la page d'accueil avec les formulaires
     */
    public function index(): void
    {
        session_start();
        
        // Vérifier si l'utilisateur est déjà connecté
        if (isset($_SESSION['user'])) {
            $this->showDashboardClient();
            return;
        }

        $errors = $_SESSION['errors'] ?? [];
        $oldData = $_SESSION['old_data'] ?? [];
        $success = $_SESSION['success'] ?? '';
        unset($_SESSION['errors'], $_SESSION['old_data'], $_SESSION['success']);

        require_once __DIR__ . '/../../templates/index.html.php';
    }

    /**
     * Traite la création d'un nouveau compte
     */
    public function register(): void
    {
        

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /');
            exit;
        }

        // Validation des données
        $errors = $this->validateRegistrationData($_POST, $_FILES);

        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            $_SESSION['old_data'] = $_POST;
            header('Location: /');
            exit;
        }

        // Upload des fichiers
        $uploadResults = $this->handleFileUploads($_FILES);
        
        if (!$uploadResults['success']) {
            $_SESSION['errors'] = [$uploadResults['message']];
            $_SESSION['old_data'] = $_POST;
            header('Location: /');
            exit;
        }

        // Création de l'utilisateur et du compte
        $userData = [
            'nom' => trim($_POST['nom']),
            'prenom' => trim($_POST['prenom']),
            'telephone' => trim($_POST['telephone']),
            'adresse' => trim($_POST['adresse']),
            'numero_piece_identite' => trim($_POST['numero_piece_identite']),
            'photo_recto' => $uploadResults['photo_recto'],
            'photo_verso' => $uploadResults['photo_verso']
        ];

        // ✨ Ajouter ce try-catch
        try {
            $result = $this->userRepository->create($userData);
        } catch (Exception $e) {
            // Supprimer les fichiers uploadés en cas d'erreur
            if (isset($uploadResults['photo_recto'])) {
                $this->fileUploadService->deleteFile($uploadResults['photo_recto']);
            }
            if (isset($uploadResults['photo_verso'])) {
                $this->fileUploadService->deleteFile($uploadResults['photo_verso']);
            }

            // Gestion spécifique des erreurs d'unicité
            $errorMessage = $e->getMessage();
            $errors = [];
            
            if (strpos($errorMessage, 'CNI') !== false) {
                $errors['numero_piece_identite'] = $errorMessage;
            } elseif (strpos($errorMessage, 'téléphone') !== false) {
                $errors['telephone'] = $errorMessage;
            } else {
                $errors['general'] = $errorMessage;
            }
            
            $_SESSION['errors'] = $errors;
            $_SESSION['old_data'] = $_POST;
            header('Location: /');
            exit;
        }

        if ($result['success']) {
            // Connexion automatique après inscription
            $user = $this->userRepository->findById($result['user_id']);
            if ($user) {
                // ✨ Envoi du SMS de bienvenue
                $smsEnvoye = $this->smsService->sendWelcomeSms(
                    $user['telephone'], 
                    $user['prenom'], 
                    $result['numero_compte'] ?? null
                );
                
                $this->loginUser($user);
                
                $successMessage = 'Compte créé avec succès !';
                if (isset($result['numero_compte'])) {
                    $successMessage .= ' Votre numéro de compte est : ' . $result['numero_compte'];
                }
                
                // ✨ Ajouter info SMS
                if ($smsEnvoye) {
                    $successMessage .= ' Un SMS de confirmation a été envoyé.';
                }
                
                $_SESSION['success'] = $successMessage;
                header('Location: /dashboard');
                exit;
            }
        } else {
            // Supprimer les fichiers uploadés en cas d'erreur
            if (isset($uploadResults['photo_recto'])) {
                $this->fileUploadService->deleteFile($uploadResults['photo_recto']);
            }
            if (isset($uploadResults['photo_verso'])) {
                $this->fileUploadService->deleteFile($uploadResults['photo_verso']);
            }

            $_SESSION['errors'] = [$result['message']];
            $_SESSION['old_data'] = $_POST;
            header('Location: /');
            exit;
        }
    }

    /**
     * Traite la connexion d'un utilisateur
     */
    public function login(): void
    {
        session_start();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /');
            exit;
        }

        $telephone = trim($_POST['loginTelephone'] ?? '');

        if (empty($telephone)) {
            $_SESSION['errors'] = ['Le numéro de téléphone est requis'];
            header('Location: /');
            exit;
        }

        // Récupérer l'utilisateur avec ses informations de compte
        $user = $this->userRepository->findByTelephoneWithAccount($telephone);

        if ($user) {
            $this->loginUser($user);
            $_SESSION['success'] = 'Connexion réussie !';
            header('Location: /dashboard');
            exit;
        } else {
            $_SESSION['errors'] = ['Aucun compte trouvé avec ce numéro de téléphone'];
            header('Location: /');
            exit;
        }
    }

    /**
     * Affiche le dashboard de l'utilisateur
     */
public function showDashboardClient()
{
    Session::requireAuth();
    $user = $_SESSION['user'];
    if ($user['profil'] !== 'CLIENT') {
        header('Location: /unauthorized');
        exit;
    }

    $transactionRepo = App::getDependency('transactionRepository');
    $allTransactions = $transactionRepo->selectAll();

    $recentTransactions = [];
    foreach ($allTransactions as $transaction) {
        if ($transaction['compte_id'] == $user['id']) {
            $recentTransactions[] = [
                'type' => in_array($transaction['type'], ['DEPOT', 'TRANSFERT_RECU']) ? 'credit' : 'debit',
                'description' => $this->getTransactionDescription($transaction['type']),
                'date_formatted' => date('d/m/Y à H:i', strtotime($transaction['created_at'])),
                'montant_formatted' => $this->formatMontant($transaction['type'], $transaction['montant'])
            ];
        }
    }

    $recentTransactions = array_slice($recentTransactions, 0, 10);

    require_once dirname(__DIR__, 2) . '/templates/dashboard.php';
}


public function showDashboardGestion()
{
    Session::requireAuth();
    $user = $_SESSION['user'];
    if ($user['profil'] !== 'SERVICE_COMMERCIAL') {
        header('Location: /unauthorized');
        exit;
    }

    $compteRepo = \App\Core\App::getDependency('compteRepository');
    $comptes = $compteRepo->selectAll();

    // Passe $user et $comptes à la vue
    require dirname(__DIR__, 2) . '/templates/dashboardGestionnaire.php';
}


// AJOUTER : Méthodes helper pour le formatage
private function getTransactionDescription($type)
{
    switch ($type) {
        case 'DEPOT':
            return 'Dépôt d\'argent';
        case 'RETRAIT':
            return 'Retrait d\'argent';
        case 'TRANSFERT_ENVOYE':
            return 'Transfert envoyé';
        case 'TRANSFERT_RECU':
            return 'Transfert reçu';
        default:
            return $type;
    }
}

private function formatMontant($type, $montant)
{
    $prefix = in_array($type, ['DEPOT', 'TRANSFERT_RECU']) ? '+' : '-';
    return $prefix . number_format(abs($montant), 0, ',', ' ') . ' FCFA';
}


    /**
     * Déconnecte l'utilisateur
     */
    public function logout(): void
    {
        session_destroy();
        header('Location: /');
        exit;
    }

    /**
     * Gère l'upload des fichiers
     */
    private function handleFileUploads(array $files): array
    {
        $results = ['success' => true];

        // Upload photo recto
        if (isset($files['photo_recto'])) {
            $rectoResult = $this->fileUploadService->uploadImage($files['photo_recto'], 'recto');
            if (!$rectoResult['success']) {
                return ['success' => false, 'message' => 'Photo recto: ' . $rectoResult['message']];
            }
            $results['photo_recto'] = $rectoResult['filename'];
        }

        // Upload photo verso
        if (isset($files['photo_verso'])) {
            $versoResult = $this->fileUploadService->uploadImage($files['photo_verso'], 'verso');
            if (!$versoResult['success']) {
                // Supprimer le fichier recto si verso échoue
                if (isset($results['photo_recto'])) {
                    $this->fileUploadService->deleteFile($results['photo_recto']);
                }
                return ['success' => false, 'message' => 'Photo verso: ' . $versoResult['message']];
            }
            $results['photo_verso'] = $versoResult['filename'];
        }

        return $results;
    }

    /**
     * Connecte un utilisateur en session
     */
    private function loginUser(array $user): void
    {
        $_SESSION['user'] = [
            'id' => $user['id'],
            'nom' => $user['nom'],
            'prenom' => $user['prenom'],
            'telephone' => $user['telephone'],
            'profil' => $user['profil'] ?? 'CLIENT',
            'numero_compte' => $user['numero_compte'] ?? null,
            'solde' => $user['solde'] ?? 0.00,
            'statut_compte' => $user['statut_compte'] ?? 'ACTIF'
        ];
    }

    /**
     * Valide les données d'inscription
     */
    private function validateRegistrationData(array $data, array $files): array
    {
        $errors = [];

        // Validation des champs texte
        if (empty(trim($data['nom'] ?? ''))) {
            $errors[] = 'Le nom est requis';
        } elseif (!preg_match('/^[A-Za-zÀ-ÖØ-öø-ÿ\s\'-]+$/', trim($data['nom']))) {
            $errors[] = 'Le nom ne doit contenir que des lettres';
        }

        if (empty(trim($data['prenom'] ?? ''))) {
            $errors[] = 'Le prénom est requis';
        } elseif (!preg_match('/^[A-Za-zÀ-ÖØ-öø-ÿ\s\'-]+$/', trim($data['prenom']))) {
            $errors[] = 'Le prénom ne doit contenir que des lettres';
        }

        if (empty(trim($data['telephone'] ?? ''))) {
            $errors[] = 'Le numéro de téléphone est requis';
        } elseif (!preg_match('/^(\+221|7[056789])[0-9]{7}$/', trim($data['telephone']))) {
            $errors[] = 'Le numéro de téléphone doit être au format sénégalais';
        }

        if (empty(trim($data['adresse'] ?? ''))) {
            $errors[] = 'L\'adresse est requise';
        }

        if (empty(trim($data['numero_piece_identite'] ?? ''))) {
            $errors[] = 'Le numéro de pièce d\'identité est requis';
        } elseif (!preg_match('/^[A-Za-z0-9]+$/', trim($data['numero_piece_identite']))) {
            $errors[] = 'Le numéro de pièce d\'identité ne doit contenir que des lettres et chiffres';
        }

        // Validation des conditions d'utilisation
        if (empty($data['terms'])) {
            $errors[] = 'Vous devez accepter les conditions d\'utilisation';
        }

        // Validation des fichiers
        if (empty($files['photo_recto']['name'])) {
            $errors[] = 'La photo recto de la pièce d\'identité est requise';
        }

        if (empty($files['photo_verso']['name'])) {
            $errors[] = 'La photo verso de la pièce d\'identité est requise';
        }

        return $errors;
    }

    

  

     public function store(){

     }

     public function create(){

     }


     public function destroy(){

     }

     public function show(){

     }

     public function edit(){

     }

     public function showAddSecondaryAccount()
{
    Session::requireAuth();
    $user = $_SESSION['user'];
    if ($user['profil'] !== 'CLIENT') {
        header('Location: /unauthorized');
        exit;
    }
    require_once dirname(__DIR__, 2) . '/templates/addSecondaryAccount.php';
}

public function handleAddSecondaryAccount()
{
    Session::requireAuth();
    $user = $_SESSION['user'];
    if ($user['profil'] !== 'CLIENT') {
        header('Location: /unauthorized');
        exit;
    }

    $telephone = trim($_POST['telephone'] ?? '');
    $errors = [];

    // Validation du téléphone
    if (empty($telephone)) {
        $errors[] = "Le numéro de téléphone est requis";
    } elseif (!preg_match('/^(\+221|7[056789])[0-9]{7}$/', $telephone)) {
        $errors[] = "Le numéro de téléphone doit être au format sénégalais";
    }

    if (!empty($errors)) {
        $_SESSION['errors'] = $errors;
        header('Location: /ajouter-compte-secondaire');
        exit;
    }

    $compteRepo = \App\Core\App::getDependency('compteRepository');
    $result = $compteRepo->createCompteSecondaire($user['id'], $telephone);

    if ($result['success']) {
        $_SESSION['success'] = 'Compte secondaire créé avec succès !';
    } else {
        $_SESSION['errors'] = [$result['message'] ?? 'Erreur lors de la création du compte secondaire'];
    }
    header('Location: /dashboard-client');
    exit;
}


public function switchAccount()
{
    Session::requireAuth();
    $user = $_SESSION['user'];
    $compteId = $_POST['compte_id'] ?? null;
    if (!$compteId) {
        header('Location: /dashboard-client');
        exit;
    }
    $compteRepo = \App\Core\App::getDependency('compteRepository');
    $compte = $compteRepo->findById($compteId);
    if ($compte && $compte['utilisateur_id'] == $user['id']) {
        $_SESSION['compte_actif'] = $compte;
    }
    header('Location: /dashboard-client');
    exit;
}
// Affiche le formulaire de recherche
public function searchForm()
{
    Session::requireAuth();
    $user = $_SESSION['user'];
    if ($user['profil'] !== 'SERVICE_COMMERCIAL') {
        header('Location: /unauthorized');
        exit;
    }
    require_once dirname(__DIR__, 2) . '/templates/rechercheCompte.php';
}

// Traite la recherche et affiche le solde + 10 dernières transactions
public function handleSearch()
{
    Session::requireAuth();
    $user = $_SESSION['user'];
    if ($user['profil'] !== 'SERVICE_COMMERCIAL') {
        header('Location: /unauthorized');
        exit;
    }

    $numero = trim($_POST['numero_compte'] ?? '');
    $compteRepo = \App\Core\App::getDependency('compteRepository');
    $transactionRepo = \App\Core\App::getDependency('transactionRepository');
    $compte = $compteRepo->findByNumeroCompte($numero);

    if (!$compte) {
        $_SESSION['errors'] = ['Aucun compte trouvé avec ce numéro'];
        header('Location: /recherche-compte');
        exit;
    }

    $transactions = $transactionRepo->getRecentTransactionsByCompte($compte['id'], 10);

    require_once dirname(__DIR__, 2) . '/templates/detailCompte.php';
}
public function showTransactions($id)
{
    // Session::requireAuth();
    $user = $_SESSION['user'];
    if ($user['profil'] !== 'SERVICE_COMMERCIAL') {
        header('Location: /unauthorized');
        exit;
    }
    $type = $_GET['type'] ?? null;
    $dateStart = $_GET['dateStart'] ?? null;
    $dateEnd = $_GET['dateEnd'] ?? null;
    $compteRepo = \App\Core\App::getDependency('compteRepository');
    $transactionRepo = \App\Core\App::getDependency('transactionRepository');
    $compte = $compteRepo->findById($id);
    $transactions = $transactionRepo->getAllTransactionsByCompte($id, $type, $dateStart, $dateEnd);
    require_once dirname(__DIR__, 2) . '/templates/transactionsCompte.php';
}
public function showCompteDetail($id)
{
    Session::requireAuth();
    $user = $_SESSION['user'];
    if ($user['profil'] !== 'SERVICE_COMMERCIAL') {
        header('Location: /unauthorized');
        exit;
    }
    $compteRepo = \App\Core\App::getDependency('compteRepository');
    $transactionRepo = \App\Core\App::getDependency('transactionRepository');
    $compte = $compteRepo->findById($id);
    $transactions = $transactionRepo->getRecentTransactionsByCompte($id, 10);
    require_once dirname(__DIR__, 2) . '/templates/detailCompte.php';
}
}