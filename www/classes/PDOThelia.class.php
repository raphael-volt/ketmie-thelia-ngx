<?php

class PDOThelia extends PDO
{
	private $statementFactory=array();
	private $queryFactory=array();
	const PREPARE = 1;
	const QUERY = 2;
	
	/**
	 * @param unknown $query
	 * @return NULL|PDOStatement
	 */
	private function getStatement($query, $mode, $options = NULL)
	{
		$i = array_search($query, $this->queryFactory);
		if($i === false)
		{
			return $this->createStatement($query, $mode, $options);
		}
		return $this->statementFactory[$i];
	}

	/**
	 * @param unknown $query
	 * @param unknown $mode
	 * @return NULL|PDOStatement
	 */
	private function createStatement($query, $mode, array $driver_options = null)
	{
		$stmt = null;
		switch ($mode) 
		{
			case self::PREPARE:
			{
				$stmt = parent::prepare($query, $driver_options);
				break;
			}
			
			case self::QUERY:
			{
				$stmt = parent::query($query);
				break;
			}
			
			default:
				;
			break;
		}
		if($stmt)
		{
			$this->queryFactory[] = $query;
			$this->statementFactory[] = $stmt;
		}
		return $stmt;
	}
	
	private static $callStatic=false;
	/**
	 * @var PDOThelia
	 */
	private static $instance;
	
	/**
	 * @return PDOThelia
	 */
	
	public static function getInstance()
	{
		if(self::$instance)
			return self::$instance;
		
		self::$callStatic = true;
		self::$instance = new PDOThelia();
		self::$callStatic = false;
		
		return self::$instance;
	}
	
	function __construct()
	{
		if( ! self::$callStatic)
			throw new Exception("Singleton usage");
		
		$dsn = "mysql:host=" . THELIA_BD_HOST . "; dbname=" . THELIA_BD_NOM . ";";
		parent::__construct($dsn, THELIA_BD_LOGIN, THELIA_BD_PASSWORD, array(PDO::MYSQL_ATTR_INIT_COMMAND=>"set names utf8"));
		$this->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	}
	/**
	 * @see PDO::prepare()
	 */
	public function prepare($statement, $driver_options = array())
	{
		return $this->getStatement($statement, self::PREPARE, $driver_options);
	}
	/**
	 * (non-PHPdoc)
	 * @see PDO::query()
	 */
	public function query($statement)
	{
		return $this->getStatement($statement, self::QUERY);
	}
	/**
	 * @param PDOStatement $stmt
	 * @param unknown $parameter
	 * @param unknown $int
	 */
	public function bindInt(PDOStatement $stmt, $parameter, $int, $executeAfter=false)
	{
		$stmt->bindValue($parameter, intval($int), PDO::PARAM_INT);
		if($executeAfter)
			$stmt->execute();
	}
	/**
	 * @param PDOStatement $stmt
	 * @param unknown $parameter
	 * @param unknown $string
	 */
	public function bindString(PDOStatement $stmt, $parameter, $string, $executeAfter=false)
	{
		$stmt->bindValue($parameter, strval($string), PDO::PARAM_STR);
		if($executeAfter)
			$stmt->execute();
	}
	/**
	 * @param PDOStatement $stmt
	 * @param unknown $class
	 * @param string $executeBefore
	 * @return mixed
	 */
	public function fetch(PDOStatement $stmt, $class, $executeBefore=false)
	{
		if($executeBefore)
			$stmt->execute();
		$stmt->setFetchMode(PDO::FETCH_CLASS, $class);
		return $stmt->fetch();
	}
	/**
	 * @param PDOStatement $stmt
	 * @param unknown $class
	 * @param string $executeBefore
	 * @return multitype:
	 */
	public function fetchAll(PDOStatement $stmt, $class, $executeBefore=false)
	{
		if($executeBefore)
			$stmt->execute();
		return $stmt->fetchAll(PDO::FETCH_CLASS, $class);
	}
	/**
	 * @param PDOStatement $stmt
	 * @param number $columnIndex
	 * @param string $executeBefore
	 * @return mixed|NULL
	 */
	public function fetchColumn(PDOStatement $stmt, $columnIndex=0, $executeBefore=false)
	{
		if($executeBefore)
			$stmt->execute();
		if($stmt->rowCount())
			return $stmt->fetch(PDO::FETCH_COLUMN, $columnIndex);
		return null;
	}
	/**
	 * @param PDOStatement $stmt
	 * @param number $columnIndex
	 * @return multitype:
	 */
	public function fetchAllColumn(PDOStatement $stmt, $columnIndex=0)
	{
		return $stmt->fetchAll(PDO::FETCH_COLUMN, $columnIndex);
	}
	
}