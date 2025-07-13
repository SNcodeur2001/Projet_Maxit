<?php

namespace App\Controller;

use App\Core\Abstract\AbstractController;
use App\Core\Validator;
use App\Repository\UserRepository;
use App\Service\SmsService;
 use App\Core\App;
use App\Enum\ValidationMessages;



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
        $validationRules = [
            'prenom' => [
                'required' => ValidationMessages::PRENOM_REQUIRED->value,
                'alpha_spaces' => ValidationMessages::PRENOM_ALPHA_SPACES->value,
                'max_length:100' => ValidationMessages::PRENOM_MAX_LENGTH->value
            ],
            'nom' => [
                'required' => ValidationMessages::NOM_REQUIRED->value,
                'alpha_spaces' => ValidationMessages::NOM_ALPHA_SPACES->value,
                'max_length:100' => ValidationMessages::NOM_MAX_LENGTH->value
            ],
            'adresse' => [
                'required' => ValidationMessages::ADRESSE_REQUIRED->value
            ],
            'telephone' => [
                'required' => ValidationMessages::TELEPHONE_REQUIRED->value,
                'phone_senegal' => ValidationMessages::TELEPHONE_FORMAT->value,
                'unique:userRepository,telephone' => ValidationMessages::TELEPHONE_UNIQUE->value
            ],
            'numero_piece_identite' => [
                'required' => ValidationMessages::CNI_REQUIRED->value,
                'cni_senegal' => ValidationMessages::CNI_FORMAT->value,
                'unique:userRepository,numero_piece_identite' => ValidationMessages::CNI_UNIQUE->value
            ],
            'photo_recto' => [
                'file_required' => ValidationMessages::PHOTO_RECTO_REQUIRED->value,
                'file_image' => ValidationMessages::PHOTO_FORMAT->value,
                'file_max_size:5242880' => ValidationMessages::PHOTO_MAX_SIZE->value
            ],
            'photo_verso' => [
                'file_required' => ValidationMessages::PHOTO_VERSO_REQUIRED->value,
                'file_image' => ValidationMessages::PHOTO_FORMAT->value,
                'file_max_size:5242880' => ValidationMessages::PHOTO_MAX_SIZE->value
            ],
            'terms' => [
                'accepted' => ValidationMessages::TERMS_ACCEPTED->value
            ]
        ];

        $errors = Validator::validateWithMessages($_POST, $validationRules);

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
                
                $successMessage = ValidationMessages::ACCOUNT_CREATED->value;
                if ($smsEnvoye) {
                    $successMessage .= ValidationMessages::SMS_SENT->value;
                }
                
                $this->session->set('success', $successMessage);
                header('Location: /');
                exit;
            } else {
                $this->session->set('errors', ['general' => ValidationMessages::ACCOUNT_CREATION_ERROR->value]);
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
                $errors['numero_piece_identite'] = ValidationMessages::CNI_ALREADY_USED->value;
            } elseif (strpos($errorMessage, 'telephone') !== false) {
                $errors['telephone'] = ValidationMessages::TELEPHONE_DB_UNIQUE->value;
            } else {
                $errors['general'] = ValidationMessages::GENERAL_ERROR->value;
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
            'loginTelephone.required' => ValidationMessages::TELEPHONE_LOGIN_REQUIRED->value,
            'loginTelephone.phone_senegal' => ValidationMessages::TELEPHONE_LOGIN_FORMAT->value
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
    $this->session->set('user', [
        'id' => $user['id'],
        'prenom' => $user['prenom'],
        'nom' => $user['nom'],
        'telephone' => $user['telephone'],
        'profil' => $user['profil'] ?? 'client'
    ]);

    // 🎯 Redirection personnalisée
    if ($user['profil'] === 'SERVICE_COMMERCIAL') {
        header('Location: /dashboard-gestionnaire');
    } else {
        header('Location: /dashboard-client');
    }
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

            throw new Exception(ValidationMessages::FILE_UPLOAD_ERROR->value . " {$fieldName}");
        }

        // Créer le dossier s'il n'existe pas
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        // Générer un nom unique pour le fichiera
        $fileExtension = pathinfo($_FILES[$fieldName]['name'], PATHINFO_EXTENSION);
        $fileName = uniqid() . '_' . time() . '.' . $fileExtension;
        $filePath = $uploadDir . $fileName;

        // Déplacer le fichier
        if (move_uploaded_file($_FILES[$fieldName]['tmp_name'], $filePath)) {
            return $filePath;
        } else {

            throw new Exception(ValidationMessages::FILE_SAVE_ERROR->value . " {$fieldName}");
        }
    }
}
