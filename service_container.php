<?php

namespace MVC;

class Service_Container implements Service_Container_Interface{
	
	protected $definitions  = [];
	protected $instances    = [];
	
	//------------------------------------------------------
	// constructors
	//------------------------------------------------------
	// allowed definitions:
	//  - string - class name to be instantiated
	//  - callback($service_manager) returning an instance
	//  - object instance
	//------------------------------------------------------
	
	function __construct(array $definitions = []){
		$this->definitions = $definitions;
	}
	
	static function create(array $definitions = []){
		return new self($definitions);
	}
	
	//------------------------------------------------------
	// setting service definition
	//------------------------------------------------------
	
	function set($key, $definition){
		$this->definitions[$key] = $definition;
		
		return $this;
	}
	
	function __set($key, $definition){
		$this->set($key, $definition);
		
		return $this;
	}
	
	//------------------------------------------------------
	// checking if service defined
	//------------------------------------------------------
	
	function has($key){
		return isset($this->definitions[$key]);
	}
	
	//------------------------------------------------------
	// getting service instance
	//------------------------------------------------------
	
	function get($key){
		if(isset($this->instances[$key])){
			return $this->instances[$key];
		}
		
		return $this->construct($key);
	}
	
	//------------------------------------------------------
	// constructing service instance
	//------------------------------------------------------
	
	protected function construct($key){
		if(!isset($this->definitions[$key])){
			return false;
		}
		
		$definition = $this->definitions[$key];
		$instance = $this->resolve_definition($definition);
		$this->instances[$key] = $instance;
		
		return $instance;
	}
	
	//------------------------------------------------------
	// resolving service definition
	//------------------------------------------------------
	
	protected function resolve_definition($definition){
		
		if(is_string($definition)){
			return new $definition;
		}
		
		if($definition instanceof \Closure){
			return $definition($this);
		}
		
		if(is_object($definition)){
			return $definition;
		}
		
		return false;
	}
	
}
