<?php

class Model 
{
	public static $_tbl;
	public static $_fields;
	public static $QueryBuilder;
	public static $_orderby = "";
	public static $_limit = "";
	public static $where_queue = [];

	function __construct()
	{
		self::init();
	}

	public static function findParam($name,$value,$class)
	{
		self::init();
		$rows = self::$QueryBuilder->select(self::$_tbl)->where($name,$value)->run(1);
		$obj = new $class;
		if(!empty($rows))
			foreach ($rows as $key => $value) {
				$obj->{$key} = $value;
			}
		return $obj;
	}

	public static function init()
	{
        self::$QueryBuilder = new QueryBuilder;
        if(isset(static::$table))
            self::$_tbl = static::$table;
        else
        {
            $reflect = new ReflectionClass(get_called_class());
            $tbl = strtolower($reflect->getShortName());
			$last_char = substr($tbl, -1);
            if($last_char == 'y')
                $tbl = substr($tbl,0,-1).'ies';
            elseif($last_char == 's')
                $tbl = $tbl.'es';
            else
                $tbl = $tbl.'s';
            
            self::$_tbl = $tbl;
        }
		self::$_fields = isset(static::$fields) ? static::$fields : "";
	}

	public static function count()
	{
		self::init();
		self::$QueryBuilder->select(self::$_tbl);

		$className = get_called_class();

		$model = new ReflectionClass($className);
		$modelProperties = $model->getProperties();
		$modelStaticProperties = $model->getStaticProperties();
		$modelProperties = array_filter($modelProperties, function($arr) use ($className, $modelStaticProperties) {
			if($arr->class == $className && !isset($modelStaticProperties[$arr->name]) && !$arr->isProtected())
				return TRUE;
			else
				return FALSE;
		});

		foreach($modelProperties as $key => $value)
		{
			// $_class = new $value->class;
			$_className = explode('\\', $value->class);
			$_className = array_pop($_className);
			$prop = preg_replace('/(?<!\ )[A-Z]/', '_$0', $_className);
			$prop = trim($prop,"_");
			self::$where_queue[] = [
				"type" => "AND",
				"key" => $value->name,
				"value" => strtolower($prop)
			];
			// self::where($value->name,$model->getProperty($value->name)->getValue());
		}

		if(count(self::$where_queue))
		{
			foreach (self::$where_queue as $key => $value) {
				if($value['type'] == "AND")
				{
					if(is_array($value['value']))
				    {
				        self::$QueryBuilder->where($value['key'],$value['value'][0],$value['value'][1]);
				    }
				    else
				    {
				        self::$QueryBuilder->where($value['key'],$value['value']);
				    }
				}

				if($value['type'] == "OR")
				{
					if(is_array($value['value']))
				    {
				        self::$QueryBuilder->orwhere($value['key'],$value['value'][0],$value['value'][1]);
				    }
				    else
				    {
				        self::$QueryBuilder->orwhere($value['key'],$value['value']);
				    }
				}

				if($value['type'] == 'IN')
				{
					self::$QueryBuilder->whereIn($value['key'],$value['value']);
				}

				if($value['type'] == 'NOT IN')
				{
					self::$QueryBuilder->whereNotIn($value['key'],$value['value']);
				}

				if($value['type'] == 'ORIN')
				{
					self::$QueryBuilder->OrWhereIn($value['key'],$value['value']);
				}
			}
		}
		if(self::$_orderby != "")
		{
		    self::$QueryBuilder->orderby(self::$_orderby);
		}
		
		if(self::$_limit != "")
		{
		    self::$QueryBuilder->setlimit(self::$_limit);
		}
		self::$where_queue = [];
		self::$_orderby = "";
		self::$_limit = "";
		return self::$QueryBuilder->runCount();
	}

