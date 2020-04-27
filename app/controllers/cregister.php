<?php

class CRegister extends Controller {

	private $model;

	public function __construct() {
		$this->model = $this->model('mregister');
		
		if(isset($_POST["submit_register"]))
		{
			if($_POST["email"] == '' || $_POST["username"] == '' || $_POST["password"] == '') {
				$this->error_msg='Please fill in all the fields.';
				$this->render($this->error_msg); // cand nu s-au completat toate datele
			}
			else {
				$this->error_msg = '';

				$email = $_POST["email"];
				$username = $_POST["username"];
				$password = $_POST["password"];

				if($this->checkExistingEmail($email)){
					
				}
				else if($this->checkExistingUsername($username)) {
					
				}
				else if(strlen($username) < 6) {
					
				}
				else if(strlen($password) < 6) {
					
				}
				else {
					
					$this->addUser($_POST["email"], $_POST["username"], $_POST["password"]);
				}
			}
			
		}
		else {
		}
	}

	public function index() {
		// :)
	}

	private function addUser($email, $username, $password) {
		$this->model->addAccount($email, $username, $password);
	}

	private function render($error_msg  = NULL) {
		$this->view('register/vregister');
		$view = new VRegister();
		$view -> loadDataIntoView($error_msg);
		echo $view -> loadView();
	}

	private function checkExistingEmail($email) {
		return $this->model->checkExistingEmail($email);
	}

	private function checkExistingUsername($username) {
		return $this->model->checkExistingUsername($username);
	}
}

?>