<?php
/**
 * PDOMysqlWork.php
 *
 * This is file with PDOMysqlWork class
 * 
 * @category	classes
 * @copyright	2012
 * @author		Igor Zhabskiy <Zhabskiy.Igor@gmail.com>
 */

/**
 * PDOMysqlWork
 * 
 * This is PDOMysqlWork class
 * 
 * @version 0.1
 */
final class PDOMysqlWork {

	/**
	 * _DB_driver
	 * 
	 * @var string	This is name of Database driver
	 */
	private $_DB_driver = 'mysql';

	/**
	 * _DB_host
	 * 
	 * @var string	This is address of host our Database
	 */
	private $_DB_host = 'mysql.hostinger.com.ua';

	/**
	 * _DB_port
	 * 
	 * @var string	This is port of protocol our Database
	 */
	private $_DB_port = '3306';
	
	/**
	 * _DB_name
	 * 
	 * @var string	This is name of Database
	 */
	private $_DB_name = 'u196910164_k';

	/**
	 * _DB_login
	 * 
	 * @var string	This is login of user our Database
	 */
	private $_DB_login = 'u196910164_k';

	/**
	 * _DB_password
	 * 
	 * @var string	This is password of user our Database
	 */
	private $_DB_password = '';

	/**
	 * _DB_encoding
	 * 
	 * @var string	This is encoding of our Database
	 */
	private $_DB_encoding = 'utf8';

	/**
	 * _sql
	 * 
	 * @var string	This is sql query to DB
	 */
	private $_sql = '';

	/**
	 * _replace
	 * 
	 * @var array	This is parameters for pdo::execute 
	 */
	private $_replace = array();

	/**
	 * _debug
	 * 
	 * @var boolean	This is debuging flag
	 */
	public $_debug = false;
	
	/**
	 * _DB_connect
	 * 
	 * @var string	This is connect to our Database
	 */
	public $_DB_connect;
	
	/**
	 * Constructor
	 *
	 * This function initialize connect to our Database
	 *
	 * @return object $_DB_connect	This is PDO connect mysql
	 */
	public function __construct() {

		/**
		 * Create variable with connect data  
		 */
		$connect = $this -> _DB_driver 
			. ':host=' . $this -> _DB_host 
			. ';port=' . $this -> _DB_port 
			. ';dbname=' . $this -> _DB_name;

		try {
			
			/**
			 * Create PDO object
			 */
			$this -> _DB_connect = new PDO(
				$connect,
				$this -> _DB_login,
				$this -> _DB_password,
				array(
					PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'' . $this -> _DB_encoding . '\''
				)
			);

			/**
			 * Set attribute for DB connect
			 */
			$this -> _DB_connect -> setAttribute(
				PDO::ATTR_ERRMODE,
				PDO::ERRMODE_EXCEPTION
			);

			return $this -> _DB_connect;

		} catch (PDOException $object) {

			/**
			 * Display error message and return false
			 */
			print json_encode(array(
				"flag" => ($this -> _debug) ? 'Error: ' . $object -> getMessage() : false
			));

			exit();

			// return 'Error: ' . $object -> getMessage();
		}
	}

	/**
	 * buildWhere
	 * 
	 * This function to build of part sql query (WHERE ...)
	 *
	 * @param string $where
	 */
	private function buildWhere($where) {
		
		if(!empty($where)){
			$this -> _sql .= " WHERE ";
		
			$c=count($this -> _replace);
		
			foreach($where as $key => $w){
		
				/**
				 * Look for any comparitive symbols within the where array value.
				 */
				if(substr($w,0,1) == '%') {
		
					/**
					 * Prep the query for PDO->prepare
					 */
					$this -> _sql .= $key . '=%:' . $c . '% && ';
		
					$this -> _replace[':'.$c] = $w;
				} else {
					
					if(substr($w, 0, 2)=='<=')
						$eq='<=';
					elseif(substr($w, 0, 2)=='>=')
						$eq='>=';
					elseif(substr($w, 0, 1)=='>')
						$eq='>';
					elseif(substr($w, 0, 1)=='<')
						$eq='<';
					elseif(substr($w, 0, 1)=='!')
						$eq='!=';
					else
						$eq='=';
			
						/**
						 * Prep the query for PDO->prepare
						 */
						$this -> _sql .= $key . $eq . ':' . $c . ' && ';
			
						$this -> _replace[':' . $c] = $w;
				}
				$c++;
			}

			$this -> _sql = substr($this -> _sql, 0, -4);
		}
	}

