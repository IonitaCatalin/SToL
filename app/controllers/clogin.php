<?php

    Class CLogin extends Controller
    {
        private $_model;

        
        public function __construct()
        {
            $_error_msg_input=null;
            $_error_validation_model=null;
            $this->model=$this->model('mlogin');
            if(isset($_POST['login_request']))
            {
                if(!isset($_POST['username']) || !isset($_POST['password']))
                {
                    $this->_error_msg_input='Please fill in all the required field';
                    $this->_render($this->_error_msg_input,null);
                }
            }
            else
            {
                $this->_render($_error_msg_input,$_error_validation_model);
            }
        }
        public function index()
        {

        }
        private function _render($error_msg_input,$model_error)
        {
            $this->view('login/vlogin');
            $view=new VLogin();
            echo $view->renderView();
            

        }

        
    }

?>