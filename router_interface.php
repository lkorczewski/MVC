<?php

namespace MVC;

interface Router_Interface {
	
	// parsing result
	function get_controller();
	function get_action();
	function get_parameters();

}

