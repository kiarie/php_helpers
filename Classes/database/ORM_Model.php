<?php
namespace elipa\database;
use PDO;
Class ORM_Model
{


	function __construct($connection_str)
	{

		try{
			//live
		//		$this->dbh = new PDO("mysql:dbname=".$connection_str['dbname'].";port=5056;host=".$connection_str['host'],$connection_str['user'],$connection_str['pass']);
			//---------------------------------------------
		$this->dbh = new PDO("mysql:dbname=".$connection_str['dbname'].";host=".$connection_str['host'],$connection_str['user'],$connection_str['pass']);
		$this->dbh->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
		}catch(PDOException $e)
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
	function where($field,$value,$operator ='=')
	{
		$fielded = strpos($field, ')')? $field : '`'.$field.'`';//if it has a bracket say WEEK(value) or YEAR(value) remove the quotes
		(isset($this->where))?$this->where .= ' AND '.$fielded.' '.$operator.'"'.$value.'"' : $this->where = ' WHERE '.$fielded.' '.$operator.'"'.$value.'"';
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
	function groupby($arguments)
	{
		if(isset($arguments)){
			$this->group = ' GROUP BY `'.$arguments.'`';
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
		$this->group = (isset($this->group))?$this->group:'';
		$this->response = $this->dbh->query($this->select.$this->where.$this->group, PDO::FETCH_ASSOC);
		// var_dump($this->response->fetchAll());
		return $this->response->fetchAll();//returns empty array if no result set found
	}
	function get()
	{
		$this->group = (isset($this->group))?$this->group:'';
		$this->response = $this->dbh->query($this->select.$this->where.$this->group, PDO::FETCH_ASSOC);

		return $this->response->fetch();
	}
	function __toString()
	{
		$this->group = (isset($this->group))?$this->group:'';
		return (string) $this->select.$this->where.$this->group;
	}
/*
*	SELECT * FROM `Aff_commissions` WHERE `aff_id` LIKE '254724375730' AND `week` LIKE '30'
*	SELECT SUM(  `ipay_commission` ) , SUM(  `commissions` ) , SUM(  `sms_costs` ) FROM  `Aff_commissions` WHERE  `aff_id` LIKE  '254705679493' AND  `week` LIKE  '30'
*	SELECT SUM(  `ipay_commission` ) , SUM(  `commissions` ) , SUM(  `sms_costs` ) FROM  `Aff_commissions` WHERE  `aff_id` LIKE  '254705679493' AND  `week` IN('30','31')
*/

}
// $arr = array("1000", "4000", "5000", "56566", "avantia", "avantigroup", "brooms", "cartelia", "chania", "cloud", "communityiss", "craydon", "DEGREES", "direxions", "f", "generwifi", "hawi", "hypermart", "ipvoice", "k3c", "kda", "kisumusafari7s", "lamudi", "matharu", "monikos", "mtrack", "MYSONS", "natives", "nolimit", "odipo", "planet", "rafikiz", "skylux", "stormers", "tenderdent", "ticket", "TINDERBOX", "tribeka", "Twakei", "ycl", "zodiak");
// $person = new ORM_Model(array('dbname' =>'affiliates', 'host' =>'localhost' ,'pass' =>'admin' , 'user'=> 'root' ));
// $person->select('online_ke', array('SUM(`ipay_comm`)', 'vid'))
// 			->in('vid', $arr)
// 			->where('txndatetime', 'YEAR(2017)')
// 			->getAll();

// 		echo $person;
// 		if(count($person) == 1):echo "jusa";endif;

//---------------------------------------------------------------------
// $res = $person->select('Aff_commissions',array('SUM(  `ipay_commission` ) as ipay_commission', 'SUM(  `commissions` ) as commissions' , 'SUM(  `sms_costs` ) as sms_costs, `week`'))
// 		->where('aff_id', '254770483647')
// 		->groupby('week')
// 		->getAll();
// 		 var_dump($res);
// 		 print($res);
// foreach ($res as $value) {
// 	# code...
// 	echo  $value['commissions']. "\t |";
// 	echo $value['ttl_commissions'] . "\t|";
// 	echo $value['costs']. "\t||";
// }
?>
