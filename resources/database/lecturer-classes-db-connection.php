<?php

/**Author : Oladele John

** © 2022 Oladele John

** Edtech classroom

** File name : resources/database/lecturer-classes-db-connection.php

** About : this module connects the edtech classroom web application to its mysql database

*/

//defining the connection varaibles
$hostname = "localhost";
$username = "john";
$password = "root";
$database = "edtech_classroom_lecturer_classes";

$conn6 = new mysqli($hostname,$username,$password,$database);

//check if its connected
if($conn6->connect_error){

//echo "<h1>Could not connect to the edtech lecturer classes database</h1>";

}
else{

//echo "<h1>Connected to the edtech lecturer classes database</h1>";
//echo $conn1->error;
}

?>