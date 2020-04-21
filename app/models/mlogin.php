<?php
    require_once '../app/core/Db.php';
    Class MLogin
    {
        private $login_result;
        public function logInUser($username, $password)
        {
            $check_username = $username;
            $check_password = $password;
            $sql="SELECT id FROM accounts WHERE username=:username AND password=:password LIMIT 1";
            try {
                $login_stmt=DB::getConnection()->prepare($sql);
                $login_stmt->bindValue(':username',$check_username);
                $login_stmt->bindValue(':password',$check_password);
                $login_stmt->execute();
                $result = $login_stmt->fetch(PDO::FETCH_ASSOC);
                if($login_stmt->rowCount()>0)
                {
                    session_start();
                    $_SESSION['USER_ID']=$result['id'];
                    header("Location:/ProiectTW/public/cfiles");
                    return true;
                }   
                else
                {
                    return false;
                }
            } catch (PDOException $e) {
                return false;
              }
           
        }

    }
?>