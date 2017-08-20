<?php

include '../../config/config.php';
include '../../includes/mysqli_connect.php';
include '../../includes/functions.php';
include '../../includes/middleware.php';


if(isset($_GET) ){
	
	$data = json_decode(file_get_contents('php://input'), true);
	$user_id = mysqli_real_escape_string($con, isset($data['user_id'])?$data['user_id']:'') ;
	if($user_id){
		$sql = "select * from  users where id = '".$user_id."'";
		$result = mysqli_query($con, $sql);
		$row = mysqli_fetch_array($result);
		
		if(count($row) <=0){
			$response = array( 'status' => 'fail' ,'msg' => "No record Found" );
		}else{
			$response = array( 'status' => 'success' ,'data' => $row );
		}
		
	}else{
		
		$sql = "select * from  users";
		$result = mysqli_query($con, $sql);
		$rows = array();
		while($row = mysqli_fetch_array($result)){
			$rows[] = $row;
		}
		if(count($rows) <=0){
			$response = array( 'status' => 'fail' ,'msg' => "No record Found" );
		}else{
			$response = array( 'status' => 'success' ,'data' => $rows );
		}
		
		
		
	}
	
	
}

if(isset($_POST) ){
	
	/*
	$voter_id = mysqli_real_escape_string($con, $_POST['voter_id']) ;
	$cell_no = mysqli_real_escape_string($con, $_POST['cell_no']) ;
	$voter_first_name = mysqli_real_escape_string($con, $_POST['voter_first_name']) ;
	$voter_last_name = mysqli_real_escape_string($con, $_POST['voter_last_name']) ;
	$age = mysqli_real_escape_string($con, $_POST['age']) ;
	$gender = mysqli_real_escape_string($con, $_POST['gender']) ;
	$blood_group = mysqli_real_escape_string($con, $_POST['blood_group']) ;
	$email = mysqli_real_escape_string($con, $_POST['email']) ;
	$caste = mysqli_real_escape_string($con, $_POST['caste']) ;
	$religion = mysqli_real_escape_string($con, $_POST['religion']) ;
	$qualification = mysqli_real_escape_string($con, $_POST['qualification']);
	$occupation = mysqli_real_escape_string($con, $_POST['occupation']);
	$date_of_birth = mysqli_real_escape_string($con, $_POST['date_of_birth']) ;
	$city_id = mysqli_real_escape_string($con, $_POST['city_id']) ;
	$district_id = mysqli_real_escape_string($con, $_POST['district_id']) ;
	$taluka_id = mysqli_real_escape_string($con, $_POST['taluka_id']) ;
	$ward_id = mysqli_real_escape_string($con, $_POST['ward_id']) ;
	*/
	
	$data = json_decode(file_get_contents('php://input'), true);
	
	$voter_id = mysqli_real_escape_string($con, isset($data['voter_id'])?$data['voter_id']:'') ;
	$cell_no = mysqli_real_escape_string($con, isset($data['cell_no'])?$data['cell_no']:'') ;
	$voter_first_name = mysqli_real_escape_string($con, isset($data['voter_first_name'])?$data['voter_first_name']:'') ;
	$voter_last_name = mysqli_real_escape_string($con, isset($data['voter_last_name'])?$data['voter_last_name']:'') ;
	$age = mysqli_real_escape_string($con, isset($data['age'])?$data['age']:'') ;
	$gender = mysqli_real_escape_string($con, isset($data['gender'])?$data['gender']:'') ;
	$blood_group = mysqli_real_escape_string($con, isset($data['blood_group'])?$data['blood_group']:'') ;
	$email = mysqli_real_escape_string($con, isset($data['email'])?$data['email']:'') ;
	$caste = mysqli_real_escape_string($con, isset($data['caste'])?$data['caste']:'') ;
	$religion = mysqli_real_escape_string($con, isset($data['religion'])?$data['religion']:'') ;
	$qualification = mysqli_real_escape_string($con, isset($data['qualification'])?$data['qualification']:'');
	$occupation = mysqli_real_escape_string($con, isset($data['occupation'])?$data['occupation']:'');
	$date_of_birth = mysqli_real_escape_string($con, isset($data['date_of_birth'])?$data['date_of_birth']:'') ;
	$city_id = mysqli_real_escape_string($con, isset($data['city_id'])?$data['city_id']:'') ;
	$district_id = mysqli_real_escape_string($con, isset($data['district_id'])?$data['district_id']:'') ;
	$taluka_id = mysqli_real_escape_string($con, isset($data['taluka_id'])?$data['taluka_id']:'') ;
	$ward_id = mysqli_real_escape_string($con, isset($data['ward_id'])?$data['ward_id']:'') ;
	
	
	$sql = "insert into users (voter_id, cell_no, email, first_name, last_name, age, gender, date_of_birth, blood_group, caste, religion, occupation, qualification, city_id, district_id, taluka_id, ward_id ) values('".$voter_id."', '".$cell_no."', '".$email."','".$voter_first_name."', '".$voter_last_name."', '".$age."', '".$gender."', '".$date_of_birth."', '".$blood_group."', '".$caste."', '".$religion."', '".$occupation."', '".$qualification."', '".$city_id."', '".$district_id."' , '".$taluka_id."', '".$ward_id."')";
	
	if (mysqli_query($con, $sql)) {
		$user_id = mysqli_insert_id($con);
		$response = array( 'status' => 'success' ,'msg' => 'Users has been created successfully',"user_id" => $user_id );
		//echo "New record created successfully";
	} else {
		$error =  "Error: " . mysqli_error($con);
		$response = array( 'status' => 'fail' ,'msg' => $error );
	}

	mysqli_close($con);
	
	echo  json_encode($response);
	
}