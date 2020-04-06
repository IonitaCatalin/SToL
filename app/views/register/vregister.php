<?php
	class VRegister {
		private $template;
		private $valid_input;

		public function __construct() {
			$this->template = 'sregister.php';
		}

		public function loadDataIntoView($msg) {
			$this->valid_input = $msg;
			//print_r($msg);
		}

		public function offerView() {
			$valid_input = $this->valid_input; //variabila locala ia valoarea celei globale
			ob_start();
			include($this->template);
			$output = ob_get_contents();
			ob_end_clean();
			return $output;
		}
	}
?>