<?php
if ($_SERVER['REQUEST_METHOD'] == "POST") {
		$listItem = $_POST['listItem'];
	
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
	
	
	$url= "http://52.90.89.104/api/task.php";

	$data = array('completed'=>$complete,'taskName'=>$listItem,'taskDate'=>$finBy);
	$data_json = json_encode($data); //This comman turns array 2 string
		
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json','Content-Length: ' . strlen($data_json)));
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
	curl_setopt($ch, CURLOPT_POSTFIELDS,$data_json);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	
	$response  = curl_exec($ch); //Body of the response
	$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
	curl_close($ch);


	if ($httpcode == 201){
		header("Location: index.php");

	} else {
		header("Location: index.php?error=add");
	}
}

?>
