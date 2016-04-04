

<?php

	require_once("class/database.php");
	require_once("class/site/site.php");
	require_once("class/recipeList.php");
	
	$db = new DBMaster();
	$db->open();
	
	$site = new Site();
	
	$rcpList = new RecipeList();
	$rcpList->recipes = $db->getRecipes(0,20);
	
	$site->setContent($rcpList);
	
	$site->setupHeader();
	$site->display();
	
	$rcp = $db->getRecipeWithIngredients(1);
	
	echo("<h1>".$rcp->name."</h1> \n");
	foreach ($rcp->ingredients as $ing)
		echo($ing->name." \n");
	/*
	$recipes = $db->getRecipes(0,20);
	
	foreach ($recipes as $recipe)
	{
		echo("WE GOT SOME RECIPES UP THIS BIATCH: ".$recipe->name);
	}
	*/