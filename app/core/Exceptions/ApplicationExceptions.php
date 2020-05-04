<?php
    class ApplicationExceptions extends Exception
    {
    public function __construct($message=null, $code = 0, Exception $previous = null) {
        $this->$message=$message;
        $this->$code=$code;
        $this->$previous=$previous;
        parent::__construct($message, $code, $previous);
    }

    public function __toString() {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    }

}

class UsernameTakenException extends ApplicationExceptions
{
    public function __toString() {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    }
}

class IncorrectPasswordException extends ApplicationExceptions
{
    public function __toString() {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    }
}
class InvalidItemId extends ApplicationExceptions
{
    public function __toString() {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    }
}
class InvalidItemParentId extends ApplicationExceptions
{
    public function __toString() {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    }
}
class ItemNameTaken extends ApplicationExceptions
{
    public function __toString() {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    }
}
class MoveInvalidNameAndType extends ApplicationExceptions
{
    public function __toString() {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    }
}

?>