	/**
	 * prepVars
	 * 
	 * Remove slashes from all retrieved variables
	 *
	 * @param array $vars
	 * @return string/boolean $ret
	 */
	private function prepVars($vars) {
		
		if(is_array($vars)) {
			foreach($vars as $key => $value) {
				$ret[$key] = $this -> prepVars($value);
			}
		} elseif (is_object($vars)) {
			foreach($vars as $key => $value) {
				$ret[$key] = $this -> prepVars($value);
			}
		} elseif(is_string($vars)) {
			$ret = stripslashes($vars);
		} else{
			$ret = $vars;
		}
		
		if ($this -> _debug) {
			return (isset($ret) and !empty($ret)) ? $ret : 'Record not found.';
		} else {
			return (isset($ret) and !empty($ret)) ? $ret : false;	
		}
	}

	/**
	 * query
	 * 
	 * General query function
	 *
	 * @param string $query
	 * @param string $vals
	 * @param string $flag
	 * 
	 * @return string
	 */
	private function query($query, $vals = '', $flag = '') {
		
		$sth = $this-> _DB_connect -> prepare($query);
		
		if (is_array($vals) and empty($flag)) {
			
			$sth->execute($vals);
			
			$result = $sth -> fetchAll(PDO::FETCH_OBJ);

		} elseif (empty($vals) and empty($flag)) {
			
			$sth->execute();
			
			$result = $sth -> fetchAll(PDO::FETCH_OBJ);
			
		} elseif (is_array($vals) and !empty($flag)) {
			
			$sth->execute($vals);
			
		} else {
			
			$sth->execute();
		}

		$e = $sth -> errorInfo();
	
		if($e[0] != '00000') {
		
			if($this -> _debug) {
				if($e[2]) {
					echo '<strong>ERROR:</strong>: ' . $e[2];
				} else {
					echo '<strong>ERROR:</strong>: General Error';
				}
			}
		}

		if($this -> _debug) {
			$this -> getQueryDebug($query, $vals, $e);
		}
		
		if (isset($result)) {
			return $this -> prepVars($result);
		}
	}

	/**
	 * selectOne
	 * 
	 * Select and return only one row
	 *
	 * @param string $table
	 * @param string $vals
	 * @param arrat	 $where
	 * @param string $extra
	 * 
	 * @return string/boolean
	 */
	public function selectOne($table, $vals = '*', $where = array(), $extra = '') {
		
		$result = $this -> select($table, $vals, $where);
		
		if ($this -> _debug) {
			return (isset($result) and is_array($result)) ? $result[0] : 'Record not found.';
		} else {
			return (isset($result) and is_array($result)) ? $result[0] : false;
		}
	}

	/**
	 * select
	 * 
	 * This fucntion make select in query 
	 *
	 * @param string $table
	 * @param string $vals
	 * @param arrat	 $where
	 * @param string $extra
	 * 
	 * @return string
	 */
	public function select($table, $vals = '*', $where = array(), $extra = '') {
		
		/**
		 * Initialize the sql query
		 */
		$this -> _replace = array();
		
		$this -> _sql = "SELECT ";
		
		/**
		 * Add all the values to be selected
		 */
		if(is_array($vals)) {
			foreach($vals as $v)
				$this -> _sql .= $v . ',';
				
				$this -> sql = substr($this->sql, 0, -1);
		} else
			$this -> _sql .= $vals;
		
			$this -> _sql .= ' FROM '.$table;
		
			/**
			 * Build the WHERE portion of the query
			 */
			$this -> buildWhere($where);
		
			$this -> _sql .= ' ' . $extra;
			
			$ret = $this -> query($this -> _sql, $this -> _replace);
			
			return $ret;
	}

	/**
	 * insert
	 * 
	 * This fucntion make insert row to table
	 *
	 * @param string $table
	 * @param string $vals
	 * 
	 * @return string
	 */
	public function insert($table, $vals) {
	
		/**
		 * Empty the replace array
		 */
		$this -> _replace = array();
		
		$this -> _sql = 'INSERT INTO ' . $table . ' SET ';
		
		/**
		 * Build the replace array and the query
		 */
		$c = count($this -> _replace);
		
		foreach($vals as $key => $v) {
			
			$this -> _sql .= $key . '=:' . $c .', ';
		
			$this -> _replace[':' . $c] = $v;
		
			$c++;
		}

		$this -> _sql = substr($this -> _sql, 0, -2);
		
		/**
		 * Run and return the query
		 */
		$flag = 'insert';
		
		$ret = $this -> query($this -> _sql, $this -> _replace, $flag);

		$id = $this -> _DB_connect -> lastInsertId();
		
		if($id) {
			return $id;
		} else {
			return $ret;
		}
	}