	public static function get()
	{
		self::init();
		self::$QueryBuilder->select(self::$_tbl);

		$className = get_called_class();

		$model = new ReflectionClass($className);
		$modelProperties = $model->getProperties();
		$modelStaticProperties = $model->getStaticProperties();
		$modelProperties = array_filter($modelProperties, function($arr) use ($className, $modelStaticProperties) {
			if($arr->class == $className && !isset($modelStaticProperties[$arr->name]) && !$arr->isProtected())
				return TRUE;
			else
				return FALSE;
		});

		foreach($modelProperties as $key => $value)
		{
			// $_class = new $value->class;
			$_className = explode('\\', $value->class);
			$_className = array_pop($_className);
			$prop = preg_replace('/(?<!\ )[A-Z]/', '_$0', $_className);
			$prop = trim($prop,"_");
			self::$where_queue[] = [
				"type" => "AND",
				"key" => $value->name,
				"value" => strtolower($prop)
			];
			// self::where($value->name,$model->getProperty($value->name)->getValue());
		}

		if(count(self::$where_queue))
		{
			foreach (self::$where_queue as $key => $value) {
				if($value['type'] == "AND")
				{
					if(is_array($value['value']))
				    {
				        self::$QueryBuilder->where($value['key'],$value['value'][0],$value['value'][1]);
				    }
				    else
				    {
				        self::$QueryBuilder->where($value['key'],$value['value']);
				    }
				}

				if($value['type'] == "OR")
				{
					if(is_array($value['value']))
				    {
				        self::$QueryBuilder->orwhere($value['key'],$value['value'][0],$value['value'][1]);
				    }
				    else
				    {
				        self::$QueryBuilder->orwhere($value['key'],$value['value']);
				    }
				}

				if($value['type'] == 'IN')
				{
					self::$QueryBuilder->whereIn($value['key'],$value['value']);
				}

				if($value['type'] == 'NOT IN')
				{
					self::$QueryBuilder->whereNotIn($value['key'],$value['value']);
				}

				if($value['type'] == 'ORIN')
				{
					self::$QueryBuilder->OrWhereIn($value['key'],$value['value']);
				}
			}
		}
		if(self::$_orderby != "")
		{
		    self::$QueryBuilder->orderby(self::$_orderby);
		}
		
		if(self::$_limit != "")
		{
		    self::$QueryBuilder->setlimit(self::$_limit);
		}
		self::$where_queue = [];
		self::$_orderby = "";
		self::$_limit = "";
		$data = self::$QueryBuilder->run();
		if(empty($data))
			return $data;
		$modelName = get_called_class();
		$model = [];
		foreach ($data as $key => $value) {
			$model[$key] = new $modelName;
			foreach ($value as $k => $val) {
				$model[$key]->{$k} = $val;
			}
		}
		

		return $model;
	}

	public static function first()
	{
		self::init();
		self::$QueryBuilder->select(self::$_tbl);

		$className = get_called_class();
		$model = new ReflectionClass($className);
		$modelProperties = $model->getProperties();
		$modelStaticProperties = $model->getStaticProperties();
		$modelProperties = array_filter($modelProperties, function($arr) use ($className, $modelStaticProperties) {
			if($arr->class == $className && !isset($modelStaticProperties[$arr->name]) && !$arr->isProtected())
				return TRUE;
			else
				return FALSE;
		});

		foreach($modelProperties as $key => $value)
		{
			// $_class = new $value->class;
			$_className = explode('\\', $value->class);
			$_className = array_pop($_className);
			$prop = preg_replace('/(?<!\ )[A-Z]/', '_$0', $_className);
			$prop = trim($prop,"_");
			self::$where_queue[] = [
				"type" => "AND",
				"key" => $value->name,
				"value" => strtolower($prop)
			];
			// self::where($value->name,$model->getProperty($value->name)->getValue());
		}

		if(count(self::$where_queue))
		{
			foreach (self::$where_queue as $key => $value) {
				if($value['type'] == "AND")
				{
					if(is_array($value['value']))
				    {
				        self::$QueryBuilder->where($value['key'],$value['value'][0],$value['value'][1]);
				    }
				    else
				    {
				        self::$QueryBuilder->where($value['key'],$value['value']);
				    }
				}

				if($value['type'] == "OR")
				{
					if(is_array($value['value']))
				    {
				        self::$QueryBuilder->orwhere($value['key'],$value['value'][0],$value['value'][1]);
				    }
				    else
				    {
				        self::$QueryBuilder->orwhere($value['key'],$value['value']);
				    }
				}

				if($value['type'] == 'IN')
				{
					self::$QueryBuilder->whereIn($value['key'],$value['value']);
				}

				if($value['type'] == 'NOT IN')
				{
					self::$QueryBuilder->whereNotIn($value['key'],$value['value']);
				}

				if($value['type'] == 'ORIN')
				{
					self::$QueryBuilder->OrWhereIn($value['key'],$value['value']);
				}
			}
		}
		if(self::$_orderby != "")
		{
		    self::$QueryBuilder->orderby(self::$_orderby);
		}
		
		if(self::$_limit != "")
		{
		    self::$QueryBuilder->limit(self::$_limit);
		}
		self::$where_queue = [];
		self::$_orderby = "";
		self::$_limit = "";

		$data = self::$QueryBuilder->run(1);
		if(empty($data))
			return $data;
		$modelName = get_called_class();
		$model = new $modelName();
		foreach ($data as $key => $value) {
			$model->{$key} = $value;
		}

		return $model;
	}

	public static function last_id()
	{
	    print_r(self::$QueryBuilder);
		return self::$QueryBuilder->last_id;
	}

	public static function find($id)
	{
		self::init();
		$PrimaryKey = self::getPrimaryKey();
		$className = get_called_class();
		$model = new ReflectionClass($className);
		$modelProperties = $model->getProperties();
		$modelStaticProperties = $model->getStaticProperties();
		$modelProperties = array_filter($modelProperties, function($arr) use ($className, $modelStaticProperties) {
			if($arr->class == $className && !isset($modelStaticProperties[$arr->name]) && !$arr->isProtected())
				return TRUE;
			else
				return FALSE;
		});

		self::$QueryBuilder->select(self::$_tbl)->where($PrimaryKey,$id);

		foreach($modelProperties as $key => $value)
		{
			$_className = explode('\\', $value->class);
			$_className = array_pop($_className);
			$prop = preg_replace('/(?<!\ )[A-Z]/', '_$0', $_className);
			$prop = trim($prop,"_");
			self::$QueryBuilder->where($value->name,strtolower($prop));
		}

		$data = self::$QueryBuilder->run(1);;
		if(empty($data))
			return $data;
		$modelName = get_called_class();
		$model = new $modelName();
		foreach ($data as $key => $value) {
			$model->{$key} = $value;
		}

		return $model;
	}

