<?php
class dialog
{
	public $title;
	public $message;
	public $name;
	public $close;
	public function __construct($title,$message, $name = 'dialog', $close = '')
	{
		$this->title = $title;
		$this->message = $message;
		$this->name = $name;
		$this->close = $close;
	}
	
	public function showDialog()
	{
		echo '
		<div id="'.$this->name.'" title="'.$this->title.'">
  			<div id="'.$this->name.'_content">';
  		echo $this->message;
		
		echo '
			</div>
		</div>
		';
		$this->Settings();
	}
	
	private function Settings()
	{
		echo '
		<script>
	  $(function() {
	    $( "#'.$this->name.'" ).dialog();
	    $("#'.$this->name.'").dialog('.$this->close.');
	  });
	  </script>
		';
	}
	
	
	
}
?>