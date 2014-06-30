<?php

namespace MVC;

// controller needs access to:
//  - model
//  - view ($this->application->
//  - redirecting (return )

abstract class Controller {
	
	protected $application;
	
	function __construct(Application $application){
		$this->application = $application;
	}
	
	function __call($method, $parameters){
		
		// some methods with useful words (like 'list') cannot be defined,
		// but can be called, it can be circumvented by accessing them
		// with __call magic method and defining them with "_action" suffix
		
		$alternative_method = $method . '_action';
		if(method_exists($this, $alternative_method)){
			return call_user_func([$this, $alternative_method], $parameters);
		}
		
		return false;
	}
	
	protected function get($key){
		return $this->application->get($key);
	}
	
	/*
	protected function __get($key){
		return $this->get($key);
	}
	*/
}
