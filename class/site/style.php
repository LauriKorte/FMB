<?php

//Class that defines a style for displaying stuff
class Style
{
	public function displayHeader()
	{
		//Displayed once per content, at the beginning
	}
	
	public function displayItem($arguments)
	{
		//Displayed zero, one or more times, depending on the content
		//the arguments are per item arguments
	}
	
	public function displayItemEnd()
	{
		//Displayed once for each displayItem called
	}
	
	public function displayFooter()
	{
		//Displayed once per content, at the very end
	}
}



//Simple example
class TextStyle extends Style
{
	public function displayHeader()
	{

	}
	
	public function displayItem($arguments)
	{
		//We just display a text from the $arguments["text"]
		//that is hopefully set by the content
		echo ("<p>".$arguments["text"]."</p>");
	}
	
	public function displayItemEnd()
	{
	}
	
	public function displayFooter()
	{
	}
}