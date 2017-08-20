<?php

include '../../config/config.php';
include '../../includes/mysqli_connect.php';
include '../../includes/functions.php';
include '../../includes/middleware.php';

$method = getMethod();
$data = json_decode(file_get_contents('php://input'), true);
 
//dd($data);

switch($method){
	case 'GET';
		$id = getIdFromUrl('users'); //current php file name without .php extension
		$id = mysqli_real_escape_string($con ,isset($id)?$id:'');
		getUsers($id, $con);
	break;
	case 'POST':
		addUsers($data, $con);
	break;
	case 'DELETE':
		$id = getIdFromUrl('users');//current php file name without .php extension
		$id = mysqli_real_escape_string($con ,isset($id)?$id:'');
		deleteUsers($id, $con);
	break;
	case 'PUT':
		$id = getIdFromUrl('users');//current php file name without .php extension
		$id = mysqli_real_escape_string($con ,isset($id)?$id:'');
		updateUsers($data, $id, $con);
	break;
	default:
	
	break;

}


function getUsers($id ='' ,$con){
	$dataResponse = array();
	$sql = "select * from users ";
			//."left join compliant_images ci on ci.complaint_id=c.id";
		
	if($id > 0){
		$sql .= " where id = '".$id."' ";
	}
	$sql .= " order by id desc  ";
	
	if($result = mysqli_query($con , $sql)){
		if( mysqli_num_rows($result) > 0){
			while($row = mysqli_fetch_array($result)){	
				$data['voter_id'] = $row['id']; 
				$data['cell_no'] = $row['cell_no']; 
				$data['email'] = $row['email']; 
				$data['first_name'] = $row['first_name']; 
				$data['last_name'] = $row['last_name']; 
				$data['age'] = $row['age']; 
				$data['gender'] = $row['gender']; 
				$data['date_of_birth'] = $row['date_of_birth']; 
				$data['blood_group'] = $row['blood_group']; 
				$data['caste'] = $row['caste']; 
				$data['occupation'] = $row['occupation']; 
				$data['qualification'] = $row['qualification']; 
				$data['religion'] = $row['religion']; 
				$data['profile_pic'] = BASE_URL.'images/users/'.$row['id'].'/'.$row['profile_pic']; 
				$dataResponse[] = $data;
			}
			$response = [ "status" => 'success' , "data" => $dataResponse ];
		}else{
		
			$response = [ "status" => 'fail' , "msg" => "No record found" ];
		}
	}else{
		$error =  "Error: " . mysqli_error($con);
		$response = array( 'status' => 'fail' ,'msg' => $error );
	}
	
	
	echo  json_encode($response);
}
	
function addUsers($data, $con){
	//echo 'inside add complaint';
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
	
	$profile_pic =  isset($data['profile_pic'])?$data['profile_pic']:'';
	
	//dd($images);
	
	$img_added = 2;
	if ($voter_id!='') {
		
		$sql = "insert into users (voter_id, cell_no, email, first_name, last_name, age, gender, date_of_birth, blood_group, caste, religion, occupation, qualification, city_id, district_id, taluka_id, ward_id ) values('".$voter_id."', '".$cell_no."', '".$email."','".$voter_first_name."', '".$voter_last_name."', '".$age."', '".$gender."', '".$date_of_birth."', '".$blood_group."', '".$caste."', '".$religion."', '".$occupation."', '".$qualification."', '".$city_id."', '".$district_id."' , '".$taluka_id."', '".$ward_id."')";
		
		if (mysqli_query($con, $sql)){
			$user_id = mysqli_insert_id($con);
			
			$path = '../../images/users/'.$user_id;
			try {
				mkdir($path);
			}catch ( Exception $e){
				$dirMsg = $e->getMessage();
			}
			$base64ImageString= $profile_pic;
			$targetPath = $path;
			$imageName = base64ToImage($base64ImageString,$targetPath);

			$sql_img = "update users set profile_pic= '".$imageName."' where id ='".$user_id."'";
			if (mysqli_query($con, $sql_img)){
				$img_added=1;
			}else{
				$img_added=0;
				//$error =  "Error: " . mysqli_error($con);
			}
			
			
			if($img_added ==0){
				$response = array( 'status' => 'success' ,'msg' => 'User has been created But unableto to update images', 'user_id' => $user_id);
			}else{
				$response = array( 'status' => 'success' ,'msg' => 'User has been created successfully'  , 'user_id' => $user_id);
			}
			
			
		}else{
			
			//$error =  "Error: " . mysqli_error($con);
		}
		

		
		//echo "New record created successfully";
		
	} else {
		
		$response = array( 'status' => 'fail' ,'msg' => "Voter id cannot be empty" );
	}

	mysqli_close($con);
	
	echo  json_encode($response);
	
	
}


