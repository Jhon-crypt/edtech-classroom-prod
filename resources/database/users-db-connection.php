<?php

/**Author : Oladele John

** © 2022 Oladele John

** Edtech classroom

** File name : resources/database/users-db-connection.php

** About : this module connects the edtech classroom to web application to its mysql database

*/

//defining the connection varaibles
$hostname = "localhost";
$username = "john";
$password = "root";
$database = "edtech_classroom_users";

$conn1 = new mysqli($hostname,$username,$password,$database);

//check if its connected
if($conn1->connect_error){

echo "<h1>Could not connect to the edtech database</h1>";

}
else{

//echo "<h1>Connected to the edtech database</h1>";
//echo $conn1->error;
}

//mysqli_close($conn);

?>