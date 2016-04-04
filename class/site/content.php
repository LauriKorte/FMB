<?php
require_once("style.php");

class Content
{
	protected $style;
	
	public function display()
	{
		if (!is_null($this->style))
			$this->style->displayHeader();
		
		$this->displayContent();
		
		if (!is_null($this->style))
			$this->style->displayFooter();
	}
	
	public function displayContent()
	{
		
	}
}