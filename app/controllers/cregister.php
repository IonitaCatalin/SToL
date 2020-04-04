<?php

class CRegister extends Controller {

	private $model;

	public function __construct() {
		//echo "hello from register";

		$this->model = $this->model('mregister');

		$actiune = null;
		if(isset($_POST["actiune"])) // setat doar daca am apasat o data butonul register
			$actiune = $_POST["actiune"];

		if ($actiune == "register") {
			if( $_POST["email"] == '' || $_POST["username"] == '' || $_POST["password"] == '') {
				$this->afiseazaPagina('incomplet'); // cand nu s-au completat toate datele
			}
			else {
				$this->adaugaUtilizator($_POST["email"], $_POST["username"], $_POST["password"]);
				$this->afiseazaPagina('succes');
			}
		} 
		else
			$this->afiseazaPagina();
	}

	public function index() {
		// nein again
	}

	private function adaugaUtilizator($email, $username, $password) {
		$this->model->addAccount($email, $username, $password);
	}

	private function afiseazaPagina($inputValid = NULL) {
		$this->view('register/vregister');
		$view = new VRegister();
		$view -> incarcaDatele($inputValid);
		echo $view -> oferaVizualizare();
	}
}

?>