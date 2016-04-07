<?php
require_once(__DIR__ . "/../site/content.php");
require_once(__DIR__ . "/../site/style.php");

class LoginFormStyle extends Style
{
	public function displayHeader()
	{
	}
	public function displayItem($arguments)
	{
		?>
		<form method="post" action="<?php echo($arguments['address']);?>">
		Username:<input type="text" name="loginUser"><br />
		Password:<input type="password" name="loginPass"><br />
		<input type="submit" name="button" value="login" />
		</form>
		<?php
	}
	public function displayItemEnd()
	{
		echo ("<div/>");
	}
	public function displayFooter()
	{
	}
}