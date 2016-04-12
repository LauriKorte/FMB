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
  		<meta name='viewport' content='width=device-width, initial-scale=1'>
  		<link rel='stylesheet' href='http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css'>
		<script src='https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js'></script>
		<script src='http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js'></script>
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
