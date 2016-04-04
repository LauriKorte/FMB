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
		
		public function getRecipes($first = 0, $last = -1)
		{
			if (is_null($this->db))
			{
				echo("DB connection not established");
				return array();
			}
			
			$sql =
			'
				SELECT rcp.id, rcp.name, rcp.description, rt.name AS resultType, diff.name AS difficulty, aoa.name AS amountOfAttention, dt.name AS dishType, mft.name AS manufacturingTime
				FROM rcp_recipe AS rcp 
				LEFT JOIN rcp_manufacturingTime AS mft ON rcp.manufacturingTime_id = mft.id
				LEFT JOIN rcp_resultType AS rt ON rcp.resultType_id = rt.id
				LEFT JOIN rcp_difficulty AS diff ON rcp.difficulty_id = diff.id
				LEFT JOIN rcp_amountOfAttention AS aoa ON rcp.amountOfAttention_id = aoa.id
				LEFT JOIN rcp_dishType AS dt ON rcp.dishType_id = dt.id
			';
						
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
			$return = array();
			
			
			foreach ($result as $rownum => $row)
			{
				$rcp = new Recipe();
				$rcp->id = $row['id'];
				$rcp->name = utf8_encode($row['name']);
				array_push($return, $rcp);
			}
			return $return;
		}
		
	}
	