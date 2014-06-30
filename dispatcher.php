<?php

namespace MVC;

//--------------------------------------------------------------------
// DISPATCHER does the following steps:
//  - it resolves the request using ROUTER
//  - it resolves the route and calls the selected CONTROLLER
//  - it handles the content returned by the CONTROLLER
//--------------------------------------------------------------------

class Dispatcher implements Dispatcher_Interface {
	protected $application;
	
	/*
	protected $default_controller  = 'home';
	protected $default_action      = 'index';
	*/
	protected $controller_path     = 'controllers';
	
	protected $current_controller  = '';
	protected $current_action      = '';
	
	function __construct(Application $application){
		$this->application = $application;
		
		$config = $application->get('config');
		
		if($config->has('controller_path')){
			$this->controller_path = $config->get('controller_path');
		}
		
		/*
		if($config->has('default_controller')){
			$this->default_controller = $config->get('default_controller');
		}
		
		if($config->has('default_action')){
			$this->default_action = $config->get('default_action');
		}
		*/
	}
	
	//----------------------------------------------------------
	// setting controller path
	//----------------------------------------------------------
	
	function dispatch(Router_Interface $router){
		$controller  = $router->get_controller();
		$action      = $router->get_action();
		$parameters  = $router->get_parameters();
		
		return $this->dispatch_route($controller, $action, $parameters);
	}
	
	//----------------------------------------------------------
	// dispatching route
	//----------------------------------------------------------
	// TODO: loop handling if there is no 'error' controller
	//----------------------------------------------------------
	
	function dispatch_route($controller, $action, array $parameters){
		$this->current_controller  = $controller;
		$this->current_action      = $action;
		
		$result = $this->call_action($controller, $action, $parameters);
		
		$response = $this->handle_result($result);
		
		return $response;
	}
	
	//----------------------------------------------------------
	// calling controller action
	//----------------------------------------------------------
	
	protected function call_action($controller, $action, array $parameters){
		
		$controller_instance = $this->construct_controller($controller);
		
		if($controller_instance == false){
			return $this->respond_with_not_found();
		}
		
		$method_call = [$controller_instance, $action];
		$result = call_user_func_array($method_call, $parameters);
		
		return $result;
	}
	
	//----------------------------------------------------------
	// instantiating a controller
	//----------------------------------------------------------
	
	protected function construct_controller($controller){
		$controller_file = "{$this->controller_path}/$controller.php";
		
		if(!file_exists($controller_file)){
			return false;
		}
		
		// controller should be loaded with namespace!
		
		require_once $controller_file;
		
		return new $controller($this->application);
	}
	
	//----------------------------------------------------------
	// handling the result of the controller
	// TODO: loop handling
	//----------------------------------------------------------
	
	protected function handle_result($result){
		
		if($result === false){
			return $this->respond_with_not_found();
		}
		
		if($result === null || $result === true){
			return true;
		}
		
		if(is_array($result)){
			return $this->handle_array_result($result);
		}
		
		if($result instanceof View_Interface){
			return $this->handle_view_result($result);
		}
		
		// TODO: $result instanceof Response_Interface
		
		return $this->respond_with_internal_server_error();
	}
	
	//----------------------------------------------------------
	// handling an array returned by a controller
	//----------------------------------------------------------
	
	function handle_array_result($array_result){
		$template = $this->current_controller . '/' . $this->current_action;
		$view = $this->application->get('view')->create($template, $array_result);
		return $this->handle_view_result($view);
	}
	
	//----------------------------------------------------------
	// handling an object implementing View_Interface
	// returned by a controller
	//----------------------------------------------------------
	
	function handle_view_result(View_Interface $view_result){
		return $view_result->render();
	}
	
	//----------------------------------------------------------
	
	function respond_with_not_found(){
		return $this->dispatch_route('error', 'handle', ['not_found']);
	}
	
	//----------------------------------------------------------
	
	function respond_with_internal_server_error(){
		return $this->dispatch_route('error', 'handle', ['internal_server_error']);
	}
	
}