<?php
	class VMesaje {
		private $sablon;
		private $mesaje;

		public function __construct() {
			$this->sablon = 'ssezatoare.tpl';
		}

		public function incarcaDatele($msg) {
			$this->mesaje = $msg;
			//print_r($mesaje);
		}

		public function oferaVizualizare($edit = NULL) {
			$msg = $this->mesaje;
			ob_start();
			include($this->sablon);
			$output = ob_get_contents();
			ob_end_clean();
			return $output;
		}
	}
?>