<?php

namespace MVC;

//-------------------------------------------------
// TODO:
//  - default values
//  - parameters, not value
//-------------------------------------------------

class Parameter_Router implements Router_Interface {
	const STATE_NOT_PARSED  = 0;
	const STATE_PARSED      = 1;
	
	protected $state = self::STATE_NOT_PARSED;
	
	protected $application;
	
	protected $controller  = 'home';
	protected $action      = 'index';
	protected $value;
	
	//-------------------------------------------------
	// making link
	//-------------------------------------------------
	// it rather belongs to some html layer
	
	function make_link($controller = null, $action = null, $value = null){
		$parameters = [];
		if($controller){
			$parameters['c'] = $controller;
		}
		if($action){
			$parameters['a'] = $action;
		}
		if($value){
			$parameters['v'] = $value;
		}
		return '?' . implode('&', $parameters);
	}
	
	//-------------------------------------------------
	// obtaining parameters
	//-------------------------------------------------
	
	function get_controller(){
		$this->parse();
		return $this->controller;
	}
	
	function get_action(){
		$this->parse();
		return $this->action;
	}
	
	function get_parameters(){
		$this->parse();
		return array($this->value);
	}
	
	//-------------------------------------------------
	// parsing request
	//-------------------------------------------------
	// TODO: bad naming: "parse" suggests the parsing
	// takes place each time the method is called
	// which is not true
	
	protected function parse(){
		if($this->state == self::STATE_NOT_PARSED){
			if(isset($_GET['c'])){
				$this->controller = $_GET['c'];
			}
			if(isset($_GET['a'])){
				$this->action = $_GET['a'];
			}
			if(isset($_GET['v'])){
				$this->value = $_GET['v'];
			}
			
			$this->state == self::STATE_PARSED;
		}
	}
	
}
