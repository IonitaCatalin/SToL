<?php

    define('CLIENT_ID','53a3e6f5-ab36-41d1-aa38-5ef621f6829d');
    define('CLIENT_SECRET','Tn2mKeXphox6vV=?S_HFuvG6?QGURqC5');
    define('GRANT_TYPE','files.readwrite offline_access');
    define('REDIRECT_URI','http://localhost/ProiectTW/public/cprofile/authorizeServiceOneDrive/');

    require_once('OnedriveException.php');
<<<<<<< HEAD:app/core/Onedrive/Onedrive.php
=======
    require_once('OnedriveAuthException.php');

>>>>>>> eb72603992d1bd687d73c9ea99f6a2eb5b2f1d55:app/core/Onedrive.php
    class OneDriveService
    {
        public static function authorizationRedirectURL()
        {
            return 'https://login.live.com/oauth20_authorize.srf?client_id='.CLIENT_ID.'&scope='.GRANT_TYPE.'&response_type=code&redirect_uri=http://localhost/ProiectTW/public/cprofile/authorizeServiceOneDrive/';
        }

        public static function getAccesRefreshToken($auth_code)
        {
            $opt_array=[
                'client_id' => CLIENT_ID,
                'redirect_uri' => REDIRECT_URI,
                'client_secret'=> CLIENT_SECRET,
                'code' => $auth_code,
                'grant_type' => 'authorization_code' 
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
<<<<<<< HEAD:app/core/Onedrive/Onedrive.php
                $response=curl_exec($curl);
                $array=json_decode($response,true);
                if(isset($array['error']))
                {
                    throw new OnedriveAuthException('The authentication token could not be redeemed!');
                }
                else return $array;
=======

            $response = curl_exec($curl);
            $array = json_decode($response, true);

            if(isset($array['error'])) {
                throw new OnedriveAuthToken('The authentication token could not be redeemed!');
            }
            else 
                return $array;
>>>>>>> eb72603992d1bd687d73c9ea99f6a2eb5b2f1d55:app/core/Onedrive.php
        }
    }

?>