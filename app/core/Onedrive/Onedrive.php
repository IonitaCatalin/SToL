<?php

    define('CLIENT_ID','53a3e6f5-ab36-41d1-aa38-5ef621f6829d');
    define('TENANT','common');
    define('CLIENT_SECRET','Tn2mKeXphox6vV=?S_HFuvG6?QGURqC5');
    define('SCOPE','openid offline_access Files.ReadWrite.All');
    define('REDIRECT_URI_AUTH','http://localhost/ProiectTW/public/cprofile/authorizeServiceOneDrive/');
    define('REDIRECT_URI_REFRESH_TOKEN','http://localhost/ProiectTW/public/cfiles');
    define('USER_DRIVE_ENDPOINT','https://graph.microsoft.com/v1.0/me/drive');

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
            $response_array=json_decode($response,true);
            if(isset($response_array['error']))
            {
                throw new OnedriveAuthException('Authorization failed please try again!');
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
                if(curl_info($get_curl,CURLINFO_HTTP_CODE)!=200 && $response_array['error']['code']=='itemNotFound')
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
                   $response_array=json_decode(curl_exec($post_curl),true);
                   return $response_array;
                }  
                else
                {
                    throw new OneDriveAuthException('The app could not create the associated working container');
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
                throw new OnedriveRenewTokensException('The access token could not be renewed!');
            }
            else return $tokens_array;

        }
        public static function signOutRedirectURL()
        {
            return 'https://login.microsoftonline.com/common/oauth2/v2.0/logout?post_logout_redirect_uri=https://localhost/ProiectTW/public/cprofile/';
        }
        public function getDriveMetadata($access_token)
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
        public function getDriveQuota($access_token)
        {
           return ($this->getDriveMetadata($access_token)['quota']);
        }
        public function getRemainingSize($access_token)
        {
           return $this->getDriveQuota($access_token)['remaining'];
        }
        public function listAllFiles($access_token)
        {
            
        }
        public function uploadFile($access_token,$file_path=null)
        {

            //Cream un resumable upload session pentru fisierul pe care intentionam sa-l uploadam
            if(file_exists($file_path))
            {
                if($this->getRemainingSize($access_token)>filesize($file_path))
                {
                    echo 'Fisier gasit!<br>Este loc de fisierul asta!';
                    $upload_session_curl=curl_init();
                    $upload_session_post=json_encode(array('item'=>array(
                        '@microsoft.graph.conflictBehavior'=>'rename',
                        'description'=>'file uploaded to onedrive from stol application',
                        'fileSystemInfo'=>array(
                            '@odata.type'=>'microsoft.graph.fileSystemInfo',
                        ),
                        'name'=>basename($file_path))
                    ));
                    curl_setopt_array($upload_session_curl,[
                        CURLOPT_RETURNTRANSFER=>1,
                        CURLOPT_URL=>USER_DRIVE_ENDPOINT.'/root:/Stol/'.basename($file_path).':/createUploadSession',
                        CURLOPT_USERAGENT=>'Stol',
                        CURLOPT_POST=>1,
                        CURLOPT_HTTPHEADER=>array("Authorization: Bearer ${access_token}",'Content-Type: application/json'),
                        CURLOPT_SSL_VERIFYPEER=>false,
                        CURLOPT_POSTFIELDS=>$upload_session_post
                    ]);
                    $response=curl_exec($upload_session_curl);
                    $upload_url=json_decode($response,true)['uploadUrl'];
                    curl_close($upload_session_curl);
                    if(filesize($file_path)<2)
                    {
                        /*
                            In cazul in care fisierul este sub 60 MB il putem incarca integral folosind un singur request,
                            insa pierdem destul de multa performanta
                        */
                    
                        $upload_curl=curl_init();
                        $file_handle=fopen($file_path,"r");
                        $bytes_content=stream_get_contents($file_handle);
                        curl_setopt_array($upload_curl,[
                            CURLOPT_RETURNTRANSFER=>1,
                            CURLOPT_URL=>$upload_url,
                            CURLOPT_USERAGENT=>'Stol',
                            CURLOPT_CUSTOMREQUEST=>'PUT',
                            CURLOPT_SSL_VERIFYPEER=>false,
                            CURLOPT_HTTPHEADER=>array("Authorization: Bearer ${access_token}",
                                                'Content-Length: '.filesize($file_path),
                                                'Content-Range: bytes '.'0-'.(filesize($file_path)-1).'/'.filesize($file_path)),
                            CURLOPT_POSTFIELDS=>$bytes_content 
                        ]);
                        $response=curl_exec($upload_curl);
                        fclose($file_handle);
                        echo '<br>'.$response;
                        echo '<br>'.curl_getinfo($upload_curl,CURLINFO_HTTP_CODE);
                        if(!(curl_getinfo($upload_curl,CURLINFO_HTTP_CODE)==200 || curl_getinfo($upload_curl,CURLINFO_HTTP_CODE)==201))
                        {
                            throw new OneDriveUploadFailedException('The update could not be completed');
                        }
                        curl_close($upload_curl);

                    }
                    else
                    {
                        echo '<br>Facem upload pe chunk-uri';
                        $fragment_size=327680*4;
                        $file_size=filesize($file_path);
                        $num_fragments=ceil($file_size/$fragment_size);
                        $bytes_remaining=$file_size;
                        $index=0;
                        echo "<br>Cantitate de bytes:".$bytes_remaining;
                        echo '<br>Limita unui fragment:'.$fragment_size;
                        echo '<br> Fragmente:'.$num_fragments;
                        $upload_response=null;
                        while($index<$num_fragments)
                        {
                            $num_bytes=$fragment_size;
                            $chunk_size=$num_bytes;
                            $start=$index*$fragment_size;
                            $end=$index*$fragment_size+$chunk_size-1;
                            $offset=$index*$fragment_size; 
                                if($bytes_remaining<$chunk_size)
                                {
                                    $num_bytes=$bytes_remaining;
                                    $chunk_size=$num_bytes;
                                    $end=$file_size-1;
                                }

                                if($stream=fopen($file_path,'r'))
                                {
                                    $data=stream_get_contents($stream,$chunk_size,$offset);
                                    fclose($stream);
                                }

                                $content_range='bytes '.$start.'-'.$end.'/'.$file_size;
                                echo $content_range;
                                $headers=array(
                                    'Content-Length:'=>$num_bytes,
                                    'Content-Range:'=>$content_range,
                                    "Authorization:Bearer ${access_token}"
                                );

                                $upload_parts_curl=curl_init();
                                curl_setopt_array($upload_parts_curl,[
                                    CURLOPT_URL=>$upload_url,
                                    CURLOPT_RETURNTRANSFER=>1,
                                    CURLOPT_CUSTOMREQUEST=>'PUT',
                                    CURLOPT_HTTPHEADER=>$headers,
                                    CURLOPT_POSTFIELDS=>$data,
                                    CURLOPT_USERAGENT=>'Stol',
                                ]);
                                $server_output=curl_exec($upload_parts_curl);
                                echo '<br>'.$server_output.'<br>';
                                curl_close($upload_parts_curl);

                                $bytes_remaining=$bytes_remaining - $chunk_size;
                                $index++;
                        }

                    }
                }
                else
                {
                    throw new OneDriveNotEnoughtSpaceException('There is no space available on Onedrive container!');
                }
            }
            else
            {
                echo 'Problema cu fisierul';
            }

        }
    }
    
?>