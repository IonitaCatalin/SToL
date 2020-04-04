<?php

	class CSezatoare extends Controller {

		private $model;
		
		public function __construct() {//$actiune, $parametri

			$_SESSION["utilizator"] = "user331"; // quick-fix pana facem sistem de register/login

			$this->model = $this->model('msezatoare');

			// daca anterior s-a apasat vreun buton, actiunea e setata
			$actiune = null;
			if(isset($_POST["actiune"])) 
				$actiune = $_POST["actiune"];

			if ($actiune == "stergeMesaj") {
				$this->stergeMesaj($_POST["id"]);
			}
			if ($actiune == "adaugaMesaj") {
				$this->adaugaMesaj($_SESSION["utilizator"], $_POST["mesaj"]);
			}
			if ($actiune == "salveazaMesaj") {
				$this->salveazaMesaj($_POST["id"], $_POST["mesaj"]);
			}
			if ($actiune == "triggerModificaMesaj") {
				$this->afiseazaMesaje($_POST["id"]);
			}
			else //default cu exceptia atunci cand e setat triggerModifcaMesaj
				$this->afiseazaMesaje(); //pus pe else la triggerModificaMesaj ca sa nu afiseze de 2 ori

		}

		public function index($parametri = null) {
			// nein , metoda default
		}

		private function stergeMesaj($idMesaj) {
			$this->model->stergeMesaj($idMesaj);
		}

		private function adaugaMesaj($utilizator, $mesaj) {
			$this->model->adaugaMesaj($utilizator, $mesaj);
		}

		private function afiseazaMesaje($edit = NULL) {
			$mesaje = $this->model->obtineMesaje();
			$this->view('sezatoare/vsezatoare');
			$view = new VMesaje();
			$view -> incarcaDatele($mesaje);
			echo $view -> oferaVizualizare($edit);
		}

		private function salveazaMesaj($id, $mesaj) {
			$this->model->modificaMesaj($id, $mesaj);
		}
	}

?>