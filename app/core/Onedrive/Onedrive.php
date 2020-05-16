<?php

    define('CLIENT_ID','53a3e6f5-ab36-41d1-aa38-5ef621f6829d');
    define('TENANT','common');
    define('CLIENT_SECRET','Tn2mKeXphox6vV=?S_HFuvG6?QGURqC5');
    define('SCOPE','openid offline_access Files.ReadWrite.All');
    define('REDIRECT_URI_AUTH','http://localhost/ProiectTW/api/user/authorize/onedrive');
    define('REDIRECT_URI_REFRESH_TOKEN','http://localhost/ProiectTW/public/cfiles');
    define('USER_DRIVE_ENDPOINT','https://graph.microsoft.com/v1.0/me/drive');

    require_once('OnedriveException.php');
    class OneDriveService
    {
        public static function authorizationRedirectURL($user_id)
        {
            return 'https://login.microsoftonline.com/'.TENANT.'/oauth2/v2.0/authorize?client_id='.CLIENT_ID.'&response_type=code&redirect_uri='.REDIRECT_URI_AUTH.'&response_mode=query&scope='.SCOPE.'&state='.$user_id;
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
            $response_array=json_decode($response,true);
            if(isset($response_array['error']))
            {
                throw new OnedriveAuthException($response_array['error'],curl_getinfo($curl,CURLINFO_HTTP_CODE));
            }
            else 
            {
                OneDriveService::initDriveContainer($response_array['access_token']);
                return $response_array;
            }
        }
        private static function initDriveContainer($access_token)
        {
            $get_curl=curl_init();
            curl_setopt_array($get_curl,[
                CURLOPT_RETURNTRANSFER=>1,
                CURLOPT_URL=>USER_DRIVE_ENDPOINT.'/root/children/Stol',
                CURLOPT_USERAGENT=>'Stol',
                CURLOPT_HTTPHEADER => array("Authorization: Bearer ${access_token}"),
                CURLOPT_SSL_VERIFYPEER=>false
            ]);
            $response=curl_exec($get_curl);
            $response_array=json_decode($response,true);
            if(isset($response_array['error']))
            {
                $http_code=null;
                if(curl_getinfo($get_curl,CURLINFO_HTTP_CODE)!=200 && $response_array['error']['code']=='itemNotFound')
                {
                   $post_curl=curl_init();
                   curl_setopt_array($post_curl,[
                    CURLOPT_RETURNTRANSFER => 1,
                    CURLOPT_URL => USER_DRIVE_ENDPOINT.'/root/children/',
                    CURLOPT_USERAGENT => 'Stol',
                    CURLOPT_POST => 1,
                    CURLOPT_HTTPHEADER => array("Authorization: Bearer ${access_token}",'Content-Type: application/json'),
                    CURLOPT_POSTFIELDS => '{
                        "name": "Stol",
                        "folder": { },
                        "@microsoft.graph.conflictBehavior": "rename"
                      }'
                   ]);
                   $http_code=curl_getinfo($post_curl,CURLINFO_HTTP_CODE);
                   $response_array=json_decode(curl_exec($post_curl),true);
                   return $response_array;
                }  
                else
                {
                    throw new OneDriveAuthException($response_array['error'],$http_code);
                }    
            }
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
            curl_close($curl);
            if(isset($tokens_array['error']))
            {
                throw new OnedriveRenewTokensException($tokens_array['error'],curl_getinfo($curl,CURLINFO_HTTP_CODE));
            }
            else return $tokens_array;

        }
        public static function signOutRedirectURL()
        {
            return 'https://login.microsoftonline.com/common/oauth2/v2.0/logout?post_logout_redirect_uri=https://localhost/ProiectTW/public/cprofile/';
        }
        public static function getDriveMetadata($access_token)
        {
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
            curl_close($curl);
            return $metadata_array;
        }
        public static function getDriveQuota($access_token)
        {
           return (OneDriveService::getDriveMetadata($access_token)['quota']);
        }
        public static function getRemainingSize($access_token)
        {
           return OneDriveService::getDriveQuota($access_token)['remaining'];
        }
        public static function uploadFile($access_token,$file_path=null,$offset,$size)
        {

            //Cream un resumable upload session pentru fisierul pe care intentionam sa-l uploadam
            if(file_exists($file_path))
            {
                if(OneDriveService::getRemainingSize($access_token)>$size)
                {
                    //echo 'Fisier gasit!<br>Este loc de fisierul asta!';
                    $random_name=uniqid("",true);
                    $upload_session_curl=curl_init();
                    $upload_session_post=json_encode(array('item'=>array(
                        '@microsoft.graph.conflictBehavior'=>'rename',
                        'description'=>'file uploaded to onedrive from stol application',
                        'fileSystemInfo'=>array(
                            '@odata.type'=>'microsoft.graph.fileSystemInfo',
                        ),
                        'name'=>$random_name)
                    ));
                    curl_setopt_array($upload_session_curl,[
                        CURLOPT_RETURNTRANSFER=>1,
                        CURLOPT_URL=>USER_DRIVE_ENDPOINT.'/root:/Stol/'.$random_name.':/createUploadSession',
                        CURLOPT_USERAGENT=>'Stol',
                        CURLOPT_POST=>1,
                        CURLOPT_HTTPHEADER=>array("Authorization: Bearer ${access_token}",'Content-Type: application/json'),
                        CURLOPT_SSL_VERIFYPEER=>false,
                        CURLOPT_POSTFIELDS=>$upload_session_post
                    ]);
                    $response=curl_exec($upload_session_curl);
                    $upload_url=json_decode($response,true)['uploadUrl'];
                    curl_close($upload_session_curl);
                    if($size<10000000)
                    {
                        
                        /*
                            In cazul in care fisierul este sub 10 MB il putem incarca integral folosind un singur request,
                            din motive de performanta
                        */
                    
                        $upload_curl=curl_init();
                        $file_handle=fopen($file_path,"r");
                        $bytes_content=stream_get_contents($file_handle,$size,$offset);
                        curl_setopt_array($upload_curl,[
                            CURLOPT_RETURNTRANSFER=>1,
                            CURLOPT_URL=>$upload_url,
                            CURLOPT_USERAGENT=>'Stol',
                            CURLOPT_CUSTOMREQUEST=>'PUT',
                            CURLOPT_SSL_VERIFYPEER=>false,
                            CURLOPT_HTTPHEADER=>array("Authorization: Bearer ${access_token}",
                                                'Content-Length: '.$size,
                                                'Content-Range: bytes '.'0-'.($size-1).'/'.$size),
                            CURLOPT_POSTFIELDS=>$bytes_content 
                        ]);
                        $response=curl_exec($upload_curl);
                        $response_array=json_decode($response,true);
                        fclose($file_handle);
                        if(!curl_getinfo($upload_curl,CURLINFO_HTTP_CODE)==200 || !curl_getinfo($upload_curl,CURLINFO_HTTP_CODE)==201)
                        {
                            throw new OneDriveUploadException($response_arary['error'],curl_getinfo($upload_curl,CURLINFO_HTTP_CODE));
                        }
                        return  $response_array['id'];
                        curl_close($upload_curl);

                    }
                    else
                    {
                        //echo '<br>Facem upload pe chunk-uri';
                        $fragment_size=327680*183;
                        $file_size=$size;
                        $num_fragments=ceil($file_size/$fragment_size);
                        //echo 'Numar de fragmente:'.$num_fragments;
                        $bytes_remaining=$file_size;
                        $index=0;
                        //echo "<br>Cantitate de bytes:".$bytes_remaining;
                        //echo '<br>Limita unui fragment:'.$fragment_size;
                        //echo '<br> Fragmente:'.$num_fragments;
                        $upload_response=null;
                        while($index<$num_fragments)
                        {
                            $num_bytes=$fragment_size;
                            $chunk_size=$num_bytes;
                            $start=$index*$fragment_size;
                            $end=$index*$fragment_size+$chunk_size-1;
                            $inner_offset=$index*$fragment_size; 
                            
                                if($bytes_remaining<$chunk_size)
                                {
                                    $num_bytes=$bytes_remaining;
                                    $chunk_size=$num_bytes;
                                    $end=$file_size-1;
                                }
                               

                                if($stream=fopen($file_path,'r'))
                                {
                                    $data=stream_get_contents($stream,$chunk_size,$inner_offset+$offset);
                                    fclose($stream);
                                }

                                $content_range='bytes '.$start.'-'.$end.'/'.$file_size;
                                //echo $content_range;
                                $upload_parts_curl=curl_init();
                                curl_setopt_array($upload_parts_curl,[
                                    CURLOPT_URL=>$upload_url,
                                    CURLOPT_RETURNTRANSFER=>1,
                                    CURLOPT_CUSTOMREQUEST=>'PUT',
                                    CURLOPT_HTTPHEADER=> array('Content-Range: '.$content_range,
                                                               'Content-Length: '.$num_bytes),
                                    CURLOPT_POSTFIELDS=>$data,
                                    CURLOPT_USERAGENT=>'Stol',
                                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                                     CURLOPT_BINARYTRANSFER => TRUE,
                                ]);
                                $server_output=curl_exec($upload_parts_curl);
                                $response_array=json_decode($server_output,true);
                                if(!(curl_getinfo($upload_parts_curl,CURLINFO_HTTP_CODE)==201 || curl_getinfo($upload_parts_curl,CURLINFO_HTTP_CODE)==202))
                                {
                                    throw new OneDriveUploadException($response_array['error']['message'],curl_getinfo($upload_parts_curl,CURLINFO_HTTP_CODE));
                                }
                                else
                                {
                                   if(curl_getinfo($upload_parts_curl,CURLINFO_HTTP_CODE)==201)
                                        return $response_array['id'];
                                }
                                curl_close($upload_parts_curl);
                                $bytes_remaining=$bytes_remaining - $chunk_size;
                                $index++;
                            }
                    }
                }
                else
                {
                    throw new OneDriveNotEnoughtSpaceException($response_arary['error'],curl_getinfo($upload_curl,CURLINFO_HTTP_CODE));
                }
            }
            else
            {
                throw new OneDriveUploadException("FIle cannot be found on server");
            }

        }
        public static function downloadFileById($access_token,$file_id,$append_to_path)
        {
            $get_download=curl_init();
            curl_setopt_array($get_download,[
                CURLOPT_URL=>USER_DRIVE_ENDPOINT."items/${file_id}/content",
                CURLOPT_RETURNTRANSFER=>1,
                CURLOPT_GET=>1,
                CURLOPT_USERAGENT=>'Stol',
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            ]);
            $result_array=json_decode(curl_exec($get_download));
            var_dump($result_array);

        }

        public static function deleteById($acces_token,$item_id)
        {
            $delete_item=curl_init();
            $curl_setopt_array($delete_itemm[
                
            ]);
        }
    }
    
?>