<?php
$conn = mysqli_connect("localhost", "root", "12345678", "prod");
if (!$conn) {
    mysqli_error();
    die();
}

$sqlStock="CREATE TABLE stock(_id INT AUTO_INCREMENT PRIMARY KEY,itemName VARCHAR(30),price INT,orders INT,sales INT,invalids TINYINT);";

$sqlCustomer="CREATE TABLE customer(_id INT AUTO_INCREMENT PRIMARY KEY, managerId INT, name VARCHAR(30), deals INT, paid INT, credit INT, total INT, orders INT);";

$sqlStaff="CREATE TABLE staff(_id INT AUTO_INCREMENT PRIMARY KEY, username VARCHAR(30),name VARCHAR(30),password VARCHAR(30),client INT,sales INT,total INT,paid INT,credit INT,orders INT);";

$sqlBill="CREATE TABLE bill(_id INT AUTO_INCREMENT PRIMARY KEY, billDate DATE,customerId INT, total INT, discPer INT, discount INT, pay INT);";

$sqlCart="CREATE TABLE cart(_id INT AUTO_INCREMENT PRIMARY KEY, billId INT,itemId INT, quantity INT, total INT, discPer INT, discount INT,pay INT);";

if (mysqli_query($conn, $sqlStock)) {
     echo "Success";	 
    } else {
        echo "Failed";
    } 

if (mysqli_query($conn, $sqlCustomer)) {
     echo "Success";	 
    } else {
        echo "Failed";
    }     

if (mysqli_query($conn, $sqlStaff)) {
     echo "Success";	 
    } else {
        echo "Failed";
    } 

if (mysqli_query($conn, $sqlBill)) {
     echo "Success";	 
    } else {
        echo "Failed";
    } 	
	
if (mysqli_query($conn, $sqlCart)) {
     echo "Success";	 
    } else {
        echo "Failed";
    } 	

	mysqli_close($conn);
?>