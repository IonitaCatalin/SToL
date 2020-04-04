<?php

class Home extends Controller {
	
	public function index($name = '') {
		
		// se apeleaza functia model din clasa parinte Controller cu parametru 'User' si se returneaza o instanta a clasei User
		$user = $this->model('User');
		$user->name = $name;
		
		//home/index din folderul views
		$this->view('home/index', ['name' => $user->name]);
	}

}