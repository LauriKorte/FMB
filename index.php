

<?php

	//Display them errors
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);

	require_once("class/database.php");
	require_once("class/siteMaster.php");
	require_once("class/site/site.php");

	require_once("class/content/recipeList.php");
	require_once("class/content/reviewList.php");
	require_once("class/content/navbar.php");
	require_once("class/content/abootStyle.php");
	require_once("class/content/recipeDisplay.php");
	require_once("class/content/loginForm.php");
	require_once("class/content/pageLinks.php");
	require_once("class/content/addRecipe.php");
	require_once("class/content/addReview.php");

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
		global $DomainPrefix;
		//Same thing as above,
		//except with two capture groups

		$rcpList = new RecipeList();
		$rcpList->linkPrefix = $DomainPrefix."/getrecipe/";
		$rcpList->recipes = $db->getRecipes((int)$rcp[1],(int)$rcp[2]);
		return $rcpList;
	});
	
	
	//Match for url: /getreviewlist/(first):(last)
	//Displays a list of reviews from (first) to (last)
	$sitem->addGetMatch("%^/getreviewlist/([0-9]+):([0-9]+)$%", function ($rcp) use ($db)
	{
		global $DomainPrefix;
		
		$rcpList = new ReviewList();
		$rcpList->linkPrefix = $DomainPrefix."/getreview/";
		$rcpList->reviews = $db->getRecipeHistory((int)$rcp[1],(int)$rcp[2]);
		return $rcpList;
	});
	
	//Match for url: /getreview/(review_id)
	//Displays detailed description of review (review_id)
	$sitem->addGetMatch("%^/getreview/([0-9]+)$%", function ($rcp) use ($db)
	{
		$rv = $db->getReviewWithRecipe((int)$rcp[1]);
		return new ItemContent(new TextStyle(), array("text" => $rv->personalComment));
	});


	//Match for url: /browse/(page)
	//Displays a linked list of recipes page (page)
	$sitem->addGetMatch("%^/browse/([0-9]+)$%", function ($rcp) use ($db, $sitem)
	{
		global $DomainPrefix;
		$cnt = $db->getRecipeCount();
		$pages = ceil($cnt/10);
		$curPage = (int)$rcp[1];

		$from = $curPage*10;
		$to = $from+10;

		$rcpList = $sitem->get("/getrecipelist/".((string)$from).":".((string)$to));


		$links = array();
		for ($i = 0; $i < $pages; $i++)
		{
			$links[((string)$i+1)] = $DomainPrefix."/browse/".((string)$i);
		}

		$plinks = new ItemContent(new PageLinksStyle(), array("links" => $links));
		$cg = new ContentGroup(array($rcpList, $plinks));


		return $cg;
	});
	//Match for url: /browsereview/(page)
	//Displays a linked list of reviews page (page)
	$sitem->addGetMatch("%^/browsereview/([0-9]+)$%", function ($rcp) use ($db, $sitem)
	{
		global $DomainPrefix;
		$cnt = $db->getRecipeCount();
		$pages = ceil($cnt/10);
		$curPage = (int)$rcp[1];

		$from = $curPage*10;
		$to = $from+10;

		$rcpList = $sitem->get("/getreviewlist/".((string)$from).":".((string)$to));


		$links = array();
		for ($i = 0; $i < $pages; $i++)
		{
			$links[((string)$i+1)] = $DomainPrefix."/browsereview/".((string)$i);
		}

		$plinks = new ItemContent(new PageLinksStyle(), array("links" => $links));
		$cg = new ContentGroup(array($rcpList, $plinks));


		return $cg;
	});
	//Match for url: /addrecipe
	$sitem->addGetMatch("%^/addrecipe$%", function ($_) use ($sitem)
	{

		$auth = new Authentication();

		if (!$auth->isLoggedIn())
		{
			$itc = new ItemContent(new TextStyle(), array("text" => "Please log in first"));

			$ckr = $sitem->get("/loginForm");
			$arr = array($itc, $ckr);
			return new ContentGroup($arr);
		}

		return new ItemContent(new RecipeAddStyle(), array());
	});
	
	//Match for url: /addreview/(recipeid)
	$sitem->addGetMatch("%^/addreview/([0-9]+)$%", function ($rcp) use ($db, $sitem)
	{

		$auth = new Authentication();

		if (!$auth->isLoggedIn())
		{
			$itc = new ItemContent(new TextStyle(), array("text" => "Please log in first"));

			$ckr = $sitem->get("/loginForm");
			$arr = array($itc, $ckr);
			return new ContentGroup($arr);
		}
		$integer = (int)($rcp[1]);
		$recipe = $db->getRecipeWithIngredients($integer);
		if (is_null($recipe))
		{
			return new ItemContent(new TextStyle(), array("text" => "Da recipe don't exist!"));
		}

		return new ItemContent(new ReviewAddStyle(), array("recipe" => $recipe));
	});

	//Match for url: /postrecipe
	$sitem->addGetMatch("%^/postrecipe$%", function ($_) use ($sitem, $db)
	{

		$auth = new Authentication();

		if (!$auth->isLoggedIn())
		{
			$itc = new ItemContent(new TextStyle(), array("text" => "Please log in first"));

			$ckr = $sitem->get("/loginForm");
			$arr = array($itc, $ckr);
			return new ContentGroup($arr);
		}

		$rcp = new Recipe();
		
		$rcp->name = $_POST["name"];
		$rcp->description = $_POST["description"];
		$rcp->dishType = $_POST["dishtype"];
		$rcp->amountOfAttention = $_POST["attention"];
		$rcp->difficulty = $_POST["difficulty"];
		$rcp->resultType = $_POST["result"];
		$rcp->manufacturingTime = $_POST["time"];

		$ingr = json_decode($_POST["ingredients"]);
		$rcp->ingredients = $ingr;

		$ret = $db->addRecipe($rcp);
		
		return new ItemContent(new TextStyle(), array("text" => $ret));
	});
	
	//Match for url: /postreview
	$sitem->addGetMatch("%^/postreview$%", function ($_) use ($sitem, $db)
	{

		$auth = new Authentication();

		if (!$auth->isLoggedIn())
		{
			$itc = new ItemContent(new TextStyle(), array("text" => "Please log in first"));

			$ckr = $sitem->get("/loginForm");
			$arr = array($itc, $ckr);
			return new ContentGroup($arr);
		}

		$rev = new RecipeHistory();
		
		$rev->recipe = $_POST["recipeid"];
		$rev->date = date('Y-m-d H:i:s');
		$rev->rating = $_POST["rating"];
		$rev->personalComment = $_POST["comment"];


		$ret = $db->addReview($rev);
		
		return new ItemContent(new TextStyle(), array("text" => $ret));
	});

	//Match for url: /deleterecipe/(id)
	$sitem->addGetMatch("%^/deleterecipe/([0-9]+)$%", function ($rcp) use ($sitem, $db)
	{

		$auth = new Authentication();

		if (!$auth->isLoggedIn())
		{
			$itc = new ItemContent(new TextStyle(), array("text" => "Please log in first"));

			$ckr = $sitem->get("/loginForm");
			$arr = array($itc, $ckr);
			return new ContentGroup($arr);
		}
		
		$ret = $db->deleteRecipe((int)$rcp[1]);
		if (!$ret)
			return new ItemContent(new TextStyle(), array("text" => "No recipe got rekt :(((("));	
		return new ItemContent(new TextStyle(), array("text" => "Da recipe got rekt!"));
	});

	//Match for url: /navbar
	$sitem->addGetMatch("%^/navbar$%", function ($_)
	{
		$auth = new Authentication();
		global $DomainPrefix;

		$links = array(
			"Front" => $DomainPrefix."/",
			"Brouse" => $DomainPrefix."/browse/0",
			"Rewievs" => $DomainPrefix."/browsereview/0",
			"Loggin'" => $DomainPrefix."/loginForm",
			"Aboot" => $DomainPrefix."/about");

		if ($auth->isLoggedIn())
		{
			$links["Write recipe"] = $DomainPrefix."/addrecipe";
		}

		return new ItemContent(new NavBarStyle(), array("links" => $links));
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
	
	$sitem->addGetMatch("%^/about$%", function ($_)
	{
		return new ItemContent(new AbootStyle(),array());
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
