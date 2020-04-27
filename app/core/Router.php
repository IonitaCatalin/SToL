<?php

require_once('Route.php');
require_once('JsonResponse.php');

class Router
{

	private $routes;
	private $matching_routes;
	private $default_route;
	
	function __construct()
	{
		/*
			Array ce va contine toate rutele disponibile
		*/
		$this->routes = array();

		/*
			Array ce va contine toate rutele pe care le consideram a fi a
		*/
		$this->matching_routes = array();

		/*
			Ruta default pentru routerul nostru
		*/
		$this->default_route = NULL;
	}

	public function addRoute() {
		$args = func_get_args();
		array_push($this->routes, new Route($args));
	}

	public function setDefaultRoute($route) {
		$this->default_route = $route;
	}

	public function run($method, $URI) {
		//In rutele declarate anterior pentru routerul nostru incercam sa o gasim pe cea din URL
		$this->findMatchingPattern($this->routes, $URI);
		if (count($this->matching_routes) == 0) {
			if ( !is_null($this->matching_routes) )
			{
				$json=new JsonResponse('error',null,'Unkown endpoint',404);
				echo $json->response();
			}

		} else {
			$valid_route=null;
			foreach ($this->matching_routes as $route) {
				if($route->methodMatches($method)==true)
				{
					$valid_route=$route;
				}
			}
				if(!is_null($valid_route))
				{
					$valid_route->run();
				}
				else
				{
					$json=new JsonResponse('error',null,'Method is not allowed on this endpoint',405);
					echo $json->response();
				}
			}

	}

	private function findMatchingMethod($routes, $method) {
		foreach ($routes as $route) {
			if ( $route->methodMatches($method) )
				array_push($this->matching_routes, $route);
		}
	}

	private function findMatchingPattern($routes, $URI) {
		$this->matching_routes = array();
		foreach ($routes as $route) {
			if ($route->patternMatches($URI))
				array_push($this->matching_routes, $route);
		}
	}

}