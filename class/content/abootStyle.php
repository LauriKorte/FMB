<?php
require_once(__DIR__ . "/../site/style.php");
class AbootStyle extends Style
{
	public function displayItem($arguments)
	{
		?>
		<div class="container">
			<h1>About our Team</h1>
			We are a bunch of students trying to make their way on this Earth and to learn everything about it.
			<p>
			We made this Food Manufacturing Book for we wanted some place to store our precious recipes of our fine cookies and other stuff. This program was also designed to be used as course exercise for the Palvelinohjelmointi IIM50300. It also serves the same purpose for Ohjelmistosuunnittelu IIO11100 course. Program uses our own database we made for Tietokannat TTZC0800 course.
			</p>
			<b>Creators of this program:</b> Lauri 'Master of Destruction' Korte, Antti 'Master of Disaster' Mäkelä &amp; Miro.
		</div>
		<?php
	}
}
