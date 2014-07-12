<?php

namespace MVC;

// - contains variables acquired from controller
// - allows displaying
// TODO:
//  - HMVC ie. calling widgets
//  - access to services: how to access data vs. services

class View implements View_Interface {
	use View_Data_Trait;
	
	protected $template_path = 'templates';
	
	protected $application;
	
	protected $template;
	protected $data = [];
	
	//------------------------------------------------------
	// constructors
	//------------------------------------------------------
	
	function __construct(Application $application, $template, array $data = []){
		$this->application  = $application;
		$this->template     = $template;
		$this->data         = $data;
		
		$config = $this->application->get('config');
		
		if($config->has('template_path')){
			$this->template_path = $config->get('template_path');
		}
	}
	
	static function create(Application $application, $template, array $data = []){
		return new self($application, $template, $data);
	}
	
	//------------------------------------------------------
	// ? escaping
	//------------------------------------------------------
	// TODO: escaping should be moved to some helper
	//------------------------------------------------------
	
	function escape($input){
		return htmlspecialchars($input);
	}
	
	//------------------------------------------------------
	// rendering
	//------------------------------------------------------
	
	function render(){
		ob_start();
		require $this->template_path . '/' . $this->template . '.php';
		ob_end_flush();
	}
	
	function fetch(){
		ob_start();
		$this->render();
		$output = ob_get_clean();
		
		return $output;
	}
	
	function __toString(){
		return $this->fetch();
	}
	
}
