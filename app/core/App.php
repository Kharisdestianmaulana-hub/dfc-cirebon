<?php
class App {
    protected $controller = 'Home';
    protected $method = 'index';
    protected $params = [];

    public function __construct() {
        $url = $this->parseURL();
        
        // Controller
        if(isset($url[0])) {
            if (file_exists('app/controllers/' . ucfirst($url[0]) . '.php')) {
                $this->controller = ucfirst($url[0]);
                unset($url[0]);
            }
        }
        require_once 'app/controllers/' . $this->controller . '.php';
        $this->controller = new $this->controller;

        // Method
        if (isset($url[1])) {
            $method = $url[1];
            $methodAlias = str_replace('-', '_', $method);

            if (method_exists($this->controller, $method)) {
                $this->method = $method;
                unset($url[1]);
            } elseif (method_exists($this->controller, $methodAlias)) {
                $this->method = $methodAlias;
                unset($url[1]);
            }
        }

        // Params
        if (!empty($url)) {
            $this->params = array_values($url);
        }

        // Call method and pass parameters array
        call_user_func_array([$this->controller, $this->method], $this->params);
    }

    public function parseURL() {
        if (isset($_GET['url'])) {
            $url = rtrim($_GET['url'], '/');
            $url = filter_var($url, FILTER_SANITIZE_URL);
            $url = explode('/', $url);
            return $url;
        }
        return [];
    }
}
