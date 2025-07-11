<?php

namespace App\Service;

use Twilio\Rest\Client;
use Exception;

class SmsService
{
    private Client $twilio;
    private string $fromNumber;

    public function __construct()
    {
        require_once __DIR__ . '/../../app/config/env.php';
        
        $this->twilio = new Client(TWILIO_SID, TWILIO_TOKEN);
        $this->fromNumber = TWILIO_PHONE_NUMBER;
    }

    public function sendWelcomeSms(string $phoneNumber, string $prenom, string $numeroCompte = null): bool
    {
        try {
            $message = "Bienvenue {$prenom} sur MAXITSA ! 🎉\n";
            $message .= "Votre compte a été créé avec succès.\n";
            
            if ($numeroCompte) {
                $message .= "Votre numéro de compte : {$numeroCompte}\n";
            }
            
            $message .= "Merci de nous faire confiance pour vos transferts et paiements.";

            $this->twilio->messages->create(
                $phoneNumber,
                [
                    'from' => $this->fromNumber,
                    'body' => $message
                ]
            );

            return true;
        } catch (Exception $e) {
            error_log("Erreur envoi SMS: " . $e->getMessage());
            return false;
        }
    }
}