<?php
require_once('router.php');
spl_autoload_register(function ($class_name) {
    include $class_name . '.php';
});
class App
{
    private $URI;          
    private $method;       
    private $raw_input;     
    
    function __construct($inputs)
    {

        $this->URI =       $this->checkKey('URI', $inputs);
        $this->raw_input =  $this->checkKey('raw_input', $inputs);
        $this->method =    $this->checkKey('method', $inputs);
    }

    private function checkKey($key, $array){
        return array_key_exists($key, $array) ? $array[$key] : NULL;
    }

    public function run() {

        $router = new Router();
        $router->addRoute('GET', '/api/user', function() {
            echo "Nimic";
        });
        $router->addRoute('POST','/api/user',function(){
            echo 'complet alta ruta';
        });

        $router->addRoute('GET', '/info/:day/:month/:year', function($day,$month,$year) {
            echo "Infos for $day/$month/$year";
        });
        $router->run($this->method, $this->URI);
    }
}