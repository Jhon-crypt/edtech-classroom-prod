<?php

/**Author : Oladele John

** © 2022 Oladele John

** Edtech classroom

** File name : resources/database/admin-setup-db-connection.php

** About : this module connects the edtech classroom web application to its mysql database

*/

//defining the connection varaibles
$hostname = "localhost";
$username = "john";
$password = "root";
$database = "edtech_classroom_admin_set_up";

$conn5 = new mysqli($hostname,$username,$password,$database);

//check if its connected
if($conn5->connect_error){

//echo "<h1>Could not connect to the edtech database</h1>";

}
else{

//echo "<h1>Connected to the edtech database</h1>";
//echo $conn1->error;
}

?>