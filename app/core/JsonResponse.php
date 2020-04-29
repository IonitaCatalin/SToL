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
    private $httpcode=null;

    public function __construct($status, $data, $message,$httpcode=200) {
        header('Content-Type: application/json');
        $this->status = $status;
        $this->data = $data;
        $this->message = $message;
        $this->httpcode=$httpcode;
    
    }

    public function response() {

        http_response_code($this->httpcode);
        
        if(is_null($this->data))
        {   
            $response_array = array(
                'status' => $this->status,
                'message' => $this->message
            );
        }   
        else
        { 
            $response_array = array(
                'status' => $this->status,
                 'data'=>$this->data,
                'message' => $this->message
            );
        }
        return json_encode($response_array);
    }

} 


?>