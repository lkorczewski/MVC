<?php

namespace MVC;

class Request {
	
	function get_parameter($parameter){
		
		if(isset($_GET[$parameter])){
			return $_GET[$parameter];
		}

		if(isset($_POST[$parameter])){
			return $_POST[$parameter];
		}
		
		return '';
	}
	
	function get_post_parameter(){
		if(isset($_POST[$parameter])){
			return $_POST[$parameter];
		}
		
		return '';
	}
	
	function get_get_parameter(){
		if(isset($_GET[$parameter])){
			return $_GET[$parameter];
		}
		
		return '';
	}
	
}