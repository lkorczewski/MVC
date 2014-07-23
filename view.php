<?php

namespace MVC;

// TODO:
//  - HMVC ie. calling widgets
//  - access to services: how to access data vs. services

class View implements View_Interface {
	use View_Data_Trait;
	
	protected $template_path = 'templates';
	
	protected $application;
	
	protected $template;
	protected $data = [];
	
	protected $parent_template;
	
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
	// services
	//------------------------------------------------------
	// TODO: There should be some shortcut,
	//  $this->get_service('html')->add_style(..) is too long
	//  ? $this->services['html']->add_style
	//  ? $this->html->add_style()
	//  ? $this['html']->add_style()
	//  ? App::html->add_style()
	//  ? HTML::add_style()
	//  ? App::database['main']->insert
	
	function get_service($service){
		return $this->application->get($service);
	}
	
	function has_service($service){
		return $this->application->has($service);
	}
	
	//------------------------------------------------------
	// ? escaping
	//------------------------------------------------------
	// TODO: escaping should be moved to some helper
	//------------------------------------------------------
	
	function escape($input){
		return htmlspecialchars($input);
	}
	
	protected function extend($parent_template){
		$this->parent_template = $parent_template;
	}
	
	//------------------------------------------------------
	// rendering
	//------------------------------------------------------
	
	protected function render_itself(){
		//echo "<p>$template : render itself</p>";
		ob_start();
		require $this->template_path . '/' . $this->template . '.php';
		ob_end_flush();
	}
	
	function render(){
		ob_start();
		$this->render_itself();
		
		if($this->parent_template){
			$content = ob_get_clean();
			$view_factory = $this->application->get('view');
			$parent_view = $view_factory->create($this->parent_template, $this->data)
				->set('_content', $content)
				->render();
		} else {
			ob_end_flush();
		}
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
