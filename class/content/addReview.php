<?php
require_once(__DIR__ . "/../site/content.php");
require_once(__DIR__ . "/../site/style.php");

class ReviewAddStyle extends Style
{
	public function displayHeader()
	{
	}
	public function displayItem($arguments)
	{
		global $DomainPrefix;
		global $db;

		$ratings = $db->getRating();

		echo ('<div class="col-md-4" >');
		echo ('<form method="post" action="'.$DomainPrefix."/postreview".'" id="reviewAdd">');
		echo ("<p>Review for recipe {$arguments['recipe']->name} </p>");
		echo ('<textarea name="comment" form="reviewAdd"></textarea><br>');

		echo ('Rating: <select name="rating">');
		foreach ($ratings as $rt)
		{
			echo ("<option value='{$rt->id}'>$rt->description</option>");
		}
		echo ('</select><br>');
		
		echo ("<input type='hidden' name='recipeid' value='{$arguments['recipe']->id}'>");

		echo ('<p><input type="submit" class="btn btn-success" value="Go Rewiev, GO!!!"></input>');
		echo ("</form> </div>");
	}
	public function displayItemEnd()
	{
	}
	public function displayFooter()
	{
		echo ("");
	}
}
