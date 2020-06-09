<?php
    Class VManual
    {
        private $template;
        private $error_msg;

        public function __construct() {
            $this->template = 'smanual.php';
        }

        public function renderManual() {
			ob_start();
			include($this->template);
			$output = ob_get_contents();
			ob_end_clean();
			return $output;
		}
    }
?>