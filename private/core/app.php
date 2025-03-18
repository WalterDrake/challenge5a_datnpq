<?php 

class App {
    protected $controller = 'home';
    protected $method = 'index';
    protected $params = array();

    public function __construct()
    {
        echo "<pre>";
        print_r($this->parseUrl());
    }

    private function parseUrl()
    {
        return explode('/', filter_var(rtrim($_GET['url'], '/'), FILTER_SANITIZE_URL));
    }
}