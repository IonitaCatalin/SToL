<?php
    class CAuthorization extends Controller
    {
        private $model;
        private $decoded;
        public function __construct()
        {
            $this->model=$this->model('mauthorization');
        }
        public function generateToken($user_id)
        {
            return $this->model->generateJWTToken($user_id);
        }
        public function validateAuthorization()
        {        
            if(isset(getallheaders()['Authorization']))
            {
                $token=getallheaders()['Authorization'];
                
                $array=explode(" ",$token);
                $return_decoded=$this->model->validateJWTToken($array[1]);
                if(is_null($return_decoded))
                {
                    $json=new JsonResponse('error',null,'Invalid access token',401);
                }
                else{
                    $decoded_array=(array)$return_decoded;
                    $this->decoded=(array)$decoded_array['data'];
                    return true;
                }

            }
            else
            {
                $json=new JsonResponse('error',null,"Missing access token");
                echo $json->response();
                return false;
            }
        }
        public function getDecoded()
        {
            return $this->decoded;
        }
        
    }
?>