function UpdateUsers($data,$id, $con){
	//echo 'inside add complaint
	//dd($data);
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
	
	$profile_pic =  isset($data['profile_pic'])?$data['profile_pic']:'';
	
	
	$sql = "insert into users (voter_id, cell_no, email, first_name, last_name, age, gender, date_of_birth, blood_group, caste, religion, occupation, qualification, city_id, district_id, taluka_id, ward_id ) values('".$voter_id."', '".$cell_no."', '".$email."','".$voter_first_name."', '".$voter_last_name."', '".$age."', '".$gender."', '".$date_of_birth."', '".$blood_group."', '".$caste."', '".$religion."', '".$occupation."', '".$qualification."', '".$city_id."', '".$district_id."' , '".$taluka_id."', '".$ward_id."')";

	if($id !=''){
		
		if($voter_id!=''){
			$set = " ,voter_id ='".$voter_id."' ";
		}
		
		if($cell_no!=''){
			$set .= " ,cell_no ='".$cell_no."' ";
		}
		
		if($email!=''){
			$set .= " ,email ='".$email."' ";
		}
		
		if($voter_first_name!=''){
			$set .= " ,first_name ='".$voter_first_name."' ";
		}
		
		if($voter_last_name!=''){
			$set .= " ,last_name ='".$voter_last_name."' ";
		}
		
		if($age!=''){
			$set .= " ,age ='".$age."' ";
		}
		
		if($gender!=''){
			$set .= " ,gender ='".$gender."' ";
		}
		
		if($date_of_birth!=''){
			$set .= " ,date_of_birth ='".$date_of_birth."' ";
		}
		
		if($blood_group!=''){
			$set .= " ,blood_group ='".$blood_group."' ";
		}
		
		if($caste!=''){
			$set .= " ,caste ='".$caste."' ";
		}
		
		if($religion!=''){
			$set .= " ,religion ='".$religion."' ";
		}
		
		if($occupation!=''){
			$set .= " ,occupation ='".$occupation."' ";
		}
		
		if($qualification!=''){
			$set .= " ,qualification ='".$qualification."' ";
		}
		
		if($city_id!=''){
			$set .= " ,city_id ='".$city_id."' ";
		}
		
		if($district_id!=''){
			$set .= " ,district_id ='".$district_id."' ";
		}
		
		if($taluka_id=''){
			$set .= " ,taluka_id ='".$taluka_id."' ";
		}
		
		if($ward_id!=''){
			$set .= " ,ward_id ='".$ward_id."' ";
		}
		
		if($profile_pic!=''){
			
			
			$path = '../../images/users/'.$id;
			try {
				if (!is_dir($path)) {
					mkdir($path, 0777, true);
				}
				
			}catch ( Exception $e){
				$dirMsg = $e->getMessage();
			}
			$base64ImageString= $profile_pic;
			$targetPath = $path;
			$imageName = base64ToImage($base64ImageString,$targetPath);
			
			$set .= " ,profile_pic ='".$imageName."' ";
		}
		
	
		$sql = "update users set id ='".$id."' ".$set." where id ='".$id."' ";
		if (mysqli_query($con, $sql)) {

				$response = array( 'status' => 'success' ,'msg' => 'Users has been updated successfully' , 'user_id' => $id );
		}else {
			$error =  "Error: " . mysqli_error($con);
			$response = array( 'status' => 'fail' ,'msg' => $error );
		}
	}else{
		$response = array( 'status' => 'fail' ,'msg' => "id is required to updated the record" );
	}
	//dd($images);
	
	mysqli_close($con);
	
	echo  json_encode($response);
	
	
}

function deleteUsers($id, $con){
	if($id !=''){
		
		$sql = "delete from users where id ='".$id."' ";
		if (mysqli_query($con, $sql)) {
		
			$response = array( 'status' => 'success' ,'msg' => 'User has been deleted successfully');
			
		}else {
			
			$error =  "Error: " . mysqli_error($con);
			$response = array( 'status' => 'fail' ,'msg' => $error );
		}
		
	}else{
		$response = array( 'status' => 'fail' ,'msg' => "id is required to delete the record" );
	}
	
	mysqli_close($con);

	echo  json_encode($response);
	
}
