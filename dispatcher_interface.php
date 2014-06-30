<?php

namespace MVC;

interface Dispatcher_Interface {
	
	function dispatch(Router_Interface $router);
	function dispatch_route($controller, $action, array $parameters);
	
}