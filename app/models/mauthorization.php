<?php 
    use \Firebase\JWT\JWT;
    class MAuthorization
    {
        private $key='4ba3a0b8801218417e8324445daf9d82bc0f6cc51a5824bef732f8130920b892a8b83b1c457098e8f8e14f6a3324198e8cf3a5d6516d021ea8fb308f8a2043b9';
        private $iss='Stol_Universal';
        private $aud="Stol_Universal";
        private $iat;
        private $nbf;
        private $exp;
        public function __construct()
        {
            $iat=time();
            $nbf=$iat;
            $exp=time()+(3*60*60);
        }
        public function generateJWTToken($user_id)
        {
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
        public function validateJWTToken($token)
        {
            try
            {
                $decoded=JWT::decode($token,$this->key,array('HS256'));
                return $decoded;
            }
            catch(Exception $e)
            {
                echo $e->getMessage();
                return null;
            }
        }
    }
?>