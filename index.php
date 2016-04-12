

<?php

	//Display them errors
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);

	require_once("class/database.php");
	require_once("class/siteMaster.php");
	require_once("class/site/site.php");

	require_once("class/content/recipeList.php");
	require_once("class/content/navbar.php");
	require_once("class/content/recipeDisplay.php");
	require_once("class/content/loginForm.php");

	require_once("class/site/content.php");
	require_once("class/authentication.php");

	//If we are in a subdirectory
	//this should be used
	$DomainPrefix = "/~H9115/fmb/FMB";


	session_start();

	//Open database connection
	$db = new DBMaster();
	$db->open();
	
	//Create sitemaster
	$sitem = new SiteMaster();

	//Add a bunch of sitemaster matches
	//Read about regexes somewhere

	//Please note that the % symbol is used as a delimiter,
	//and it must be the first and the last character
	//of the regex

	//Match for url: /getrecipe/(recipe_id)
	//Displays detailed description of recipe (recipe_id)
	$sitem->addGetMatch("%^/getrecipe/([0-9]+)$%", function ($rcp) use ($db)
	{
		//The $rcp variable here is the return of the php regex
		//preg_match() function

		//It returns an array, where the first element is the matched string
		//followed by regex capture groups

		//We used a capture group for the recipe_id number,
		//and it is our only capture, so it is found int $rcp[1]

		$rcpDisplay = new RecipeDisplay();
		$rcpDisplay->recipe = $db->getRecipeWithIngredients((int)$rcp[1]);
		return $rcpDisplay;
	});

	//Match for url: /getrecipelist/(first):(last)
	//Displays a list of recipes from (first) to (last)
	$sitem->addGetMatch("%^/getrecipelist/([0-9]+):([0-9]+)$%", function ($rcp) use ($db)
	{
		//Same thing as above,
		//except with two capture groups

		$rcpList = new RecipeList();
		$rcpList->recipes = $db->getRecipes((int)$rcp[1],(int)$rcp[2]);
		return $rcpList;
	});

	//Match for url: /navbar
	$sitem->addGetMatch("%^/navbar$%", function ($_)
	{
		global $DomainPrefix;
		return new ItemContent(new NavBarStyle(), array("links" => array(
			"Front" => $DomainPrefix."/",
			"Test recipe" => $DomainPrefix."/getrecipe/1",
			"Loggin'" => $DomainPrefix."/loginForm")));
	});

	//Match for url: /login
	//Should be used when we want to login
	//The username and password should be in the POST
	$sitem->addGetMatch("%^/login$%", function ($a) use ($sitem)
	{

		$auth = new Authentication();

		if (!$auth->isLoggedIn())
		{
			$itc = new ItemContent(new TextStyle(), array("text" => "Failed to log in"));

			$ckr = $sitem->get("/loginForm");
			$arr = array($itc, $ckr);
			return new ContentGroup($arr);
		}
		return new ItemContent(new TextStyle(), array("text" => "You log in!!!"));
		
	});

	//Match for url: /loginform
	//Displays a login form
	$sitem->addGetMatch("%^/loginForm$%", function ($_)
	{
		global $DomainPrefix;
		$lgn = new ItemContent();
		$lgn->style = new LoginFormStyle();
		$lgn->arguments = array("address" => $DomainPrefix."/login");
		return $lgn;
	});

	//Match for url: /
	//Displays the front page
	$sitem->addGetMatch("%^/$%", function ($_)
	{
		return new ItemContent(new TextStyle(), array("text" => "guess this a front page"));
	});

	//Match for everything else
	$sitem->addGetMatch("%.+%", function ($_)
	{
		return new ItemContent(new TextStyle(), array("text" => "404 not found"));
	});


	$content = $sitem->get($_GET["_url"]);

	$cg = array(
			$sitem->get("/navbar"),
			$content
	);


	$site = new Site();
	$site->setContents($cg);
	
	$site->setupHeader();
	$site->display();
