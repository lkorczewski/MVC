<?php

namespace MVC;

use \Config\Config_Interface;

class Application {
	
	protected $services;
	
	// constructors
	
	function __construct(Config_Interface $config = null){
		
		// TODO: remove from hardcoding
		$this->services = new \MVC\Service_Container([
			'router'      => function() { return new \MVC\Segmented_Router($this->services); },
			'dispatcher'  => function() { return new \MVC\Dispatcher($this); },
			'view'        => function() { return new \MVC\View_Factory($this); },
			'html'        => '\MVC\HTML',
		]);
		
		// TODO: to be moved
		if($config !== null){
			$this->services->set('config', $config);
		}
	}
	
	static function create(Config_Interface $config = null){
		return new self($config);
	}
	
	// service access
	// TODO: finish
	
	function has($key){
		return $this->services->has($key);
	}

	function get($key){
		if(!$this->services->has($key)){
			return false;
		}
		
		return $this->services->get($key);
	}
	
	// object access
	
	function __get($key){
		return $this->get($key);
	}
	
	// execution
	
	function run(){
		$this->initialize();
		
		$router = $this->get('router');
		$this->get('dispatcher')->dispatch($router); // TODO: it's pretty clumsy
	}
	
	// TODO: temporary solution
	
	protected function initialize(){
		/* ... */
	}
	
}
