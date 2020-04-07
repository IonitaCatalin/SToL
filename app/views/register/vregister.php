<?php
	class VRegister {
		private $template;
		private $input_msg;

		public function __construct() {
			$this->template = 'sregister.php';
		}

		public function loadDataIntoView($msg) {
			$this->input_msg = $msg;
		}

		public function renderView() {
			$input_msg = $this->input_msg; //variabila locala ia valoarea celei globale
			ob_start();
			include($this->template);
			$output = ob_get_contents();
			ob_end_clean();
			return $output;
		}
	}
?>