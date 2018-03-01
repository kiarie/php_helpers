<?php
namespace helpers\database;
use PDO;
class ORM_Model
{
	/** Initialise class with a connection string required the connection string is an
	 * assoc array with keys 'host,user,pass,dbname,port*'
	 */
	function __construct($connection_str)
	{
		try{
		$port =(isset($connection_str['port']))?$connection_str['port']: 3306;
		$this->dbh = new PDO("mysql:dbname=".$connection_str['dbname'].";port=".$port.";host=".$connection_str['host'],$connection_str['user'],$connection_str['pass']);
		$this->dbh->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
		}catch(\PDOException $e)
		{
        	$this->dbh = null;
			throw new \Exception($e->getMessage());
		}
	}
	/**@func insert {params} table_name, arguments
	 * Prepares an insert statement for later execution when values are passed,(therefore you can prepare once and use that prepared statement multiple times to insert values)
	 * arguments can be one value or an array of values
	 * usage: insert(tablename, arguments)
	 */
	public function insert($table, $arguments)
	{
		 $args = (is_array($arguments))? implode('`,`', $arguments): $arguments;
		 $placeholders = rtrim(str_repeat('?,', count($arguments)), ',');//rtrim removes to the right the character you pass it in this case a comma(,), str_repeat appends string the number of times specified
		 $stmt = (string)'INSERT IGNORE INTO `'.$table.'` (`'.$args.'`) VALUES ('.$placeholders.')';
	   	$this->insert = $this->dbh->prepare($stmt);

		return $this;
	}
	/**@func select {params} table_name[,arguments]
	 *  This is the first part of the select clause i.e. SELECT arguments FROM tablename
	 * arguments is * by defualt and also can be an array. 
	 * usage select('tablname',array(col1,col2,..)) or just select(tablename) for default
	 */
	public function select($table, $arguments ='*')
	{
		$args = (is_array($arguments))? implode(', ', $arguments) : $arguments;
		$this->select = (string)'SELECT '.$args.' FROM `'.$table.'`';
		return $this;
	}
	/**@func update {params} table_name
	 *  This is the first part of the UPDATE clause i.e. UPDATE tablename
	 * 	usage update('tablename')
	 */
	public function update($table)
	{
		$this->update = (string)'UPDATE `'.$table.'`';
		return $this;
	}
	/** @func where {params} field,value,[operator]
	 *  Essentially add the WHERE clause and if there is already an earlier where/in clause,
	 *  it adds AND WHERE. For OR WHERE see the orWhere function
	 *  usage: orWhere('field',10,'>')
	 */
	function where($field,$value,$operator ='=')
	{
		$value = (is_string($value))?$value:(string)$value;
		$fielded = strpos($field, ')')? $field : '`'.$field.'`';//if it has a bracket say WEEK(value) or YEAR(value) remove the quotes
		(isset($this->where))?$this->where .= ' AND '.$fielded.' '.$operator.' "'.$value.'"' : $this->where = ' WHERE '.$fielded.' '.$operator.' "'.$value.'"';


		return $this;
	}
	/** @func orWhere {params} field,value,[operator]
	 *  Essentially add the OR WHERE clause only if there is already an earlier where/in clause
	 *  usage: orWhere('field',10,'>')
	 */
	function orWhere($field,$value,$operator ='=')
	{
		$value = (is_string($value))?$value:(string)$value;
		$fielded = strpos($field, ')')? $field : '`'.$field.'`';//if it has a bracket say WEEK(value) or YEAR(value) remove the quotes
		//Only when there is a previous WHERE clause do we set it
		(isset($this->where))?$this->where .= ' OR '.$fielded.' '.$operator.' "'.$value.'"' :'';


		return $this;
	}
	/** @func values {params} arguments as an array
	 * 	This adds the values clause in the insert statement that was prepared earlier
	 *  and executes the prepared statement
	 *  usage values(array('val1',$val2,...))
	 */
	function values($arguments)
	{
		if(!is_array($arguments)){
			throw new \InvalidArgumentException('Should Pass an Array');
		}
		try
		{
		$stmt = $this->insert;
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
	/** @function set {params} columns
	 * takes an associtive array of the columns and desired values to be updated.
	 * it completes the update statement by adding the SET column = value clause,..
	 * usage: set(array('fieldname'=>value[,..[,..]]))
	 */
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
	}
	/* @func groupby {params} arguments 
	* groupby('fieldone') or groupby('fieldone,fieldtwo,fieldthree') 
	* The argument must be one string and multiple values are comma seperated within that one string
	*/
	function groupby($arguments)
	{
		if(isset($arguments)){
			$this->group = ' GROUP BY `'.$arguments.'`';	
		}
		return $this;
		
	}
	/** @func orderby {params} field, orderby 
	* Creates the ORDER BY 'fieldname' ASC/DESC statement 
	* usage: orderby('fieldname', 'ASC')
	*/
	function orderby($field, $orderby)
	{
		$this->orderby = ' ORDER BY `'.$field.'` '.$orderby;
		return $this;
	}
	/* @func limit {params} from, to 
	* creates the LIMIT to, from SQL statement
	* usage: limit(100) or limit(20,50)
	*/
	function limit($from, $to='')
	{
		if(isset($from)){
			$to =(empty($to))?$to:','.$to;//if to is empty pass it as it is, if it  has value add a comma and the to
			$this->limit = ' LIMIT '.$from.$to;	
		}
		return $this;
		
	}
	/* @func in {params} field, values creates the WHERE 'field' IN (comma_seperated values ) query
	* The argument must be one string and multiple values are comma seperated within that one string
	* usage: in("field","value1,value2,..,..") 
	*/
	function in($field, $value)
	{
		if(is_array($value)){
			$mer = implode('","', $value);
		}
		(isset($this->where))?$this->where .= ' AND '.$field.' IN("'.$mer.'")': $this->where = ' WHERE `'.$field.'` IN("'.$mer.'")';


		return $this;
	}
	/* @func getAll returns multiple values in result set in an assoc array
	* usage: getAll() 
	*/
	function getAll()
	{
		$query = $this->chainer($this);
		$this->gbc();
		$this->response = $this->dbh->query($query, PDO::FETCH_ASSOC);
		$response = $this->response->fetchAll();//returns empty array if no result set found
		unset($this->where);
		return $response;
	}
	/**
	* @func get returns one value of the result set in an associtive array
	* usage: get() 
	*/
	function get()
	{
		$query = $this->chainer($this);
		$this->gbc();
		$this->response = $this->dbh->query($query, PDO::FETCH_ASSOC);
		$response = $this->response->fetch();
		unset($this->where);//unset everytime to prevent reuse of previous where clauses
		return $response;
	}
	/**
	 * @func chainer {params} takes the current object instance
	 * Builds the querystring from the chained methods in the class
	 */
	private function chainer($thisObj){
		$chained = null;
		foreach($thisObj as $value){
			if(!is_object($value) && !is_array($value)){
				$chained = $chained.$value;		
			}
		}
		return $chained;
	}
	/* GarbageCollector cleans up variables in every new run */
	private function gbc()
	{
		$this->group = (isset($this->group))?$this->group:'';
		$this->where = (isset($this->where))?$this->where:'';
	}
	/**
	 * This allows you to print the object and see the statement in whole its a function Overload of
	 * to_string function
	 */
	function __toString()
	{
		$this->gbc();
		return (string) $this->select.$this->where.$this->group;
	}
}
?>