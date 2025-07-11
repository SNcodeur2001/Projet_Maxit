<?php

namespace App\Entity;

enum StatutEnum: string
{
    case Principale = 'principal';
    case Secondaire = 'secondaire';
}
