<?php

class GoogledriveException extends Exception {

	public $message = '';
	public $code = '';
	public $previous = '';

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

class GoogledriveInvalidateAccessTokenException extends GoogledriveException {
    public function __toString() {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    }
}

class GoogledriveRenewAccessTokenException extends GoogledriveException {
    public function __toString() {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    }
}

class GoogledriveListAllFilesException extends GoogledriveException {
    public function __toString() {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    }
}

class GoogledriveGetFileMetadataException extends GoogledriveException {
    public function __toString() {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    }
}

class GoogledriveDownloadFileByIdException extends GoogledriveException {
    public function __toString() {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    }
}

class GoogledriveUploadFileException extends GoogledriveException {
    public function __toString() {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    }
}

?>