<?php
class ckeditor
{
	public $registry;
	public $textArea;
	public $defaultValue;
	public $toolbar;
	public $editorConfig;
	
	public function __construct($registry, $textArea, $defualtValue = null, $toolbar = 'basic')
	{
		$this->registry = $registry;
		$this->textArea = $textArea;
		$this->defaultValue = $defualtValue;
		$this->toolbar = $toolbar;
	}
	
	public function Display()
	{
		echo '
			<textarea name="'.$this->textArea.'" id="'.$this->textArea.'" >'.$this->defaultValue.'</textarea>
		';
		
		$this->settings();
	}
	
	public function settings()
	{
		switch($this->toolbar)
		{
			case 'basic':
				$this->basicToolbar();
				break;
			case 'admin':
				$this->adminToolbar();
			default:
				$this->basicToolbar();
		}
		echo $this->editorConfig;
		echo '
		$("#'.$this->textArea.'").ckeditor(config);
		</script>';
	}
	
	public function basicToolbar()
	{
		$this->editorConfig = '
		<script>
				
		  var myToolbar = [             
		 	{name: \'clipboard\', items: [\'Cut\', \'Copy\', \'Paste\',\'PasteText\',\'PasteFromWord\',\'-\',\'Undo\',\'Redo\']},
			{name: \'editing\',items: [\'Ecayt\']},
			{name:\'basicstyles\',items:[\'Bold\',\'Italic\',\'-\',\'RemoveFormat\']},
			{name:\'paragraph\',items:[\'Outdent\',\'Indent\',\'-\']},
			{name:\'styles\',items:[\'Styles\']},
			{name:\'tools\',items:[\'Maximize\']}
		  ];                                              
		  var config = {             
			  toolbar_mySimpleToolbar: myToolbar,            
			  toolbar: \'mySimpleToolbar\'                 
		  }; 
		  ';
	}
	
	public function adminToolbar()
	{
		$this->editorConfig = '
		<script>
		var myToolbar = [  
			{name: \'clipboard\', items: [\'Cut\', \'Copy\', \'Paste\',\'PasteText\',\'PasteFromWord\',\'-\',\'Undo\',\'Redo\']},
			{name: \'editing\',items: [\'Ecayt\']},
			{name:\'links\',items:[\'Link\',\'Unlink\',\'Anchor\']},
			{name:\'insert\',items:[\'Image\',\'Table\',\'HorizontalRule\',\'SpecialChar\']},
			{name:\'tools\',items:[\'Maximize\']},
			{name:\'document\',items:[\'Source\']},
			\'/\',
			{name:\'basicstyles\',items:[\'Bold\',\'Italic\',\'Strike\',\'-\',\'RemoveFormat\']},
			{name:\'paragraph\',items:[\'NumberedList\',\'BulletedList\',\'-\',\'Outdent\',\'Indent\',\'-\',\'Blockquote\']},
			{name:\'styles\',items:[\'Styles\',\'Format\']},
			{name:\'about\',items:[\'About\']}
		];
		 var config = {             
		  toolbar_mySimpleToolbar: myToolbar,            
		  toolbar: \'mySimpleToolbar\'                 
	  };
	  ';
	}
}
?>