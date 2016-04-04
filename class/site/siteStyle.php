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
		echo (
<<<HTMLHEADER
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8"/>
<title>
{$this->title}
</title>
</head>
<body>

		
HTMLHEADER
		);
	}
	public function displayFooter()
	{
		echo (
<<<HTMLFOOTER
</body>
</html>
HTMLFOOTER
		);
	}
}