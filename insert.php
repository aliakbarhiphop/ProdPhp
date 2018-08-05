<?php
$conn = mysqli_connect("localhost", "root", "", "prod");
if (!$conn) {
    mysqli_error();
    die();
}

$sqlCustomer="INSERT INTO customer(managerId,name,deals,total,paid,credit,orders)VALUES('0','ALI','5','5000','3000','2000','5');";

if (mysqli_query($conn, $sqlCustomer)) {
     echo "Success";	 
    } else {
        echo "Failed";
    }      
	$sqlCustomer="INSERT INTO customer(managerId,name,deals,total,paid,credit,orders)VALUES('0','AKBAR','15','15000','13000','12000','15');";
	if (mysqli_query($conn, $sqlCustomer)) {
     echo "Success";	 
    } else {
        echo "Failed";
    }      
		$sqlCustomer="INSERT INTO customer(managerId,name,deals,total,paid,credit,orders)VALUES('0','JOSEPH','50','50000','30000','20000','50');";
	if (mysqli_query($conn, $sqlCustomer)) {
     echo "Success";	 
    } else {
        echo "Failed";
    }      
	mysqli_close($conn);
?>