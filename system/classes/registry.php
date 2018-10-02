<?php
class registry
{
	public $vars;
	
	public function __construct()
	{
		
	}
	
	public function __get($index)
	{
		return $this->vars[$index];
	}
	
	public function __set($index,$value)
	{
		$this->vars[$index] = $value;
	}
	
	function __sleep(){ /*serialize on sleep*/
        $this->vars = serialize($this->vars);
    }
    function __wake(){ /*un serialize on wake*/
        $this->vars = unserialize($this->vars);
    }
}
?>