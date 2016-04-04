<?php
require_once("content.php");
require_once("siteStyle.php");

class Site extends Content
{
	private $title;
	private $content;
	
	public function __construct()
	{
		$this->style = new SiteStyle();
		$this->style->title = "FMB";
	}
	
	public function setupHeader()
	{
		header('Content-Type: text/html; charset=utf-8');
	}
	
	public function setContent($content)
	{
		$this->content = $content;
	}

	public function displayContent()
	{
		if (!is_null($this->content))
		{
			$this->content->display();
		}
	}
	
}