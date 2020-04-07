<?php

    Class CLogin extends Controller
    {
        private $model;      
        private $error_msg_input;
        private $error_msg_model;
        public function __construct()
        {
           
            $this->model=$this->model('mlogin');
            if(isset($_POST['submit_login']))
            {
                if($_POST['username']=='' || $_POST['password']=='')
                {
                    $this->error_msg_input='Please fill in all the required field';
                    $this->render($this->error_msg_input);
                    
                }
                else
                {
                    $email=$_POST['username'];
                    $password=$_POST['password'];
                    $login_status=$this->logInUser($email,$password);
                    if(!$login_status)
                    {
                        $this->error_msg_model='Wrong username or password';
                    }
                    $this->render(null,$this->error_msg_model);

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
        private function render($error_msg_input=null,$error_model=null)
        {
            $this->view('login/vlogin');
            $view=new VLogin();
            $view->loadDataIntoView($error_msg_input,$error_model);
            echo $view->renderView();
        }
        private function logInUser($username,$password)
        {
           return $this->model->logInUser($username,$password);
        }
    }
?>