<?php

require_once("style.php");

class SiteStyle extends Style
{
	public $title;
	public function __construct()
	{
		$this->title = "";
	}

	public function displayHeader()
	{
		echo ("
		<!DOCTYPE html>
		<html>
		<head>
		<meta charset='UTF-8'/>
		<title>
		{$this->title}
		</title>
		</head>
		<body>
		");
	}

	public function displayItem($arguments)
	{
		echo ("
			<!-- Beginning of content {$arguments['name']} -->
			");
	}
	
	public function displayItemEnd()
	{
	}

	public function displayFooter()
	{
		echo ("
		</body>
		</html>
		");
	}
}