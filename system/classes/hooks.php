<?php
class Hooks
{
	protected $registry;
	
	public function __construct($registry)
	{
		$this->registry = $registry;
	}  	
	
	public function add_action($action,$class,$method)
	{
		global $actions;

    	$actions[$action] = $class . '/' . $method;
	}
	
	public function do_action($action)
	{
		global $actions;    

	    foreach($actions as $key => $act) 
	    {
	        if ($key == $action) 
	        {
	            $info = explode('/',$act);
	            if (is_object($this->registry->info[0]))
	            {
					if(method_exists($this->registry->info[0], $info[1]))
					{
						call_user_method($info[1],$this->registry->info[0]);
					}
				}
	        }
	    }
	}
}

?>

  	