<?php

class Style
{
	public function displayHeader()
	{
	}
	
	public function displayItem($arguments)
	{
	}
	
	public function displayItemEnd()
	{
	}
	
	public function displayFooter()
	{
	}
}



class TextStyle
{
	public function displayHeader()
	{
	}
	
	public function displayItem($arguments)
	{
		echo ("<p>".$arguments["text"]."</p>");
	}
	
	public function displayItemEnd()
	{
	}
	
	public function displayFooter()
	{
	}
}