<?php


require_once(__DIR__ . "/../site/style.php");

class ReviewListStyle extends Style
{
	public function displayHeader()
	{
		echo('<table class="table table-striped">');
	}
	public function displayItem($arguments)
	{
		echo ("<tr><td><a href='{$arguments['link']}'>Review: {$arguments['name']}</a></td>");
		echo ("</tr>");
	}
	public function displayItemEnd()
	{
	}
	public function displayFooter()
	{
		echo('</table>');
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
				array('name' => $review->recipeName." ".(string)$review->date, 'link' => $this->linkPrefix.((string)$review->id)));
			$this->style->displayItemEnd();
		}
	}
}