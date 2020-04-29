<?php

class CRegister extends Controller {

	private $model;

	public function __construct() {
		$this->model = $this->model('mregister');
	}

	public function registerUser() {

		$content_type = isset($_SERVER['CONTENT_TYPE']) ? $_SERVER['CONTENT_TYPE'] : '';
        if (stripos($content_type, 'application/json') === false)
        {
            $json = new JsonResponse('error', null, 'Only application/json content-type allowed', 415);
            echo $json->response();
            return;
        }

		$post_data = file_get_contents('php://input');
		$post_array = json_decode($post_data, true);

		if(!is_array($post_array)) {
			$json = new JsonResponse('error', null, 'Malformed request,JSON data object could not be parsed', 400);
			echo $json->response();
			return;
		}

        if( (isset($post_array['email'])==false) || 
        	(isset($post_array['username'])==false) ||
        	(isset($post_array['password'])==false) )
        {
			$json = new JsonResponse('error', null, 'Malformed request, required fields are missing', 400);
			echo $json->response();
        }
        else if($this->checkExistingEmail($post_array['email'])){
			$json = new JsonResponse('error', null, 'The provided email is already in use.', 400);
			echo $json->response();
		}
		else if($this->checkExistingUsername($post_array['username'])) {
			$json = new JsonResponse('error', null, 'The provided username is already in use.', 400);
			echo $json->response();
		}
		else if(strlen($post_array['username']) < 6) {
			$json = new JsonResponse('error', null, 'The provided username is too short.', 400);
			echo $json->response();
		}
		else if(strlen($post_array['password']) < 6) {
			$json = new JsonResponse('error', null, 'The provided password is too short.', 400);
			echo $json->response();	
		}
		else
		{
			$this->model->addAccount($post_array['email'], $post_array['username'], $post_array['password']);
			$json=new JsonResponse('success', null, 'User account successfully created');
			echo $json->response();
        }
	}

	private function checkExistingEmail($email) {
		return $this->model->checkExistingEmail($email);
	}

	private function checkExistingUsername($username) {
		return $this->model->checkExistingUsername($username);
	}
}

?>