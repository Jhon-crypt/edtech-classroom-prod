<?php

/**Author : Oladele John

** © 2022 Oladele John

** Edtech classroom

** File name : resources/database/class-test-result-db-connection.php

** About : this module connects the edtech classroom to web application to its mysql database

*/ 

//defining the connection varaibles
$hostname = "sql102.epizy.com";
$username = "epiz_31748541";
$password = "6sBY5RSduDKek";
$database = "epiz_31748541_class_test_result";

$conn20 = new mysqli($hostname,$username,$password,$database);

//check if its connected
if($conn20->connect_error){

echo "<h1>Could not connect to the edtech database</h1>";

}
else{

//echo "<h1>Connected to the edtech database</h1>";
//echo $conn1->error;
}

//mysqli_close($conn);

?>