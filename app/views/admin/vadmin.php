<?php

    Class VAdmin
    {
        private $template;
        private $error_msg;

        public function __construct() {
            $this->template = 'sadmin.php';
        }

        public function renderView() {
			ob_start();
			include($this->template);
			$output = ob_get_contents();
            ob_end_clean();
			return $output;
		}
    }
?>