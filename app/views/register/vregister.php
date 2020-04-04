<?php
	class VRegister {
		private $sablon;
		private $inputValid;

		public function __construct() {
			$this->sablon = 'sregister.tpl';
		}

		public function incarcaDatele($msg) {
			$this->inputValid = $msg;
			//print_r($msg);
		}

		public function oferaVizualizare() {
			$inputValid = $this->inputValid; //variabila locala ia valoarea celei globale
			ob_start();
			include($this->sablon);
			$output = ob_get_contents();
			ob_end_clean();
			return $output;
		}
	}
?>