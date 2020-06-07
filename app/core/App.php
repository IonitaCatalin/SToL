<?php
class App
{
    private $URI;          
    private $method;       
    private $raw_input;
    private $authorize;   
    private $max_upload_chunk=10000000;
    private $admin_user_id;

    function __construct($inputs)
    {
        $this->admin_user_id='c4c5df0d7ed360c14262fbc3a0f46fac';
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

        $router->addRoute('GET','/page/admin',function(){
            $page_controller=new CPage();
            $page_controller->renderAdmin();
        });
        
        // info despre profile
        $router->addRoute('GET', '/api/user', function()
        {
            if($this->authorize->validateAuthorization())
            {
                $profile_controller = new CProfile();
                $user_id = $this->authorize->getDecoded()["user_id"];
                $profile_controller->getUser($user_id);
            }
        });
        // info despre storage
        $router->addRoute('GET', '/api/user/storage', function()
        {
            if($this->authorize->validateAuthorization())
            {
                $profile_controller = new CProfile();
                $user_id = $this->authorize->getDecoded()["user_id"];
                $profile_controller->getUserStorageData($user_id);
            }
        });
        // modificare profile user
        $router->addRoute('PATCH','/api/user',function()
        {
            if($this->authorize->validateAuthorization())
            {
                $profile_controller = new CProfile();
                $user_id = $this->authorize->getDecoded()["user_id"];
                $profile_controller->changeUserData($user_id);
            }
        });
        // deautorizarea unui serviciu anume
        $router->addRoute('DELETE', '/api/user/deauthorize/:service', function($service)
        {
            if($this->authorize->validateAuthorization())
            {
                $profile_controller = new CProfile();
                $user_id = $this->authorize->getDecoded()["user_id"];
                $profile_controller-> deAuth($service, $user_id);
            }
        });

        // autorizarea unui serviciu
        $router->addRoute('GET','/api/user/authorize/:service', function($service)
        {
            if($this->authorize->validateAuthorization())
            {
                $profile_controller = new CProfile();
                $user_id = $this->authorize->getDecoded()["user_id"];
                $profile_controller-> preAuthorization($service, $user_id);
            }

        });

        // endpoint pe care este redirectat user-ul de catre serviciu
        $router->addRoute('GET', '/api/user/authorize/:service/:code', function($service,$code)
        {

            $global_array = $GLOBALS['array_of_query_string'];
            if(isset($global_array['code'])){
                $code = $global_array['code'];
                $user_id = $global_array['state'];
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
        $router->addRoute('GET','/api/items/:item_id',function($item_id){
            if($this->authorize->validateAuthorization())
            {
                $items_controller=new CItems();
                $user_id = $this->authorize->getDecoded()["user_id"];
                $items_controller->getItemMetadata($user_id,$item_id);
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
        // crearea unei sesiuni de upload
        $router->addRoute('POST','/api/upload/:parent_id',function($parent_id){
            if($this->authorize->validateAuthorization())
            {
                $upload_controller=new CUpload();
                $upload_controller->createUpload($this->authorize->getDecoded()['user_id'],$parent_id,$this->max_upload_chunk);
            }
        });
        // incarcare fisier pe chunk-uri
        $router->addRoute('PUT','/api/upload/:upload_id',function($upload_id){
            $upload_controller=new CUpload();
            $upload_controller->uploadFile($upload_id,$this->max_upload_chunk);
        });
        // anularea unui upload
        $router->addRoute('DELETE','/api/upload/:upload_id',function($upload_id){
            $upload_controller=new CUpload();
            $upload_controller->deleteUpload($upload_id);
        });
        // test upload
        $router->addRoute('GET', '/api/testUpload', function(){
            if($this->authorize->validateAuthorization())
            {
                $upload_controller = new CUpload();
                $user_id = $this->authorize->getDecoded()["user_id"];
                $upload_controller->testFunction($user_id);
            }
        });
        // test download
        $router->addRoute('GET', '/api/testDownload', function(){
            if($this->authorize->validateAuthorization())
            {
                $download_controller = new CDownload();
                $user_id = $this->authorize->getDecoded()["user_id"];
                $download_controller->testFunction($user_id);
            }
        });
        // un post (fara body ?! i.e ruta get e luata, eventual folosim alta) pentru a obtine un link de download
        $router->addRoute('POST', '/api/download/:file_id', function($file_id){
            if($this->authorize->validateAuthorization())
            {
                $download_controller = new CDownload();
                $user_id = $this->authorize->getDecoded()["user_id"];
                $download_controller->createDownload($user_id, $file_id);
            }
        });
        // download fisier folosind link-ul primit
        $router->addRoute('GET', '/api/download/:download_id', function($download_id){
            $download_controller = new CDownload();
            $download_controller->downloadFile($download_id);
        });
        // cautare in fisiere
        $router->addRoute('GET','/api/search/:search_name',function($search_name){
            if($this->authorize->validateAuthorization())
            {
                $item_controller=new CItems();
                $user_id=$this->authorize->getDecoded()['user_id'];
                $item_controller->searchByName($user_id,$search_name);
            }
        });
        // construieste fisierul csv si returneaza adresa de unde se poate descarca
        $router->addRoute('POST', '/api/admin/download_csv', function(){
            if($this->authorize->validateAuthorization())
            {
                if($this->authorize->getDecoded()['user_id']==$this->admin_user_id)
                {
                    $admin_controller = new CAdmin();
                    $user_id = $this->authorize->getDecoded()["user_id"];
                    $admin_controller->createCSVFileAndDownloadLink($user_id);
                }
                else
                {
                    $json=new JsonResponse(409,null,'Provided authorization token does not belong to an administrator',409);
                    echo $json->response();
                }
            }
        });
        // pentru descarcarea fisierului csv
        $router->addRoute('GET', '/api/admin/download_csv/:download_id', function($download_id)
        {
            $admin_controller = new CAdmin();
            $admin_controller->downloadCSVFile($download_id);
        });
        // pentru obtinerea datelor despre utilizatori
        $router->addRoute('GET', '/api/admin/users', function(){
            if($this->authorize->validateAuthorization())
            {
                if($this->authorize->getDecoded()['user_id']==$this->admin_user_id)
                {
                    $admin_controller = new CAdmin();
                    $user_id = $this->authorize->getDecoded()["user_id"];
                    $admin_controller->getUsersData($user_id);
                }
                else
                {
                    $json=new JsonResponse(409,null,'Provided authorization token does not belong to an administrator',409);
                    echo $json->response();
                }
            }
        });

        $router->addRoute('GET','/api/admin/services',function(){
            if($this->authorize->validateAuthorization())
            {
                if($this->authorize->getDecoded()['user_id']==$this->admin_user_id)
                {
                     $admin_controller = new CAdmin();
                     $admin_controller->getStatusForServices();

                }
                else
                {
                    $json=new JsonResponse(409,null,'Provided authorization token does not belong to an administrator',409);
                    echo $json->response();
                }
            }
        });

        $router->addRoute('POST','/api/admin/services/:service',function($service){
            if($this->authorize->validateAuthorization())
            {
                if($this->authorize->getDecoded()['user_id']==$this->admin_user_id)
                {
                     $admin_controller = new CAdmin();
                     $user_id = $this->authorize->getDecoded()["user_id"];
                     $admin_controller->updateAllowFor($service);
                }
                else
                {
                    $json=new JsonResponse(409,null,'Provided authorization token does not belong to an administrator',409);
                    echo $json->response();
                }
            }
        });

        $router->run($this->method, $this->URI);
    }
}

?>