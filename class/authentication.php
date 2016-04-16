<?php

class Authentication
{
	private $authStage;

	private static function getAuthenticationLevel($id)
	{
		if ($id == 1337)
			return 10;
		return 0;
	}

	private static function authenticate($uid, $passwd)
	{
		Authentication::logout();

		if ($uid == "admin" && $passwd == "secret")
		{
			$_SESSION["loginId"] = 1337;
			return 10;
		}	
		return 0;
	}

	public static function logout()
	{
		if (isset($_SESSION["loginId"]))
			unset($_SESSION["loginId"]);
	}

	public function __construct()
	{
		$this->authStage = 0;
		if (isset($_POST["loginUser"]) && isset($_POST["loginPass"]))
		{
			$this->authStage = Authentication::authenticate($_POST["loginUser"], $_POST["loginPass"]);
		}
		else if (isset($_SESSION["loginId"]))
		{
			$this->authStage = Authentication::getAuthenticationLevel($_SESSION["loginId"]);
		}
	}

	public function allowDBWrite()
	{
		if ($this->authStage > 1)
			return true;
		return false;
	}

	public function isLoggedIn()
	{
		if ($this->authStage == 0)
			return false;
		return true;
	}
}