	public static function where($clause1, $clause2, $clause3 = false)
	{
		self::$where_queue[] = [
			"type" => "AND",
			"key" => $clause1,
			"value" => $clause3==false ? $clause2 : [$clause2, $clause3]
		];

		return new static;
	}

	public static function whereIn($field, $clause)
	{
		self::$where_queue[] = [
			"type" => "IN",
			"key" => $field,
			"value" => $clause
		];

		return new static;
	}

	public static function whereNotIn($field, $clause)
	{
		self::$where_queue[] = [
			"type" => "NOT IN",
			"key" => $field,
			"value" => $clause
		];

		return new static;
	}
	
	public static function orwhere($clause1, $clause2, $clause3 = false)
	{
		self::$where_queue[] = [
			"type" => "OR",
			"key" => $clause1,
			"value" => $clause3==false ? $clause2 : [$clause2, $clause3]
		];
		
		return new static;
	}

	public static function orWhereIn($field, $clause)
	{
		self::$where_queue[] = [
			"type" => "ORIN",
			"key" => $field,
			"value" => $clause
		];

		return new static;
	}
	
	public static function orderby($clause, $sort = "asc")
	{
		self::$_orderby = " $clause $sort";
		
		return new static;
	}
	
	public static function limit($number_rows)
	{
		self::$_limit = $number_rows;
		
		return new static;
	}

	public function hasOne($class, $criteria = array())
	{
		$model = new $class;
		if($criteria){
			foreach($criteria as $key => $value){
				$model->where($key,$this->{$value});
			}
		}
		$data = $model->first();
		$method = debug_backtrace()[1]['function'];
		debug_backtrace()[0]['object']->{$method} = $data;
		return $data;
	}

	public function hasMany($class, $criteria = array())
	{
		$model = new $class;
		if($criteria){
			foreach($criteria as $key => $value){
				$model->where($key,$this->{$value});
			}
		}
		$data = $model->get();
		$method = debug_backtrace()[1]['function'];
		debug_backtrace()[0]['object']->{$method} = $data;
		return $data;
	}

	public function belongsToMany($class, $criteria = array())
	{
		$modelName = get_called_class();
	}

	public static function delete($id)
	{
		self::init();
		$PrimaryKey = self::getPrimaryKey();
		return self::$QueryBuilder->delete(self::$_tbl)->where($PrimaryKey,$id)->run();
	}

	public function save($param=false)
	{
	    self::init();
		if($param == false)
		{
			$param = [];
			foreach (self::$_fields as $key => $value) {
				if(isset($this->{$value}))
					$param[$value] = $this->{$value};
			}
		}

		$className = get_called_class();
		$model = new ReflectionClass($className);
		$modelProperties = $model->getProperties();
		$modelStaticProperties = $model->getStaticProperties();
		$modelProperties = array_filter($modelProperties, function($arr) use ($className, $modelStaticProperties) {
			if($arr->class == $className && !isset($modelStaticProperties[$arr->name]) && !$arr->isProtected())
				return TRUE;
			else
				return FALSE;
		});

		foreach($modelProperties as $key => $value)
		{
			if(isset($param[$value->name]))
				continue;

			$_className = explode('\\', $value->class);
			$_className = array_pop($_className);
			$prop = preg_replace('/(?<!\ )[A-Z]/', '_$0', $_className);
			$prop = trim($prop,"_");
			$param[$value->name] = strtolower($prop);
		}

		$PrimaryKey = self::getPrimaryKey();
		if(isset($this->{$PrimaryKey}))
		{
			$rows = self::find($this->{$PrimaryKey});
			if(!empty($rows))
			{
				self::$QueryBuilder->is_select = 0;
				self::$QueryBuilder->is_where = 0;
				return self::$QueryBuilder->update(self::$_tbl,$param)->where($PrimaryKey,$this->{$PrimaryKey})->run();
			}
		}
		return self::$QueryBuilder->insert(self::$_tbl,$param)->run();
	}

	public static function getPrimaryKey()
	{
		self::init();
		$QueryBuilder = new QueryBuilder;
		$QueryBuilder->sql = "SHOW index FROM ".self::$_tbl." WHERE Key_name = 'PRIMARY'";
		$QueryBuilder->is_select = 1;
		$data = $QueryBuilder->run(1);
		return $data->Column_name;
	}

	public static function runRaw($sql)
	{
		self::init();
		$QueryBuilder = new QueryBuilder;
		$QueryBuilder->is_select = 1;
		$QueryBuilder->sql = $sql;
		return $QueryBuilder->run();
	}
}