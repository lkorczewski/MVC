<?php

namespace MVC;

// - contains variables acquired from controller
// - allows displaying
// TODO:
//  - HMVC ie. calling widgets
//  - access to services: how to access data vs. services


trait View_Data_Trait {
	
	//------------------------------------------------------
	// data access
	//------------------------------------------------------
	
	function set($label, $value){
		$this->data[$label] = $value;
		return self;
	}
	
	function has($label){
		return isset($this->data['label']);
	}
	
	function get($label){
		return $this->data[$label];
	}
	
	//------------------------------------------------------
	// array access
	//------------------------------------------------------
	// not implemented because I don't like unsetting anything
	//------------------------------------------------------
	
	//------------------------------------------------------
	// object access
	//------------------------------------------------------
	
	function __set($key, $value){
		$this->data[$key] = $value;
	}
	
	function __get($key){
		return $this->data[$key];
	}
	
	function __isset($key){
		return isset($this->data[$key]);
	}
	
}
