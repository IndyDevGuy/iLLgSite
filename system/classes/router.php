<?php
//router clas
class router
{
	public $controllerName;
	public $controllerMethod;
	
	public $controller;
	
	public $registry;
	
	public function __construct($registry)
	{
		$this->registry = $registry;
		if (!isset($_GET['rt']))
		{
			$this->controllerName = 'Home';
			$this->controllerMethod = 'Index';
		}
		else
		{
			$this->controllerName = ucfirst($_GET['rt']);
			if (!isset($_GET['method']))
			{
				$this->controllerMethod = 'Index';
			}
			else
			{
				$this->controllerMethod = ucfirst($_GET['method']);
			}	
		}
	}
	
	public function doRoute()
	{
		if (class_exists($this->controllerName . 'Controller'))
		{
			$controllername = $this->controllerName . 'Controller';
			$this->controller = new $controllername($this->registry);
			$classMethodArray = array($this->controller, $this->controllerMethod);
			if (method_exists($this->controller, $this->controllerMethod))
			{
				$method = $this->controllerMethod;
				$this->controller->$method();
			}
			else
			{
				$this->controller = new notfoundcontroller($this->registry);
				$this->controller->index();
			}
		}
		else
		{
			$this->controller = new notfoundcontroller($this->registry);
			$this->controller->index();
		}
	}
}
?>