<?php
$conn = mysqli_connect("localhost", "root", "12345678", "prod");
if (!$conn) {
     mysqli_error();
     die();
}

$response            = array();
$response["success"] = false;
$response["status"]  = "INVALID";

$action = $_POST["action"];

if ($action == "select") {
	
     $tableName= $_POST["tableName"];
	 
	 if($_POST["projection"] != null){
         $projection = json_decode($_POST["projection"]);
	 }else{
		 $projection = null;
	 }
	 
	 $selection = $_POST["selection"];
	 
	 if($_POST["selectionArg"] != null){
         $selectionArgs = json_decode($_POST["selectionArg"]);
	 }else{
		   $selectionArgs = null;
	 }
	 
	 $sortOrder = $_POST["sortOrder"];

     $result = "SELECT "; 

	     $size= count($projection);
	     $result = $result . $projection[0] . " AS a1";
		 
		 $x = 2;
	     for($i = 1; $i < $size; $i++){
             $result = $result . "," . $projection[$i] . " AS a" . $x++;
        }

     $result = $result . " FROM " . $tableName;

     if($selection != null){
	     $selectionJoin=" WHERE ";
	     $SECOND=$selection;
	     for($j=0;$j<count($selectionArgs);$j++){
		     $FIRST =strstr($SECOND, '?', true);
		     $selectionJoin=$selectionJoin . $FIRST . "'" . $selectionArgs[$j] . "'";
	         $TEMP=strstr($SECOND, '?', false);
	         $SECOND=substr($TEMP, 1);
	     }
         $result = $result . $selectionJoin . $SECOND;	
     }

     if($sortOrder != null){
	     $result = $result . " ORDER BY " . $sortOrder;
     }

     $result=$result . ";";

     $queryResponse   = mysqli_query($conn, $result);
     $affected = mysqli_affected_rows($conn);
     if ($affected > 0) {
         $response["success"] = true;
		
         $cursorArray = array(); 
         while ($row = mysqli_fetch_array($queryResponse, MYSQLI_ASSOC)) {
			
             $temp = array();
			 $x = 1;
		     for($i=0;$i<count($projection);$i++){
	             $temp[$projection[$i]] = $row["a" . $x++]; 
		     }	   
             array_push($cursorArray, $temp);
         }    
		
	     $response["cursor"] = $cursorArray;
	 
    } else {
         $response["status"] = "EMPTY DATABASE";
    }	
} 

elseif($action == "insert"){
	$tableName = $_POST["tableName"];
	$columnNames = json_decode($_POST["columnNames"]);
	$insertValues = json_decode($_POST["insertValues"]);
	
	$insertQuery = "INSERT INTO " . $tableName . "(";
	
	$insertSize = count($columnNames);
	$insertQuery = $insertQuery . $columnNames[0];
	for($i = 1; $i < $insertSize; $i++){
		$insertQuery = $insertQuery . " , " . $columnNames[$i];
	}
	
	$insertQuery = $insertQuery . ") VALUES (";
	
	$insertQuery = $insertQuery . "'" . $insertValues[0] . "'";
	for($i = 1; $i < $insertSize; $i++){
		$insertQuery = $insertQuery . " , '" . $insertValues[$i] . "'";
	}
	$insertQuery = $insertQuery . ");";

	if (mysqli_query($conn, $insertQuery)) {
        $response["idOfLastInsertedRaw"] = mysqli_insert_id($conn);
        $response["success"]             = true;                
    } else {
        $response["status"] = "INSERT ERROR";
    }
}

elseif($action == "delete"){
	 $tableName= $_POST["tableName"];

	 $selection = $_POST["selection"];
	 
	 if($_POST["selectionArg"] != null){
         $selectionArgs = json_decode($_POST["selectionArg"]);
	 }else{
		   $selectionArgs = null;
	 }
	
	 $deleteQuery = "DELETE FROM " . $tableName;
	 
	 if($selection != null){
	     $selectionJoin=" WHERE ";
	     $SECOND=$selection;
	     for($j=0;$j<count($selectionArgs);$j++){
		     $FIRST =strstr($SECOND, '?', true);
		     $selectionJoin=$selectionJoin . $FIRST . "'" . $selectionArgs[$j] . "'";
	         $TEMP=strstr($SECOND, '?', false);
	         $SECOND=substr($TEMP, 1);
	     }
         $deleteQuery = $deleteQuery . $selectionJoin;	
     }
	 
	 $deleteQuery = $deleteQuery . ";";
	 
     if (mysqli_query($conn, $deleteQuery)) {
		$response["affectedRaw"]= mysqli_affected_rows($conn);
        $response["success"] = true;
     } else {
        $response["status"] = "DELETE ERROR";
     }
}

elseif ($action == "update") {
	
	$tableName = $_POST["tableName"];
	
	$columnNames = json_decode($_POST["columnNames"]);
	
	$insertValues = json_decode($_POST["insertValues"]);
	
	$selection = $_POST["selection"]; 
	 
	if($_POST["selectionArg"] != null){
        $selectionArgs = json_decode($_POST["selectionArg"]);
    }else{
		 $selectionArgs = null;
	}
	
	$updateQuery = "UPDATE " . $tableName . " SET ";
    
	$insertSize = count($columnNames);
	$updateQuery = $updateQuery . $columnNames[0] . " = '" . $insertValues[0] . "'";
	for($i = 1; $i < $insertSize; $i++){
		$updateQuery = $updateQuery . ", " .$columnNames[$i] . " = '" . $insertValues[$i] . "'";
	}	
	
	if($selection != null){
	     $selectionJoin=" WHERE ";
	     $SECOND=$selection;
	     for($j=0;$j<count($selectionArgs);$j++){
		     $FIRST =strstr($SECOND, '?', true);
		     $selectionJoin=$selectionJoin . $FIRST . "'" . $selectionArgs[$j] . "'";
	         $TEMP=strstr($SECOND, '?', false);
	         $SECOND=substr($TEMP, 1);
	     }
         $updateQuery = $updateQuery . $selectionJoin;	
     }
	 
	 $updateQuery = $updateQuery . ";";
	
	
    if (mysqli_query($conn, $updateQuery)) {
		$response["affectedRaw"]= mysqli_affected_rows($conn);
        $response["success"] = true;
    } else {
        $response["status"] = "UPDATE ERROR";
    }
} 

echo json_encode($response);
mysqli_close($conn);	

?>
