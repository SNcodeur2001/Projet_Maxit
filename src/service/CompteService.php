<?php 
namespace App\Service;

use App\Repository\CompteRepository;
use App\Core\App;
class CompteService{
    private CompteRepository $compteRepository;
    public function __construct()
    {
        $this->compteRepository = App::getdependency("compteService");
    }

    public function showComptes(){
        return $this->compteRepository->selectAll();
    }
}