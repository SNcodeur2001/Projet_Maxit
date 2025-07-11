<?php

use App\Controller\CompteController;
use App\Controller\SecurityController;
use App\Core\Router;

// Routes de sécurité (pas de middleware)
Router::get('/', SecurityController::class, 'index');
Router::post('/login', SecurityController::class, 'login');
Router::post('/register', CompteController::class, 'register');

// Routes protégées
Router::get('/dashboard', CompteController::class, 'showDashboard');
Router::get('/transactions', CompteController::class, 'showAllTransactions'); // Optionnel
Router::get('/logout', CompteController::class, 'logout');

// Résoudre la route
Router::resolve();
