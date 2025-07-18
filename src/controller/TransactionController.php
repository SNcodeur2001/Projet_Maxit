<?php
namespace App\Controller;
use App\Service\TransactionService;

use App\Core\App;
use App\Core\Session;

class TransactionController{
    private TransactionService $transactionService;
    
    public function __construct()
    {
        $this->transactionService = App::getDependency("transactionService");
    }


    public function showTransactionsByCompte(int $id): array
    {
        $id = Session::get('id');
        return $this->transactionService->showTransactionsByCompte($id);
    }
}