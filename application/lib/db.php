<?php

require dirname(__FILE__) . '/config/db.php';

/**
 * Database Class
 */
class DB extends PDO {
	
	private static $_host		= DB_HOST;
	
	private static $_user		= DB_USER;
	
	private static $_pass		= DB_PASS;
	
	private static $_dbname		= DB_NAME;
	
    /**
     * Instance for static call (singleton)
     * 
     * @var type 
     */
	private static $_instance	= null;
	
    /**
     * Errors array
     * 
     * @var type 
     */
	protected $errors			= array();
	
	/**
	 * Extends PD Class
	 * 
	 * @param string $host
	 * @param string $dbname
	 * @param string $user
	 * @param string $pass
	 * @param array $options
	 */
	public function __construct($host = 'localhost', $dbname = '', $user = 'root', $pass = '', $options = array()) {
		$dsn = 'mysql:dbname=' . $dbname . ';host=' . $host;
		parent::__construct($dsn, $user, $pass, $options);
		self::query('SET NAMES utf8');
	}
	
	/**
	 * Return an instance of DB
	 * 
	 * @return DB
	 */
	public static function instance() {
		if (!(self::$_instance instanceof DB)) {
			self::$_instance = new self(self::$_host, self::$_dbname, self::$_user, self::$_pass);
		}
		return self::$_instance;
	}
	
	/**
	 * Fetch All quickly
	 * 
	 * @param string $sql
	 * @return Object
	 */
	public static function fetchAll($sql) {
		return self::instance()->query($sql)->fetchAll(PDO::FETCH_OBJ);
	}
	
	/**
	 * Fetch Row quickly
	 * 
	 * @param string $sql
	 * @return Object
	 */
	public static function fetchRow($sql) {
		return self::instance()->query($sql)->fetch(PDO::FETCH_OBJ);
	}
	
	/**
	 * Execute SQL statement quickly
	 * 
	 * @param string $sql
	 * @return int
	 */
	public static function execute($sql) {
		return self::instance()->exec($sql);
	}
	
	/**
	 * Quote
	 * 
	 * @param string|array $string
	 * @return string
	 */
	public static function q($mixed) {
		if (is_array($mixed)) {
			$string = array();
			foreach ($mixed as $term) {
				$string[] = self::q($term);
			}
			return '(' . implode(',', $string) . ')';
		} else {
			return self::instance()->quote($mixed);
		}
	}
}

