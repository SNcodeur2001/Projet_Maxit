<?php

namespace App\Enum;

enum ValidationMessages: string
{
    // Messages de validation des champs utilisateur
    case PRENOM_REQUIRED = 'Le prénom est obligatoire';
    case PRENOM_ALPHA_SPACES = 'Le prénom ne doit contenir que des lettres et espaces';
    case PRENOM_MAX_LENGTH = 'Le prénom ne peut dépasser 100 caractères';
    
    case NOM_REQUIRED = 'Le nom est obligatoire';
    case NOM_ALPHA_SPACES = 'Le nom ne doit contenir que des lettres et espaces';
    case NOM_MAX_LENGTH = 'Le nom ne peut dépasser 100 caractères';
    
    case ADRESSE_REQUIRED = 'L\'adresse est obligatoire';
    
    case TELEPHONE_REQUIRED = 'Le numéro de téléphone est obligatoire';
    case TELEPHONE_FORMAT = 'Le numéro doit être au format sénégalais (ex: +221701234567)';
    case TELEPHONE_UNIQUE = 'Ce numéro de téléphone est déjà utilisé';
    case TELEPHONE_LOGIN_REQUIRED = 'Veuillez saisir votre numéro de téléphone';
    case TELEPHONE_LOGIN_FORMAT = 'Le numéro de téléphone doit être au format sénégalais valide';
    case TELEPHONE_UNKNOWN = 'Numéro de téléphone inconnu';
    
    case CNI_REQUIRED = 'Le numéro de CNI est obligatoire';
    case CNI_FORMAT = 'Le numéro CNI doit contenir exactement 13 chiffres';
    case CNI_UNIQUE = 'Ce numéro de CNI est déjà enregistré';
    case CNI_ALREADY_USED = 'Ce numéro de CNI est déjà utilisé par un autre compte.';
    
    case PHOTO_RECTO_REQUIRED = 'La photo recto est obligatoire';
    case PHOTO_VERSO_REQUIRED = 'La photo verso est obligatoire';
    case PHOTO_FORMAT = 'Le fichier doit être une image';
    case PHOTO_MAX_SIZE = 'La taille maximale est de 5MB';
    
    case TERMS_ACCEPTED = 'Vous devez accepter les conditions d\'utilisation';
    
    // Messages de succès
    case ACCOUNT_CREATED = 'Votre compte a été créé avec succès !';
    case SMS_SENT = ' Un SMS de confirmation a été envoyé.';
    
    // Messages d'erreur généraux
    case ACCOUNT_CREATION_ERROR = 'Erreur lors de la création du compte.';
    case GENERAL_ERROR = 'Erreur lors de la création du compte. Veuillez réessayer.';
    case FILE_UPLOAD_ERROR = 'Erreur lors de l\'upload du fichier';
    case FILE_SAVE_ERROR = 'Impossible de sauvegarder le fichier';
    
    // Messages d'erreur base de données
    case TELEPHONE_DB_UNIQUE = 'Ce numéro de téléphone est déjà utilisé par un autre compte.';
}