<?php

namespace App\Controllers;
use App\Models\User;

class UserController {
	public $con = 'mani';
	function __contruct(){
		echo 'Inside  controllers';
		$user  = new User();
	}
}