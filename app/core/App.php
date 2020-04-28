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

        $router->addRoute('GET','/page/login/',function(){
            echo 'Login';
        });
        
        $router->addRoute('GET','/page/register',function(){
            echo 'Register';
        });

        $router->addRoute('GET','/page/profile',function(){
            echo 'Profile';
        });

        $router->addRoute('GET','/page/files/',function(){
            session_start();
            echo $_SESSION['USER_ID'];
        });

        $router->addRoute('POST','/api/user/',function(){
            if(CSession::isUserAuthorized())
            {
                
            }
            else
            {
                $json=new JsonResponse('error',null,'Unauthorized user',405);
            }
            
        });
        $router->addRoute('GET','/api/user/',function(){
            if(CSession::isUserAuthorized())
            {
                
            }
            else
            {
                $json=new JsonResponse('error',null,'Unauthorized user',405);
                echo $json->response();
            }
            
        });
        $router->addRoute('PATCH','/api/user',function(){

        });

        $router->addRoute('POST','/api/user/login',function(){
            $loginController=new CLogin();
            $loginController->logInUser();
        });

        $router->run($this->method, $this->URI);
    }
}

?>