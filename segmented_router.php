<?php

namespace MVC;

class Segmented_Router implements Router_Interface {
	
	const STATE_UNPARSED  = 0;
	const STATE_PARSED    = 1;
	
	protected $state = self::STATE_UNPARSED;
	
	protected $services;
	
	protected $controller  = 'home';
	protected $action      = 'index';
	protected $parameters  = [];
	
	//-------------------------------------------------
	// constructor
	//-------------------------------------------------
	
	function __construct(Service_Container_Interface $services){
		
		// TODO: config access should be lazy
		if($services->has('config')){
			
			$config = $services->get('config');
			
			if($config->has('default_controller')){
				$controller = $config->get('default_controller');
			}
			
			if($config->has('default_action')){
				$action = $config->get('default_action');
			}
		}
		
	}
	
	//-------------------------------------------------
	// getting route components // TODO: terminology!
	//-------------------------------------------------
	
	function get_controller(){
		$this->parse_url();
		return $this->controller;
	}
	
	function get_action(){
		$this->parse_url();
		return $this->action;
	}
	
	function get_parameters(){
		$this->parse_url();
		return $this->parameters;
	}
	
	function get_parameter($index){
		$this->parse_url();
		return $this->parameters[$index];
	}
	
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
	
	protected function parse_url(){
		
		if($this->state == self::STATE_PARSED){
			return true;
		}
		
		echo '<pre>';
		echo var_dump($_SERVER);
		echo '</pre>';
		
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
		
		$this->state = self::STATE_PARSED;
		
		return true;
	}
	
	protected function get_script_base(){
		// removing "/index.php"
		return substr($_SERVER['SCRIPT_NAME'], 0, -10);
	}
	
}

