<?php
class App
{
    private $URI;          
    private $method;       
    private $raw_input;
    private $authorize;   

    function __construct($inputs)
    {
        $this->authorize = new AuthorizationHandler();
        $this->URI =$this->checkKey('URI', $inputs);
        $this->method =$this->checkKey('method', $inputs);
    }

    private function checkKey($key, $array){
        return array_key_exists($key, $array) ? $array[$key] : NULL;
    }

    public function run() {

        $router = new Router();
        //$authorize = new AuthorizationHandler();

        $router->addRoute('GET','/page/login',function(){
                $page_controller=new CPage();
                $page_controller->renderLogin();
        });
        
        $router->addRoute('GET','/page/register',function(){
                $pageController=new CPage();
                $pageController->renderRegister(); 
        });

        $router->addRoute('GET','/page/profile',function(){
            if(CSession::isUserAuthorized())
            {
                $page_controller=new CPage();
                $page_controller->renderProfile();
            }
            else
            {
                header('Location:'.'http://localhost/ProiectTW/page/login');
            }
        });

        $router->addRoute('GET', '/page/files',function(){
            if(CSession::isUserAuthorized())
            {
                $page_controller=new CPage();
                $page_controller->renderFiles();
            }
            else
            {
                header('Location:'.'http://localhost/ProiectTW/page/login');
            }
        });

        $router->addRoute('GET', '/api/user', function()
        {
            if($this->authorize->validateAuthorization())
            {
                $profile_controller = new CProfile();
                $user_id = $this->authorize->getDecoded()["user_id"];
                $profile_controller->getUser($user_id);
            }
        });

        $router->addRoute('PATCH','/api/user',function()
        {
            if($this->authorize->validateAuthorization())
            {
                $profile_controller = new CProfile();
                $user_id = $this->authorize->getDecoded()["user_id"];
                $profile_controller->changeUserData($user_id);
            }
        });

        $router->addRoute('GET','/api/user/authorize/:service', function($service)
        {
            if($this->authorize->validateAuthorization())
            {
                $profile_controller = new CProfile();
                $profile_controller->preAuthorization($service);
            }

        });

        $router->addRoute('GET', '/api/user/authorize/:service/:code',function($service,$code)
        {
            // consider ca nu trb verificat jwt tokenul aici deoarece pe calea aceasta se intra de pe unul din servicii dupa redirect, atunci cand intoarce acces token-ul
            //if($this->authorize->validateAuthorization())
            //{
                $global_array = $GLOBALS['array_of_query_string'];
                if(isset($global_array['code'])){
                    $code = $global_array['code'];
                    $profile_controller=new CProfile();
                    $profile_controller->authorizeServices($service, $code);
                }
            //}
        });

        $router->addRoute('GET','/api/jwt',function()
        {
            if($this->authorize->validateAuthorization())
            {
                var_dump($this->authorize->getDecoded());
            }
        });

        $router->addRoute('POST', '/api/user/login', function()
        {
            $login_controller = new CLogin();
            $login_controller->logInUser();
        });

        $router->addRoute('POST', '/api/user/register', function(){
            $register_controller = new CRegister();
            $register_controller->registerUser();
        });

        $router->run($this->method, $this->URI);
    }
}

?>