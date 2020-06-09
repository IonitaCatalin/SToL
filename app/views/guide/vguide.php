<?php
    Class VGuide
    {
        private $template;
        private $error_msg;

        public function __construct() {
            $this->template = 'sguide.php';
        }

        public function renderGuide() {
			ob_start();
			include($this->template);
			$output = ob_get_contents();
			ob_end_clean();
			return $output;
		}
    }
?>