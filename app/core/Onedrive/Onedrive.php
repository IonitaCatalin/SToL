<?php

    define('CLIENT_ID','53a3e6f5-ab36-41d1-aa38-5ef621f6829d');
    define('TENANT','common');
    define('CLIENT_SECRET','Tn2mKeXphox6vV=?S_HFuvG6?QGURqC5');
    define('SCOPE','openid User.Read offline_access Files.ReadWrite.All');
    define('REDIRECT_URI_AUTH','http://localhost/ProiectTW/public/cprofile/authorizeServiceOneDrive/');

    require_once('OnedriveException.php');
    class OneDriveService
    {
        public static function authorizationRedirectURL()
        {
            return 'https://login.microsoftonline.com/'.TENANT.'/oauth2/v2.0/authorize?client_id='.CLIENT_ID.'&response_type=code&redirect_uri='.REDIRECT_URI_AUTH.'&response_mode=query&scope='.SCOPE;
        }

        public static function getAccesRefreshToken($auth_code)
        {
            $opt_array=[
                'client_id' => CLIENT_ID,
                'redirect_uri' => REDIRECT_URI_AUTH,
                'client_secret'=> CLIENT_SECRET,
                'code' => $auth_code,
                'grant_type' => 'authorization_code' 
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
        public function renewTokens($refresh_token)
        {
            $opt_array=[
                'client_id' => CLIENT_ID,
                'scope' => SCOPE,
                'refresh_token'=>$refresh_token,
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
            $array=json_decode($response,true);
            if(isset($array['error']))
            {
                throw new OnedriveRedeemTokenException('The access token could not be renowed!');
            }
            else return $array;

        }
        public static function signOutRedirectURL()
        {
            return 'https://login.microsoftonline.com/common/oauth2/v2.0/logout?post_logout_redirect_uri=https://localhost/ProiectTW/public/cprofile/';
        }
    }
    
?>