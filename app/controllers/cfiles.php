<?php
require_once '../app/core/Onedrive/Onedrive.php';
require_once '../app/core/Onedrive/OnedriveException.php';
require_once '../app/core/GDrive/Googledrive.php';
require_once '../app/core/Dropbox/Dropbox.php';
class CFiles extends Controller {

	private $model;
	private OnedriveService $onedrive;

	public function __construct() {
		$this->model = $this->model('mfiles');	
		$this->onedrive=new OneDriveService;
	}

	public function index() {
		session_start();
	}

	// functii precum getFilesList iar datele sa ajunga in view ?

	//Un mic endpoint de test pentru a testa libraria de Onedrive sigur aceasta v-a disparea in productie :-)
	public function testOneDrive()
	{
		session_start();
		try
		{
			$this->onedrive->uploadFile($this->model->getAccessToken('onedrive',$_SESSION['USER_ID']),'D:\XAMPP\htdocs\ProiectTW\upload_test\track.mp3');
		}
		catch(OneDriveNotEnoughtSpaceException $exception)
		{
			echo 'Nu exista spatiu frate';
		}
		catch(OneDriveUploadFailedException $exception)
		{
			echo 'Ceva nu a mers bine';
		}
		
	}

}


?>