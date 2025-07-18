<?php
namespace App\Service;

use App\Repository\TransactionRepository;
use App\Core\App;

class TransactionService{
    private TransactionRepository $transactionRepository;

    public function __construct(){
        $this->transactionRepository = App::getDependency("transactionRepository");
    }

    public function showTransactionsByCompte(int $id): array
    {
        return $this->transactionRepository->getAllTransactionsByCompte($id);
    }
}