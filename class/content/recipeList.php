<?php
require_once(__DIR__ . "/../site/content.php");
require_once("recipeListStyle.php");

class RecipeList extends Content
{
	public $recipes;
	public $linkPrefix;
	
	public function __construct()
	{
		$this->style = new RecipeListStyle();
	}
	
	public function displayContent()
	{
		
		foreach ($this->recipes as $recipe)
		{
			$this->style->displayItem(
				array('name' => $recipe->name, 'link' => $this->linkPrefix.((string)$recipe->id)));
			$this->style->displayItemEnd();
		}
	}
}