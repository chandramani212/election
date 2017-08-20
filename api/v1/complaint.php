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
		$id = getIdFromUrl('complaint');//current php file name without .php extension
		$id = mysqli_real_escape_string($con ,isset($id)?$id:'');
		getComplaint($id, $con);
	break;
	case 'POST':
		addComplaint($data, $con);
	break;
	case 'DELETE':
		$id = getIdFromUrl('complaint');
		$id = mysqli_real_escape_string($con ,isset($id)?$id:'');
		deleteComplaint($id, $con);
	break;
	case 'PUT':
		$id = getIdFromUrl('complaint');
		$id = mysqli_real_escape_string($con ,isset($id)?$id:'');
		updateComplaint($data, $id, $con);
	break;
	default:
	
	break;

}

function getComplaint($id ='' ,$con){
	$dataResponse = array();
	$sql = "select c.* from complaints c ";
			//."left join compliant_images ci on ci.complaint_id=c.id";
		
	if($id > 0){
		$sql .= " where c.id = '".$id."' ";
	}
	$sql .= " order by c.id desc  ";
	
	if($result = mysqli_query($con , $sql)){
		if( mysqli_num_rows($result) > 0){
			while($row = mysqli_fetch_array($result)){	
				$data['complaint_id'] = $row['id']; 
				$data['ward_id'] = $row['ward_id']; 
				$data['user_id'] = $row['user_id']; 
				$data['message'] = $row['message']; 
				$data['status'] = $row['status']; 
				
				$sql_img = "select image from complaint_images where complaint_id ='".$row['id']."'";
				if($result_img = mysqli_query($con , $sql_img)){
					$data['images'] = array();
					while($row_img = mysqli_fetch_array($result_img)){	
						$data['images'][] = $row_img['image'];
					}
				}
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
	
function addComplaint($data, $con){
	//echo 'inside add complaint';
	$ward_id = mysqli_real_escape_string($con, isset($data['ward_id'])?$data['ward_id']:'') ;
	$user_id = mysqli_real_escape_string($con, isset($data['user_id'])?$data['user_id']:'') ;
	$message = mysqli_real_escape_string($con, isset($data['message'])?$data['message']:'') ;
	$images =  isset($data['images'])?$data['images']:'';
	
	//dd($images);
	
	$sql = "insert into complaints (ward_id, user_id, message, status) values('".$ward_id."', '".$user_id."', '".$message."','PENDING')";
	$img_added = 2;
	if (mysqli_query($con, $sql)) {
		$complaint_id = mysqli_insert_id($con);
		if(count($images) > 0 ){
						
			foreach($images as $image){
				
				$base64ImageString= $image;
				$targetPath = '../../images/complaint';
				$imageName = base64ToImage($base64ImageString,$targetPath);
 
				
				$sql_img = "insert into complaint_images (complaint_id, image) values('".$complaint_id."', '".$imageName."')";
				if (mysqli_query($con, $sql_img)){
					$img_added=1;
				}else{
					$img_added=0;
					//$error =  "Error: " . mysqli_error($con);
				}
			}
		}
		
		if($img_added ==0){
			$response = array( 'status' => 'success' ,'msg' => 'Complaint has been created successfully But unable to add images',"complaint_id" => $complaint_id );
		}else{
			$response = array( 'status' => 'success' ,'msg' => 'Complaint has been created successfully',"complaint_id" => $complaint_id );
		}
		
		//echo "New record created successfully";
		
	} else {
		$error =  "Error: " . mysqli_error($con);
		$response = array( 'status' => 'fail' ,'msg' => $error );
	}

	mysqli_close($con);
	
	echo  json_encode($response);
	
	
}


function UpdateComplaint($data,$id, $con){
	//echo 'inside add complaint';
	$ward_id = mysqli_real_escape_string($con, isset($data['ward_id'])?$data['ward_id']:'') ;
	$user_id = mysqli_real_escape_string($con, isset($data['user_id'])?$data['user_id']:'') ;
	$message = mysqli_real_escape_string($con, isset($data['message'])?$data['message']:'') ;
	$images =  isset($data['images'])?$data['images']:'';
	

	if($id !=''){
		
		if($message!=''){
			$set = " ,message ='".$message."' ";
		}
		
		if($ward_id!=''){
			$set .= " ,ward_id ='".$ward_id."' ";
		}
		
		if($user_id!=''){
			$set .= " ,user_id ='".$user_id."' ";
		}
	
	
		$sql = "update complaints set id ='".$id."' ".$set." where id ='".$id."' ";
		$img_added = 2;
		if (mysqli_query($con, $sql)) {
			if(count($images) > 0 ){
					
				$sql_del = "delete from complaint_images where complaint_id ='".$id."' ";
				mysqli_query($con, $sql_del);
				foreach($images as $image){
					
					$base64ImageString= $image;
					$targetPath = '../../images/complaint';
					$imageName = base64ToImage($base64ImageString,$targetPath);
	 
					
					 $sql_img = "insert into complaint_images (complaint_id, image) values('".$id."', '".$imageName."')";
					if (mysqli_query($con, $sql_img)){
						$img_added=1;
					}else{
						$img_added=0;
						//$error =  "Error: " . mysqli_error($con);
					}
				
				}
			}
			
			if($img_added ==0){
				$response = array( 'status' => 'success' ,'msg' => 'Complaint has been updated successfully But unable to update images' );
			}else{
				$response = array( 'status' => 'success' ,'msg' => 'Complaint has been updated successfully');
			}
			
			
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

function deleteComplaint($id, $con){
	if($id !=''){
		
		$sql = "delete from complaints where id ='".$id."' ";
		if (mysqli_query($con, $sql)) {
			
			$sql_del = "delete from complaint_images where complaint_id ='".$id."' ";
			mysqli_query($con, $sql_del);
			$response = array( 'status' => 'success' ,'msg' => 'Complaint has been deleted successfully');
			
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