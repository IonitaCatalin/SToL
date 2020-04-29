<?php

class Controller {

	public function model($model) {
		
		require_once $_SERVER["DOCUMENT_ROOT"].'/ProiectTW/app/models/' . $model . '.php';
		return new $model();
	}
	public function view($view) {

		require_once $_SERVER["DOCUMENT_ROOT"].'/ProiectTW/app/views/'.$view.'.php';
	}
}