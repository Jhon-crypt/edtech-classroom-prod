<?php

/**Author : Oladele John

** © 2022 Oladele John

** Web application

** File name : courses/create-classroom/create-clasroom-model.php

** About : this module processes the course classrooom form

*/ 

/**PSUEDO ALGORITHM
 * *
 * initiate the create classroom class
 * fetch the lecturer session
 * fetch all the create class data from the class create form
 * sanitize the data
 * define other auto generated data
 * create the neccessary classroom database
 * process the uploaded instructional material
 * process the class note
 * insert the data into the classroom database
 * perform some administrative functions
 * redirect the lecturer to the class dashboard
 * 
 * *
 */

//initiate the create classroom class
class createClassroom{

    public $lecturer_session;

    public $class_note;

    public $class_topic;

    public $about_class;

    public $class_assignment;

    public $due_date;
    
    public $course_encrypted_id;

    public $course_code;


    public $sanitized_course_code;

    public $sanitized_class_note;

    public $sanitized_class_topic;

    public $sanitized_about_class;

    public $sanitized_class_assignment;

    public $sanitized_due_date;

    public $sanitized_course_encrypted_id;



    public $material_type;

    public $class_encrypted_id;

    public $class_status;

    public $date_created;

    public $time_created;

    public $assignment_type;

    public $assignment_status;

    public $date_assignment_created;

    public $time_assignment_created;

    public $note_file_path;


    public $material_file_name;

    public $create_classroom_status;

    //fetch the lecturer session
    public function fetchLecturerSession(){

       $this->lecturer_session = $_SESSION['lecturer_session'];

    }


    //fetch all the create class data from the class create form
    public function fetchClassData(){

       $this->class_note = $_POST['classNote'];

       $this->class_topic = $_POST['topic'];

       $this->about_class = $_POST['about'];

       $this->class_assignment = $_POST['assignment'];

       $this->due_date = $_POST['dueDate'];

       $this->course_encrypted_id = $_POST['courseEncryptedId'];

       $this->course_code = $_POST['courseCode'];

    }

    //sanitize the data
    public function sanitize($connection,$data){

        $data = htmlspecialchars($data);
        $data = stripslashes($data);
        $data = strip_tags($data);
        //$data = $connection->real_escape_string($data);

        return $data; 

    }

    //define other auto generated data
    public function autoGeneratedData(){

        //$this->material_type = "pdf";

        $this->class_encrypted_id = md5(rand());

        $this->class_status = "active"; // or locked

        $this->date_created = date("Y/m/d");

        $this->time_created = date("h:i:sa");

        $this->assignment_type = "text";

        $this->assignment_status = "Active";

        $this->date_assignment_created = date("Y/m/d");

        $this->time_assignment_created = date("h:i:sa");



    }

    //create the neccessary classroom database
    public function classAttendaceTable(){

        //require the env library
        require('../../vendorEnv/autoload.php');

        $user_db_conn_env = Dotenv\Dotenv::createImmutable(__DIR__, '../../env/db-conn-var.env');
        $user_db_conn_env->load();

        // course db connection
        include('../../resources/database/courses-classes-attendance-db-connection.php');

        $class_attendance_table_query = "CREATE TABLE attendance_of_class_".$this->class_encrypted_id."
        (
         id int NOT NULL AUTO_INCREMENT,
         student_fullname text,
         student_username text,
         student_matric_number text,
         student_avatar text,
         student_encrypted_id text,
         type text,
         date text,
         time text,
         status text,
         PRIMARY KEY(id)
        )";

        if($conn11->query($class_attendance_table_query)){

            //echo "class attendance table created";

        }else{

            //echo "could not created class attendance table";
            //echo $conn11->error;

        }

        mysqli_close($conn11);

    }

    public function classChatTable(){

        //require the env library
        require('../../vendorEnv/autoload.php');

        $user_db_conn_env = Dotenv\Dotenv::createImmutable(__DIR__, '../../env/db-conn-var.env');
        $user_db_conn_env->load();

        // course db connection
        include('../../resources/database/courses-classes-chat-db-connection.php');

        $class_chat_table_query = "CREATE TABLE classroom_chat_of_class_".$this->class_encrypted_id."
        (
         id int NOT NULL AUTO_INCREMENT,
         fullname text,
         username text,
         avatar text,
         encrypted_id text,
         message text,
         date text,
         time text,
         status text,
         PRIMARY KEY(id)
        )";

        if($conn12->query($class_chat_table_query)){

            //echo "Class chat is created";

        }else{

            //echo "Could not create class chat table";

        }

        mysqli_close($conn12);

    }

