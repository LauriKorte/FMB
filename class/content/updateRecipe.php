<?php
require_once(__DIR__ . "/../site/content.php");
require_once(__DIR__ . "/../site/style.php");

class RecipeUpdateStyle extends Style
{
	public function displayItem($arguments)
	{
		$current = $arguments["recipe"];
		if (!is_null($current))
		{
			global $DomainPrefix;
			global $db;

			$dishtype = $db->getDishType();
			$attn = $db->getAmountOfAttention();
			$mtime = $db->getManufacturingTime();
			$diff = $db->getDifficulty();
			$rtype = $db->getResultType();

			echo ('<div class="col-md-4" >');
			echo ('<form method="post" action="'.$DomainPrefix."/updaterecipe".'" id="recipeAdd">');
			echo ('Name: <input type="text" name="name" value="'.$current->name.'"><br>');
			echo ('Description:<br> <textarea name="description" form="recipeAdd">'.$current->description.'</textarea><br>');
			echo ('<input type="hidden" name="id" value="'.$current->id.'">');

			echo ('Dishtype: <select name="dishtype">');
			foreach ($dishtype as $dt)
			{
				if ($current->dishType == $dt->id)
					echo ("<option value='{$dt->id}' selected='selected'>$dt->name</option>");
				else
					echo ("<option value='{$dt->id}'>$dt->name</option>");
			}
			echo ('</select><br>');

			echo ('Amount of attention: <select name="attention">');
			foreach ($attn as $at)
			{
				if ($current->amountOfAttention == $at->id)
					echo ("<option value='{$at->id}' selected='selected'>{$at->name}</option>");
				else
					echo ("<option value='{$at->id}'>{$at->name}</option>");
			}
			echo ('</select><br>');	

			echo ('Difficulty: <select name="difficulty">');
			foreach ($diff as $at)
			{
				if ($current->difficulty == $at->id)
					echo ("<option value='{$at->id}' selected='selected'>{$at->name}</option>");
				else
					echo ("<option value='{$at->id}'>{$at->name}</option>");
			}
			echo ('</select><br>');	

			echo ('Result type: <select name="result">');
			foreach ($rtype as $at)
			{
				if ($current->resultType == $at->id)
					echo ("<option value='{$at->id}' selected='selected'>{$at->name}</option>");
				else
					echo ("<option value='{$at->id}'>{$at->name}</option>");
			}
			echo ('</select><br>');	

			echo ('Manufacturing time: <select name="time">');
			foreach ($mtime as $at)
			{
				$timed = "{$at->minimumTime} - {$at->maximumTime} minutes";
				$inHours = $at->minimumTime / 60;
				if ($inHours > 3)
				{
					if ($at->maximumTime < $at->minimumTime)
						$timed = ((string)($at->minimumTime / 60))."+  hours";
					else
						$timed = ((string)($at->minimumTime / 60))." - ".((string)($at->maximumTime / 60))." hours";
				}
				if ($current->manufacturingTime == $at->id)
					echo ("<option value='{$at->id}' selected='selected'>{$at->name} ({$timed})</option>");
				else
					echo ("<option value='{$at->id}'>{$at->name} ({$timed})</option>");
			}
			echo ('</select><br>');	

			//Some JS hax

			//make ingredients selectable and fill a hidden form value with all the selected ingredients in JSON format
			?>
			<input id="ingListJSON" type="hidden" name="ingredients" value="[]"/>

			<div id="ingList">
			</div>
			<br>
			Recipe:
			<br>
			<div id="targetList">
			</div>

			<script>
			var ings = [
			<?php

			//Print available ingredients
			$ings = $db->getIngredientsWithUnits();

			foreach ($ings as $ing)
			{
				echo('{name : "'.$ing->name.'", unit: "'.$ing->unitName.'", id: '.$ing->id.'},');
			}

			?>
			];
			
			var preings = {};
			<?php
				foreach ($current->ingredients as $ing)
				{
					echo('preings['.$ing->id.'] = '.$ing->amount.';');
				}
			?>
			var tarings = {};

			var ilist = document.getElementById("ingList");
			var tlist = document.getElementById("targetList");
			
			var addToT = function(ing, amt)
			{
				let tli = document.createElement("button");
				tli.type = "button";
				tli.className = "btn btn-warning";
				ing.tli = tli;
				tli.innerHTML = ing.name+" "+amt+" "+ing.unit;
				tlist.appendChild(tli);
				tarings[ing.id] = amt;
				
				tli.onclick = function()
				{
					tlist.removeChild(tli);
					ing.li.style.display = "";
					delete tarings[ing.id];
					toLidArray();
				}
				toLidArray();
			}
			
			
			var toLidArray = function()
			{
				var lid = [];
				for (var ingid in tarings)
				{
					if (!tarings.hasOwnProperty(ingid))
						continue;
					lid.push({id: ingid, amount: tarings[ingid]});
				}
				document.getElementById("ingListJSON").value = JSON.stringify(lid);
				
			}
			

			for (var i = 0; i < ings.length; i++)
			{
				let ouring = ings[i];
				let li = document.createElement("button");
				li.type = "button";
				li.className = "btn btn-default";
				ouring.li = li;	
				li.innerHTML = ouring.name;
				li.onclick = function()
				{
					var amt = window.prompt("How much "+ouring.name+" ("+ouring.unit+")","1");
					var num = Number(amt);
					if (isNaN(num))
						return;
					li.style.display = "none";
					addToT(ouring, amt);
					
				};
				ilist.appendChild(li);
				
				if (ouring.id in preings)
				{
					
					li.style.display = "none";
					addToT(ouring, preings[ouring.id]);
				}
				
			}

			</script>
			<?php

			echo ('<p><input type="submit" class="btn btn-success" value="Update Repice, update!!!"></input>');
			echo ("</form> </div>");
		}
		else
		{
			echo ('No recipes here!');
		}
	}
}
