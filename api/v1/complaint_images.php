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
		$id = getIdFromUrl('complaint_images'); //current php file name without .php extension
		$id = mysqli_real_escape_string($con ,isset($id)?$id:'');
		getComplaintImages($id, $con);
	break;
	case 'POST':
		addComplaintImages($data, $con);
	break;
	case 'DELETE':
		$id = getIdFromUrl('complaint_images');//current php file name without .php extension
		$id = mysqli_real_escape_string($con ,isset($id)?$id:'');
		deleteComplaintImages($id, $con);
	break;
	case 'PUT':
		$id = getIdFromUrl('complaint_images');//current php file name without .php extension
		$id = mysqli_real_escape_string($con ,isset($id)?$id:'');
		updateComplaintImages($data, $id, $con);
	break;
	default:
	
	break;

}

function getComplaintImages($id ='' ,$con){
	$dataResponse = array();
	$sql = "select ci.* from complaint_images ci ";
			//."left join compliant_images ci on ci.complaint_id=c.id";
		
	if($id > 0){
		$sql .= " where ci.id = '".$id."' ";
	}
	$sql .= " order by ci.id desc  ";
	
	if($result = mysqli_query($con , $sql)){
		if( mysqli_num_rows($result) > 0){
			while($row = mysqli_fetch_array($result)){	
				$data['complaint_images_id'] = $row['id']; 
				$data['complaint_id'] = $row['complaint_id']; 
				$data['image'] = BASE_URL.'images/complaint/'.$row['image']; 
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
	
function addComplaintImages($data, $con){
	//echo 'inside add complaint';
	$complaint_id = mysqli_real_escape_string($con, isset($data['complaint_id'])?$data['complaint_id']:'') ;
	$images =  isset($data['images'])?$data['images']:'';
	
	//dd($images);
	
	$img_added = 2;
	if ($complaint_id!='') {
		
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
			$response = array( 'status' => 'fail' ,'msg' => 'Unable to add complaint images' );
		}else{
			$response = array( 'status' => 'success' ,'msg' => 'Complaint Images has been created successfully' );
		}
		
		//echo "New record created successfully";
		
	} else {
		
		$response = array( 'status' => 'fail' ,'msg' => "Complaint id cannot be empty" );
	}

	mysqli_close($con);
	
	echo  json_encode($response);
	
	
}


function UpdateComplaintImages($data,$id, $con){
	//echo 'inside add complaint
	//dd($data);
	$complaint_id = mysqli_real_escape_string($con, isset($data['complaint_id'])?$data['complaint_id']:'') ;
	$image =  isset($data['image'])?$data['image']:'';
	

	if($id !=''){
		
		if($complaint_id!=''){
			$set = " ,complaint_id ='".$complaint_id."' ";
		}
		
		
		$base64ImageString= $image;
		$targetPath = '../../images/complaint';
		$imageName = base64ToImage($base64ImageString,$targetPath);
		
		if($imageName!=''){
			$set .= " ,image ='".$imageName."' ";
		}
	
		echo $sql = "update complaint_images set id ='".$id."' ".$set." where id ='".$id."' ";
		if (mysqli_query($con, $sql)) {

				$response = array( 'status' => 'success' ,'msg' => 'Complaint Images has been updated successfully' , 'complaint_images_id' => $id );
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

function deleteComplaintImages($id, $con){
	if($id !=''){
		
		$sql = "delete from complaint_images where id ='".$id."' ";
		if (mysqli_query($con, $sql)) {
			
			$response = array( 'status' => 'success' ,'msg' => 'Complaint Images has been deleted successfully');
			
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