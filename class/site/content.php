<?php
require_once("style.php");


class BaseContent
{	
	public function display()
	{

	}
}


class Content extends BaseContent
{
	public $style;
	
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

class ContentGroup extends BaseContent
{
	public $contents;
	public function __construct($contents)
	{
		$this->contents = $contents;
	}

	public function display()
	{
		foreach ($this->contents as $ct)
		{
			$ct->display();
		}
	}
}

class ItemContent extends Content
{
	public $style;
	public $arguments;

	public function __construct($style = null, $arguments = null)
	{
		$this->style = $style;
		$this->arguments = $arguments;
	}
	
	public function displayContent()
	{
		$this->style->displayItem($this->arguments);
		$this->style->displayItemEnd();	
	}
}