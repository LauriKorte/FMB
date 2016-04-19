<?php
require_once(__DIR__ . "/../site/style.php");
class FrontStyle extends Style
{
	public function displayItem($arguments)
	{
		?>
		<div class="container">
			<h1>Welcome to the Food Manufacturing Book</h1>
			This is a cookbook for MEN, who want to find their own way to do stuff.
			<p>
			If you think you are worthy to cook like MEN you can try to guess the 'secret' password for this page.
			</p>
			<p>
			Do this and I might deem you worthy enough to enter. But do it right, or i shall banish you.
			</p>
		</div>
		<?php
	}
}
