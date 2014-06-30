<?php

namespace MVC;

interface View_Interface {
	
	// constructor
	function __construct(Application $application, $template, array $data = []);
	
	// factory method
	static function create(Application $application, $template, array $data = []);
	
	// setter for data
	function set($key, $value);
	
	// getter for data
	function get($key);
	
	// magic setter for data
	function __set($key, $value);
	
	// magic getter for data
	function __get($key);
	
	// rendering the view into the output
	function render();
	
	// fetching the view into a variable
	function fetch();
	
	// converting the output into a variable
	function __toString();
	
}