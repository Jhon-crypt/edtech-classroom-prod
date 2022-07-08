<?php

/**Author : Oladele John

** © 2022 Oladele John

** Web application

** File name : classes/class-assignment/submit-assignnment-model.php

** About : this module allows the student to submit their assignment

*/ 

/**PSUEDO ALGORITHM
 * *
 * define the submit submit assignment class
 * fetch the student session
 * fetch the student data
 * cache the student data
 * define the student data
 * fetch the assignment data
 * sanitize the assignment data
 * define the auto generated data
 * insert the assignment data into the assignment database
 * alert the lecturer
 * redirect the student to the class assignment
 * perform administrative functions
 * 
 * *
 */

//cache library
require('../../vendorPhpfastcache/autoload.php');
use Phpfastcache\CacheManager;
use Phpfastcache\Config\ConfigurationOption;

//define the submit submit assignment class
class submitAssignment{

    public $student_session;


    public $student_data_result;

    public $cached_student_result;

    public $student_fullname;

    public $student_username;

    public $student_matric;

    public $student_avatar;


    public $course_code;

    public $assignment_answer;

    public $course_id;

    public $class_id;

    public $assignment_status;

    public $lecturer_id;


    public $sanitized_assignment_answer;


    public $date;

    public $time;

    public $grade;

    public $status;


    public $assignment_submittion_status;

    //fetch the student session
    public function fetchStudentSession(){

        if(isset($_SESSION['student_session'])){

            $this->student_session = $_SESSION['student_session'];

        }

    }

    //fetch the student data
    public function fetchStudentData(){

        //require the env library
        require('../../vendorEnv/autoload.php'); 

        $user_db_conn_env = Dotenv\Dotenv::createImmutable(__DIR__, '../../env/db-conn-var.env');
        $user_db_conn_env->load();

        // user db connection
        include('../../resources/database/users-db-connection.php');
        //conn1

        $student_data_query = "SELECT * FROM students WHERE encrypted_id = '$this->student_session'";

        $this->student_data_result = $conn1->query($student_data_query);

        mysqli_close($conn1);


    }

    //caching the student data query
    public function cacheStudentData(){

        CacheManager::setDefaultConfig(new ConfigurationOption([
               'path' => '', // or in windows "C:/tmp/"
        ]));
        
        $InstanceCache = CacheManager::getInstance('files');
        
        $key = "stduent_data";
        $Cached_student_data_result = $InstanceCache->getItem($key);

        if (!$Cached_student_data_result->isHit()) {
            $Cached_student_data_result->set($this->student_data_result)->expiresAfter(1);//in seconds, also accepts Datetime
            $InstanceCache->save($Cached_student_data_result); // Save the cache item just like you do with doctrine and entities
        
            $this->cached_student_result = $Cached_student_data_result->get();
            //echo 'FIRST LOAD // WROTE OBJECT TO CACHE // RELOAD THE PAGE AND SEE // ';
        
        } else {
            
            $this->cached_student_result = $Cached_student_data_result->get();
            //echo 'READ FROM CACHE // ';
        
            $InstanceCache->deleteItem($key);
        }


    }

    //define student data
    public function defineStudentData(){

        $student_data_row = $this->cached_student_result->fetch_assoc();

        $this->student_fullname = $student_data_row['fullname'];

        $this->student_username = $student_data_row['username'];

        $this->student_matric = $student_data_row['matric_number'];

        $this->student_avatar = $student_data_row['avatar'];

    }


    //fetch the assignment data
    public function fetchAssignmentData(){

        $this->course_code = $_POST['courseCode'];

        $this->assignment_answer = $_POST['assignmentAnswer'];

        $this->course_id = $_POST['courseId'];

        $this->class_id = $_POST['classId'];

        $this->assignment_status = $_POST['status'];

        $this->lecturer_id = $_POST['lecturerId'];

    }

    //sanitize the assignment data
    public function sanitize($connection,$data){

        $data = htmlspecialchars($data);
        $data = stripslashes($data);
        $data = strip_tags($data);
        $data = $connection->real_escape_string($data);

        return $data; 

    }


    //define the auto generated data
    public function defineAutoGeneratedData(){

        $this->date = date("Y-m-d");

        $this->time = date("h:i:sa");

        $this->grade = "not set";

        $this->status = "ungraded";

    }

    //insert the assignment data into the assignment database
    public function insertIntoAssignment(){

        //require the env library
        require('../../vendorEnv/autoload.php');

        $user_db_conn_env = Dotenv\Dotenv::createImmutable(__DIR__, '../../env/db-conn-var.env');
        $user_db_conn_env->load();

        include('../../resources/database/courses-classes-assignments-db-connection.php');

        $insert_into_assignment_query = "INSERT INTO assignment_submissions_of_class_".$this->class_id."
        (
            student_fullname,student_username,student_matric,student_avatar,student_answer,encrypted_id,date,time,
            grade,status
        )
        VALUES(
            '$this->student_fullname','$this->student_username','$this->student_matric','$this->student_avatar',
            '$this->sanitized_assignment_answer','$this->student_session','$this->date','$this->time','$this->grade','$this->status'
        )
        ";

        if($conn16->query($insert_into_assignment_query)){

            $this->assignment_submittion_status = TRUE;

        }else{
  
            $this->assignment_submittion_status = FALSE;

        }

    }

    //alert the lecturer
    public function alertLecturer(){

        $link = "";

        $message = " submitted one of your assignments in ";

        $status = "unread";

        //require the env library
        require('../../vendorEnv/autoload.php');

        $user_db_conn_env = Dotenv\Dotenv::createImmutable(__DIR__, '../../env/db-conn-var.env');
        $user_db_conn_env->load();

        // user db connection
        include('../../resources/database/users-notification-db-connection.php');

        $notify_lecturer_query = "INSERT INTO notification_for_user".$this->lecturer_id."
        (
            link,message,date,time,status
        )
        VALUES(
            '$link$this->course_id','$this->student_fullname $message $this->course_code','$this->date','$this->time','$status'
        )
        ";

        if($conn15->query($notify_lecturer_query)){

            echo "Student have been notified";

        }else{

            echo "Could not notify student";

        }

        mysqli_close($conn15);

    }


    //redirect the student to the class assignment
    public function redirectStudent(){

        if($this->assignment_submittion_status == TRUE){

            header("location:student-class-assignment-controller.php?classId=$this->class_id&&courseId=$this->course_id");

        }

    }


}

?>