<?php 

class Model {
	
	protected $db;
	/*protected $tableName;*/
	function __construct($db) {
		try {
			$this->db = $db;
		} catch (PDOException $e) {
			echo ("error");
		}
	}
}