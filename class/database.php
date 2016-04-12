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
				$rcp->description = $this->convertCharSet($row['description']);
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
	
