<?php

use App\Controller\CompteController;
use App\Controller\SecurityController;
use App\Core\Router;

// Routes publiques (pour les invités uniquement)
Router::get('/', SecurityController::class, 'index');
Router::post('/login', SecurityController::class, 'login');
Router::post('/register', CompteController::class, 'register');

// Routes protégées (nécessitent une authentification)
Router::get('/dashboard', CompteController::class, 'showDashboard', ['auth']);
Router::get('/transactions', CompteController::class, 'showAllTransactions', ['auth']);
Router::get('/logout', CompteController::class, 'logout', ['auth']);

// Résoudre la route
// Router::resolve();
