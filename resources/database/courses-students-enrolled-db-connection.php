<?php

/**Author : Oladele John

** © 2022 Oladele John

** Edtech classroom

** File name : resources/database/courses-students-enrolled-db-connection.php

** About : this module connects the edtech classroom web application to its mysql database

*/

//defining the connection varaibles
$hostname = "localhost";
$username = "john";
$password = "root";
$database = "edtech_classroom_course_enrolled_students";

$conn9 = new mysqli($hostname,$username,$password,$database);

//check if its connected
if($conn9->connect_error){

//echo "<h1>Could not connect to the edtech courses student enrolled database</h1>";

}
else{

//echo "<h1>Connected to the edtech edtech courses student enrolled database</h1>";
//echo $conn1->error;
}

?>