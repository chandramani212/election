<?php
session_start();

include '../../config/config.php';
include '../../includes/mysqli_connect.php';
include '../../includes/functions.php';
include '../../includes/middleware.php';




if(isset($_POST)){

	$data = json_decode(file_get_contents('php://input'), true);

	$user_name = mysqli_real_escape_string($con , isset($data['user_name'])?$data['user_name']:'');
	$password =  mysqli_real_escape_string($con , isset($data['password'])?$data['password']:'');
	$cell_no =  mysqli_real_escape_string($con , isset($data['cell_no'])?$data['cell_no']:'');
	$otp =  mysqli_real_escape_string($con , isset($data['otp'])?$data['otp']:'');
	$action = mysqli_real_escape_string($con , isset($data['action'])?$data['action']:'');
	
	if($action == 'getOtp'){
		$otp_number = getOtp();
		$_SESSION['otp'] = $otp_number;
		
		//Have to integrate sms gateway for sending otp to customer
	}else{
	
		//if($_SESSION['otp'] == $otp){
		
			$sql = "select id,voter_id,cell_no from users where voter_id = '".$user_name."' ";

			if($result = mysqli_query($con , $sql) ){
				if( mysqli_num_rows($result) > 0){
		
					$sql = "update users set password ='".$password."' where voter_id ='".$user_name."' ";
					if($result = mysqli_query($con , $sql) ){
					
						$response = [ "status" => 'success' , "msg" => "Password has been created successflly"];

					}else{
					
						$response = [ "status" => 'fail' , "msg" => "Unable to set password"];
					}
					
				}else{
				
					$response = [ "status" => 'fail' , "msg" => "Unable to find record of voter_id :$user_name "];
				}
				
			}else{
			
				$error =  "Error: " . mysqli_error($con);
				$response = array( 'status' => 'fail' ,'msg' => $error );
			}
		/*
		}else{
		
			$response = [ "status" => 'fail' , "msg" => "Otp does not match"];
		}*/
		
		
	}


	mysqli_close($con);
	echo  json_encode($response);
}