	/**
	 * Update
	 * 
	 * This fucntion make update row to table
	 *
	 * @param string $table
	 * @param string $vals
	 * @param array  $where
	 * 
	 * @return string
	 */
	public function update($table, $vals, $where = array()) {
		
		/**
		 * Empty the replace array
		 */
		$this -> _replace = array();
		
		$this -> _sql = 'UPDATE ' . $table . ' SET ';
		
		/**
		 * Build the replace array and the query
		 */
		$c = count($this -> _replace);
		
		foreach($vals as $key => $v) {
			$this -> _sql .= $key . '=:' . $c . ', ';
			
			$this -> _replace[':' . $c] = $v;
			
			$c++;
		}
		
		$this -> _sql = substr($this -> _sql, 0, -2);
		
		/**
		 * Build the WHERE portion of the query
		 */
		$this -> buildWhere($where);
		
		/**
		 * Run and return the query
		 */
		$flag = 'update';

		return $this -> query($this -> _sql, $this -> _replace, $flag);
	}

	/**
	 * delete
	 * 
	 * This fucntion make delete row from table
	 *
	 * @param string $table
	 * @param array  $where
	 * 
	 * @return string
	 */
	public function delete($table, $where) {
	
		/**
		 * Empty the replace array
		 */
		$this -> _replace = array();
		
		$this -> _sql = 'DELETE FROM ' . $table;
		
		/**
		 * Build the WHERE portion of the query
		 */
		$this -> buildWhere($where);
		
		/**
		 * Run and return the query
		 */
		$flag = 'delete';
		
		return $this -> query($this -> _sql, $this -> _replace, $flag);
	}

	/**
	 * getCount
	 * 
	 * Get the number of records matching the requirements
	 *
	 * @param string $table
	 * @param string $where
	 * 
	 * @return string
	 */
	public function getCount($table, $where = '') {

		/**
		 * Start query
		 */
		$this -> _sql = "SELECT COUNT(*) FROM ".$table;
		
		if(is_array($where)) {
		
			/**
			 * Build the WHERE portion of the query
			 */
			$this -> buildWhere($where);
		
		} else {
			$this -> _replace = null;
		}
		
		/**
		 * Select row form table
		 */
		$data_array = $this -> query($this -> _sql, $this -> _replace);
		
		/**
		 * Return the row count
		 */
		return $data_array[0]['COUNT(*)'];
	}

	/**
	 * getValue
	 * 
	 * Gets value of requested column
	 *
	 * @param string $table
	 * @param string $val
	 * @param array  $where
	 * 
	 * @return string
	 */
	public function getValue($table, $val, $where = array()) {
	
		/**
		 * Run query
		 */
		$data_array = $this -> select($table, $val, $where);
		
		/**
		 * Return requested value
		 */
		return $data_array[0][$val];
	}

	/**
	 * getQueryDebug
	 *
	 * This function is query debugging  
	 *
	 * @param string $query
	 * @param string $val
	 * @param integer $er
	 */
	private function getQueryDebug($query, $val, $er = 0) {
	
		echo '<p>';

		if (is_array($val)) {
		
			foreach($val as $key=>$value) {
				$query=str_replace($key, "'" . $value . "'", $query);
			}
		}

		echo '<strong>QUERY:</strong><br />' . $query;
		
		if($er){
			echo '<br /><br /><strong>Raw error:</strong><pre>';
			
			print_r($er);
			
			echo '</pre>';
		}
		
		echo '</p><hr />';
	}

	/**
	 * Destructor
	 *
	 * This is destructor close connect with Data Base
	 */
	public function __destruct() {

		$this -> _DB_connect = null;
	}
}

/**
 * Set default timezone
 */
date_default_timezone_set('Europe/Kiev');

/**
 * Validate email address
 */
$email = $_REQUEST['email'];
$email_validate = filter_var($email, FILTER_VALIDATE_EMAIL);
$table = 'subscribers';

/**
 * Check email validate
 */
if ($email_validate == true) {

	/**
	 * Instantiate PDOMysqlWork class
	 */
	$db = new PDOMysqlWork();

	/**
	 * Chack double email
	 */
	$double = $db -> selectOne($table, 'id_email', array('email_address' => $email));

	if ($double) {
		/**
		 * Prepare request
		 */
		print json_encode(array(
				"flag" => 'double'
		));

		exit();
	}

	/**
	 * Debugging queries (default disabled)
	 */
	// $db -> _debug = true;

	/**
	 * Create array with data that will be insert to our table 
	 */
	$options = array(
		"id_email"			=> '',
		"email_address"	=> $email,
		"ip_address" 		=> $_SERVER['REMOTE_ADDR'],
		"browser"				=> $_SERVER['HTTP_USER_AGENT'],
		"date"					=> date("Y-m-d H:i:s")
	);

	/**
	 * Insert into table; returns primary key of that row
	 */
	$db -> insert($table, $options);

	/**
	 * Prepare request
	 */
	print json_encode(array(
		"flag" => true
	));
} else {
	/**
	 * Prepare request
	 */
	print json_encode(array(
		"flag" => false
	));
}
?>