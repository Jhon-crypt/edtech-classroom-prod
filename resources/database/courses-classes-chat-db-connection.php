<?php

/**Author : Oladele John

** © 2022 Oladele John

** Edtech classroom

** File name : resources/database/courses-classes-attendance-db-connection.php

** About : this module connects the edtech classroom web application to its mysql database

*/

//defining the connection varaibles
$hostname = "localhost";
$username = "john";
$password = "root";
$database = "edtech_classroom_course_classroom_chat";

$conn12 = new mysqli($hostname,$username,$password,$database);

//check if its connected
if($conn12->connect_error){

//echo "<h1>Could not connect to the edtech courses classroom chat</h1>";

}
else{

//echo "<h1>Connected to the edtech edtech classroom chat database</h1>";
//echo $conn1->error;
}

?>