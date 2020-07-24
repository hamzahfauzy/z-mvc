<?php

class QueryBuilder
{

    public $connection = '';
    public $sql = "";
	public $is_where = 0;
	public $is_select = 0;
	public $is_insert = 0;
	public $last_id = 0;
    
    function __construct()
    {
        $this->connection = new Connection;
	}
	

    function select($tbl)
	{
		if(!$this->is_select)
			$this->is_select = 1;
		$this->sql = "SELECT * FROM $tbl";
		return $this;
	}

	function update($tbl,$set)
	{
		$_set = "";
		$index = 0;
		$count = count($set);
		foreach ($set as $key => $value) {
		    if($value == "NULL" || $value == "CURRENT_TIMESTAMP")
    			$_set .= $key."=".$value;
    		else
			$_set .= $key."='".$value."'";
			if($index < $count-1)
				$_set .= ", ";
			$index++;
		}
		$this->sql = "UPDATE $tbl SET $_set ";
		return $this;
	}

	function insert($tbl,$set)
	{
		$fields = implode(",", array_keys($set));
		$values = implode(",", array_map(function($val){
			return $val == "NULL" || $val == "CURRENT_TIMESTAMP" ? $val : "'$val'";
		},array_values($set))
		);

		if(!$this->is_insert)
			$this->is_insert = 1;
		$this->sql = "INSERT INTO $tbl ($fields) VALUES ($values)";
		return $this;
	}

	function delete($tbl)
	{
		$this->sql = "DELETE FROM $tbl ";
		return $this;
	}

	function where($clause1, $clause2, $clause3 = false)
	{
		if(!$this->is_where)
		{
			$this->is_where = 1;
			$this->sql .= " WHERE ";
		}
		else
		{
			$this->sql .= " AND ";
		}
		if($clause3 == false)
		    $this->sql .= " $clause1='$clause2'";
    	else
    	{
    	    $this->sql .= " $clause1 $clause2 '$clause3'";
    	}
    	
		return $this;
	}

	function whereIn($field, $clauses)
	{
		if(!$this->is_where)
		{
			$this->is_where = 1;
			$this->sql .= " WHERE ";
		}
		else
		{
			$this->sql .= " AND ";
		}
		$clause = "('".implode("','",$clauses)."')";
		$this->sql .= " $field IN $clause";
		return $this;
	}

	function whereNotIn($field, $clauses)
	{
		if(!$this->is_where)
		{
			$this->is_where = 1;
			$this->sql .= " WHERE ";
		}
		else
		{
			$this->sql .= " AND ";
		}
		$clause = "('".implode("','",$clauses)."')";
		$this->sql .= " $field NOT IN $clause";
		return $this;
	}

	function orWhereIn($field, $clauses)
	{
		if(!$this->is_where)
		{
			$this->is_where = 1;
			$this->sql .= " WHERE ";
		}
		else
		{
			$this->sql .= " OR ";
		}
		$clause = "('".implode("','",$clauses)."')";
		$this->sql .= " $field IN $clause";
		return $this;
	}

	function orwhere($clause1, $clause2, $clause3 = false)
	{
	    if($clause3 == false)
    		$this->sql .= " OR $clause1='$clause2'";
    	else
    	    $this->sql .= " OR $clause1 $clause2 '$clause3'";
    	    
		return $this;
	}
	
	function orderby($value)
	{
	    
    	$this->sql .= " ORDER BY $value";
		return $this;
	}
	
	function setlimit($value)
	{
	    
    	$this->sql .= " LIMIT $value";
		return $this;
	}

	function run($type = false)
	{
		if($this->is_select)
		{
            $rows = $this->connection->query($this->sql) or showError($this->connection->error,'mysql');
			if($type)
                return $rows->fetch_object();
			else
			{
				// $return = [];
				// while($rs = $rows->fetch_object())
				// {
				// 	$return[] = $rs;
				// }

                // return $return;
                return $rows->fetch_all(MYSQLI_ASSOC);
			}
		}
		if(!empty($this->sql))
		{
			$this->connection->query($this->sql) or showError($this->connection->error,'mysql');
			$this->last_id = $this->is_insert ? $this->connection->insert_id : 0;
			return $this->last_id == 0 ? true : $this->last_id; //$this->connection;
		}
	}

	function runCount()
	{
		$sql = str_replace("SELECT *","SELECT COUNT(*) AS COUNT_TOTAL",$this->sql);
		$rows = $this->connection->query($sql) or showError($this->connection->error,'mysql');
		$rows = $rows->fetch_object();
        return $rows->COUNT_TOTAL;
	}



}