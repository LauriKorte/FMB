<?php
	require_once("/home/H9115/public_html/phpconf/db-init-rcp.php");
	require_once("recipe.php");
	
	class DBMaster
	{
		private $db;
		public function open()
		{
			$this->db = getDBConnection();
		}
		
		private function parseRecipes($recipeResult)
		{
			$return = array();
			foreach ($recipeResult as $rownum => $row)
			{
				$rcp = new Recipe();
				$rcp->id = $row['id'];
				$rcp->name = utf8_encode($row['name']);
				array_push($return, $rcp);
			}
			return $return;
		}
		
		private function parseIngredients($ingredientResult)
		{
			$return = array();
			foreach ($ingredientResult as $rownum => $row)
			{
				$ing = new Ingredient();
				$ing->id = $row['id'];
				$ing->name = utf8_encode($row['name']);
				array_push($return, $ing);
			}
			return $return;
		}
		
		public function getRecipeWithIngredients($id)
		{
			if (is_null($this->db))
			{
				echo("DB connection not established");
				return null;
			}
			$sql = 'SELECT * FROM rcp_recipe WHERE id = :id';
			
			$qr = $this->db->prepare($sql);
			$qr->execute(array('id' => $id));
			$result = $qr->fetchAll();
			
			$rcp = $this->parseRecipes($result);
			$rcp = $rcp[0];
		
			
			$sql = 'SELECT * FROM rcp_recipe_has_ingredient AS rhi
					LEFT JOIN rcp_ingredient AS ing ON rhi.ingredient_id = ing.id 
					WHERE recipe_id = :id';
					
			$qr = $this->db->prepare($sql);
			$qr->execute(array('id' => $id));
			$result = $qr->fetchAll();
			
			$ingredients = $this->parseIngredients($result);
			
			$rcp->ingredients = $ingredients;
			return $rcp;
		}
		
		public function getRecipes($first = 0, $last = -1)
		{
			if (is_null($this->db))
			{
				echo("DB connection not established");
				return array();
			}
			
			$sql = 'SELECT * FROM rcp_recipe ';
						
			$args = array();
			
			if ($last < $first)
			{
				$sql .= 'WHERE ROWNUM >= :first';
				$args = array(':first' => $first);
				
			}
			else
			{
				$sql .= 'LIMIT :first, :count';
				$args = array(':first' => $first, ':count' => $last-$first);
			}
			$qr = $this->db->prepare($sql);
			$qr->execute($args);
			
			
			
			$result = $qr->fetchAll();
			
			return $this->parseRecipes($result);
		}
		
	}
	