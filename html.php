<?php

namespace MVC;

class HTML {
	
	protected $styles   = [];
	protected $scripts  = [];
	
	protected $styles_path   = 'styles';
	protected $scripts_path  = 'scripts';
	
	function __construct(Application $application){
		if($application->has('config')){
			$config = $application->get('config');
			
			if($config->has('styles_path')){
				$this->styles_path = $config->get('styles_path');
			}
			
			if($config->has('scripts_path')){
				$this->scripts_path = $config->get('scripts_path');
			}
		}
		
	}
	
	function add_style($style){
		if(!in_array($style, $this->styles)){
			$this->styles[] = $style;
		}
	}
	
	function add_script($script){
		if(!in_array($script, $this->scripts)){
			$this->scripts[] = $script;
		}
	}
	
	function print_styles(){
		$styles_path = $this->styles_path ? $this->styles_path . '/' : '';
		
		foreach($this->styles as $style){
			echo '<link rel="stylesheet" type="text/css" href="' . $styles_path . $style . '">'. "\n";
		}
	}
	
	function print_scripts(){
		$scripts_path = $this->scripts_path ? $this->scripts_path . '/' : '';
		
		foreach($this->scripts as $script){
			echo '<script type="text/javascript" src="' . $scripts_path . $script . '"></script>'. "\n";
		}
	}
}

