<?php
namespace elipa\database;
use PDO;
class ORM_Model
{
	function __construct($connection_str)
	{
		try{
		$port =(isset($connection_str['port']))?$connection_str['port']: 3306;
		$this->dbh = new PDO("mysql:dbname=".$connection_str['dbname'].";port=".$port.";host=".$connection_str['host'],$connection_str['user'],$connection_str['pass']);
		$this->dbh->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
		}catch(\PDOException $e)
		{
			unset($this->dbh);
			$returnval['status'] = 404;
			$returnval['message'] = $e->getMessage();
			return $returnval;
		}
	}
	public function insert($table, $arguments)
	{
		 $args = (is_array($arguments))? implode('`,`', $arguments): $arguments;
		 $placeholders = rtrim(str_repeat('?,', count($arguments)), ',');//rtrim removes to the right the character you pass it in this case a comma(,), str_repeat appends string the number of times specified
		 $stmt = (string)'INSERT IGNORE INTO `'.$table.'` (`'.$args.'`) VALUES ('.$placeholders.')';
	   	$this->insert = $this->dbh->prepare($stmt);

		return $this;
	}
	public function select($table, $arguments ='*')
	{
		$args = (is_array($arguments))? implode(', ', $arguments) : $arguments;
		$this->select = (string)'SELECT '.$args.' FROM `'.$table.'`';
		return $this;
	}
	public function update($table)
	{
		$this->update = (string)'UPDATE `'.$table.'`';
		return $this;
	}
	function where($field,$value,$operator ='=')
	{
		$value = (is_string($value))?$value:(string)$value;
		$fielded = strpos($field, ')')? $field : '`'.$field.'`';//if it has a bracket say WEEK(value) or YEAR(value) remove the quotes
		(isset($this->where))?$this->where .= ' AND '.$fielded.' '.$operator.' "'.$value.'"' : $this->where = ' WHERE '.$fielded.' '.$operator.' "'.$value.'"';
		$this->value[] = $value; 

		return $this;
	}
	function values($arguments)
	{
		// $args = (is_array($arguments))? implode('","', $value): $arguments;
		echo "called once X";
		try
		{
		// $stmt = $this->dbh->prepare($this->insert);
		$stmt = $this->insert;
		$count = 1;
			for ($i=1; $i <= count($arguments); $i++) { 
				$stmt->bindParam($i, $arguments[$i-1], PDO::PARAM_STR);
			}
		$arr = $stmt->errorInfo();
		//catch statement errors with PDOStatement::errorInfo()
         //                            //these cannot be caught by PDOExeption 
	        if(!is_null($arr[2]) && $arr[1] !== 0)
	        {
	         throw new \Exception($arr[2], $arr[1]);
	        } 
	    $stmt->execute();  
	        // print_r($query->errorInfo());
        }catch(\PDOException $e){
        	 $msg['code'] = $e->getCode();//208 is when the table is not found remember
	         throw new \Exception($e->getMessage(), $msg['code']);
	       } 
        
       return $stmt;

	}
	function set($columns)
	{
		if(is_array($columns))
		{
			$set = ' SET ';
			foreach ($columns as $key => $value) 
			{
		   		$set .=  '`'.$key.'` = "'.$value.'",';  
			}
			try{
				$this->gbc();
				$this->response = $this->dbh->prepare($this->update.rtrim($set,',').$this->where);
				$this->response->execute();
				unset($this->where);
		    	return $this->response->rowCount() . " records UPDATED successfully";	
		    }catch(\PDOExeption $e){
		    	$msg['code'] = $e->getCode();//208 is when the table is not found remember
	         	throw new \Exception($e->getMessage(), $msg['code']);
		    }
			
		}
		return false;
		
		//var_dump($this->response);
	}
	function groupby($arguments)
	{
		if(isset($arguments)){
			$this->group = ' GROUP BY `'.$arguments.'`';	
		}
		return $this;
		
	}
	function limit($from, $to='')
	{
		if(isset($from)){
			$to =(empty($to))?$to:','.$to;//if to is empty pass it as it is, if it  has value add a comma and the to
			$this->group = ' LIMIT '.$from.$to;	
		}
		return $this;
		
	}
	function in($field, $value)
	{
		if(is_array($value)){
			$mer = implode('","', $value);
		}
		(isset($this->where))?$this->where .= ' AND '.$field.' IN("'.$mer.'")': $this->where = ' WHERE `'.$field.'` IN("'.$mer.'")';
		$this->value[] = $value; 

		return $this;
	}
	function getAll()
	{
		$this->gbc();
		$this->response = $this->dbh->query($this->select.$this->where.$this->group, PDO::FETCH_ASSOC);
		// var_dump($this->response->fetchAll());
		$response = $this->response->fetchAll();//returns empty array if no result set found
		unset($this->where);
		return $response;
	}
	function get()
	{
		$this->gbc();
		$this->response = $this->dbh->query($this->select.$this->where.$this->group, PDO::FETCH_ASSOC);
		// echo $this->response;
		$response = $this->response->fetch();
		unset($this->where);
		return $response;
	}
	function gbc()
	{
		/* GarbageCollector */
		$this->group = (isset($this->group))?$this->group:'';
		$this->where = (isset($this->where))?$this->where:'';
	}
	function __toString()
	{
		$this->gbc();
		return (string) $this->select.$this->where.$this->group;
	}
}
?>