    public function classAssignmentTable(){

        //require the env library
        require('../../vendorEnv/autoload.php');

        $user_db_conn_env = Dotenv\Dotenv::createImmutable(__DIR__, '../../env/db-conn-var.env');
        $user_db_conn_env->load();

        // course db connection
        include('../../resources/database/courses-classes-assignments-db-connection.php');

        $class_assignments_table_query = "CREATE TABLE assignment_submissions_of_class_".$this->class_encrypted_id."
        (
         id int NOT NULL AUTO_INCREMENT,
         course_code text,
         student_fullname text,
         student_username text,
         student_matric text,
         student_avatar text,
         student_answer text,
         encrypted_id text,
         date text,
         time text,
         grade text,
         status text,
         PRIMARY KEY(id)
        )";

        if($conn16->query($class_assignments_table_query)){

            //echo "Class chat is created";

        }else{

            //echo "Could not create class chat table";

        }

        mysqli_close($conn16);

    }

    public function classTestTable(){

        // course db connection
        include('../../resources/database/class-test-db-connection.php');

        $class_test_table_query = "CREATE TABLE test_of_class_".$this->class_encrypted_id."
        (
         id int NOT NULL AUTO_INCREMENT,
         test_title text,
         total_no_questions int,
         minutes_limit text,
         seconds_limit text,
         test_encrypted_id text,
         date_created text,
         time_created text,
         status text,
         PRIMARY KEY(id)
        )";

        if($conn19->query($class_test_table_query)){

            //echo "Class chat is created";

        }else{

            //echo "Could not create class chat table";

        }

        mysqli_close($conn19);

    }

    //process the class note
    public function processClassNote(){

        $random_number = rand();

        $this->note_file_path = "../../resources/class-notes/".$random_number.".txt";

        $noteFile = fopen($this->note_file_path,"w");

        fwrite($noteFile, $this->sanitized_class_note);

          /*$read_note_content_file = file($this->note_file_path);

            //displaying all the lines in the file
            foreach($read_note_content_file as $note_line){

                echo '
    
                <div class="container">
    
                    <span align="justify">'.$note_line.'</span>


                </div>
    
                <!-- note content container -->
    
                ';
    
            }*/

    }

    //insert the data into the classroom database
    public function insertIntoClassromDatabase(){

        //require the env library
        require('../../vendorEnv/autoload.php');

        $user_db_conn_env = Dotenv\Dotenv::createImmutable(__DIR__, '../../env/db-conn-var.env');
        $user_db_conn_env->load();

        // course db connection
        include('../../resources/database/courses-classes-db-connection.php');

        $insert_into_classroom_query = "INSERT INTO classes_of_course_".$this->sanitized_course_encrypted_id." 
        (
            course_code,class_topic,about_class,class_note,class_encrypted_id,lecturer_encrypted_id,class_status,date_created, 
            time_created,assignment_topic,assignment_type,assignment_status,date_assignment_created,
            time_assignmnet_created,deadline
        ) 
        VALUES(
            '$this->sanitized_course_code','$this->sanitized_class_topic','$this->sanitized_about_class','$this->note_file_path',
            '$this->class_encrypted_id','$this->lecturer_session','$this->class_status',
            '$this->date_created','$this->time_created','$this->sanitized_class_assignment','$this->assignment_type',
            '$this->assignment_status','$this->date_assignment_created','$this->time_assignment_created',
            '$this->sanitized_due_date'

        )";

        if($conn10->query($insert_into_classroom_query)){

            $this->create_classroom_status = TRUE;

        }else{

            $this->create_classroom_status = FALSE;

        }

        mysqli_close($conn10);

    }


    //perform some administrative functions


    //redirect the lecturer to the class dashboard
    public function redirectLecturer(){

        header("location:../../classes/class-dashboard/index.php?id=$this->class_encrypted_id&&course_id=$this->sanitized_course_encrypted_id");

    }


}

?>