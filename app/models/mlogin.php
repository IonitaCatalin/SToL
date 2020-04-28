<?php
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
                    return $result['id'];
                }   
                else
                {
                    return null;
                }
            } catch (PDOException $e) {
                return false;
              }
           
        }

    }
?>