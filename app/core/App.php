<?php
class App
{
    private $URI;          
    private $method;       
    private $raw_input;
    private $authorize;   
    private $max_upload_chunk=256;

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
        $router->addRoute('GET','/page/login',function(){
                $page_controller=new CPage();
                $page_controller->renderLogin();
        });
        
        $router->addRoute('GET','/page/register',function(){
                $pageController=new CPage();
                $pageController->renderRegister(); 
        });

        $router->addRoute('GET','/page/profile',function(){
            $page_controller=new CPage();
            $page_controller->renderProfile();
        });

        $router->addRoute('GET', '/page/files',function(){
                $page_controller=new CPage();
                $page_controller->renderFiles();
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

        $router->addRoute('DELETE', '/api/user/deauthorize/:service', function($service)
        {
            if($this->authorize->validateAuthorization())
            {
                $profile_controller = new CProfile();
                $user_id = $this->authorize->getDecoded()["user_id"];
                $profile_controller-> deAuth($service, $user_id);
            }
        });


        $router->addRoute('GET','/api/user/authorize/:service', function($service)
        {
            if($this->authorize->validateAuthorization())
            {
                $profile_controller = new CProfile();
                $user_id = $this->authorize->getDecoded()["user_id"];
                $profile_controller-> preAuthorization($service, $user_id);
            }

        });

        $router->addRoute('GET', '/api/user/authorize/:service/:code', function($service,$code)
        {

            $global_array = $GLOBALS['array_of_query_string'];
            if(isset($global_array['code'])){
                $code = $global_array['code'];
                $user_id = $global_array['state'];                        // !!!! trebuie verificat  pt alte servicii
                $profile_controller=new CProfile();
                $profile_controller->authorizeServices($service, $code, $user_id);
                header('Location: http://localhost/ProiectTW/page/profile');
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
        // creeaza un folder in alt folder
        $router->addRoute('POST','/api/items/:parent_id',function($parent_id){
            if($this->authorize->validateAuthorization())
            {
                $items_controller=new CItems();
                $items_controller->createFolderItem($this->authorize->getDecoded()['user_id'],$parent_id);
            }
        });
        // creeaza un folder in root
        $router->addRoute('POST','/api/items/',function(){
            if($this->authorize->validateAuthorization())
            {
                $items_controller=new CItems();
                $items_controller->createFolderItemToRoot($this->authorize->getDecoded()['user_id']);
            }
        });
        // obtine fisiere dintr-un folder
        $router->addRoute('GET','/api/items/:parent_id',function($parent_id){
            if($this->authorize->validateAuthorization())
            {
                $items_controller=new CItems();
                $user_id = $this->authorize->getDecoded()["user_id"];
                $items_controller->getItemsFromFolder($user_id, $parent_id);
            }
        });
        // obtine fisiere din root
        $router->addRoute('GET','/api/items/',function(){
            if($this->authorize->validateAuthorization())
            {
                $items_controller=new CItems();
                $user_id = $this->authorize->getDecoded()["user_id"];
                $items_controller->getItemsFromRoot($user_id);
            }
        });
        // rename pt files sau foldere
        $router->addRoute('PATCH','/api/items/:item_id',function($item_id){
            if($this->authorize->validateAuthorization())
            {
                $items_controller=new CItems();
                $items_controller->updateItem($this->authorize->getDecoded()['user_id'],$item_id);
            }
        });
        // delete file or folder
        $router->addRoute('DELETE','/api/items/:item_id',function($item_id){
            if($this->authorize->validateAuthorization())
            {
                $items_controller=new CItems();
                $items_controller->deleteItem($this->authorize->getDecoded()['user_id'], $item_id);
            }
        });
        // mutare fisier sau folder sub un nou parinte
        $router->addRoute('PUT','/api/items/:item_id/:new_parent_id', function($item_id, $new_parent_id){
            if($this->authorize->validateAuthorization())
            {
                $items_controller=new CItems();
                $items_controller->moveItem($this->authorize->getDecoded()['user_id'], $item_id, $new_parent_id);
            }
        });

        $router->addRoute('POST','/api/upload/:parent_id',function($parent_id){
            if($this->authorize->validateAuthorization())
            {
                $upload_controller=new CUpload();
                $upload_controller->createUpload($this->authorize->getDecoded()['user_id'],$parent_id,$this->max_upload_chunk);
            }
        });
        $router->addRoute('PUT','/api/upload/:upload_id',function($upload_id){
            $upload_controller=new CUpload();
            $upload_controller->uploadFile($upload_id,$this->max_upload_chunk);
        });

        $router->addRoute('DELETE','/api/upload/:upload_file',function($upload_id){
            $upload_controller=new CUpload();
            $upload_controller->deleteUpload($upload_id);
        });
        
        $router->addRoute('GET', '/api/testUpload', function(){
            if($this->authorize->validateAuthorization())
            {
                $upload_controller = new CUpload();
                $user_id = $this->authorize->getDecoded()["user_id"];
                $upload_controller->testFunction($user_id);
            }
        });

        $router->run($this->method, $this->URI);
    }
}

?>