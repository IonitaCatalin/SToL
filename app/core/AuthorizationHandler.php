<?php
    use \Firebase\JWT\JWT;

    class AuthorizationHandler
    {
        private $model;
        private $decoded;
        private $key='4ba3a0b8801218417e8324445daf9d82bc0f6cc51a5824bef732f8130920b892a8b83b1c457098e8f8e14f6a3324198e8cf3a5d6516d021ea8fb308f8a2043b9';
        private $iss='Stol_Universal';
        private $aud="Stol_Universal";
        private $iat;
        private $nbf;
        private $exp;

        public function generateToken($user_id)
        {
            $iat=time();
            $nbf=$iat;
            $exp=time()+(3*60*60);

            $token=array(
                "iss"=>$this->iss,
                "aud"=>$this->aud,
                "iat"=>$this->iat,
                "nbf"=>$this->nbf,
                "exp"=>$this->exp,
                "data"=>array(
                    "user_id"=>$user_id
                )
            );
            
            $jwt=JWT::encode($token,$this->key);
            return $jwt;
        }

        public function validateAuthorization()
        {        
            if(isset(getallheaders()['Authorization']))
            {
                $token=getallheaders()['Authorization'];
                $array=explode(" ",$token);
                try{
                    $return_decoded=JWT::decode($array[1],$this->key,array('HS256'));
                    $decoded_array=(array)$return_decoded;
                    $this->decoded=(array)$decoded_array['data'];
                    return true;
                }
                catch(Exception $exception)
                {
                    $json=new JsonResponse('error',null,'Invalid access token',401);
                    echo $json->response();
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