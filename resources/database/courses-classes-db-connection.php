<?php

/**Author : Oladele John

** © 2022 Oladele John

** Edtech classroom

** File name : resources/database/courses-classes-db-connection.php

** About : this module connects the edtech classroom web application to its mysql database

*/

//defining the connection varaibles
$hostname = "localhost";
$username = "john";
$password = "root";
$database = "edtech_classroom_course_classes";

$conn10 = new mysqli($hostname,$username,$password,$database);

//check if its connected
if($conn10->connect_error){

//echo "<h1>Could not connect to the edtech courses classes database</h1>";

}
else{

//echo "<h1>Connected to the edtech edtech courses classes database</h1>";
//echo $conn1->error;
}

?>