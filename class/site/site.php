<?php
require_once("content.php");
require_once("siteStyle.php");

class Site extends Content
{
	private $title;
	private $contents;
	private $gotContent;
	
	public function __construct()
	{
		$this->style = new SiteStyle();
		$this->style->title = "FMB";
		$this->gotContent = false;
	}
	
	public function setupHeader()
	{
		$this->gotContent = false;

		if (!is_null($this->contents))
		{
			foreach ($this->contents as $content)
			{
				if (!is_null($content))
				{
					$this->gotContent = true;
					break;
				}
			}
		}
		if ($this->gotContent  == false)
		{
			header("HTTP/1.1 404 Not Found");
		}
		else
		{
			header("HTTP/1.1 200 OK");
		}

		header('Content-Type: text/html; charset=utf-8');
	}
	
	public function setContents($contents)
	{
		$this->contents = $contents;
	}

	public function displayContent()
	{
		if ($this->gotContent)
		{
			if (!is_null($this->contents))
			{
				foreach ($this->contents as $content)
				{
					if (!is_null($content))
						$content->display();
				}
			}
		}
		else
		{
			echo("<p>HTTP 404 Not Found: no content!</p>");
		}
	}
	
}