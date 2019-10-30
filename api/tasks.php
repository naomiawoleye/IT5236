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

//GET TASK
if ($_SERVER['REQUEST_METHOD'] == "GET") {
    if (!$dbconnecterror) {
		try {
			$sql = "SELECT * FROM doList";
			$stmt = $dbh->prepare($sql);
			$stmt->execute();
			$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
			$final = [];
			
			foreach ($result as $task) {
				//Rename task
				
				$task['taskName'] = $task['listItem'];
				$task['taskDate'] = $task['finishDate'];
				$task['completed'] = $task['complete'];
				
				//delete old 
				unset ($task['listItem'], $task['finishDate'], $task['complete']);
				
				//store upadte task
				$final[] = $task;
			}
			
			
			
			
			http_response_code(200); //task created
			echo json_encode ($result);
			exit();
			
		} catch (PDOException $e) {
			http_response_code(504); //Gateway Timeout
			echo "database exception maybe fields";
			exit();
		}
    }
} else {
	http_response_code(405); //method not allowed
	exit();
}




?>