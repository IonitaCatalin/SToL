<?php

class CSession
{
    public static function isUserAuthorized()
    {
        session_start();
        if(isset($_SESSION['USER_ID']))
        {
            return true;
        }
        else
        {
            return false;
        }
    }
}
?>