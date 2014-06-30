<?php

namespace MVC;

class View_Factory {
	
	protected $application;
	
	function __construct(Application $application){
		$this->application = $application;
	}
	
	function create($template, array $data){
		return new View($this->application, $template, $data);
	}
	
}