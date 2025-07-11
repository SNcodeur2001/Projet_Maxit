<?php

namespace App\Controller;

use App\Core\Abstract\AbstractController;
use App\Core\Validator;
use App\Repository\UserRepository;
use App\Service\SmsService;
 use App\Core\App;



use Exception;

class SecurityController extends AbstractController
{
    private UserRepository $userRepository;
    private SmsService $smsService;

    public function __construct()
    {
        parent::__construct();
        $this->userRepository = App::getdependency('userRepository');
        $this->smsService =App::getDependency('smsService');
    }

    public function store()
    {
        // ✨ Règlesles validation mises à jour avec CNI sénégalais
        $rules = [
            'prenom' => 'required|alpha_spaces|max_length:100',
            'nom' => 'required|alpha_spaces|max_length:100',
            'adresse' => 'required',
            'telephone' => 'required|phone_senegal|phone_unique',
            'numero_piece_identite' => 'required|cni_senegal|cni_unique',
            'photo_recto' => 'file_required|file_image|file_max_size:5242880',
            'photo_v_vo' => 'file_required|file_image|file_max_size:5242880',
            'terms' => 'accepted'
        ];

        // ✨ Messages personnalisés pour CNI
        $messages = [
            'telephone.phone_senegal' => 'Le numéro de téléphone doit être au format sénégalais (ex: +221701234567 ou 701234567)',
            'telephone.phone_unique' => 'Ce numéro de téléphone est déjà utilisé par un autre compte',
            'numero_piece_identite.cni_nenegal' => 'Le numéro CNI doit contenir exactement 13 chiffres (ex: 1234567890123)',
            'numero_piece_identite.cni_unique' => 'Ce numéro de CNI est déjà enregistré dans notre système',
            'terms.accepted' => 'Vous devez accepter les conditions d\'utilisation'
        ];

        // Validation en une seule ligne !
        $errors = Validator::validate($_POST, $rules, $messages);

        if (!empty($errors)) {
            $this->session->set('errors', $errors);
            $this->session->set('old_data', $_POST);
            header('Location: /');
            exit;
        }

        // Si validation réussie, traiter l'inscription
        try {
            $photoRectoPath = $this->handleFileUpload('photo_recto', 'uploads/pieces_identite/');
            $photoVersoPath = $this->handleFileUpload('photo_verso', 'uploads/pieces_identite/');

            $userData = [
                'prenom' => trim($_POST['prenom']),
                'nom' => trim($_POST['nom']),
                'adresse' => trim($_POST['adresse']),
                'telephone' => trim($_POST['telephone']),
                'numero_piece_identite' => trim($_POST['numero_piece_identite']),
                'photo_recto' => $photoRectoPath,
                'photo_verso' => $photoVersoPath,
                'type' => 'client',
                'created_at' => date('Y-m-d H:i:s')
            ];

            $userId = $this->userRepository->create($userData);

            if ($userId) {
                // ✨ Envoi du SMS de bienvenue
                $smsEnvoye = $this->smsService->sendWelcomeSms(
                    $userData['telephone'], 
                    $userData['prenom']
                );
                
                $successMessage = 'Votre compte a été créé avec succès !';
                if ($smsEnvoye) {
                    $successMessage .= ' Un SMS de confirmation a été envoyé.';
                }
                
                $this->session->set('success', $successMessage);
                header('Location: /');
                exit;
            } else {
                $this->session->set('errors', ['general' => 'Erreur lors de la création du compte.']);
                $this->session->set('old_data', $_POST);
                header('Location: /');
                exit;
            }

        } catch (Exception $e) {
            // ✨ Gestion spécifique des erreurs d'unicité PostgreSQL
            $errorMessage = $e->getMessage();
            $errors = [];
            
            if (strpos($errorMessage, 'utilisateur_piece_unique') !== false || 
                strpos($errorMessage, 'numero_piece_identite') !== false) {
                $errors['numero_piece_identite'] = 'Ce numéro de CNI est déjà utilisé par un autre compte.';
            } elseif (strpos($errorMessage, 'telephone') !== false) {
                $errors['telephone'] = 'Ce numéro de téléphone est déjà utilisé par un autre compte.';
            } else {
                $errors['general'] = 'Erreur lors de la création du compte. Veuillez réessayer.';
            }
            
            $this->session->set('errors', $errors);
            $this->session->set('old_data', $_POST);
            header('Location: /');
            exit;
        }
    }

    public function create()
    {
        // Implementation required by abstract class
    }

    public function destroy()
    {
        // Implementation required by abstract class
    }

    public function show()
    {
        // Implementation required by abstract class
    }

    public function edit()
    {
        // Implementation required by abstract class
    }

    public function index()
    {
        // Si l'utilisateur est déjà connecté, rediriger vers le dashboard
        if ($this->session->has('user')) {
            header('Location: /dashboard');
            exit;
        }

        $this->renderHtml('index.html.php');
    }

    public function login()
    {
        // Validation simple pour la connexion
        $rules = [
            'loginTelephone' => 'required|phone_senegal'
        ];

        $messages = [
            'loginTelephone.required' => 'Veuillez saisir votre numéro de téléphone',
            'loginTelephone.phone_senegal' => 'Le numéro de téléphone doit être au format sénégalais valide'
        ];

        // Validation en une ligne
        $errors = Validator::validate($_POST, $rules, $messages);

        if (!empty($errors)) {
            $this->session->set('errors', $errors);
            header('Location: /');
            exit;
        }

        $telephone = trim($_POST['loginTelephone']);
        $user = $this->userRepository->findByTelephone($telephone);

        if ($user) {
            // Connexion réussie
            $this->session->set('user', [
                'id' => $user['id'],
                'prenom' => $user['prenom'],
                'nom' => $user['nom'],
                'telephone' => $user['telephone'],
                'type' => $user['type'] ?? 'client'
            ]);

            header('Location: /dashboard');
            exit;
        } else {
            $this->session->set('errors', ['loginTelephone' => 'Numéro de téléphone inconnu']);
            header('Location: /');
            exit;
        }
    }

    public function logout()
    {
        $this->session->destroy();
        header('Location: /');
        exit;
    }

    /**
     * Gère l'upload d'un fichier
     */
    private function handleFileUpload(string $fieldName, string $uploadDir): string
    {
        if (!isset($_FILES[$fieldName]) || $_FILES[$fieldName]['error'] !== UPLOAD_ERR_OK) {
            throw new Exception("Erreur lors de l'upload du fichier {$fieldName}");
        }

        // Créer le dossier s'il n'existe pas
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        // Générer un nom unique pour le fichier
        $fileExtension = pathinfo($_FILES[$fieldName]['name'], PATHINFO_EXTENSION);
        $fileName = uniqid() . '_' . time() . '.' . $fileExtension;
        $filePath = $uploadDir . $fileName;

        // Déplacer le fichier
        if (move_uploaded_file($_FILES[$fieldName]['tmp_name'], $filePath)) {
            return $filePath;
        } else {
            throw new Exception("Impossible de sauvegarder le fichier {$fieldName}");
        }
    }
}
