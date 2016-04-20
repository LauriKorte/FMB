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
		echo ('<div class="container">');
		?>
		<form method="post" action="<?php echo($arguments['address']);?>">
			<table>
				<tr>
					<td>Username:</td>
					<td><input type="text" name="loginUser"/></td>	
				</tr>
				<tr>
					<td>Password:</td>
					<td><input type="password" name="loginPass"/></td>	
				</tr>
			</table>
			<input type="submit" name="button" class="btn btn-primary btn-lg" value="Login" />
		</form>
		<?php
	}
	public function displayItemEnd()
	{
		echo ("</div>");
	}
	public function displayFooter()
	{
	}
}
