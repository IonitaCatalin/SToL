<?php

    Class CLogin extends Controller
    {
        private $model;

        public function __construct()
        {
            $this->model=$this->model('mlogin');
        }
        public function logInUser()
        {
            $content_type = isset($_SERVER['CONTENT_TYPE']) ? $_SERVER['CONTENT_TYPE'] : '';
            if (stripos($content_type, 'application/json') === false) {
                $json=new JsonResponse('error',null,'Only application/json content-type allowed',415);
                echo $json->response();
            }
            $post_data=file_get_contents('php://input');
            $post_array=json_decode($post_data,true);
            if(!is_array($post_array))
            {
                $json=new JsonResponse('error',null,'Malformed request,JSON data object could not be parsed',400);
                echo $json->response();
            }
            if(isset($post_array['username'])==false || isset($post_array['password'])==false)
            {
                    $json=new JsonResponse('error',null,'Malformed request,required fields are missing',400);
                    echo $json->response();
            }
           else
           {
                $user_id=$this->model->logInUser($post_array['username'],$post_array['password']);
                if(!is_null($user_id))
                {
                    session_start();
                    $_SESSION['USER_ID'] = $user_id;
                    
                    $authorize=new AuthorizationHandler();
                    $token=$authorize->generateToken($user_id);
                    //setcookie('access_data', $token, 10800, "/", null);
                    setcookie('jwt_token', $token, time()+60*60*24*365, '/'); 
                    $json=new JsonResponse('success',array('access_token'=>$token),'User succesfully logged in, access token was provided', 200);
                    echo $json->response();
                }
                else
                {
                    $json=new JsonResponse('error',null,'Invalid credentials or user does not exist',401);
                    echo $json->response();
                }
           }
        }
    }
?>