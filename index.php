

<?php
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);

	require_once("class/database.php");
	require_once("class/siteMaster.php");
	require_once("class/site/site.php");
	require_once("class/content/recipeList.php");
	require_once("class/content/recipeDisplay.php");
	require_once("class/content/loginForm.php");
	require_once("class/site/content.php");
	require_once("class/authentication.php");


	$DomainPrefix = "";

	session_start();

	$db = new DBMaster();
	$db->open();
	
	$sitem = new SiteMaster();

	$sitem->addGetMatch("%^/getrecipe/([0-9]+)$%", function ($rcp) use ($db)
	{
		$rcpDisplay = new RecipeDisplay();
		$rcpDisplay->recipe = $db->getRecipeWithIngredients((int)$rcp[1]);
		return $rcpDisplay;
	});

	$sitem->addGetMatch("%^/getrecipelist/([0-9]+):([0-9]+)$%", function ($rcp) use ($db)
	{
		$rcpList = new RecipeList();
		$rcpList->recipes = $db->getRecipes((int)$rcp[1],(int)$rcp[2]);
		return $rcpList;
	});

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
		return new ItemContent(new TextStyle(), ["text" => "You log in!!!"]);
		
	});

	$sitem->addGetMatch("%^/loginForm$%", function ($_)
	{
		global $DomainPrefix;
		$lgn = new ItemContent();
		$lgn->style = new LoginFormStyle();
		$lgn->arguments = array("address" => $DomainPrefix."/login");
		return $lgn;
	});

	//Match for everything else
	$sitem->addGetMatch("%.+%", function ($_)
	{
		return new ItemContent(new TextStyle(), ["text" => "get out of here!!!!"]);
	});

	$content = $sitem->get($_GET["_url"]);

	$site = new Site();
	$site->setContents(array($content));
	
	$site->setupHeader();
	$site->display();
