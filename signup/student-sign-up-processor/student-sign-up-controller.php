<?php
/**Author : Oladele John

** © 2022 Oladele John

** Edtech classroom

** File name : signup/student-signup-processing/student-sign-up-controller.php

** About : this controller controlls the student student sign up model

*/

/**PSUEDO ALGORITHM
 * *
 * initiate the student sign up controller class
 * then define the controlleer function and ....
 * 
 */

//initiate the student sign up controller class
//initiate the student sign up controller class
class studentSignUpController{

    public $fullname;

    public $username;

    public $matric_number;

    public $about;

    public $avatar_filename;

    public $level;

    public $sign_up_status;



    //then define the controlleer function and ....
    public function controller(){
    
        //incluing the student sign up model
        require('student-sign-up-model.php');

        $student_signup = new studentSignUp();

        $student_signup->fetchStudentData();

        //checking if the all fetched student data were submitted
        if(!empty($student_signup->email) && !empty($student_signup->name) && !empty($student_signup->gender) 
        && !empty($student_signup->level) && !empty($student_signup->matric_number) && !empty($student_signup->about) 
        && !empty($student_signup->username) && !empty($student_signup->password) 
        && !empty($_FILES['avatar']['type'])){

            //require the env library
            require('../../vendorEnv/autoload.php');

            $user_db_conn_env = Dotenv\Dotenv::createImmutable(__DIR__, '../../env/db-conn-var.env');
            $user_db_conn_env->load();

            // user db connection
            include('../../resources/database/users-db-connection.php');

            $student_signup->sanitized_email = $student_signup->sanitize($conn1,$student_signup->email);

            $student_signup->sanitized_name = $student_signup->sanitize($conn1,$student_signup->name);

            $student_signup->sanitized_gender = $student_signup->sanitize($conn1,$student_signup->gender);

            $student_signup->sanitized_level = $student_signup->sanitize($conn1,$student_signup->level);

            $student_signup->sanitized_matric_number = $student_signup->sanitize($conn1,$student_signup->matric_number);

            $student_signup->sanitized_about = $student_signup->sanitize($conn1,$student_signup->about);

            $student_signup->sanitized_username = $student_signup->sanitize($conn1,$student_signup->username);

            $student_signup->sanitized_password = $student_signup->sanitize($conn1,$student_signup->password);

            $student_signup->autoGeneratedData();

            $student_signup->studentCourseEnrollDbTable();

            $student_signup->studentResetPassword();
            
            $student_signup->studentNotification();

            $student_signup->processingStudentAvatar();

            $student_signup->insertStudentIntoDb();

            mysqli_close($conn1);

            //Setting up profile preveiew varaibles
            $this->fullname = $student_signup->sanitized_name;

            $this->username = $student_signup->sanitized_username;

            $this->matric_number = $student_signup->sanitized_matric_number;

            $this->about = $student_signup->sanitized_about;

            $this->avatar_filename = $student_signup->avatar_file_name;

            $this->level = $student_signup->sanitized_level;

            $this->sign_up_status = $student_signup->signup_status;

        }
        else{

           echo "Not all forms were submitted";

        }


    }


}

?>