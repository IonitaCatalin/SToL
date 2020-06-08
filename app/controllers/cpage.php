<?php

    class CPage extends Controller
    {
        
        public function __construct()
        {
            
        }
        public function renderLogin()
        {
            $this->view('login/vlogin');
            $login_view=new VLogin();
            echo $login_view->renderView();
        }
        public function renderProfile()
        {
            $this->view('profile/vprofile');
            $profile_view=new VProfile();
            echo $profile_view->renderView();
        }
        public function renderFiles()
        {
            $this->view('files/vfiles');
            $files_view=new VFiles();
            echo $files_view->renderView();
        }
        public function renderRegister()
        {
            $this->view('register/vregister');
            $register_view=new VRegister();
            echo $register_view->renderView();
        }
        public function renderAdmin()
        {
            $this->view('admin/vadmin');
            $admin_view=new VAdmin();
            echo $admin_view->renderView();
        }
    }
?>