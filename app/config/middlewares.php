<?php
// config/core/middleware.php

use App\Core\Middlewares\Auth;

$middlewares = [
    'auth'      => Auth::class
];

// function auth()
// {
//     session_start();
//     if (empty($_SESSION['user_logged'])) {
//         header('Location: /login');
//         exit;
//     }
// }

// function isVendeur()
// {
//     session_start();
//     if (($_SESSION['user']['type'] ?? '') !== 'vendeur') {
//         header('Location: /list');
//         exit;
//     }
// }

// function isClient()
// {
//     session_start();
//     if (($_SESSION['user']['type'] ?? '') !== 'client') {
//         header('Location: /list');
//         exit;
//     }
// }

// function runMiddlewares(array $names)
// {
//     global $middlewares;
//     foreach ($names as $name) {
//         if (isset($middlewares[$name])) {
//             call_user_func($middlewares[$name]);
//         }
//     }
// }
