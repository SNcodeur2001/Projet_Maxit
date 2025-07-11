<?php

namespace App\Service;

class FileUploadService
{
    private string $uploadDir;
    private array $allowedTypes;
    private int $maxFileSize;

    public function __construct()
    {
        $this->uploadDir = __DIR__ . '/../../upload/images/';
        $this->allowedTypes = ['image/jpeg', 'image/png', 'image/jpg'];
        $this->maxFileSize = 5 * 1024 * 1024; // 5MB

        // Créer le dossier s'il n'existe pas
        if (!is_dir($this->uploadDir)) {
            mkdir($this->uploadDir, 0755, true);
        }
    }

    /**
     * Upload un fichier image
     */
    public function uploadImage(array $file, string $prefix = ''): array
    {
        try {
            // Vérifications de base
            if (!isset($file['tmp_name']) || empty($file['tmp_name'])) {
                return ['success' => false, 'message' => 'Aucun fichier sélectionné'];
            }

            if ($file['error'] !== UPLOAD_ERR_OK) {
                return ['success' => false, 'message' => 'Erreur lors de l\'upload du fichier'];
            }

            // Vérifier la taille du fichier
            if ($file['size'] > $this->maxFileSize) {
                return ['success' => false, 'message' => 'Le fichier est trop volumineux (max 5MB)'];
            }

            // Vérifier le type MIME
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mimeType = finfo_file($finfo, $file['tmp_name']);
            finfo_close($finfo);

            if (!in_array($mimeType, $this->allowedTypes)) {
                return ['success' => false, 'message' => 'Type de fichier non autorisé. Seuls JPG, JPEG et PNG sont acceptés'];
            }

            // Générer un nom de fichier unique
            $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
            $fileName = $prefix . '_' . uniqid() . '_' . time() . '.' . $extension;
            $filePath = $this->uploadDir . $fileName;

            // Déplacer le fichier
            if (move_uploaded_file($file['tmp_name'], $filePath)) {
                return [
                    'success' => true,
                    'filename' => $fileName,
                    'path' => $filePath
                ];
            } else {
                return ['success' => false, 'message' => 'Erreur lors de la sauvegarde du fichier'];
            }

        } catch (\Exception $e) {
            return ['success' => false, 'message' => 'Erreur système : ' . $e->getMessage()];
        }
    }

    /**
     * Supprime un fichier
     */
    public function deleteFile(string $filename): bool
    {
        $filePath = $this->uploadDir . $filename;
        if (file_exists($filePath)) {
            return unlink($filePath);
        }
        return true;
    }

    /**
     * Valide les fichiers uploadés
     */
    public function validateFiles(array $files): array
    {
        $errors = [];

        foreach ($files as $fieldName => $file) {
            if (empty($file['tmp_name'])) {
                $errors[] = "Le fichier {$fieldName} est requis";
                continue;
            }

            if ($file['error'] !== UPLOAD_ERR_OK) {
                $errors[] = "Erreur lors de l'upload du fichier {$fieldName}";
                continue;
            }

            if ($file['size'] > $this->maxFileSize) {
                $errors[] = "Le fichier {$fieldName} est trop volumineux (max 5MB)";
                continue;
            }

            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mimeType = finfo_file($finfo, $file['tmp_name']);
            finfo_close($finfo);

            if (!in_array($mimeType, $this->allowedTypes)) {
                $errors[] = "Le fichier {$fieldName} doit être une image (JPG, JPEG, PNG)";
            }
        }

        return $errors;
    }
}