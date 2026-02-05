<?php
namespace Bramus\Router;

class Router
{
    private $routes = array();
    private $notFound;

    public function get($pattern, $handler)
    {
        $this->map('GET', $pattern, $handler);
    }

    public function post($pattern, $handler)
    {
        $this->map('POST', $pattern, $handler);
    }

    public function set404($handler)
    {
        $this->notFound = $handler;
    }

    private function map($method, $pattern, $handler)
    {
        $this->routes[] = array($method, $pattern, $handler);
    }

    public function run()
    {
        $method = $_SERVER['REQUEST_METHOD'];
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        foreach ($this->routes as $route) {
            list($routeMethod, $pattern, $handler) = $route;
            if ($routeMethod !== $method) {
                continue;
            }
            $patternRegex = '@^' . preg_replace('/\{([^}]+)\}/', '([^/]+)', $pattern) . '$@';
            if (preg_match($patternRegex, $uri, $matches)) {
                array_shift($matches);
                if (is_callable($handler)) {
                    call_user_func_array($handler, $matches);
                } elseif (is_array($handler)) {
                    $class = new $handler[0]();
                    call_user_func_array(array($class, $handler[1]), $matches);
                }
                return;
            }
        }
        if ($this->notFound) {
            call_user_func($this->notFound);
        } else {
            http_response_code(404);
            echo '404';
        }
    }
}
