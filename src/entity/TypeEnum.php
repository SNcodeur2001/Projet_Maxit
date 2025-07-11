<?php
namespace App\Entity;

enum TypeEnum: string{
    case DEPOT = 'depot';
    case RETRAIT = 'retrait';
    case PAIEMENT = 'paiement';
}