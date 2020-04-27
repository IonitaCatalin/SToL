<?php

    Class CLogin extends Controller
    {
        private $model;

        public function __construct()
        {
           
            $this->model=$this->model('mlogin');
            if(isset($_POST['submit_login']))
            {
                if($_POST['username'] == '' || $_POST['password'] == '')
                {
                    
                }
                else
                {
                    $this->error_msg = null;
                    $username=$_POST['username'];
                    $password=$_POST['password'];
                    $login_status=$this->logInUser($username,$password);
                    if(!$login_status)
                    {
                        // $this->error_msg = 'Wrong username or password';
                    }
                }
            }
            else
            {
                
            }
        }

        public function index()
        {
           
        }
        private function logInUser($username,$password)
        {
           return $this->model->logInUser($username,$password);
        }
    }
?>