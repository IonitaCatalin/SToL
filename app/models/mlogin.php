<?php
    require_once '../app/core/Db.php';
    Class MLogin
    {
        private $login_result;
        public function logInUser($email,$password)
        {
            $check_email=$email;
            $check_password=$password;
            $sql="SELECT id,email,password FROM accounts WHERE email=:email AND password=:password LIMIT 1";
            $login_stmt=DB::getConnection()->prepare($sql);
            $login_stmt->bindValue(':email',$check_email);
            $login_stmt->bindValue(':password',$check_password);
            $login_stmt->execute();
            if($login_stmt->rowCount()>0)
            {
                session_start();
                header("Location:/public/cfiles/");
                die();
                return true;
            }   
            else
            {
                return false;
            }
        }
    }
?>