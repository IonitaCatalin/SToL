<?php
        /*
			    Cream o clasa particulara JsonResponse pentru a pastra acelasi format JSON
			    In general formatul este:
				status='error' sau 'succes'
				data=array de date
				message='mesaj de eroare in caz de eroare'
		*/
    Class JsonResponse
    {
        private $status='success';
        private $data_payload=array();
        private $message=null;
        public function __construct($status,$data_payload,$message)
        {
            header('Content-Type: application/json');
            $this->status=$status;
            $this->data_payload=$data_payload;
            $this->message=$message;
        }
        public function response()
        {
            $response_array=array(
                'status'=>$this->status,
                'data'=>$this->data_payload,
                'message'=>$this->message
            );
            return json_encode($response_array);
        }
    } 


?>