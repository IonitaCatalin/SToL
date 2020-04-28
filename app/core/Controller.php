<?php

class Controller {

	public function model($model) {
		
		require_once $_SERVER["DOCUMENT_ROOT"].'/ProiectTW/app/models/' . $model . '.php';
		return new $model();
	}
}