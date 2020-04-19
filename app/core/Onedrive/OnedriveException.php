<?php

class OneDriveException extends Exception {

    public function __construct($message, $code = 0, Exception $previous = null) {
        $this->$message = $message;
        $this->$code = $code;
        $this->$previous = $previous;
        parent::__construct($message, $code, $previous);
    }

    public function __toString() {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    }

}

<<<<<<< HEAD:app/core/Onedrive/OnedriveException.php
class OneDriveAuthException extends OneDriveException
{
    
}
=======
>>>>>>> eb72603992d1bd687d73c9ea99f6a2eb5b2f1d55:app/core/OnedriveException.php
?>