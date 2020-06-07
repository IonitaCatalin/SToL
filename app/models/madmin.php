<?php

class MAdmin
{
	public function createCSVFile($user_id, $users_array, $file_id)
	{
		$path = $_SERVER['DOCUMENT_ROOT'].'/ProiectTW/downloads/'. $file_id;

		// --- algoritm pt construit csv-ul...

		// folosirea tabloului users pt a obtine si scrie datele in fisier
		// users contine nume de utilizatori..
		
		// ----------------------
		for($index=0;$index<count($users_array);$index++)
		{
			$get_files_sql="SELECT * FROM ACCOUNTS JOIN ITEMS ON ACCOUNTS.ID=ITEMS.USER_ID JOIN FILES ON FILES.ITEM_ID=ITEMS.ITEM_ID JOIN FRAGMENTS ON FRAGMENTS.FRAGMENTS_ID=FILES.FRAGMENTS_ID WHERE ACCOUNTS.USERNAME=:username";
			$get_files_stmt=DB::getConnection()->prepare($get_files_sql);
			$get_files_stmt->execute([
				'username'=>$users_array[$index]
			]);
			$result_array=$get_files_stmt->fetchAll();
			if(!is_null($result_array))
			{
				for($iterator=0;$iterator<count($result_array);$iterator++)
				{
					$csv_string="{$users_array[$index]};{$result_array[$iterator]['name']};{$result_array[$iterator]['item_id']};{$result_array[$iterator]['folder_id']}\n";
					file_put_contents($path,$csv_string,FILE_APPEND);
				}
			}
			
		}
	}

 

	public function downloadCSVFile($download_id)
	{
		$path = $_SERVER['DOCUMENT_ROOT'].'/ProiectTW/downloads/'. $download_id;
		$file_name = "data.csv";

		// trimiterea propriu zisa a fisierului catre client
		$chunk_size = 1024 * 1024 * 8; // unitati de cate 8MB
		$fd = null;
		if (file_exists($path))
		{
			$fd = fopen($path, "rb");
			header('Content-Description: File Transfer');
			header('Content-Type: application/octet-stream');
			header('Content-Disposition: attachment; filename="' . $file_name . '"');
			header('Expires: 0');
			header('Cache-Control: must-revalidate');
			header('Pragma: public');
			header('Content-Length: ' . filesize($path));

			while(!feof($fd)) {
				$buffer = fread($fd, $chunk_size);
				echo $buffer;
				ob_flush();
				flush();
			}

			fclose($fd);
			unlink($path); // stergere fisier dupa trimitere
		}
		else {
			throw new InvalidDownloadId();
		}

	}

	public function getUsersData($user_id)
	{

		$result_array = array();
		$count = 0;

		$list_users_sql = "SELECT id, username, email FROM accounts";
		$list_users_stmt = DB::getConnection()->prepare($list_users_sql);
		$list_users_stmt->execute();
		if($list_users_stmt->rowCount()>0)
		{
			while($row = $list_users_stmt->fetch(PDO::FETCH_ASSOC)) {
				$result_array[$count]["username"] = $row["username"];
				$result_array[$count]["email"] = $row["email"];
				$result_array[$count]["number"] = self::getNumberOfFiles($row["id"]);
				$services = self::getAvailableServices($row["id"]);
				$result_array[$count]["onedrive"] = $services["onedrive"];
				$result_array[$count]["dropbox"] = $services["dropbox"];
				$result_array[$count]["googledrive"] = $services["googledrive"];
				$count ++ ;
			}
		}

		return $result_array;
	}

	public function getNumberOfFiles($user_id)
	{
		$get_files_no_sql='SELECT COUNT(*) FROM files f JOIN items i WHERE f.item_id=i.item_id AND user_id = :user_id';
		$get_files_no_stmt=DB::getConnection()->prepare($get_files_no_sql);
		$get_files_no_stmt->execute([
			'user_id'=>$user_id
		]);
		if($get_files_no_stmt->rowCount()>0) {
			$result_array = $get_files_no_stmt->fetch(PDO::FETCH_ASSOC);
			return $result_array["COUNT(*)"]; // :)) am uitat cum se face
		}
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
	public function getStatus()
	{
		$get_services="SELECT * FROM ALLOWED";
		$get_services=DB::getConnection()->prepare($get_services);
		$get_services->execute();
		$result_array=$get_services->fetchAll();
		$return_array=array();
		for($iterator=0;$iterator<count($result_array);$iterator++)
		{
			if($result_array[$iterator]['allowed']==1)
				$return_array[$result_array[$iterator]['service']]=true;
			else 
				$return_array[$result_array[$iterator]['service']]=false;
			
		}
		return $return_array;
	}
	public function updateServiceAllow($service,$value)
	{
		$update_service_sql="UPDATE ALLOWED SET allowed=:value WHERE service=:service";
		$update_service_stmt=DB::getConnection()->prepare($update_service_sql);
		switch($service)
		{
			case 'onedrive':
			{
				$bool_value= ($value)?1:0;
				$update_service_stmt->execute([
					'service'=>'onedrive',
					'value'=>$bool_value
				]);
				break;
			}
			case 'dropbox':
				{
					$bool_value= ($value)?1:0;
					$update_service_stmt->execute([
						'service'=>'dropbox',
						'value'=>$bool_value
					]);
					break;
				}
			case 'googledrive':
			{
				$bool_value= ($value)?1:0;
				$update_service_stmt->execute([
					'service'=>'googledrive',
					'value'=>$bool_value
					]);
				break;
			}
		}
		

	}


}

?>