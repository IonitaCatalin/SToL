<?php
    Class VLogin
    {
        private $template;
        private $error_input;
        private $error_model;
        public function __construct() {
            $this->template = 'slogin.php';
        }
        public function loadDataIntoView($error_input,$error_model)
        {
            $this->error_input=$error_input;
            $this->error_model=$error_model;
        }
        public function renderView() {
            $error_input=$this->error_input;
            $error_model=$this->error_model;
			ob_start();
			include($this->template);
			$output = ob_get_contents();
			ob_end_clean();
			return $output;
		}
    }
?>