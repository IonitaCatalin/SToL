<?php

    define('CLIENT_ID','53a3e6f5-ab36-41d1-aa38-5ef621f6829d');
    define('TENANT','common');
    define('CLIENT_SECRET','Tn2mKeXphox6vV=?S_HFuvG6?QGURqC5');
    define('SCOPE','openid offline_access Files.ReadWrite.All');
    define('REDIRECT_URI_AUTH','http://localhost/ProiectTW/public/cprofile/authorizeServiceOneDrive/');
    define('REDIRECT_URI_REFRESH_TOKEN','http://localhost/ProiectTW/public/cfiles');

    require_once('OnedriveException.php');
    class OneDriveService
    {
        private $drive_id;
        public static function authorizationRedirectURL()
        {
            return 'https://login.microsoftonline.com/'.TENANT.'/oauth2/v2.0/authorize?client_id='.CLIENT_ID.'&response_type=code&redirect_uri='.REDIRECT_URI_AUTH.'&response_mode=query&scope='.SCOPE;
        }

        public static function getAccesRefreshToken($auth_code)
        {
            $opt_array=[
                'client_id' => CLIENT_ID,
                'scope'=>'offline_access Files.ReadWrite.All',
                'code' => $auth_code,
                'redirect_uri' => REDIRECT_URI_AUTH,
                'grant_type' => 'authorization_code',
                'client_secret'=> CLIENT_SECRET,
            ];
            $query_string=http_build_query($opt_array);
            
            $curl = curl_init();
            curl_setopt_array($curl, [
                CURLOPT_RETURNTRANSFER => 1,
                CURLOPT_URL => 'https://login.microsoftonline.com/'.TENANT.'/oauth2/v2.0/token',
                CURLOPT_USERAGENT => 'Stol',
                CURLOPT_POST => 1,
                CURLOPT_HTTPHEADER => array('Content-Type: application/x-www-form-urlencoded'),
                CURLOPT_POSTFIELDS => $query_string
            ]);
            $response=curl_exec($curl);
            $array=json_decode($response,true);
            if(isset($array['error']))
            {
                throw new OnedriveRedeemTokenException('The authentication token could not be redeemed!');
            }
            else return $array;
        }
        public static function renewTokens($refresh_token)
        {
            $opt_array=[
                'client_id' => CLIENT_ID,
                'scope'=>'offline_access Files.ReadWrite.All',
                'refresh_token'=>$refresh_token,
                'redirect_uri '=>REDIRECT_URI_REFRESH_TOKEN,
                'grant_type' => 'refresh_token',
                'client_secret'=>CLIENT_SECRET
            ];
            $query_string=http_build_query($opt_array);
            $curl = curl_init();

            curl_setopt_array($curl, [
                CURLOPT_RETURNTRANSFER => 1,
                CURLOPT_URL => 'https://login.live.com/oauth20_token.srf',
                CURLOPT_USERAGENT => 'Stol',
                CURLOPT_POST => 1,
                CURLOPT_HTTPHEADER => array('Content-Type: application/x-www-form-urlencoded'),
                CURLOPT_POSTFIELDS => $query_string
            ]);
            $response=curl_exec($curl);
            $tokens_array=json_decode($response,true);
            if(isset($tokens_array['error']))
            {
                throw new OnedriveRedeemTokenException('The access token could not be renowed!');
            }
            else return $tokens_array;

        }
        public static function signOutRedirectURL()
        {
            return 'https://login.microsoftonline.com/common/oauth2/v2.0/logout?post_logout_redirect_uri=https://localhost/ProiectTW/public/cprofile/';
        }
        public function getDriveMetadata($access_token)
        {
            echo $access_token;
            echo '<br>';
            $curl=curl_init();
            curl_setopt_array($curl,[
                CURLOPT_RETURNTRANSFER=>1,
                CURLOPT_URL=>'https://graph.microsoft.com/v1.0/me/drive',
                CURLOPT_USERAGENT=>'Stol',
                CURLOPT_HTTPHEADER=>array("Authorization: Bearer ${access_token}"),
                CURLOPT_SSL_VERIFYPEER=>false
            ]);
            $response=curl_exec($curl);
            $metadata_array=json_decode($response,true);
            return $metadata_array;
        }
    }
    
?>