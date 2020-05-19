<?php

require_once('DropboxException.php');

    define('DROPBOX_APP_KEY','yakh16kscg1cb6o');
    define('DROPBOX_APP_SECRET','9guq9i3k5vibx8x');
    define('DROPBOX_REDIRECT_URI','http://localhost/ProiectTW/api/user/authorize/dropbox');

    class DropboxService
    {
        public static function authorizationRedirectURL($user_id)
        {
            $endpoint = "https://www.dropbox.com/oauth2/authorize";
            $params = [
                'response_type' => 'code',
                'client_id' => DROPBOX_APP_KEY,
                'redirect_uri' => DROPBOX_REDIRECT_URI,
                'state' => $user_id
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
            $httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
            if($httpcode != 200){
                throw new DropboxAuthException(
                    __METHOD__. ' '.__LINE__.' '.$httpcode, json_decode($response, true)['error'] . ' ' . json_decode($response, true)['error_description']);
            }

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
            $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
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
                    __METHOD__. ' '.__LINE__.' '.$httpcode , $json_metadata);
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

        public static function getStorageQuota($token)
        {
            $ch = curl_init();
            curl_setopt_array($ch, array(
                CURLOPT_URL => 'https://api.dropboxapi.com/2/users/get_space_usage',
                CURLOPT_RETURNTRANSFER => 1,
                CURLOPT_POST => 1,
                CURLOPT_POSTFIELDS => json_encode(null), // lol :)
                CURLOPT_HTTPHEADER => array(
                    "Authorization: Bearer ${token}",
                    "Content-Type: application/json"
                )
            ));
            if(($response = curl_exec($ch)) === false){
                throw new DropboxStorageQuotaException(
                    __METHOD__. ' '.__LINE__ , "Curl error: " . curl_error($ch));
            }
            $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            if($httpcode != 200){
                throw new DropboxStorageQuotaException(
                    __METHOD__. ' '.__LINE__.' '.$httpcode , $response);
            }
            curl_close($ch);
            //echo '<pre>'; print_r(json_decode($response, true)); echo '</pre>';
            return json_decode($response, true); // used and allocation/allocated
        }

        public static function downloadFileById($token, $file_id, $append_to_path)
        {

            $metadate = null;
            //functia pt metadate trateaza si inexistenta fisierului si token invalid
            try {
                $metadate = self::getFileMetadataById($token, $file_id);
            } catch (Exception $exception) {
                throw new DropboxDownloadFileByIdException(
                    __METHOD__. ' '.__LINE__, $exception->message);
            }

            // // creez fisierul gol la care voi da append
            // $path = $_SERVER['DOCUMENT_ROOT'].'/ProiectTW/downloads/' . $metadate['name'];
            // file_put_contents($path, '');
            $path = $append_to_path;

            $chunk_size = 256 * 1024 * 32; // unitati de cate 8MB
            $offset = 0;
            //echo "File size: " . $metadate["size"] . "<br>";

            while($offset != $metadate["size"])
            {
                $chunk_size = ($offset + $chunk_size) <= $metadate["size"] ? $chunk_size : ($metadate["size"] - $offset);
                //echo "Descarc intervalul $offset - " . ($offset + $chunk_size - 1) . "<br>";

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
                        "Dropbox-API-Arg: $params",
                        "Range:bytes=" . $offset . '-' . ($offset + $chunk_size - 1)
                    )
                ));

                if(($data = curl_exec($ch)) === false){
                    unlink($path);  // sterg fisierul partial in caz de eroare
                    throw new DropboxDownloadFileByIdException(
                        __METHOD__. ' '.__LINE__ , 'Curl error: ' . curl_error($ch));
                }
                $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                curl_close($ch);
                if(($httpcode != 200) && ($httpcode != 206)){
                    unlink($path);  // sterg fisierul partial in caz de eroare
                    $array = json_decode($data, true);
                    //echo "<pre>"; print_r($array); echo "</pre>";
                    throw new DropboxDownloadFileByIdException(
                        __METHOD__. ' '.__LINE__ , $array['error_summary']);
                }

                $file_part = $data;
                file_put_contents($path, $file_part, FILE_APPEND);
                $offset += $chunk_size;
            }

            //echo "Am terminat de descarcat la: $path";
            return $path;
        }

        public static function uploadFile($token, $path, $start_offset, $length)
        {
            if(!file_exists($path)) {
                throw new DropboxUploadFileException(
                    __METHOD__. ' '.__LINE__, "Nu exista niciun fisier la $path");
            }

            $filesize = $length;

            $data = self::getStorageQuota($token);
            if(($data['used'] + $filesize) > $data['allocation']['allocated']){
                throw new DropboxNotEnoughStorageSpaceException(
                    __METHOD__. ' '.__LINE__, "Nu exista suficient spatiu disponibil."
                );
            }

            $unit = 256 * 1024 * 4; // 1 MB
            if($filesize > (3 * $unit)) // macar 3 chunks pt start, append si finish
                return self::uploadLargeFile($token, $path, $unit, $start_offset, $length);
            else
                return self::uploadSmallFile($token, $path, $start_offset, $length);

        }

        public function uploadLargeFile($token, $path, $unit, $start_offset, $filesize) {

            $filename = uniqid("", true);
            //echo "FILESIZE: $filesize <br>";

            $offset = $start_offset;
            $service_file_offset = 0;
            $session_id = null;

            while($service_file_offset != $filesize)
            {

                $chunk_size = ($offset + $unit) <= ($start_offset + $filesize) ? $unit : ($start_offset + $filesize - $offset);
                $file = file_get_contents($path, false, null, $offset, $chunk_size);

                if($service_file_offset == 0)    // start upload session
                {
                    // echo '<pre>';
                    // echo "START: Incarc intervalul $offset - " . ($offset + $chunk_size - 1);
                    // echo '</pre>';

                    $params = 
                    '{' .
                        '"close": false' .
                    '}';

                    $ch = curl_init();
                    curl_setopt_array($ch, array(
                        CURLOPT_URL => 'https://content.dropboxapi.com/2/files/upload_session/start',
                        CURLOPT_RETURNTRANSFER => 1,
                        CURLOPT_BINARYTRANSFER => 1,
                        CURLOPT_FOLLOWLOCATION => true,
                        CURLOPT_SSL_VERIFYPEER => false,
                        CURLOPT_SSL_VERIFYHOST => false,
                        CURLOPT_HTTPHEADER => array(
                            "Authorization: Bearer ${token}",
                            "Dropbox-API-Arg: " . $params,
                            "Content-Type: application/octet-stream"
                        ),
                        CURLOPT_POSTFIELDS => $file
                    ));
                    if(($response = curl_exec($ch)) === false){
                        throw new DropboxUploadFileException(
                            __METHOD__. ' '.__LINE__ , 'Curl error: ' . curl_error($ch));
                    }
                    $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                    if($httpcode != 200){
                        throw new DropboxUploadFileException(
                            __METHOD__. ' '.__LINE__.' '.$httpcode , $response);
                    }
                    curl_close($ch);

                    // pt erorile primite sub forma de string
                    if(!($array = json_decode($response, true))) {
                        throw new DropboxUploadFileException(
                            __METHOD__. ' '.__LINE__ , $response);
                    }

                    $session_id = json_decode($response, true)['session_id'];
                    // echo '<pre>';
                    // echo $session_id;
                    // echo '</pre>';
                    $service_file_offset += $chunk_size;
                    $offset += $chunk_size;
                }
                else    
                {

                    if(($service_file_offset + $chunk_size) < $filesize) { // session append

                        // echo '<pre>';
                        // echo "APPEND: Incarc intervalul $offset - " . ($offset + $chunk_size - 1);
                        // echo '</pre>';

                        $params = 
                        '{' .
                            '"cursor": {' .
                                '"session_id": "' . $session_id . '",' .
                                '"offset": ' . $service_file_offset .
                            '},' .
                            '"close": false' .
                        '}';

                        // echo '<pre>';
                        // echo $params;
                        // echo '</pre>';

                        $ch = curl_init();
                        curl_setopt_array($ch, array(
                            CURLOPT_URL => 'https://content.dropboxapi.com/2/files/upload_session/append_v2',
                            CURLOPT_RETURNTRANSFER => 1,
                            CURLOPT_BINARYTRANSFER => 1,
                            CURLOPT_FOLLOWLOCATION => true,
                            CURLOPT_SSL_VERIFYPEER => false,
                            CURLOPT_SSL_VERIFYHOST => false,
                            CURLOPT_HTTPHEADER => array(
                                "Authorization: Bearer ${token}",
                                "Dropbox-API-Arg: " . $params,
                                "Content-Type: application/octet-stream"
                            ),
                            CURLOPT_POSTFIELDS => $file
                        ));
                        if(($response = curl_exec($ch)) === false){
                            throw new DropboxUploadFileException(
                                __METHOD__. ' '.__LINE__ , 'Curl error: ' . curl_error($ch));
                        }
                        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                        if($httpcode != 200){
                            throw new DropboxUploadFileException(
                                __METHOD__. ' '.__LINE__.' '.$httpcode , $response);
                        }
                        curl_close($ch);
                        $service_file_offset += $chunk_size;
                        $offset += $chunk_size;
                    }
                    else    // session finish
                    {

                        // echo '<pre>';
                        // echo "FINISH: Incarc intervalul $offset - " . ($offset + $chunk_size - 1);
                        // echo '</pre>';

                        $params = 
                        '{' .
                            '"cursor" : {' .
                                '"session_id": "' . $session_id . '",' .
                                '"offset": ' . $service_file_offset .
                            '},' .
                            '"commit": {' .
                                '"path": "/Stol/' . $filename . '",' .
                                '"mode": "add",' .
                                '"autorename": true,' .
                                '"mute": false,' .
                                '"strict_conflict": false' .
                            '} ' .
                        '}';

                        $ch = curl_init();
                        curl_setopt_array($ch, array(
                            CURLOPT_URL => 'https://content.dropboxapi.com/2/files/upload_session/finish',
                            CURLOPT_RETURNTRANSFER => 1,
                            CURLOPT_BINARYTRANSFER => 1,
                            CURLOPT_FOLLOWLOCATION => true,
                            CURLOPT_SSL_VERIFYPEER => false,
                            CURLOPT_SSL_VERIFYHOST => false,
                            CURLOPT_HTTPHEADER => array(
                                "Authorization: Bearer ${token}",
                                "Dropbox-API-Arg: " . $params,
                                "Content-Type: application/octet-stream"
                            ),
                            CURLOPT_POSTFIELDS => $file
                        ));
                        if(($response = curl_exec($ch)) === false){
                            throw new DropboxUploadFileException(
                                __METHOD__. ' '.__LINE__ , 'Curl error: ' . curl_error($ch));
                        }
                        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                        if($httpcode != 200){
                            throw new DropboxUploadFileException(
                                __METHOD__. ' '.__LINE__.' '.$httpcode , $response);
                        }
                        curl_close($ch);

                        $service_file_offset += $chunk_size; // setez ca sa iasa din while
                        $offset += $chunk_size;
                        //echo $response;
                        return json_decode($response, true)["id"];
                    }

                } // else
            } // while
        }

        public static function uploadSmallFile($token, $path, $start_offset, $length) {

            $file = file_get_contents($path, false, null, $start_offset, $length);
            $filename = uniqid("", true);
            $filesize = $length;

            $params = 
            '{' .
                '"path": "/Stol/' . $filename . '",' .
                '"mode": "add",' .
                '"autorename": true,' .
                '"mute": false,' .
                '"strict_conflict": false' .
            '}';

            // echo '<pre>';
            // echo $params;
            // echo '</pre>';

            $ch = curl_init();
            curl_setopt_array($ch, array(
                CURLOPT_URL => 'https://content.dropboxapi.com/2/files/upload',
                CURLOPT_RETURNTRANSFER => 1,
                CURLOPT_BINARYTRANSFER => 1,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_SSL_VERIFYHOST => false,
                CURLOPT_HTTPHEADER => array(
                    "Authorization: Bearer ${token}",
                    "Dropbox-API-Arg: " . $params,
                    "Content-Type: application/octet-stream"
                ),
                CURLOPT_POSTFIELDS => $file
            ));
            if(($response = curl_exec($ch)) === false){
                throw new DropboxUploadFileException(
                    __METHOD__. ' '.__LINE__ , 'Curl error: ' . curl_error($ch));
            }
            $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            if($httpcode != 200){
                throw new DropboxUploadFileException(
                    __METHOD__. ' '.__LINE__.' '.$httpcode , $response);
            }
            curl_close($ch);

            // pt erorile primite sub forma de string
            if(!($array = json_decode($response, true))) {
                throw new DropboxUploadFileException(
                    __METHOD__. ' '.__LINE__ , $response);
            }

            // echo '<pre>';
            // echo $response;
            // echo '</pre>';
            //echo "Small file upload successfully";
            return json_decode($response, true)["id"];

        }


        public static function deleteFileById($token, $file_id)
        {
            $post_fields = '{"path": "' . $file_id . '"}';

            $ch = curl_init();
            curl_setopt_array($ch, array(
                CURLOPT_URL => 'https://api.dropboxapi.com/2/files/delete_v2',
                CURLOPT_RETURNTRANSFER => 1,
                CURLOPT_POST => 1,
                CURLOPT_POSTFIELDS => $post_fields,
                CURLOPT_HTTPHEADER => array(
                    "Authorization: Bearer ${token}",
                    "Content-Type: application/json"
                )
            ));
            if(($response = curl_exec($ch)) === false){
                throw new DropboxDeleteException(
                    __METHOD__. ' '.__LINE__ , "Curl error: " . curl_error($ch));
            }
            $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            if($httpcode != 200){
                throw new DropboxDeleteException(
                    __METHOD__. ' '.__LINE__.' '.$httpcode , $response);
            }
            curl_close($ch);
            //echo '<pre>'; print_r(json_decode($response, true)); echo '</pre>'; // returneaza metadatele fisierului de sters..
            //return true; // sau altceva ?
            //Am presupus ca nu e necesar sa returnam ceva doar sa vedem daca delete-ul a esuat
        }

    }

?>