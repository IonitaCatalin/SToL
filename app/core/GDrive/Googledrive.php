<?php

    class GoogleDriveService
    {
        public static function authorizationRedirectURL() {

            $endpoint = "https://accounts.google.com/o/oauth2/v2/auth";
            // $client_id = "570482443729-qqchddo5v01cjvnn5r93du9oh34m1jco.apps.googleusercontent.com";
            // $response_type = "code";
            // $scope = "https://www.googleapis.com/auth/drive.metadata.readonly";
            // $redirect_uri = "http://localhost/ProiectTW/public/cprofile/authorizeServiceGoogleDrive/";
            // $access_type = "offline";
            // $prompt = "consent";

            $params = [
                //'prompt' => "consent",
                'scope' => "https://www.googleapis.com/auth/drive.metadata.readonly",
                '$access_type' => "offline",
                'response_type' => "code",
                'redirect_uri' => "http://localhost/ProiectTW/public/cprofile/authorizeServiceGoogleDrive/",
                'client_id' => "570482443729-qqchddo5v01cjvnn5r93du9oh34m1jco.apps.googleusercontent.com"
            ];

            $query_string = http_build_query($params);
            $request_permissions_url = $endpoint . '?' . $query_string;

            return $request_permissions_url;
        }


        public static function getAccesRefreshToken($code)
        {
            // $code = $code; // :)
            // $client_id = "570482443729-qqchddo5v01cjvnn5r93du9oh34m1jco.apps.googleusercontent.com";
            // $client_secret = "eNo_ecjNkFkxJhzvuQ3QsJIF";
            // $redirect_uri = "http://localhost/ProiectTW/public/cprofile/authorizeServiceGoogleDrive/";
            // $grant_type = "authorization_code";

            $array=[
                'code' => $code,
                'client_id' => "570482443729-qqchddo5v01cjvnn5r93du9oh34m1jco.apps.googleusercontent.com",
                'client_secret' => "eNo_ecjNkFkxJhzvuQ3QsJIF",
                'redirect_uri' => "http://localhost/ProiectTW/public/cprofile/authorizeServiceGoogleDrive/",
                'grant_type' => "authorization_code"
            ];

            $post_fields=http_build_query($array);
            //echo "<br><br>".$query_string;
            $curl=curl_init();
            curl_setopt_array($curl, [
                CURLOPT_URL => 'https://oauth2.googleapis.com/token',
                CURLOPT_HTTPHEADER => array('Content-Type: application/x-www-form-urlencoded'),
                CURLOPT_RETURNTRANSFER => 1,
                CURLOPT_POST => 1,
                //CURLOPT_USERAGENT => 'Stol',
                CURLOPT_POSTFIELDS => $post_fields
            ]);

            $response = curl_exec($curl);
            //print_r(json_decode($response,true));
            //echo "-----getAccess---------";
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
    }

?>