<?php 
// Declare the credentials to the database
	$dbconnecterror = FALSE;
	$dbh = NULL; 

require_once 'credentials.php'; 
	try{
		
		$conn_string = "mysql:host=".$dbserver.";dbname=".$db;
		
		$dbh= new PDO($conn_string, $dbusername, $dbpassword);
		$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		
	}catch(Exception $e){
		//$dbconnecterror = TRUE;
		//Datbase issues were encoutered 
		http_response_code(504);
		//to get out of php
		exit();
	}

	//UPDATE TASK
	if ($_SERVER['REQUEST_METHOD'] == "PUT") {
		if(array_key_exists('listID', $_GET)){
			$listID = $_GET['listID'];
		} else {
				http_response_code(400);
				echo "Missing list id";
				exit();
		}
				
		//Decoding the json body from the request 
		$task = json_decode(file_get_contents('php://input'),true);
		//make sure task is not null

		if ($task == NULL) {
			http_response_code(400);
			exit();
		}

		if (array_key_exists('completed', $task)) {
			$complete = $task["completed"];
		} else {
			http_response_code(400);
			//the bad request
			echo "Missing complete";
			exit();
		}
		if (array_key_exists('taskName', $task)) {
			$taskName = $task["taskName"];
		} else {
				http_response_code(400);
				echo "Missing task name";
				exit();
			}
		if (array_key_exists('taskDate', $task)) {
			$taskDate = $task["taskDate"];
		} else {
				http_response_code(400);
				echo "Missing task date";
				exit();
			}

		//Add the other two fields here
		
		
	if (!$dbconnecterror) { 
		try {
			$sql = "UPDATE doList SET complete=:complete, listItem=:listItem, finishDate=:finishDate WHERE listID=:listID"; 
			$stmt = $dbh->prepare($sql); 
			$stmt->bindParam(":complete", $complete); 
			$stmt->bindParam(":listItem", $taskName); 
			$stmt->bindParam(":finishDate", $taskDate); 
			$stmt->bindParam(":listID", $listID); 
			$response = $stmt->execute();
			http_response_code(204);
			exit();	

		} catch (PDOException $e) {
			http_response_code(504);
			exit();
		}	
	} else {
		http_response_code(504);
		echo "database error";
		exit();
	}

//ADD TASK
} else if ($_SERVER['REQUEST_METHOD'] == "POST"){
	//Decoding the json body from the request 
	$task = json_decode(file_get_contents('php://input'),true);
	if ($task == NULL) {
		http_response_code(400);
		echo 'missing json body';
		exit();
	}
	
	//Data Validation
	if (array_key_exists('completed', $task)) {
		$complete = $task["completed"];
	   } else {
		   http_response_code(400);
		   //the bad request
			echo "Missing complete";
		   exit();
	   }
   if (array_key_exists('taskName', $task)) {
		$taskName = $task["taskName"];
	   
	   } else {
		   http_response_code(400);
			echo "Missing task name";
		   exit();
	   }
   if (array_key_exists('taskDate', $task)) {
		$taskDate = $task["taskDate"];
	   
	   } else {
		   http_response_code(400);
			echo "Missing task date";
		   exit();
	   }
	
	   if (!$dbconnecterror) {
		try {
			$sql = "INSERT INTO doList (complete, listItem, finishDate) VALUES (:complete, :listItem, :finishDate)";
			$stmt = $dbh->prepare($sql);			
			$stmt->bindParam(":complete", $complete);
			$stmt->bindParam(":listItem", $taskName);
			$stmt->bindParam(":finishDate", $taskDate);
			$response = $stmt->execute();	
			$taskID = $dbh->lastInsertId();
			http_response_code(201);

			exit();

			
		} catch (PDOException $e) {
			http_response_code(504);
			echo "database error";
			exit();
		}	
	} else {
		http_response_code(504);
		echo "database error";
		exit();
	}   


//DELETE TASK
} else if ($_SERVER['REQUEST_METHOD'] == "DELETE"){
	if(array_key_exists('listID', $_GET)){
		$listID = $_GET['listID'];
	} else {
			http_response_code(400);
			echo "Missing list id";
			exit();
	}
	if (!$dbconnecterror) {
		try {
			$sql = "DELETE FROM doList where listID = :listID";
			$stmt = $dbh->prepare($sql);			
			$stmt->bindParam(":listID", $listID);
		
			$response = $stmt->execute();	
			http_response_code(204);
			exit();
			
		} catch (PDOException $e) {
			http_response_code(504);
			echo "database error";
			
			exit();
		}	
	} else {
		http_response_code(504);
		echo "database error";
		exit();
	}

} else {
	http_response_code(405); //method not allowed
	exit();
}



?>
