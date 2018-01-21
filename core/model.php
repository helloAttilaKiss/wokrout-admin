<?php 

abstract class Model
{
	protected $db;

	function __construct(){
		$this->db = $this->get_db_read_handler(DB_DATABASE, DB_HOSTNAME, DB_USERNAME, DB_PASSWORD);
	}

	private function get_db_read_handler($dbname, $host, $user, $pass, $port = "", $charset = "utf8"){
		$dsn = "mysql:dbname=".$dbname.";host=".$host;
		if($port != "") { $dsn .= ";port=".$port; }
		if($charset != "") { $dsn .= ";charset={$charset}"; }
		$dbo = new PDO($dsn, $user, $pass);
		$dbo->exec("set names {$charset}");
		return $dbo;
	}
}
