<?php

class HTML {
	
	$styles = [];
	$scripts = [];
	
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
		foreach($this->styles as $style){
			echo '<link rel="stylesheet" type="text/css" href="' . $style . '">'. "\n";
		}
	}
	
	function print_scripts(){
		foreach($this->scripts as $script){
			echo '<script type="text/javascript" src="' . $script . '"></script>'. "\n";
		}
	}
}