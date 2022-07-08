<?php

/**Author : Oladele John

** © 2022 Oladele John

** Web application

** File name : saved-messages/index.php

** About : this module redirect the user to the user saved messages from a class chat based on the user type

*/

/**PSUEDO ALGORITHM
 * *
 * define the saved messages redirect class
 * define the redirect function
 * 
 * *
 */
session_start();

class savedMessagesRedirect{

    public function redirectUser(){

        if(isset($_SESSION['lecturer_session'])){

           header('location:lecturer-saved-messages.php');

        }elseif(isset($_SESSION['student_session'])){

           header('location:student-saved-messages.php');

        }else{

           header('location:logout-success.php');

        }

    }

}

$saved_messages_redirect_controller = new savedMessagesRedirect();

$saved_messages_redirect_controller->redirectUser();

?>