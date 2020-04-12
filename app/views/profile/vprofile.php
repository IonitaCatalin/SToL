<?php

    Class VProfile
    {
        private $template;
        private $data;

        public function __construct() {
            $this->template = 'sprofile.php';
        }

        public function loadDataIntoView($data = []) {
            $this->data = $data;
        }

        public function renderView() {
            //$nume_var = $this->data["ceva"]; ??
			ob_start();
			include($this->template);
			$output = ob_get_contents();
			ob_end_clean();
			return $output;
		}
    }
?>