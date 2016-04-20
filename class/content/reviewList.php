<?php


require_once(__DIR__ . "/../site/style.php");

class ReviewListStyle extends Style
{
	public function displayHeader()
	{
		echo('<div class="container">');
		echo('<table class="table table-striped">');
	}
	public function displayItem($arguments)
	{
		echo ("<tr><td><a href='{$arguments['link']}'>{$arguments['name']}</a></td><td>{$arguments['rating']}</td><td>{$arguments['date']}</td>");
		echo ("</tr>");
	}
	public function displayItemEnd()
	{
	}
	public function displayFooter()
	{
		echo('</table>');
		echo('</div>');
	}
}

require_once(__DIR__ . "/../site/content.php");
require_once("recipeListStyle.php");

class ReviewList extends Content
{
	public $reviews;
	public $linkPrefix;
	
	public function __construct()
	{
		$this->style = new ReviewListStyle();
	}
	
	public function displayContent()
	{
		
		foreach ($this->reviews as $review)
		{
			$this->style->displayItem(
				array('name' => $review->recipeName, 'rating' => $review->ratingName.' '.$review->ratingStars.'/5', 'date' => (string)$review->date, 'link' => $this->linkPrefix.((string)$review->id)));
			$this->style->displayItemEnd();
		}
	}
}