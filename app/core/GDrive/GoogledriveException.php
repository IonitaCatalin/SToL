<?php

class GoogledriveException extends Exception {

	public $message = '';
	public $code = '';
	public $previous = '';
    public $path = '';

    public function __construct($path, $message, $code = 0, Exception $previous = null) {
        $this->path = $path;
        $this->message = $message;
        $this->code = $code;
        $this->previous = $previous;
        parent::__construct($message, $code, $previous);
    }

    public function __toString() {
        return __CLASS__ . ": [{$this->code}]: {$this->message}";
    }

}

class GoogledriveAuthException extends GoogledriveException {
    public function __toString() {
        return __CLASS__ . $this->path .": [{$this->code}]: {$this->message}";
    }
}

class GoogledriveInvalidateAccessTokenException extends GoogledriveException {
    public function __toString() {
        return __CLASS__ . $this->path .": [{$this->code}]: {$this->message}";
    }
}

class GoogledriveRenewAccessTokenException extends GoogledriveException {
    public function __toString() {
        return __CLASS__ . $this->path .": [{$this->code}]: {$this->message}";
    }
}

class GoogledriveListAllFilesException extends GoogledriveException {
    public function __toString() {
        return __CLASS__ . $this->path .": [{$this->code}]: {$this->message}";
    }
}

class GoogledriveGetFileMetadataException extends GoogledriveException {
    public function __toString() {
        return __CLASS__ . $this->path .": [{$this->code}]: {$this->message}";
    }
}

class GoogledriveDownloadFileByIdException extends GoogledriveException {
    public function __toString() {
        return __CLASS__ . $this->path .": [{$this->code}]: {$this->message}";
    }
}

class GoogledriveUploadFileException extends GoogledriveException {
    public function __toString() {
        return __CLASS__ . $this->path .": [{$this->code}]: {$this->message}";
    }
}

class GoogledriveStorageQuotaException extends GoogledriveException {
    public function __toString() {
        return __CLASS__ . $this->path .": [{$this->code}]: {$this->message}";
    }
}

class GoogledriveNotEnoughStorageSpaceException extends GoogledriveException {
    public function __toString() {
        return __CLASS__ . $this->path .": [{$this->code}]: {$this->message}";
    }
}

class GoogledriveDeleteException extends GoogledriveException {
    public function __toString() {
        return __CLASS__ . $this->path .": [{$this->code}]: {$this->message}";
    }
}

?>