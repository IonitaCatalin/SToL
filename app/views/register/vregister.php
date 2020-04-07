<?php
	class VRegister {
		private $template;
		private $error_msg;

		public function __construct() {
			$this->template = 'sregister.php';
		}

		public function loadDataIntoView($msg) {
			$this->error_msg= $msg;
		}

		public function loadView() {
			$error_msg = $this->error_msg; 
			ob_start();
			include($this->template);
			$output = ob_get_contents();
			ob_end_clean();
			return $output;
		}
	}
?>