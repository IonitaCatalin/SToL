<?php

class App {
	protected $controller = 'home'; // default

	protected $method = 'index'; // default

	protected $params = [];

	public function __construct() {

		//print_r($this->parseUrl());
		$url = $this->parseUrl();	// array cu elemente din url separate de '/'

		// daca se specifica un controller
		if(!is_null($url)) {
			if(file_exists('../app/controllers/' . $url[0] . '.php')) {
				$this->controller = $url[0]; //il setam
				unset($url[0]);	//'distrugem' pozitia 0 din array
			}
		}

		// includem controller-ul (setat sau daca nu, pe cel default)
		require_once '../app/controllers/' . $this->controller . '.php';

		// ex: controller devine din string-ul 'home' o instanta a clasei Home inclusa mai sus
		$this->controller = new $this->controller;

		// daca se specifica si o metoda
		if(isset($url[1])) {
			// daca exista metoda in controller(care e acum instanta unei clase)
			if(method_exists($this->controller, $url[1])) {
				$this->method = $url[1];
				unset($url[1]);
			}
		}

		// luam din url si parametrii ramasi
		$this->params = $url ? array_values($url) : [];
		//print_r($this->params);

		// metoda primeste controller si method ca un array, si parametrii ca al doilea argument. Are roul de a face apelul
		call_user_func_array([$this->controller, $this->method], $this->params);
	}

	public function parseUrl() {
		if(isset($_GET['url'])) {
			//echo $_GET['url'];
			// 1. rtrim: eliminare '/' final pt a nu fi considerat ca urmeaza un alt element
			// 2.filter_var: elimina caractere 'ilegale', pare inutil in cazul nostru
			// 3.explode sparge string-ul dupa caracterul '/'. Ideea e sa obtinem cuvintele cheie sa identificam /controller/method/parameter1/parameter2 Identificam parametrii pt a fi trimisi la controllere, views, etc...
			return $url = explode('/', filter_var(rtrim($_GET['url'], '/'), FILTER_SANITIZE_URL));
		}
	}
}