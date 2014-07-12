<?php

namespace MVC;

abstract class Router {
	
	const STATE_UNPARSED  = 0;
	const STATE_PARSED    = 1;
	
	protected $state = self::STATE_UNPARSED;
	
	protected $controller  = 'home';
	protected $action      = 'index';
	protected $parameters  = [];
	
	function __construct(Service_Container_Interface $services){
		
		// TODO: lazy config access
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
	
	// getting route components
	
	function get_controller(){
		$this->parse_if_needed();
		return $this->controller;
	}
	
	function get_action(){
		$this->parse_if_needed();
		return $this->action;
	}
	
	function get_parameters(){
		$this->parse_if_needed();
		return $this->parameters;
	}
	
	function get_parameter($key){
		$this->parse_if_needed();
		return $this->parameters['key'];
	}
	
	function parse_if_needed(){
		if($this->state == self::STATE_UNPARSED){
			$this->parse();
			$this->state = self::STATE_PARSED;
		}
	}
	
}
