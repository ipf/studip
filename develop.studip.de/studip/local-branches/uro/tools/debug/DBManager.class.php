<?php

/*
* Copyright (C) 2007 - Marcus Lunzenauer <mlunzena@uos.de>
*
* This program is free software; you can redistribute it and/or
* modify it under the terms of the GNU General Public License as
* published by the Free Software Foundation; either version 2 of
* the License, or (at your option) any later version.
*/
require "StudipDebug.class.php";

class DebugPDO extends PDO {
	
	public function query($query_string){
		$time = microtime(true);
		$ret = parent::query($query_string);
		StudipDebug::log_query($query_string, $time);
		return $ret;
	}
	public function exec($query_string){
		$time = microtime(true);
		$ret = parent::exec($query_string);
		StudipDebug::log_query($query_string, $time);
		return $ret;
	}
}

class DebugPDOStatement extends PDOStatement {
	public $query_params = NULL;
	public $dbh;
	protected function __construct($dbh) {
		$this->dbh = $dbh;
	}
	public function execute($arr){
		$this->queryParams = $arr;
		$time = microtime(true);
		$ret =  parent::execute($arr);
		StudipDebug::log_query($this->getActualQuery(), $time);
		return $ret;
	}
	
	public function getActualQuery(){
		$rv = $this->queryString;
		while (preg_match('/\?/', $rv)) $rv = preg_replace('/\?/','%'.(++$i).'$s',$rv,1);
		foreach ($this->queryParams as $k=>$v) $arr[$k]= $this->dbh->quote($v);
		return vsprintf($rv, $arr);
	}
}


/**
* This class provides a singleton instance that is used to manage PDO database
* connections.
*
* Example of use:
*
*   # getting a PDO connection
*   $key = 'studip';
*   $db = DBManager::get($key);
*   $db->query('SELECT * FROM user_info');
*
*   # setting a PDO connection
*   $manager = DBManager::getInstance();
*   $manager->setConnection('example', 'mysql:host=localhost;dbname=example',
*                           'root', '');
*
*
* @package     studip
* @subpackage  lib
*
* @author    mlunzena
* @copyright (c) Authors
*/

class DBManager {
	
	
	/**
	* the singleton instance
	*
	* @access  private
	* @var     DBManager
	*/
	static private $instance;
	
	
	/**
	* an array of connections of the singleton instance
	*
	* @access  private
	* @var     array
	*/
	private $connections;
	
	
	/**
	* @access private
	*
	* @return void
	*/
	private function __construct() {
		$this->connections = array();
	}
	
	
	/**
	* This method returns the singleton instance of this class.
	*
	* @return DBManager  the singleton instance
	*/
	public function getInstance() {
		if (is_null(DBManager::$instance)) {
			DBManager::$instance = new DBManager();
		}
		return DBManager::$instance;
	}
	
	
	/**
	* This method returns the database connection to the given key. Throws a
	* DBManagerException if there is no such connection.
	*
	* @param  string  the database connection's key
	*
	* @throw DBManagerException
	*
	* @return PDO     the database connection
	*/
	public function getConnection($database) {
		
		if (!isset($this->connections[$database])) {
			throw new DBManagerException('Database connection: "'.$database.
			'" does not exist.');
		}
		
		return $this->connections[$database];
	}
	
	
	/**
	* This method creates a database connection and stores it under the given
	* key.
	*
	* @param  string    the key of the database connection
	* @param  string    the connection's DSN
	* @param  string    the connection's username
	* @param  string    the connection's password
	*
	* @return DBManager this instance, useful for cascading method calls
	*/
	public function setConnection($database, $dsn, $user, $pass) {
		$this->connections[$database] = new DebugPDO($dsn, $user, $pass);
		$this->connections[$database]->setAttribute(PDO::ATTR_ERRMODE,
		PDO::ERRMODE_EXCEPTION);
		$this->connections[$database]->setAttribute(PDO::ATTR_STATEMENT_CLASS, array('DebugPDOStatement',array($this->connections[$database])));										
		if ($this->connections[$database]->getAttribute(PDO::ATTR_DRIVER_NAME) == 'mysql') {
			$this->connections[$database]->setAttribute(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY, true);
		}
		return $this;
	}
	
	
	/**
	* This method creates an alias for a database connection.
	*
	* @param  string    the new key of the database connection
	* @param  string    the old key of the database connection
	*
	* @return DBManager this instance, useful for cascading method calls
	*/
	public function aliasConnection($new, $old) {
		
		if (!isset($this->connections[$old])) {
			throw new DBManagerException('No database found using key: ' . $old);
		}
		
		$this->connections[$new] = $this->connections[$old];
		
		return $this;
	}
	
	
	/**
	* Shortcut static method to retrieve the database connection for a given key.
	*
	* @param  string  the database connection's key
	*
	* @return PDO     the database connection
	*/
	static public function get($database = 'studip') {
		$manager = DBManager::getInstance();
		return $manager->getConnection($database);
	}
}


/**
* @package     studip
* @subpackage  lib
*
* @author    mlunzena
* @copyright (c) Authors
*/

class DBManagerException extends Exception {
	
	
	/**
	* @param  string   the message of this exception
	*
	* @return void
	*/
	public function __construct($message) {
		parent::__construct($message);
	}
}
