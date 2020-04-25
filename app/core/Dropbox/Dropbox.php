<?php

    require_once('DropboxException.php');

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

        public static function listAllFiles($token)
        {
            $post_fields = 
            '{
                "path": "",
                "recursive": true,
                "include_media_info": false,
                "include_deleted": false,
                "include_has_explicit_shared_members": false,
                "include_mounted_folders": true,
                "include_non_downloadable_files": true
            }';

            $ch = curl_init();
            curl_setopt_array($ch, array(
                CURLOPT_URL => "https://api.dropboxapi.com/2/files/list_folder",
                CURLOPT_RETURNTRANSFER => 1,
                CURLOPT_POST => 1,
                CURLOPT_POSTFIELDS => $post_fields,
                CURLOPT_HTTPHEADER => array(
                    "Authorization: Bearer ${token}",
                    "Content-Type: application/json"
                )
            ));
            if(($result = curl_exec($ch)) === false){
                throw new DropboxListAllFilesException(
                    __METHOD__. ' '.__LINE__ , "Curl error: " . curl_error($ch));
            }
            if($httpcode != 200){
                throw new DropboxListAllFilesException(
                    __METHOD__. ' '.__LINE__.' '.$httpcode, $result);
            }
            curl_close($ch);

            // cand e eroare de la token sau path '/', returneaza un simplu string
            if(!($array = json_decode($result, true))) {
                throw new DropboxListAllFilesException(
                    __METHOD__. ' '.__LINE__ , $result);
            }

            $array = json_decode($result, true);
            if(array_key_exists('error_summary', $array)) {
                //echo "<pre>"; print_r($array); echo "</pre>";
                throw new DropboxListAllFilesException( // eroare path
                    __METHOD__. ' '.__LINE__ , $array['error_summary']);
            }

            echo "<pre>";
            print_r($array);
            echo "</pre>";
            
        }

        public static function getFileMetadataById($token, $file_id)
        {

            $post_fields = 
            '{
                "path": "' . $file_id . '",
                "include_media_info": false,
                "include_deleted": false,
                "include_has_explicit_shared_members": false
            }';

            $ch = curl_init();
            curl_setopt_array($ch, array(
                CURLOPT_URL => 'https://api.dropboxapi.com/2/files/get_metadata',
                CURLOPT_RETURNTRANSFER => 1,
                CURLOPT_POST => 1,
                CURLOPT_POSTFIELDS => $post_fields,
                CURLOPT_HTTPHEADER => array(
                    "Authorization: Bearer ${token}",
                    "Content-Type: application/json"
                )
            ));
            if(($json_metadata = curl_exec($ch)) === false){
                throw new DropboxGetFileMetadataException(
                    __METHOD__. ' '.__LINE__ , "Curl error: " . curl_error($ch));
            }
            $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            if($httpcode != 200){
                throw new DropboxGetFileMetadataException(
                    __METHOD__. ' '.__LINE__.' '.$httpcode , $data);
            }
            curl_close($ch);

            // pt unele erori: token invalid, link gresit.. returneaza un simplu string
            if(!($array = json_decode($json_metadata, true))) {
                throw new DropboxGetFileMetadataException(
                    __METHOD__. ' '.__LINE__ , $json_metadata);
            }
            
            // cand fisierul nu exista si altele?
            if(array_key_exists('error_summary', $array)){
                //echo '<pre>'; print_r($array); echo '</pre>';
                throw new DropboxGetFileMetadataException(
                    // path/not_found == nu gaseste id-ul fisierului
                    __METHOD__. ' '.__LINE__ , $array['error_summary']);
            }

            //echo '<pre>'; print_r($array); echo '</pre>';

            return $array;
        }

        public static function downloadFileById($token, $file_id)
        {

            $metadate = null;
            //functia pt metadate trateaza si inexistenta fisierului si token invalid
            try {
                $metadate = self::getFileMetadataById($token, $file_id);
            } catch (Exception $exception) {
                throw new DropboxDownloadFileByIdException(
                    __METHOD__. ' '.__LINE__, $exception->message);
            }
            if($metadate == null){
                throw new DropboxDownloadFileByIdException(
                    __METHOD__. ' '.__LINE__ , "Nu s-au putut obtine metadate pentru $file_id");
            }


            $params = '{ "path": "' . $file_id . '" }' ;

            $ch = curl_init();
            curl_setopt_array($ch, array(
                CURLOPT_URL => 'https://content.dropboxapi.com/2/files/download',
                CURLOPT_RETURNTRANSFER => 1,
                CURLOPT_BINARYTRANSFER => 1,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_SSL_VERIFYHOST => false,
                CURLOPT_HTTPHEADER => array(
                    "Authorization: Bearer ${token}",
                    "Dropbox-API-Arg: $params"
                )
            ));

            if(($data = curl_exec($ch)) === false){
                throw new DropboxDownloadFileByIdException(
                    __METHOD__. ' '.__LINE__ , 'Curl error: ' . curl_error($ch));
            }
            $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            if($httpcode != 200){
                throw new DropboxDownloadFileByIdException(
                    __METHOD__. ' '.__LINE__.' '.$httpcode , $data);
            }
            curl_close($ch);

            $array = json_decode($data, true);
            if(array_key_exists('error_summary', $array)){
                //echo "<pre>"; print_r($array); echo "</pre>";
                throw new DropboxDownloadFileByIdException(
                    __METHOD__. ' '.__LINE__ , $array['error_summary']);
            }

            $file = $data;
            // dialogul de save file
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="' . $metadate['name'] . '"');
            header('Content-Length: ' . $metadate['size']);
            ob_clean();
            echo ($file);
            return true;
            echo "Am terminat de salvat"; // ar trebui sa nu apara :)
        }

    }

?>