<?php

namespace App\Core;

use App\Core\Router;
use App\Core\Abstract\Database;
use App\Core\Session;
use App\Core\Validator;
use App\Service\UserService;
use App\Service\EmailService;
use App\Service\FileUploadService;
use App\Repository\UserRepository;
use App\Repository\CompteRepository;
use App\Repository\TransactionRepository;
use App\Controller\SecurityController;
use App\Controller\CompteController;

class App
{
    private static array $dependencies = [];

    public static function getDependencies(): array
    {
        if (empty(self::$dependencies)) {
            self::$dependencies = [
                "core" => [
                    "router" => new Router(),
                    "database" => Database::getConnection(),
                    "session" => Session::getInstance(),
                    "validator" => new Validator(),
                ],
                
                "services" => [
                   
                    "fileUploadService" => new FileUploadService(),
                ],
                
                "repositories" => [
                    "userRepository" => new UserRepository(),
                    "compteRepository" => new CompteRepository(),
                ],
                
                "controllers" => [
                    "securityController" => new SecurityController(),
                    "compteController" => new CompteController(),
                ]
            ];
        }
        return self::$dependencies;
    }

    public static function getDependencie(string $key): mixed
    {
        foreach (self::getDependencies() as $category => $dependencies) {
            if (isset($dependencies[$key])) {
                return $dependencies[$key];
            }
        }
        throw new \Exception("La dépendance '$key' est introuvable.");
    }
}