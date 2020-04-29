<?php
class App
{
    private $URI;          
    private $method;       
    private $raw_input;     

    function __construct($inputs)
    {
        $this->URI =$this->checkKey('URI', $inputs);
        $this->raw_input =$this->checkKey('raw_input', $inputs);
        $this->method =$this->checkKey('method', $inputs);
    }

    private function checkKey($key, $array){
        return array_key_exists($key, $array) ? $array[$key] : NULL;
    }

    public function run() {

        $router = new Router();

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

        $router->addRoute('GET','/page/files',function(){
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

        $router->addRoute('POST','/api/user',function(){
            if(CSession::isUserAuthorized())
            {
                $register_controller=new CRegister();
                $register_controller->registerUser();
            }
            else
            {
                $json=new JsonResponse('error',null,'Unauthorized user',401);
                echo $json->response();
            }
            
        });
        $router->addRoute('GET','/api/user',function(){
            if(CSession::isUserAuthorized())
            {
                $profile_controller=new CProfile();
                $profile_controller->getUser();
            }
            else
            {
                $json=new JsonResponse('error',null,'Unauthorized user',401);
                echo $json->response();
            }
        });
        $router->addRoute('PATCH','/api/user',function(){
            if(CSession::isUserAuthorized())
            {
                $profile_controller=new CProfile();
                $profile_controller->changeUserData();
            }
            else
            {
                $json=new JsonResponse('error',null,'Unauthorized user',401);
                echo $json->response();
            }
        });

        $router->addRoute('GET','/api/user/authorize/:service',function($service){
           
        });

        $router->addRoute('POST','/api/user/login',function(){
            $login_controller = new CLogin();
            $login_controller->logInUser();
        });

        $router->addRoute('POST','/api/user/register', function(){
            $register_controller = new CRegister();
            $register_controller->registerUser();
        });

        $router->run($this->method, $this->URI);
    }
}

?>