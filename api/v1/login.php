<?php

include '../../config/config.php';
include '../../includes/mysqli_connect.php';
include '../../includes/functions.php';
include '../../includes/middleware.php';



if(isset($_POST) ){
	$data = json_decode(file_get_contents('php://input'), true);

	$user_name = mysqli_real_escape_string($con , isset($data['user_name'])?$data['user_name']:'') ;
	$password =  mysqli_real_escape_string($con , isset($data['password'])?$data['password']:'') ;

	$sql = "select id,voter_id,cell_no from users where voter_id = '".$user_name."' and password = '".$password."' ";

	if($result = mysqli_query($con , $sql) ){
		if( mysqli_num_rows($result) > 0){
			$row = mysqli_fetch_array($result);
			$response = [ 
				"status" => 'success' ,
				"data" => [
					"user_id" => $row['id'],
					"voter_id" => $row['voter_id'],
					"cell_no" => $row['cell_no'],
					"token" => encode_arr($row ),
				] 
			];
		
		}else{
		
		 $response = [ "status" => 'fail' , "msg" => "No Record found or username or password does not match"];
		}

	}else{
		
		$error =  "Error: " . mysqli_error($con);
		$response = array( 'status' => 'fail' ,'msg' => $error );
		
	}

	mysqli_close($con);
	echo  json_encode($response);
}