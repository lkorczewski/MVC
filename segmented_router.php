<?php

namespace MVC;

class Segmented_Router extends Router implements Router_Interface {
	
	protected $services;
	
	//-------------------------------------------------
	// making link out of components
	//-------------------------------------------------
	// ERROR!
	// controller = '' or action = '' results
	// in wrong behaviour because PATH_INFO reduces
	// multiple slashes
	
	function make_link($controller = '', $action = '', array $parameters = []){
		$segments = [];
		
		if($controller || $action || $parameters){
			$segments[] = $controller;
		}
		
		if($action || $parameters){
			$segments[] = $action;
		}
		
		if($parameters){
			$segments = array_merge($segments, $parameters);
		}
		
		$path = $this->get_script_base() . '/' . implode('/', $segments);
		
		return $path;
	}
	
	//-------------------------------------------------
	// parsing url for segments
	//-------------------------------------------------
	// - $_SERVER - bad for testing?
	// - $_SERVER['PATH_INFO'] - reduces multiple slashes
	//   may be circumvented with substr(PHP_SELF, strlen(SCRIPT_NAME))!
	//-------------------------------------------------
	
	protected function parse(){
		
		if(isset($_SERVER['PATH_INFO'])){
			
			$parts = explode('/', $_SERVER['PATH_INFO']);
			
			if(isset($parts[1])){
				$this->controller = $parts[1];
			}
			
			if(isset($parts[2])){
				$this->action = $parts[2];
			}
			
			if(isset($parts[3])){
				$this->parameters  = array_splice($parts, 3);
			}
			
		}
		
	}
	
	//-------------------------------------------------
	// getting script base
	//-------------------------------------------------
	
	protected function get_script_base(){
		// removing "/index.php"
		return substr($_SERVER['SCRIPT_NAME'], 0, -10);
	}
	
}

