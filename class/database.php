<?php
	require_once("../db-init.php");
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

		public function getIngredientsWithUnits()
		{
			if (is_null($this->db))
			{
				echo("DB connection not established");
				return null;
			}

			$sql = 'SELECT ing.id as id, ing.name as name, ingunit.shorthand as unitname
					FROM rcp_ingredient AS ing
					LEFT JOIN rcp_ingredientUnit as ingunit ON ing.ingredientUnit_id = ingunit.id';
					
			$qr = $this->db->prepare($sql);
			$qr->execute();
			$result = $qr->fetchAll();
			return $this->parseIngredients($result);
			
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

		public function getRecipeCount()
		{
			if (is_null($this->db))
			{
				echo("DB connection not established");
				return array();
			}
			
			$sql = 'SELECT COUNT(*) as cnt FROM rcp_recipe';
			
			$qr = $this->db->prepare($sql);
			$qr->execute();
			
			return $qr->fetch()["cnt"];
		}


		public function addRecipe($rcp)
		{
			if (is_null($this->db))
			{
				echo("DB connection not established");
				return array();
			}
			
			$sql = 'INSERT INTO rcp_recipe 
					(name,description,
					manufacturingTime_id,resultType_id,
					amountOfAttention_id,difficulty_id,dishType_id)
					values
					(:name, :description, :mtime, :rtype, :attn, :diff, :dishtype);';

			$qr = $this->db->prepare($sql);
			$qr->bindValue(':name', $rcp->name, PDO::PARAM_STR);
			$qr->bindValue(':description', $rcp->description, PDO::PARAM_STR);
			$qr->bindValue(':mtime', $rcp->manufacturingTime, PDO::PARAM_INT);
			$qr->bindValue(':rtype', $rcp->resultType, PDO::PARAM_INT);
			$qr->bindValue(':attn', $rcp->amountOfAttention, PDO::PARAM_INT);
			$qr->bindValue(':diff', $rcp->difficulty, PDO::PARAM_INT);
			$qr->bindValue(':dishtype', $rcp->dishType, PDO::PARAM_INT);
			
			$res = $qr->execute();
			if (!$res)
				return false;

			if (count($rcp->ingredients) == 0)
				return true;
			$lid = $this->db->lastInsertId();

			$params = array();
			$sql = 'INSERT INTO rcp_recipe_has_ingredient (recipe_id, ingredient_id, amount) values ';
			$isFirst = true;
			foreach ($rcp->ingredients as $ing)
			{
				if (!$isFirst)
					$sql .= ',';
				$isFirst = false;
				$sql .= '( ? ,  ?, ? )';
				array_push($params, (int)$lid);
				array_push($params, (int)$ing->id);
				array_push($params, (int)$ing->amount);

			}
			$qr = $this->db->prepare($sql);
			$res = $qr->execute($params);
			
			print_r($qr->fetch());
			return $res;
		}

		public function deleteRecipe($rid)
		{
			if (is_null($this->db))
			{
				echo("DB connection not established");
				return array();
			}
			$sql = 'DELETE FROM rcp_recipe_has_ingredient WHERE recipe_id=:iad ';
			$qr = $this->db->prepare($sql);
			$qr->bindValue(':iad', $rid, PDO::PARAM_INT);
			if (!$qr->execute())
				return false;
			$sql = 'DELETE FROM rcp_recipe WHERE id=:iad ';

			$qr = $this->db->prepare($sql);

			$qr->bindValue(':iad', $rid, PDO::PARAM_INT);
			
			return $qr->execute();
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
/*--------------------------------Miun muutokset--------------------------------------*/	
		private function parseDifficulty($recipeResult)
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
		
		public function getDifficulty($first = 0, $last = -1)
		{
			if (is_null($this->db))
			{
				echo("DB connection not established");
				return array();
			}
			
			$sql = 'SELECT * FROM rcp_difficulty ';
			
			$result = $this->doRangedQuery($first,$last,$sql);
			
			return $this->parseDifficulty($result);
		}
		
		private function parseAmountOfAttention($recipeResult)
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
		
		public function getAmountOfAttention($first = 0, $last = -1)
		{
			if (is_null($this->db))
			{
				echo("DB connection not established");
				return array();
			}
			
			$sql = 'SELECT * FROM rcp_amountOfAttention ';
			
			$result = $this->doRangedQuery($first,$last,$sql);
			
			return $this->parseAmountOfAttention($result);
		}
		
		private function parseDishType($recipeResult)
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
		
		public function getDishType($first = 0, $last = -1)
		{
			if (is_null($this->db))
			{
				echo("DB connection not established");
				return array();
			}
			
			$sql = 'SELECT * FROM rcp_dishType ';
			
			$result = $this->doRangedQuery($first,$last,$sql);
			
			return $this->parseDishType($result);
		}
		
		private function parseIngredientType($recipeResult)
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
		
		public function getIngredientType($first = 0, $last = -1)
		{
			if (is_null($this->db))
			{
				echo("DB connection not established");
				return array();
			}
			
			$sql = 'SELECT * FROM rcp_ingredientType ';
			
			$result = $this->doRangedQuery($first,$last,$sql);
			
			return $this->parseIngredientType($result);
		}
		
		private function parseIngredientStorage($recipeResult)
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
		
		public function getIngredientStorage($first = 0, $last = -1)
		{
			if (is_null($this->db))
			{
				echo("DB connection not established");
				return array();
			}
			
			$sql = 'SELECT * FROM rcp_ingredientStorage ';
			
			$result = $this->doRangedQuery($first,$last,$sql);
			
			return $this->parseIngredientStorage($result);
		}
		
		private function parseIngredientUnit($recipeResult)
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
		
		public function getIngredientUnit($first = 0, $last = -1)
		{
			if (is_null($this->db))
			{
				echo("DB connection not established");
				return array();
			}
			
			$sql = 'SELECT * FROM rcp_ingredientUnit ';
			
			$result = $this->doRangedQuery($first,$last,$sql);
			
			return $this->parseIngredientUnit($result);
		}
		
		private function parseManufacturingTime($recipeResult)
		{
			$return = array();
			foreach ($recipeResult as $rownum => $row)
			{
				$rcp = new Recipe();
				$rcp->id = $row['id'];
				$rcp->name = $this->convertCharSet($row['name']);
				$rcp->minimumTime = $this->convertCharSet($row['minimumTime']);
				$rcp->maximumTime = $this->convertCharSet($row['maximumTime']);
				array_push($return, $rcp);
			}
			return $return;
		}
		
		public function getManufacturingTime($first = 0, $last = -1)
		{
			if (is_null($this->db))
			{
				echo("DB connection not established");
				return array();
			}
			
			$sql = 'SELECT * FROM rcp_manufacturingTime ';
			
			$result = $this->doRangedQuery($first,$last,$sql);
			
			return $this->parseManufacturingTime($result);
		}
		
		private function parseRating($recipeResult)
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
		
		public function getRating($first = 0, $last = -1)
		{
			if (is_null($this->db))
			{
				echo("DB connection not established");
				return array();
			}
			
			$sql = 'SELECT * FROM rcp_rating ';
			
			$result = $this->doRangedQuery($first,$last,$sql);
			
			return $this->parseRating($result);
		}
		
		private function parseResultType($recipeResult)
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
		
		public function getResultType($first = 0, $last = -1)
		{
			if (is_null($this->db))
			{
				echo("DB connection not established");
				return array();
			}
			
			$sql = 'SELECT * FROM rcp_resultType ';
			
			$result = $this->doRangedQuery($first,$last,$sql);
			
			return $this->parseResultType($result);
		}
	}
	
