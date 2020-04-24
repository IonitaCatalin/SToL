<?php

    Class VProfile
    {
        private $template;
        private $error_msg;

        public function __construct() {
            $this->template = 'sprofile.php';
        }

        public function loadDataIntoView($error_msg) {
            $this->error_msg = $error_msg;
        }

        public function renderView() {
            $error_msg=$this->error_msg;
			ob_start();
			include($this->template);
			$output = ob_get_contents();
            ob_end_clean();
			return $output;
		}
    }
?>