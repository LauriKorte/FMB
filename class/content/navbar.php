<?php

require_once(__DIR__ . "/../site/style.php");
class NavBarStyle extends Style
{
	public function displayHeader()
	{
	
	}
	public function displayItem($arguments)
	{
		global $DomainPrefix;
		echo ('<nav class="navbar navbar-default" role="navigation">
				<div class="navbar-header">
					 
					<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
						 <span class="sr-only">Toggle navigation</span><span class="icon-bar"></span><span class="icon-bar"></span><span class="icon-bar"></span>');
						 
		echo("</button><a onmouseover=\"this.innerHTML='OGR';\" onmouseout=\"this.innerHTML='FMB';\" class='navbar-brand' href='{$DomainPrefix}/'>FMB</a>");
		echo ('</div>
				
				<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
					<ul class="nav navbar-nav">');

		if (!is_null($arguments['links']))
		{
			foreach ($arguments['links'] as $text => $target)
			{
				echo ("<li><a href='{$target}'>{$text}</a></li>");
			}
		}
	}
	public function displayItemEnd()
	{
		echo ('</ul>
		       </div>
		       </nav>');
	}
	public function displayFooter()
	{
	
	}
}
