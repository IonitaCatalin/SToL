<?php
    
/*
    Cream o clasa particulara JsonResponse pentru a pastra acelasi format JSON
    In general formatul este:
	status='error' sau 'succes'
	data=array de date
	message='mesaj de eroare in caz de eroare'
*/

class JsonResponse {

    private $status = 'success';
    private $data = array();
    private $message = null;

    public function __construct($status, $data, $message) {
        header('Content-Type: application/json');
        $this->status = $status;
        $this->data = $data;
        $this->message = $message;
    }

    public function response() {
        $response_array = array(
            'status' => $this->status,
            'data' => $this->data,
            'message' => $this->message
        );
        return json_encode($response_array);
    }

} 


?>