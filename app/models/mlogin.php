<?php
    require_once '../app/core/Db.php';
    Class MLogin
    {
        private $login_result;
        public function logInUser($username, $password)
        {
            $check_username = $username;
            $check_password = $password;
            $sql="SELECT 'abc' FROM accounts WHERE username=:username AND password=:password LIMIT 1";
            $login_stmt=DB::getConnection()->prepare($sql);
            $login_stmt->bindValue(':username',$check_username);
            $login_stmt->bindValue(':password',$check_password);
            $login_stmt->execute();
            if($login_stmt->rowCount()>0)
            {
                session_start();
                header("Location:/ProiectTW/public/cfiles/");
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