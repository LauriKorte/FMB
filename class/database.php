<?php
	require_once("/home/H9115/public_html/phpconf/db-init-rcp.php");
	require_once("recipe.php");
	
	class DBMaster
	{
		protected function convertCharSet($str)
		{
			return $str;
		}

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
				$rcp->name = $this->convertCharSet($row['name']);
				$rcp->description = $this->convertCharSet($row['description']);
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
				$ing->name = $this->convertCharSet($row['name']);

				if (isset($row['amount']))
					$ing->amount = $row['amount'];

				if (isset($row['unitname']))
					$ing->unitName = $row['unitname'];
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

			if (count($result) == 0)
			{
				return null;
			}
			
			$rcp = $this->parseRecipes($result);
			$rcp = $rcp[0];
			
			$sql = 'SELECT ing.id as id, ing.name as name, rhi.amount as amount, ingunit.shorthand as unitname
					FROM rcp_recipe_has_ingredient AS rhi
					LEFT JOIN rcp_ingredient AS ing ON rhi.ingredient_id = ing.id
					LEFT JOIN rcp_ingredientUnit as ingunit ON ing.ingredientUnit_id = ingunit.id
					WHERE recipe_id = :id';
					
			$qr = $this->db->prepare($sql);
			$qr->execute(array('id' => $id));
			$result = $qr->fetchAll();
			
			$ingredients = $this->parseIngredients($result);
			
			$rcp->ingredients = $ingredients;
			return $rcp;
		}

		private function doRangedQuery($first,$last,$sql)
		{
			$qr;
			if ($last < $first)
			{
				//MySQL has some... limitations
				//or at least I do
				$sql .= 'LIMIT :beg, 18446744073709551615';
				$qr = $this->db->prepare($sql);
				$qr->bindValue(':beg', $first, PDO::PARAM_INT);
				
			}
			else
			{
				$sql .= 'LIMIT :beg, :end';
				
				$qr = $this->db->prepare($sql);
				$qr->bindValue(':beg', $first, PDO::PARAM_INT);
				$qr->bindValue(':end', $last-$first, PDO::PARAM_INT);
			}
			$qr->execute();
			return $qr->fetchAll();
		}
		
		public function getRecipes($first = 0, $last = -1)
		{
			if (is_null($this->db))
			{
				echo("DB connection not established");
				return array();
			}
			
			$sql = 'SELECT * FROM rcp_recipe ';
			
			$result = $this->doRangedQuery($first,$last,$sql);
			
			return $this->parseRecipes($result);
		}

		public function getIngredients($first = 0, $last = -1)
		{
			if (is_null($this->db))
			{
				echo("DB connection not established");
				return array();
			}
			
			$sql = 'SELECT * FROM rcp_ingredient ';
			
			$result = $this->doRangedQuery($first,$last,$sql);
			
			return $this->parseIngredients($result);
		}
		
	}
	
