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
		if(isset($_SESSION['USER_ID'])) {
			$this->render();
		}
		else {
			header('Location:'.'http://localhost/ProiectTW/public/cprofile');
		}
	}

	// functii precum getFilesList iar datele sa ajunga in view ?

	private function render($data = []) {
		$this->view('files/vfiles');
		$view = new VFiles();
		$view -> loadDataIntoView($data);
		echo $view -> renderView();
	}
	//Un mic endpoint de test pentru a testa libraria de Onedrive sigur aceasta v-a disparea in productie :-)
	public function testOneDrive()
	{
		session_start();
		//$this->onedrive->uploadFile($this->model->getAccessToken('onedrive',$_SESSION['USER_ID']),'D:\XAMPP\htdocs\ProiectTW\upload_test\iverilog.exe');
		try
		{
			$this->onedrive->uploadFile($this->model->getAccessToken('onedrive',$_SESSION['USER_ID']),'D:\XAMPP\htdocs\ProiectTW\upload_test\sample2.bin');
		}
		catch(OneDriveNotEnoughtSpaceException $exception)
		{
			echo 'Nu exista spatiu frate';
		}
		
	}

}


?>