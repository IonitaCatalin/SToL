<?php

    define('DROPBOX_APP_KEY','yakh16kscg1cb6o');
    define('DROPBOX_APP_SECRET','9guq9i3k5vibx8x');
    define('DROPBOX_REDIRECT_URI','http://localhost/ProiectTW/public/cprofile/authorizeServiceDropbox/');

    class DropboxService
    {
        public static function authorizationRedirectURL()
        {
            $endpoint = "https://www.dropbox.com/oauth2/authorize";
            $params = [
                'response_type' => 'code',
                'client_id' => DROPBOX_APP_KEY,
                'redirect_uri' => DROPBOX_REDIRECT_URI
            ];
            $query_string = http_build_query($params);
            $request_authorization_url = $endpoint . '?' . $query_string;

            return $request_authorization_url;
        }

        public static function getAccesRefreshToken($code)
        {
            $array=[
                'code' => $code,
                'grant_type' => 'authorization_code',
                'client_id' => DROPBOX_APP_KEY,
                'client_secret' => DROPBOX_APP_SECRET,
                'redirect_uri' => DROPBOX_REDIRECT_URI
            ];
            $post_fields = http_build_query($array);

            $curl=curl_init();
            curl_setopt_array($curl, [
                CURLOPT_URL => 'https://api.dropboxapi.com/oauth2/token',
                CURLOPT_HTTPHEADER => array('Content-Type: application/x-www-form-urlencoded'),
                CURLOPT_RETURNTRANSFER => 1,
                CURLOPT_SSL_VERIFYPEER => FALSE,
                CURLOPT_POST => 1,
                CURLOPT_POSTFIELDS => $post_fields
            ]);

            $response = curl_exec($curl);
            //print_r(json_decode($response,true)); //access_token, token_type, uid, account_id, no refresh token :'(
            curl_close($curl);
            return json_decode($response,true);
        }
    }

?>