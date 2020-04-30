<?php
    require_once('GoogledriveException.php');

    // le-am numit cu google deoarece e conflict cu cele de la onedrive
    define('GOOGLE_CLIENT_ID', '570482443729-qqchddo5v01cjvnn5r93du9oh34m1jco.apps.googleusercontent.com');
    define('GOOGLE_CLIENT_SECRET', 'eNo_ecjNkFkxJhzvuQ3QsJIF');
    define('GOOGLE_REDIRECT_URI', 'http://localhost/ProiectTW/api/user/authorize/googledrive');

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
            $httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
            if($httpcode != 200){
                throw new GoogledriveAuthException(
                    __METHOD__. ' '.__LINE__.' '.$httpcode, json_decode($response, true)['error']);
            }
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
            if(($result = curl_exec($ch)) === false){
                throw new GoogledriveInvalidateAccessTokenException(
                    __METHOD__. ' '.__LINE__ , "Curl error: " . curl_error($ch));
            }
            $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            if($httpcode != 200){
                throw new GoogledriveInvalidateAccessTokenException(
                    __METHOD__. ' '.__LINE__.' '.$httpcode, json_decode($result, true)['error']);
            }
            curl_close($ch);

            return true; // succes
        }

        public static function renewAccessToken($refreshToken)
        {
            $array=[
                'client_id' => GOOGLE_CLIENT_ID,
                'client_secret' => GOOGLE_CLIENT_SECRET,
                'grant_type' => "refresh_token",
                'refresh_token' => $refreshToken
            ];

            $post_fields=http_build_query($array);
            //echo "<br><br>".$query_string;
            $ch=curl_init();
            curl_setopt_array($ch, [
                CURLOPT_URL => 'https://oauth2.googleapis.com/token',
                CURLOPT_RETURNTRANSFER => 1,
                CURLOPT_HTTPHEADER => array('Content-Type: application/x-www-form-urlencoded'),
                CURLOPT_SSL_VERIFYPEER => FALSE,
                CURLOPT_POST => 1,
                CURLOPT_POSTFIELDS => $post_fields
            ]);

            if(($result = curl_exec($ch)) === false){
                throw new GoogledriveRenewAccessTokenException(
                    __METHOD__. ' '.__LINE__ , "Curl error: " . curl_error($ch));
            }
            $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);
            if($httpcode != 200){
                throw new GoogledriveRenewAccessTokenException(
                    __METHOD__. ' '.__LINE__.' '.$httpcode, json_decode($result, true)['error']);
            }

            $asoc_array = json_decode($result, true);
            $access_token=$asoc_array['access_token'];
            return $access_token;

        }

        public static function listAllFiles($token)
        {
            $ch = curl_init();
            curl_setopt_array($ch, array(
                CURLOPT_URL => "https://www.googleapis.com/drive/v3/files?q=%27me%27%20in%20owners%20and%20trashed%20%3D%20false", //fara fisiere shared sau deleted
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "GET",
                CURLOPT_HTTPHEADER => array("authorization: Bearer ${token}"),
            ));
            if(($result = curl_exec($ch)) === false){
                throw new GoogledriveListAllFilesException(
                    __METHOD__. ' '.__LINE__, "Curl error: " . curl_error($ch));
            }
            $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);
            if($httpcode != 200){
                $asoc_array = json_decode($result, true);
                throw new GoogledriveListAllFilesException(
                    __METHOD__. ' '.__LINE__.' '.$httpcode, $asoc_array['error']['message'], $asoc_array['error']['code']);
            }

            echo '<pre>';
            print_r($result);
            echo '</pre>';
        }

        public static function getStorageQuota($token)
        {
            $ch = curl_init();
            curl_setopt_array($ch, array(
                CURLOPT_URL => "https://www.googleapis.com/drive/v3/about?fields=storageQuota",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "GET",
                CURLOPT_HTTPHEADER => array("authorization: Bearer ${token}"),
            ));
            if(($result = curl_exec($ch)) === false){
                throw new GoogledriveStorageQuotaException(
                    __METHOD__. ' '.__LINE__, "Curl error: " . curl_error($ch));
            }
            $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);
            if($httpcode != 200){
                $asoc_array = json_decode($result, true);
                throw new GoogledriveStorageQuotaException(
                    __METHOD__. ' '.__LINE__.' '.$httpcode, $asoc_array['error']['message'], $asoc_array['error']['code']);
            }

            //echo '<pre>'; print_r(json_decode($result, true)); echo '</pre>';
            return json_decode($result, true)['storageQuota']; // limit, usage, usageInDrive, usageInTrash
        }

        public static function getFileMetadataById($token, $file_id)
        {
            // v2 ofera mai multe informatii
            $url = 'https://www.googleapis.com/drive/v2/files/' . $file_id;

            $ch = curl_init();
            curl_setopt_array($ch, array(
                CURLOPT_URL => $url,
                CURLOPT_HEADER => 0,
                CURLOPT_RETURNTRANSFER => 1,
                CURLOPT_BINARYTRANSFER => 1,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_SSL_VERIFYHOST => false,
                CURLOPT_CONNECTTIMEOUT => 20,
                CURLOPT_HTTPHEADER => array("Authorization: Bearer ${token}")
            ));
            if(($json_metadata = curl_exec($ch)) === false){
                throw new GoogledriveGetFileMetadataException(
                    __METHOD__. ' '.__LINE__ , "Curl error: " . curl_error($ch));
            }
            $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);
            if($httpcode != 200){
                $asoc_array = json_decode($json_metadata, true);
                throw new GoogledriveGetFileMetadataException(
                    __METHOD__. ' '.__LINE__.' '.$httpcode, $asoc_array['error']['message'], $asoc_array['error']['code']);
            }

            $metadata = json_decode($json_metadata, true);

            // echo '<pre>';
            // print_r($metadata);
            // echo '</pre>';
            return $metadata;
        }

        public static function downloadFileById($token, $file_id) {

            $url = 'https://www.googleapis.com/drive/v2/files/' . $file_id .'?alt=media';

            $ch = curl_init();
            curl_setopt_array($ch, array(
                CURLOPT_URL => $url,
                CURLOPT_HEADER => 0,
                CURLOPT_RETURNTRANSFER => 1,
                CURLOPT_BINARYTRANSFER => 1,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_SSL_VERIFYHOST => false,
                CURLOPT_CONNECTTIMEOUT => 20,
                CURLOPT_HTTPHEADER => array("Authorization: Bearer ${token}")
            ));

            if(($data = curl_exec($ch)) === false){
                throw new GoogledriveDownloadFileByIdException(
                    __METHOD__. ' '.__LINE__.' '."Curl error: " . curl_error($ch));
            }
            $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);
            if($httpcode != 200){
                $asoc_array = json_decode($data, true);
                throw new GoogledriveDownloadFileByIdException(
                    __METHOD__. ' '.__LINE__.' '.$httpcode, $asoc_array['error']['message'], $asoc_array['error']['code']);
            }

            $metadate = self::getFileMetadataById($token, $file_id);
            if($metadate == null){
                throw new GoogledriveDownloadFileByIdException(
                    __METHOD__. ' '.__LINE__ , "Download: nu exista metadate pentru fisierul $file_id");            
            }

            $file = $data;

            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="' . $metadate['title'] . '"');
            header('Content-Length: ' . $metadate['fileSize']);
            ob_clean();
            echo ($file);
            return true; //return ca sa nu mai apara si alte lucruri in fisier
            echo "Am terminat de salvat"; //ar trebui sa nu apara in fisier :)

        }

        public static function uploadFile($token, $path = null) {

            $url = "https://www.googleapis.com/upload/drive/v2/files?uploadType=resumable";

            //$path = 'D:\Downloads\uploadedFile.txt'; //sa nu fie empty..
            $path = 'D:\Downloads\uploadedFile.rar';
            //$path = 'D:\Downloads\uploadedFile.png';
            //$path = 'D:\Downloads\iobituninstaller.exe';
            //$path = 'D:\Downloads\BigFile.txt';


            if(!file_exists($path)) {
                throw new GoogledriveUploadFileException(
                    __METHOD__. ' '.__LINE__ , "Nu exista niciun fisier la $path");
            }

            $filesize = filesize($path);
            $storageQuota = self::getStorageQuota($token);
            if(($storageQuota['usage'] + $filesize)  >  $storageQuota['limit']) {
                throw new GoogledriveNotEnoughStorageSpaceException(
                    __METHOD__. ' '.__LINE__ , "Nu exista suficient spatiu disponibil");
            }

            // 1. upload metadate fisier pt a primi un 'resumable' upload link
            $filename = basename($path);
            $metadata = '{ "title": "' . $filename . '" }';
            $metadatasize = strlen($metadata);

            $ch = curl_init();
            curl_setopt_array($ch, array(
                CURLOPT_URL => $url,
                CURLOPT_SSL_VERIFYPEER => FALSE,
                CURLOPT_RETURNTRANSFER => TRUE, //return the transfer as a string
                CURLOPT_HEADER => TRUE, //enable headers
                CURLOPT_NOBODY => TRUE, //get only headers
                CURLOPT_BINARYTRANSFER => TRUE,
                CURLOPT_POST => TRUE,
                CURLOPT_HTTPHEADER => array(
                    //"X-Upload-Content-Type: application/octet-stream",
                    //"X-Upload-Content-Length: " . $metadatasize,
                    "Content-Type: application/json; charset=UTF-8",
                    "Content-Length: " . $metadatasize,
                    "Authorization: Bearer " . $token
                ),
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_POSTFIELDS => $metadata,
                // ---- https://stackoverflow.com/a/41135574
                CURLOPT_HEADERFUNCTION =>
                    function($curl, $header) use (&$headers) {
                        $len = strlen($header);
                        $header = explode(':', $header, 2);
                        if (count($header) < 2) // ignore invalid headers
                          return $len;

                        $headers[strtolower(trim($header[0]))][] = trim($header[1]);

                        return $len;
                    }
                // ----
            ));
            if(($response = curl_exec($ch)) === false){
                throw new GoogledriveUploadFileException(
                    __METHOD__. ' '.__LINE__ , "Curl error: " . curl_error($ch));
            }
            $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            if($httpcode != 200){
                // separare body de headere
                $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
                $header = substr($response, 0, $header_size);
                $body = substr($response, $header_size);
                $body = json_decode($body, true);
                //echo '<pre>'; print_r($body); echo '</pre>';
                throw new GoogledriveUploadFileException(
                    __METHOD__. ' '.__LINE__.' '.$httpcode, $body['error']['message'], $body['error']['code']);
            }
            curl_close($ch);

            // echo '<pre>';
            // print_r($headers);
            // echo '</pre>';

            if(!array_key_exists('location', $headers)){
                throw new GoogledriveUploadFileException(
                    __METHOD__. ' '.__LINE__, 'No "Location" header with upload link received !!!');
            }
            else
                $resumable_url = $headers['location'][0];

            // 2. upload fisier folosind link-ul primit

            $filesize = filesize($path);
            echo "FILESIZE: $filesize <br>";
            $unit = 256 * 1024 * 8; // unitati de cate 1024KB

            $total_uploaded = 0;
            $offset = 0;
            $i = 0;
            while($offset != $filesize) {
                
                $chunk_size = ($offset + $unit) <= $filesize ? $unit : ($filesize - $offset );
                $file = file_get_contents($path, false, null, $offset, $chunk_size);

                echo "Incarc intervalul $offset -- " . ($offset + $chunk_size - 1) ."/$filesize";
                $ch2 = curl_init();
                curl_setopt_array($ch2, array(
                    CURLOPT_URL => $resumable_url,
                    CURLOPT_SSL_VERIFYPEER => FALSE,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_RETURNTRANSFER => TRUE,
                    CURLOPT_BINARYTRANSFER => TRUE,
                    CURLOPT_CUSTOMREQUEST => "PUT",
                    CURLOPT_HTTPHEADER => array(
                        "Content-Type: application/octet-stream",
                        "Content-Length: " . $chunk_size,
                        "Content-Range: bytes " . $offset."-".($offset + $chunk_size - 1)."/".$filesize,
                        "Authorization: Bearer " . $token
                    ),
                    //CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_POSTFIELDS => $file,
                    CURLOPT_HEADERFUNCTION =>
                    function($curl, $header) use (&$headers) {
                        $len = strlen($header);
                        $header = explode(':', $header, 2);
                        if (count($header) < 2) // ignore invalid headers
                          return $len;

                        $headers[strtolower(trim($header[0]))][] = trim($header[1]);

                        return $len;
                    }
                ));
                if(($response = curl_exec($ch2)) === false){
                    throw new GoogledriveUploadFileException(
                        __METHOD__. ' '.__LINE__, "Curl error: " . curl_error($ch2));
                }
                $httpcode = curl_getinfo($ch2, CURLINFO_HTTP_CODE);
                curl_close($ch2);


                if($httpcode == 200 || $httpcode == 201) {
                    echo "<br> UPLOADED: " . ($offset+$chunk_size) ." - " . (($offset+$chunk_size)/$filesize * 100) . "%<br>";
                    echo 'Succes';
                    return true;
                }
                else if($httpcode == 308) {
                    $ranges = $headers['range'];
                    $last_range = end($ranges);     //ultimul elem din array
                    $last_range = substr_replace($last_range, '', 0, 6); //elimin 'bytes='
                    //echo explode("-", $last_range)[1];
                    $offset = explode("-", $last_range)[1]; // selectez al doilea nr din "nr1-nr2"
                    $offset ++; // citim incepand cu urmatorul octet
                }
                else if($httpcode != 308){
                    $asoc_array = json_decode($response, true);
                    throw new GoogledriveUploadFileException(
                        __METHOD__. ' '.__LINE__.' '.$httpcode, $asoc_array['error']['message'], $asoc_array['error']['code']);
                }

                echo "<br> UPLOADED: $offset - " . ($offset/$filesize * 100) . "%<br>";
            }

        }

    }
?>