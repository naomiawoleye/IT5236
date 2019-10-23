<?php
if ($_SERVER['REQUEST_METHOD'] == "POST") {
	$listItem = $_POST['listItem'];
	$listID = $_POST['listID'];
	
	if (array_key_exists('fin', $_POST)) {
		$complete = 1;
	} else {
		$complete = 0;
	}
	if (empty($_POST['finBy'])) {
		$finBy = null;
	} else {
		$finBy = $_POST['finBy'];
	}

	//Make a call to the api 
	
	//Build URL for API
	$url= "http://52.90.89.104/api/task.php?listID=$listID";
	//Create a JSON strong 
	$data = array('completed'=>$complete,'taskName'=>$listItem,'taskDate'=>$finBy);
	$data_json = json_encode($data); //This comman turns array 2 string
	
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json','Content-Length: ' . strlen($data_json)));
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
	curl_setopt($ch, CURLOPT_POSTFIELDS,$data_json);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	$response  = curl_exec($ch); //Body of the response
	$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
	curl_close($ch);
	

	//If we get a 204 status code
	if ($httpcode == 204){
		header("Location: index.php");
	}else {
		//If we do not get a 204 status code
		header("Location: index.php?error=edit");
	}
	
	
			
	
	//API errors 
	
	

			
		
}
?>
