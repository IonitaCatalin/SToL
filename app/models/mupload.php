<?php
    class MUpload
    {
        public function testUploadOnedrive($user_id)
        {
            $path=$_SERVER['DOCUMENT_ROOT'].'/ProiectTW/uploads/955eb964652f83d43f3e77fe17a570ea';
            OneDriveService::uploadFile($this->getAccessToken($user_id,'onedrive'),$path,0,500);
        }

        public function testUploadGoogledrive($user_id)
        {
            $path=$_SERVER['DOCUMENT_ROOT'].'/ProiectTW/uploads/955eb964652f83d43f3e77fe17a570ea';
            // pt un fisier de 25,306,104 bytes
            //GoogleDriveService::uploadFile($this->getAccessToken($user_id,'googledrive'), $path, 0, 15000000);
            //GoogleDriveService::uploadFile($this->getAccessToken($user_id,'googledrive'), $path, 15000000, 10000000);
            //GoogleDriveService::uploadFile($this->getAccessToken($user_id,'googledrive'), $path, 25000000, 306104);

            // pt un fisier de 627,831 bytes
            //GoogleDriveService::uploadFile($this->getAccessToken($user_id,'googledrive'), $path, 0, 600000);
            //GoogleDriveService::uploadFile($this->getAccessToken($user_id,'googledrive'), $path, 600000, 27800);
            //GoogleDriveService::uploadFile($this->getAccessToken($user_id,'googledrive'), $path, 627800, 31);
        }

        public function testUploadDropbox($user_id)
        {
            //$path=$_SERVER['DOCUMENT_ROOT'].'/ProiectTW/uploads/d074b412a892735d0ab26e5b09465315';
            //DropboxService::uploadFile($this->getAccessToken($user_id,'dropbox'), $path, 0, 15000000);
            //DropboxService::uploadFile($this->getAccessToken($user_id,'dropbox'), $path, 15000000, 10000000);
            //DropboxService::uploadFile($this->getAccessToken($user_id,'dropbox'), $path, 25000000, 306104);

            // 627,831 bytes
            //$path=$_SERVER['DOCUMENT_ROOT'].'/ProiectTW/uploads/777a2a2279bb8ba4c857bbf41dbd79c6';
            //DropboxService::uploadFile($this->getAccessToken($user_id,'dropbox'), $path, 0, 300000);
            //DropboxService::uploadFile($this->getAccessToken($user_id,'dropbox'), $path, 300000, 300000);
            //DropboxService::uploadFile($this->getAccessToken($user_id,'dropbox'), $path, 600000, 27831);
        }
        public function uploadFileWithMode($fragments_id,$upload_id)
        {
               
                $bytes=random_bytes(16);
                $item_id=bin2hex($bytes);
                $get_upload_sql="SELECT * FROM UPLOADS WHERE upload_id=:upload_id";
                $get_upload_stmt=DB::getConnection()->prepare($get_upload_sql);
                $get_upload_stmt->execute([
                    'upload_id'=>$upload_id
                ]);
                $upload_info=$get_upload_stmt->fetch(PDO::FETCH_ASSOC);
                $mode=$upload_info['mode'];
            switch($mode)
            {
                case 'fragmented':
                {
                    $this->uploadFileFragmented($fragments_id,$upload_id,$item_id);
                    break;
                }
                case 'redundant':
                {
                    $this->uploadFileRedundant($fragments_id,$upload_id,$item_id);
                    break;
                }
                
            }
        }
        public function uploadFileRedundant($fragments_id,$upload_id,$item_id)
        {
            $get_upload_sql="SELECT * FROM UPLOADS WHERE upload_id=:upload_id";
            $get_upload_stmt=DB::getConnection()->prepare($get_upload_sql);
            $get_upload_stmt->execute([
                'upload_id'=>$upload_id
            ]);
            $upload_info=$get_upload_stmt->fetch(PDO::FETCH_ASSOC);
            $path=$_SERVER['DOCUMENT_ROOT'].'/ProiectTW/uploads/'.$upload_info['file_reference'];
            $filesize=filesize($path);

            $insert_item_sql="INSERT INTO ITEMS(user_id,item_id,content_type) VALUES (:user_id,:item_id,'file')";
            $insert_item_stmt=DB::getConnection()->prepare($insert_item_sql);
            $insert_item_stmt->execute([
                'user_id'=>$upload_info['user_id'],
                'item_id'=>$item_id,
            ]);

            $insert_file_sql="INSERT INTO FILES(item_id,folder_id,name,fragments_id) VALUES (:item_id,:folder_id,:name,:fragments_id)";
            $insert_file_stmt=DB::getConnection()->prepare($insert_file_sql);
            $insert_file_stmt->execute([
                'item_id'=>$item_id,
                'folder_id'=>$upload_info['parent_id'],
                'name'=>$upload_info['name'],
                'fragments_id'=>$fragments_id
            ]);

            $available_services = $this->getAvailableServices($upload_info['user_id']);
            $available_storage=$this->getServicesRemainingStorage($upload_info['user_id'],$available_services);
            $count_services=0;
            foreach($available_storage as $key=>$value)
            {
                if($value)
                    $count_services++;
            }
            if($count_services==0)
            {
                throw new NoStorageServices();
            }
            else if($count_services==1)
            {
                throw new InvalidRedundancyException();
            }
            else
            {
                $bytes=random_bytes(16);
                $redundancy_id=bin2hex($bytes);
                $insert_fragment_sql="INSERT INTO FRAGMENTS(fragments_id,service,offset,service_id,fragment_size,redundancy_id) VALUES(:fragments_id,:service,:offset,:service_id,:fragment_size,:redundancy_id)";
                $insert_fragment_stmt=DB::getConnection()->prepare($insert_fragment_sql);
                foreach($available_services as $key=>$value)
                {
                    switch($key)
                    {
                        case 'onedrive':
                        {
                            if($value)
                            {
                                $insert_fragment_stmt->execute([
                                    'fragments_id'=>$fragments_id,
                                    'service'=>'onedrive',
                                    'offset'=>0,
                                    'service_id'=>OneDriveService::uploadFile($this->getAccessToken($upload_info['user_id'],'onedrive'),$path,0,$filesize),
                                    'fragment_size'=>$filesize,
                                    'redundancy_id'=>$redundancy_id
                                ]);
                            }
                            break;
                        }
                        case 'googledrive':
                        {
                            if($value)
                            {
                                $insert_fragment_stmt->execute([
                                    'fragments_id'=>$fragments_id,
                                    'service'=>'googledrive',
                                    'offset'=>0,
                                    'service_id'=>GoogleDriveService::uploadFile($this->getAccessToken($upload_info['user_id'],'googledrive'),$path,0,$filesize),
                                    'fragment_size'=>$filesize,
                                    'redundancy_id'=>$redundancy_id
                                ]);
                            }
                            break;
                        }
                        case 'dropbox':
                        {
                            if($value)
                            {
                                $insert_fragment_stmt->execute([
                                    'fragments_id'=>$fragments_id,
                                    'service'=>'dropbox',
                                    'offset'=>0,
                                    'service_id'=>DropboxService::uploadFile($this->getAccessToken($upload_info['user_id'],'dropbox'),$path,0,$filesize),
                                    'fragment_size'=>$filesize,
                                    'redundancy_id'=>$redundancy_id
                                ]);
                            }
                            break;
                        }
                    }
                }
            }


        }
        public function uploadFileFragmented($fragments_id,$upload_id,$item_id)
        {
                //$path=$_SERVER['DOCUMENT_ROOT'].'/ProiectTW/uploads/955eb964652f83d43f3e77fe17a570ea'; 
                $get_upload_sql="SELECT * FROM UPLOADS WHERE upload_id=:upload_id";
                $get_upload_stmt=DB::getConnection()->prepare($get_upload_sql);
                $get_upload_stmt->execute([
                    'upload_id'=>$upload_id
                ]);
                $upload_info=$get_upload_stmt->fetch(PDO::FETCH_ASSOC);
                $path=$_SERVER['DOCUMENT_ROOT'].'/ProiectTW/uploads/'.$upload_info['file_reference'];
                $file_splitting = $this->computeFileSplitting($upload_info['user_id'], $path);

                //print_r($file_splitting);

                $insert_item_sql="INSERT INTO ITEMS(user_id,item_id,content_type) VALUES (:user_id,:item_id,'file')";
                $insert_item_stmt=DB::getConnection()->prepare($insert_item_sql);
                $insert_item_stmt->execute([
                    'user_id'=>$upload_info['user_id'],
                    'item_id'=>$item_id,
                ]);

                $insert_file_sql="INSERT INTO FILES(item_id,folder_id,name,fragments_id) VALUES (:item_id,:folder_id,:name,:fragments_id)";
                $insert_file_stmt=DB::getConnection()->prepare($insert_file_sql);
                $insert_file_stmt->execute([
                    'item_id'=>$item_id,
                    'folder_id'=>$upload_info['parent_id'],
                    'name'=>$upload_info['name'],
                    'fragments_id'=>$fragments_id
                ]);

            // ---
            // inserari in baza de date si apeluri catre functiile de upload, acum ca se cunoaste splitting-ul
            $insert_fragment_sql="INSERT INTO FRAGMENTS(fragments_id,service,offset,service_id,fragment_size) VALUES(:fragments_id,:service,:offset,:service_id,:fragment_size)";
            $insert_fragment_stmt=DB::getConnection()->prepare($insert_fragment_sql);
            $offset = 0;
            if($file_splitting["onedrive"] != 0 ) {
                //echo "Incarc pe onedrive start: $offset, dimensiune: " . $file_spliting["onedrive"];
                $insert_fragment_stmt->execute([
                    'fragments_id'=>$fragments_id,
                    'service'=>'onedrive',
                    'offset'=>$offset,
                    'service_id'=>OneDriveService::uploadFile($this->getAccessToken($upload_info['user_id'],'onedrive'),$path,$offset,$file_splitting['onedrive']),
                    'fragment_size'=>$file_splitting['onedrive']
                ]);
                $offset += $file_splitting["onedrive"]; 
            }
            if($file_splitting["dropbox"] != 0 ) {
                //echo "Incarc pe dropbox start: $offset, dimensiune: " . $file_spliting["dropbox"];
                $insert_fragment_stmt->execute([
                    'fragments_id'=>$fragments_id,
                    'service'=>'dropbox',
                    'offset'=>$offset,
                    'service_id'=>DropboxService::uploadFile($this->getAccessToken($upload_info['user_id'],'dropbox'),$path,$offset,$file_splitting['dropbox']),
                    'fragment_size'=>$file_splitting['dropbox']
                ]);
                $offset += $file_splitting["dropbox"];
            }
            if($file_splitting["googledrive"] != 0 ) {
                //echo "Incarc pe googledrive start: $offset, dimensiune: " . $file_spliting["googledrive"];
                $insert_fragment_stmt->execute([
                    'fragments_id'=>$fragments_id,
                    'service'=>'googledrive',
                    'offset'=>$offset,
                    'service_id'=>GoogleDriveService::uploadFile($this->getAccessToken($upload_info['user_id'],'googledrive'),$path,$offset,$file_splitting['googledrive']),
                    'fragment_size'=>$file_splitting['googledrive']
                ]);
                $offset += $file_splitting["googledrive"];
            }
            // ---
        }

        public function computeFileSplitting($user_id, $path)
        {
            $gdrive_part = $onedrive_part = $dropbox_part = 0;
            $filesize = filesize($path);
            $available_services = $this->getAvailableServices($user_id);
            $available_storage = $this->getServicesRemainingStorage($user_id, $available_services);

            // ---- Test zone

            // $filesize = 627827;
            // echo "Fisierul are dimensiungetSea: $filesize ----";

            // $available_services["onedrive"] = true;
            // $available_services["dropbox"] = false;
            // $available_services["googledrive"] = true;

            // $available_storage["onedrive"] = 150000;
            // $available_storage["dropbox"] = null;
            // $available_storage["googledrive"] = 700000;

            //echo "SPATIU TOTAL DISPONIBIL: " . array_sum($available_storage) . "----";
            // -----


            if($filesize > array_sum($available_storage)){      // atentie la test, va insuma chiar daca available == false
                //echo "Nu este suficient spatiul disponibil pe toate serviciile cumulate !!!";
                throw new NotEnoughStorage();
            }

            if(!$available_services["onedrive"] && !$available_services["dropbox"] && !$available_services["googledrive"]) {
                throw new NoStorageServices();
            }

            // initial, presupunem ca punem 1 treime pe fiecare serviciu
            $onedrive_part = intdiv($filesize, 3);
            $dropbox_part = intdiv($filesize, 3);
            $gdrive_part = intdiv($filesize, 3);
            if($filesize % 3 == 1)  { $gdrive_part ++; }
            if($filesize % 3 == 2)  { $onedrive_part++; $gdrive_part ++; }
            //echo "INITIAL: $onedrive_part|$dropbox_part|$gdrive_part----";

            if($available_services["onedrive"] == false) {
                $this->resplit_to_gdrive_dropbox($onedrive_part, $available_services, $available_storage, $gdrive_part, $dropbox_part);
                $onedrive_part = 0;
            }
            else if($onedrive_part > $available_storage["onedrive"]) {
                $this->resplit_to_gdrive_dropbox($onedrive_part - $available_storage["onedrive"], $available_services, $available_storage, $gdrive_part, $dropbox_part);
                $onedrive_part = $available_storage["onedrive"];
            }

            if($available_services["dropbox"] == false) {
                $this->resplit_to_onedrive_gdrive($dropbox_part, $available_services, $available_storage, $onedrive_part, $gdrive_part);
                $dropbox_part = 0;
            }
            else if($dropbox_part > $available_storage["dropbox"]) {
                $this->resplit_to_onedrive_gdrive($dropbox_part - $available_storage["dropbox"], $available_services, $available_storage, $onedrive_part, $gdrive_part);
                $dropbox_part = $available_storage["dropbox"];
            }

            if($available_services["googledrive"] == false) {
                $this->resplit_to_onedrive_dropbox($gdrive_part, $available_services, $available_storage, $onedrive_part, $dropbox_part);
                $gdrive_part = 0;
            }
            else if($gdrive_part > $available_storage["googledrive"]) {
                $this->resplit_to_onedrive_dropbox($gdrive_part - $available_storage["googledrive"], $available_services, $available_storage, $onedrive_part, $dropbox_part);
                $gdrive_part = $available_storage["googledrive"];
            }

            //echo "FINAL: $onedrive_part|$dropbox_part|$gdrive_part----";
            //echo "SUMA CONTROL: " . ($onedrive_part + $dropbox_part + $gdrive_part);
            return array("onedrive" => $onedrive_part, "dropbox" => $dropbox_part, "googledrive" => $gdrive_part);
        }

        public function resplit_to_gdrive_dropbox($size, $available_services, $available_storage, &$gdrive_part, &$dropbox_part)
        {
            if($available_services["googledrive"] == false) {
                $dropbox_part += ($gdrive_part + $size);
                $gdrive_part = 0;
                return;
            }
            if($available_services["dropbox"] == false) {
                $gdrive_part += ($dropbox_part + $size);
                $dropbox_part = 0;
                return;
            }
            if(($gdrive_part + intdiv($size, 2)) > $available_storage["googledrive"]) {
                $temp = $available_storage["googledrive"] - $gdrive_part;
                $gdrive_part += $temp;
                $dropbox_part += $size - $temp;
                return;
            }
            if(($dropbox_part + intdiv($size, 2)) > $available_storage["dropbox"]) {
                $temp = $available_storage["dropbox"] - $dropbox_part;
                $dropbox_part += $temp;
                $gdrive_part += $size - $temp;
                return;
            }
            $gdrive_part += intdiv($size, 2);
            $dropbox_part += intdiv($size, 2);
            if($size % 2 == 1){
                ($gdrive_part+1) > $available_storage["googledrive"] ? $dropbox_part++ : $gdrive_part++;
            }
        }

        public function resplit_to_onedrive_gdrive($size, $available_services, $available_storage, &$onedrive_part, &$gdrive_part)
        {
            if($available_services["onedrive"] == false) {
                $gdrive_part += ($onedrive_part + $size);
                $onedrive_part = 0;
                return;
            }
            if($available_services["googledrive"] == false) {
                $onedrive_part += ($gdrive_part + $size);
                $gdrive_part = 0;
                return;
            }
            if(($onedrive_part + intdiv($size, 2)) > $available_storage["onedrive"]) {
                $temp = $available_storage["onedrive"] - $onedrive_part;
                $onedrive_part += $temp;
                $gdrive_part += $size - $temp;
                return;
            }
            if(($gdrive_part + intdiv($size, 2)) > $available_storage["googledrive"]) {
                $temp = $available_storage["googledrive"] - $gdrive_part;
                $gdrive_part += $temp;
                $onedrive_part += $size - $temp;
                return;
            }
            $onedrive_part += intdiv($size, 2);
            $gdrive_part += intdiv($size, 2);
            if($size % 2 == 1){
                ($gdrive_part+1) > $available_storage["googledrive"] ? $onedrive_part++ : $gdrive_part++;
            }
        }

        public function resplit_to_onedrive_dropbox($size, $available_services, $available_storage, &$onedrive_part, &$dropbox_part)
        {
            if($available_services["onedrive"] == false) {
                $dropbox_part += ($onedrive_part + $size);
                $onedrive_part = 0;
                return;
            }
            if($available_services["dropbox"] == false) {
                $onedrive_part += ($dropbox_part + $size);
                $dropbox_part = 0;
                return;
            }
            if(($onedrive_part + intdiv($size, 2)) > $available_storage["onedrive"]) {
                $temp = $available_storage["onedrive"] - $onedrive_part;
                $onedrive_part += $temp;
                $dropbox_part += $size - $temp;
                return;
            }
            if(($dropbox_part + intdiv($size, 2)) > $available_storage["dropbox"]) {
                $temp = $available_storage["dropbox"] - $dropbox_part;
                $dropbox_part += $temp;
                $onedrive_part += $size - $temp;
                return;
            }
            $onedrive_part += intdiv($size, 2);
            $dropbox_part += intdiv($size, 2);
            if($size % 2 == 1){
                ($onedrive_part+1) > $available_storage["onedrive"] ? $dropbox_part++ : $onedrive_part++;
            }
        }

        public function getServicesRemainingStorage($user_id, $available_services)
        {
            $result = array();
            if($available_services["onedrive"])
                $result["onedrive"] = OnedriveService::getDriveQuota($this->getAccessToken($user_id, 'onedrive'))["remaining"];
            if($available_services["googledrive"]) {
                $temp = GoogleDriveService::getStorageQuota($this->getAccessToken($user_id, 'googledrive'));
                $result["googledrive"] = $temp["limit"] - $temp["usage"];
            }
            if($available_services["dropbox"]){
                $temp = DropboxService::getStorageQuota($this->getAccessToken($user_id, 'dropbox'));
                $result["dropbox"] = $temp["allocation"]["allocated"] - $temp["used"];
            }

            //print_r(OnedriveService::getDriveQuota($this->getAccessToken($user_id, 'onedrive')));
            //print_r(GoogleDriveService::getStorageQuota($this->getAccessToken($user_id, 'googledrive')));
            //print_r(DropboxService::getStorageQuota($this->getAccessToken($user_id, 'dropbox')));
            return $result;
        }

        public function getAvailableServices($user_id)
        {
            $result = array();

            $get_onedrive_query = "SELECT user_id FROM onedrive_service WHERE user_id = :user_id";
            $get_onedrive_stmt = DB::getConnection()->prepare($get_onedrive_query);
            $get_onedrive_stmt->execute(['user_id'=>$user_id]);
            if($get_onedrive_stmt->rowCount() > 0)
                $result["onedrive"] = true;
            else
                $result["onedrive"] = false;

            $get_googledrive_query = "SELECT user_id FROM googledrive_service WHERE user_id = :user_id";
            $get_googledrive_stmt = DB::getConnection()->prepare($get_googledrive_query);
            $get_googledrive_stmt->execute(['user_id'=>$user_id]);
            if($get_googledrive_stmt->rowCount()>0)
                $result["googledrive"] = true;
            else
                $result["googledrive"] = false;

            $get_dropbox_query = "SELECT user_id FROM dropbox_service WHERE user_id = :user_id";
            $get_dropbox_stmt = DB::getConnection()->prepare($get_dropbox_query);
            $get_dropbox_stmt->execute(['user_id'=>$user_id]);
            if($get_dropbox_stmt->rowCount()>0)
                $result["dropbox"] = true;
            else
                $result["dropbox"] = false;

            return $result;
        }

        public function getAccessToken($user_id,$service)
        {
            switch($service)
            {
                case 'onedrive':
                {
                    $get_onedrive_sql='SELECT * FROM onedrive_service WHERE user_id=:id';
                    $get_onedrive_stmt=DB::getConnection()->prepare($get_onedrive_sql);
                    $get_onedrive_stmt->execute([
                        'id'=>$user_id
                    ]);
                    if($get_onedrive_stmt->rowCount()>0)
                    {
                        $result_array=$get_onedrive_stmt->fetch(PDO::FETCH_ASSOC);
                        $generated_at=date("Y-m-d H:i:s",strtotime($result_array['generated_at']));
                        $current_time=date("Y-m-d H:i:s",time());
                        $seconds_diff=strtotime($current_time)-strtotime($generated_at);
                        if($seconds_diff<$result_array['expires_in'])
                        {
                            return $result_array['access_token'];
                        }
                        else
                        {
                            $renewed_tokens=OneDriveService::renewTokens($result_array['refresh_token']);
                            $update_tokens_sql="UPDATE onedrive_service SET access_token=:access_token,refresh_token=:refresh_token,generated_at=:generated_at,expires_in=:expires_in";
                            $update_tokens_stmt=DB::getConnection()->prepare($update_tokens_sql);
                            $update_tokens_stmt->execute([
                                'access_token'=>$renewed_tokens['access_token'],
                                'refresh_token'=>$renewed_tokens['refresh_token'],
                                'generated_at'=>date("Y-m-d H:i:s"),
                                'expires_in'=>$renewed_tokens['expires_in']
                            ]);
                            return $renewed_tokens['access_token'];
                        }
                    }
                    else
                    {
                        return null;
                    }
                    break;
                }
                case 'googledrive':
                {
                    $get_gdrive_sql='SELECT * FROM googledrive_service WHERE user_id=:id';
                    $get_gdrive_stmt=DB::getConnection()->prepare($get_gdrive_sql);
                    $get_gdrive_stmt->execute([
                        'id'=>$user_id
                    ]);
                    if($get_gdrive_stmt->rowCount()>0)
                    {
                       
                        $result_array=$get_gdrive_stmt->fetch(PDO::FETCH_ASSOC);
                        $generated_at=date("Y-m-d H:i:s",strtotime($result_array['generated_at']));
                        $current_time=date("Y-m-d H:i:s",time());
                        $seconds_diff=strtotime($current_time)-strtotime($generated_at);
                        if($seconds_diff<$result_array['expires_in'])
                        {
                            return $result_array['access_token'];
                        }
                        else
                        {
                            $renewed_tokens=GoogleDriveService::renewAccessToken($result_array['refresh_token']);
                            $update_tokens_sql="UPDATE googledrive_service SET access_token=:access_token,generated_at=:generated_at,expires_in=:expires_in";
                            $update_tokens_stmt=DB::getConnection()->prepare($update_tokens_sql);
                            $update_tokens_stmt->execute([
                                'access_token'=>$renewed_tokens['access_token'],
                                'generated_at'=>date("Y-m-d H:i:s"),
                                'expires_in'=>$renewed_tokens['expires_in']
                            ]);
                            return $renewed_tokens['access_token'];
                        }
                    }
                    else
                    {
                        return null;
                    }
                    break;
                }
                case 'dropbox':
                {
                    $get_dropbox_sql='SELECT * FROM dropbox_service WHERE user_id=:id';
                    $get_dropbox_stmt=DB::getConnection()->prepare($get_dropbox_sql);
                    $get_dropbox_stmt->execute([
                        'id'=>$user_id
                    ]);
                    if($get_dropbox_stmt->rowCount()>0)
                    {
                        $result_array=$get_dropbox_stmt->fetch(PDO::FETCH_ASSOC);
                        return $result_array['access_token'];
                    }
                    else
                    {
                        return null;
                    }
                    break;
                }
            }
        }

        public function startUpload($upload_id,$user_id,$parent_id,$filename,$filesize,$mode)
        {
            $check_parent_sql='SELECT item_id FROM FOLDERS WHERE item_id=:id';
            $check_parent_stmt=DB::getConnection()->prepare($check_parent_sql);
            $check_parent_stmt->execute([
                'id'=>$parent_id
            ]);
            if($check_parent_stmt->rowCount()>0)
            {   
                $check_name_sql='SELECT item_id FROM FILES WHERE folder_id=:id AND name=:name';
                $check_name_stmt=DB::getConnection()->prepare($check_name_sql);
                $check_name_stmt->execute([
                    'id'=>$parent_id,
                    'name'=>$filename
                ]);
                if($check_name_stmt->rowCount()==0)
                {
                    $bytes=random_bytes(16);
                    $file_reference=bin2hex($bytes);
                    $insert_upload_sql="INSERT INTO UPLOADS(user_id,upload_id,parent_id,file_reference,name,expected_size,status,mode) VALUES (:user_id,:upload_id,:parent_id,:file_reference,:name,:expected_size,'chunking',:mode)";
                    $insert_upload_stmt=DB::getConnection()->prepare($insert_upload_sql);
                    $insert_upload_stmt->execute([
                        'user_id'=>$user_id,
                        'upload_id'=>$upload_id,
                        'parent_id'=>$parent_id,
                        'file_reference'=>$file_reference,
                        'name'=>$filename,
                        'expected_size'=>$filesize,
                        'mode'=>$mode
                    ]);
                    $path = $_SERVER['DOCUMENT_ROOT'].'/ProiectTW/uploads/'.$file_reference;
                    $file = fopen($path,"w");
                    fclose($file);
                }
                else
                {
                    throw new ItemnameTaken();
                }
            }
            else
            {
                throw new InvalidItemParentId();
            }
        }

        public function appendChunks($upload_id,$chunk_size)
        {
            $check_upload_id="SELECT * FROM UPLOADS WHERE upload_id=:id";
            $check_upload_stmt=DB::getConnection()->prepare($check_upload_id);
            $check_upload_stmt->execute([
                'id'=>$upload_id
            ]);
            if($check_upload_stmt->rowCount()>0)
            {
                $post_data=file_get_contents('php://input');
                if(strlen($post_data)>$chunk_size)
                {
                    throw new UnsupportedChunkSize();
                }
                else
                {   
                    $upload_array=$check_upload_stmt->fetch(PDO::FETCH_ASSOC);
                    $path=$_SERVER['DOCUMENT_ROOT'].'/ProiectTW/uploads/'.$upload_array['file_reference'];
                    file_put_contents($path,$post_data,FILE_APPEND);
                    if(filesize($path)==$upload_array['expected_size'])
                    {
                        return true;
                    }
                    else return false;    
                }
            }
            else
            {
                throw new InvalidUploadId();
            }
        }

        public function deletePublicUpload($upload_id)
        {
            $check_upload_sql="SELECT * FROM UPLOADS WHERE upload_id=:id";
            $check_upload_stmt=DB::getConnection()->prepare($check_upload_sql);
            $check_upload_stmt->execute([
                'id'=>$upload_id
            ]);
            if($check_upload_stmt->rowCount()>0)
            {
                
                $result_upload=$check_upload_stmt->fetch(PDO::FETCH_ASSOC);
                if($result_upload['status']=='chunking')
                {
                    unlink($_SERVER['DOCUMENT_ROOT'].'/ProiectTW/uploads/'.$result_upload['file_reference']);
                    $delete_upload_sql="DELETE FROM uploads WHERE upload_id=:id";
                    $delete_upload_stmt=DB::getConnection()->prepare($delete_upload_sql);
                    $delete_upload_stmt->execute([
                        'id'=>$upload_id
                    ]);
                }
                else
                {
                    throw new DeleteSplittingFile();
                }
            }   
            else
            {
                throw new InvalidUploadId();
            }
        }

        public function completePublicUpload($upload_id)
        {
            $get_upload_sql="SELECT * FROM UPLOADS WHERE upload_id=:upload_id";
            $get_upload_stmt=DB::getConnection()->prepare($get_upload_sql);
            $get_upload_stmt->execute([
                'upload_id'=>$upload_id
            ]);
            if($get_upload_stmt->rowCount()>0)
            {
                $result_upload=$get_upload_stmt->fetch(PDO::FETCH_ASSOC);
                $file_reference=$result_upload['file_reference'];
                unlink($_SERVER['DOCUMENT_ROOT'].'/ProiectTW/uploads/'.$result_upload['file_reference']);
                $delete_upload_sql="DELETE FROM UPLOADS WHERE upload_id=:upload_id";
                $delete_upload_stmt=DB::getConnection()->prepare($delete_upload_sql);
                $delete_upload_stmt->execute([
                    'upload_id'=>$upload_id
                ]);
            }
            else
            {
                throw new InvalidUploadId();
            }
        }

        public function deleteIncompleteUpload($upload_id,$fragments_id)
        {
            $get_upload_sql="SELECT * FROM UPLOADS WHERE upload_id=:upload_id";
            $get_upload_stmt=DB::getConnection()->prepare($get_upload_sql);
            $get_upload_stmt->execute([
                'upload_id'=>$upload_id
            ]);
            $result_upload=$get_upload_stmt->fetch(PDO::FETCH_ASSOC);
            if($get_upload_stmt->rowCount()>0)
            {
                unlink($_SERVER['DOCUMENT_ROOT'].'/ProiectTW/uploads/'.$result_upload['file_reference']);
                $delete_upload_sql="DELETE FROM UPLOADS WHERE upload_id=:upload_id";
                $delete_upload_stmt=DB::getConnection()->prepare($delete_upload_sql);
                $delete_upload_stmt->execute([
                    'upload_id'=>$upload_id
                ]);
                $get_files_sql="SELECT * FROM FILES WHERE fragments_id=:fragments_id";
                $get_files_stmt=DB::getConnection()->prepare($get_files_sql);
                $get_files_stmt->execute([
                    'fragments_id'=>$fragments_id
                ]);
                $file_result=$get_files_stmt->fetch(PDO::FETCH_ASSOC);

                //Logica de stergere pe servicii


                $get_item_id_sql="SELECT item_id FROM FILES WHERE fragments_id=:fragments_id";
                $get_item_id_stmt=DB::getConnection()->prepare($get_item_id_sql);
                $get_item_id_stmt->execute([
                    'fragments_id'=>$fragments_id
                ]);
                $result_id=$get_item_id_stmt->fetch(PDO::FETCH_ASSOC);

                $delete_files_sql="DELETE FROM FILES WHERE fragments_id=:fragments_id";
                $delete_files_stmt=DB::getConnection()->prepare($delete_files_sql);
                $delete_files_stmt->execute([
                    'fragments_id'=>$fragments_id
                ]);

                $delete_items_sql="DELETE FROM ITEMS WHERE item_id=:item_id AND content_type='file'";
                $delete_items_stmt=DB::getConnection()->prepare($delete_items_sql);
                $delete_items_stmt->execute([
                    'item_id'=>$result_id['item_id']
                ]);
            }

        }
        public function statusChangeToSplitting($upload_id)
        {
            $change_status_sql="UPDATE uploads SET status='splitting' WHERE upload_id=:id";
            $change_status_stmt=DB::getConnection()->prepare($change_status_sql);
            $change_status_stmt->execute([
                'id'=>$upload_id
            ]);
        }
       

}
?>