<?php

    // le-am numit cu google deoarece e conflict cu cele de la drive
    define('GOOGLE_CLIENT_ID', '570482443729-qqchddo5v01cjvnn5r93du9oh34m1jco.apps.googleusercontent.com');
    define('GOOGLE_CLIENT_SECRET', 'eNo_ecjNkFkxJhzvuQ3QsJIF');
    define('GOOGLE_REDIRECT_URI', 'http://localhost/ProiectTW/public/cprofile/authorizeServiceGoogleDrive/');

    class GoogleDriveService
    {
        public static function authorizationRedirectURL() {

            $endpoint = "https://accounts.google.com/o/oauth2/v2/auth";

            $params = [
                //'prompt' => "consent",
                'scope' => "https://www.googleapis.com/auth/drive",
                'access_type' => "offline",
                'response_type' => "code",
                'redirect_uri' => GOOGLE_REDIRECT_URI,
                'client_id' => GOOGLE_CLIENT_ID
            ];

            $query_string = http_build_query($params);
            $request_permissions_url = $endpoint . '?' . $query_string;

            return $request_permissions_url;
        }

        public static function getAccesRefreshToken($code)
        {

            $array=[
                'code' => $code,
                'client_id' => GOOGLE_CLIENT_ID,
                'client_secret' => GOOGLE_CLIENT_SECRET,
                'redirect_uri' => GOOGLE_REDIRECT_URI,
                'grant_type' => "authorization_code"
            ];

            $post_fields=http_build_query($array);
            //echo "<br><br>".$query_string;
            $curl=curl_init();
            curl_setopt_array($curl, [
                CURLOPT_URL => 'https://oauth2.googleapis.com/token',
                CURLOPT_HTTPHEADER => array('Content-Type: application/x-www-form-urlencoded'),
                CURLOPT_RETURNTRANSFER => 1,
                CURLOPT_SSL_VERIFYPEER => FALSE,
                CURLOPT_POST => 1,
                //CURLOPT_USERAGENT => 'Stol',
                CURLOPT_POSTFIELDS => $post_fields
            ]);

            $response = curl_exec($curl);
            //print_r(json_decode($response,true));
            curl_close($curl);
            return json_decode($response,true);
        }

        public static function removeAccessRefreshToken($token)
        {
            $url = 'https://oauth2.googleapis.com/revoke?token='.$token;
            $ch = curl_init();
            curl_setopt_array($ch, [
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => 1,
                CURLOPT_POST => 1,
                CURLOPT_HTTPHEADER => array('Content-Type: application/x-www-form-urlencoded'),
            ]);
            $result = curl_exec($ch);
            if (curl_errno($ch)) {
                echo 'Error:' . curl_error($ch);
            }

            $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);
            echo 'HTTP code: ' . $httpcode; //200 succes, 40X nein

        }

        public static function getAccessTokenAfterRefresh($refreshToken)
        {
            $array=[
                'client_id' => GOOGLE_CLIENT_ID,
                'client_secret' => GOOGLE_CLIENT_SECRET,
                'grant_type' => "refresh_token",
                'refresh_token' => $refreshToken
            ];

            $post_fields=http_build_query($array);
            //echo "<br><br>".$query_string;
            $curl=curl_init();
            curl_setopt_array($curl, [
                CURLOPT_URL => 'https://oauth2.googleapis.com/token',
                CURLOPT_RETURNTRANSFER => 1,
                CURLOPT_HTTPHEADER => array('Content-Type: application/x-www-form-urlencoded'),
                CURLOPT_SSL_VERIFYPEER => FALSE,
                CURLOPT_POST => 1,
                CURLOPT_POSTFIELDS => $post_fields
            ]);

            $response = curl_exec($curl);
            curl_close($curl);
            $asoc_array = json_decode($response,true);
            //print_r(json_decode($response,true));
            if(array_key_exists("error", $asoc_array)) {
                echo "<br>Error: " . $asoc_array['error'] . " - " .$asoc_array["error_description"] . "<br>";
                return null; // ?
            }
            else {
                $access_token=$decoded_json['access_token'];
                return $access_token;
            }
        }

        public static function listAllFiles($token)
        {
            $ch = curl_init();
            curl_setopt_array($ch, array(
                CURLOPT_URL => "https://www.googleapis.com/drive/v3/files",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "GET",
                CURLOPT_HTTPHEADER => array("authorization: Bearer ${token}"),
            ));
            $result = curl_exec($ch);
            $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);
            echo '<br>HTTP code: ' . $httpcode; //200 succes, 40X nein
            echo '<pre>';
            print_r($result);
            echo '</pre>';
        }
    }

?>