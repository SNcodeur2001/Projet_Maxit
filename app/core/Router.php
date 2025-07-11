<?php

namespace App\Core;

class Router
{
    private static array $routes = [];
    private static bool $routesLoaded = false;

    public static function get(string $uri, string $controller, string $action, array $middlewares = []): void
    {
        self::$routes['GET'][$uri] = [
            'controller' => $controller,
            'action' => $action,
            'middlewares' => $middlewares
        ];
    }

    public static function post(string $uri, string $controller, string $action, array $middlewares = []): void
    {
        self::$routes['POST'][$uri] = [
            'controller' => $controller,
            'action' => $action,
            'middlewares' => $middlewares
        ];
    }

    public static function resolve(): void
    {
        // Charger les routes si pas encore fait
        if (!self::$routesLoaded) {
            self::loadRoutes();
        }

        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $method = $_SERVER['REQUEST_METHOD'];

        if (isset(self::$routes[$method][$uri])) {
            $route = self::$routes[$method][$uri];

            // Inclure le fichier middleware.php pour accéder à runMiddlewares()
            // require_once __DIR__ . '/middleware.php';

            // Exécuter les middlewares si définis
            // if (!empty($route['middlewares'])) {
            //     runMiddlewares($route['middlewares']);
            // }

            $controllerName = $route['controller'];
            $action = $route['action'];

            $controller = new $controllerName();
            $controller->$action();
        } else {
            // 404 - Route non trouvée
            http_response_code(404);
            require_once '../templates/404.php';
        }
    }

    private static function loadRoutes(): void
    {
        require_once __DIR__ . '/../../routes/route.web.php';
        self::$routesLoaded = true;
    }
}