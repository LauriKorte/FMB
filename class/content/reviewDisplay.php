<?php
require_once(__DIR__ . "/../site/content.php");
require_once(__DIR__ . "/../site/style.php");
require_once(__DIR__ . "/../authentication.php");


class ReviewDisplayStyle extends Style
{
	public function displayHeader()
	{
		echo ("<hr />");
	}
	public function displayItem($arguments)
	{

		global $DomainPrefix;
		echo ('<div class="col-md-4">');
		
		if (!is_null($arguments['review']))
		{
			$rv = $arguments['review'];
			echo ("<h2>Review for {$rv->recipeName}</h2>");
			echo ("<p>{$rv->personalComment}</p>");
			
			
		
			echo ("<strong>{$rv->ratingName} {$rv->ratingStars}/5 </strong><br>");
			
			for ($i = 0; $i < 5; $i++)
			{
				if ($i < $rv->ratingStars)
					echo("<img src='{$DomainPrefix}/assets/bread.png' width='48' height='auto'>");
				else
					echo("<img src='{$DomainPrefix}/assets/bread.png' width='12' height='auto'>");
					
			}

			$auth = new Authentication();
			if ($auth->isLoggedIn())
			{
				echo ("<p><a href='{$DomainPrefix}/deletereview/{$rv->id}'><button type='button' class='btn btn-danger'>Delete review</button></a>");
			}

		}
		else
		{
			echo("<p>There's no reviews here.</p>");
		}
	}
	public function displayItemEnd()
	{
		echo ("</div>");
	}
	public function displayFooter()
	{
		echo ("");
	}
}
