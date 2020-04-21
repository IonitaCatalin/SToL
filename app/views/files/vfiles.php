<?php

    Class VFiles
    {
        private $template;
        private $data;

        public function __construct() {
            $this->template = 'sfiles.php';
        }

        public function loadDataIntoView($data = []) {
            $this->data = $data;
        }

        public function renderView() {
            $data = $this->data;
			ob_start();
			include($this->template);
			$output = ob_get_contents();
			ob_end_clean();
			return $output;
		}
    }
?>