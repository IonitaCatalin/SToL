<?php

    Class CLogin extends Controller
    {
        private $model;
        private $error_msg;

        public function __construct()
        {
           
            $this->model=$this->model('mlogin');
            if(isset($_POST['submit_login']))
            {
                if($_POST['username'] == '' || $_POST['password'] == '')
                {
                    $this->error_msg = 'Please fill in all the required fields';
                    $this->render($this->error_msg); 
                }
                else
                {
                    $this->error_msg = null;
                    $username=$_POST['username'];
                    $password=$_POST['password'];
                    $login_status=$this->logInUser($username,$password);
                    if(!$login_status)
                    {
                        $this->error_msg = 'Wrong username or password';
                    }
                    $this->render($this->error_msg);
                }
            }
            else
            {
                $this->render();
            }
        }

        public function index()
        {
           
        }
        private function render($error_msg = NULL)
        {
            $this->view('login/vlogin');
            $view=new VLogin();
            $view->loadDataIntoView($error_msg);
            echo $view->renderView();
        }
        private function logInUser($username,$password)
        {
           return $this->model->logInUser($username,$password);
        }
    }
?>