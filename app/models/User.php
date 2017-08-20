<?php

namespace App\Models;

class User {
	
	protected $tableName = 'users';

	public function __construct(){
		echo 'table '.$this->tableName.' is created';
	}


}