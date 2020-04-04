<?php

class Controller {

	public function model($model) {
		// include codul modelului dorit si apoi returneaza o instanta
		require_once '../app/models/' . $model . '.php';
		return new $model();
	}

	public function view($view, $data = []) {

		require_once '../app/views/' . $view . '.php';
	}

}