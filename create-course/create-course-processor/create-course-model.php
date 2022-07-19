<?php

/**Author : Oladele John

** © 2022 Oladele John

** Web application

** File name : create-course/create-course-processor/create-course-model.php

** About : this module creates the lecturer course

*/

/**PSUEDO ALGORITHM
 * *
 * initiate the create course class
 * fetch all the create course data from the course form
 * sanitize the data
 * fetch the lecturer full name and encrypted id
 * cache the lecturer data result
 * defining the lecturer data
 * define other auto generated data
 * create the neccessary course database
 * create the course outline file
 * insert the data into the course database
 * perform some administrative functions
 * redirect the lecturer to the course dashboard
 * 
 * *
 */

//cache library
require('../../vendorPhpfastcache/autoload.php');
use Phpfastcache\CacheManager;
use Phpfastcache\Config\ConfigurationOption;

//initiate the create course class
class createCourse{

    public $lecturer_session;

    public $course_code;

    public $course_title;

    public $level;

    public $about_course;

    public $course_outline;


    public $sanitized_course_code;

    public $sanitized_course_title;

    public $sanitized_level;

    public $sanitized_about_course;

    public $sanitized_course_outline;


    public $fetch_lecturer_data_result;

    public $cached_lecturer_result;

    public $lecturer_data_row;

    public $lecturer_title;

    public $lecturer_fullname;

    public $lecturer_avatar;

    public $lecturer_encrypted_id;


    public $course_id;

    public $course_encrypted_id;

    public $date_created;

    public $time_created;

    public $status;


    public $course_outline_file_path;

    //fetch all the create course data from the course form
    public function fetchCourseData(){

       session_regenerate_id(true); 

       $this->lecturer_session = $_SESSION['lecturer_session'];

       $this->course_code = $_POST['courseCode'];

       $this->course_title = $_POST['courseTitle'];

       $this->level = $_POST['level'];

       $this->about_course = $_POST['aboutCourse'];

       $this->course_outline = $_POST['courseOutline'];

    }

    //sanitize the data
    public function sanitize($connection,$data){

        $data = htmlspecialchars($data);
        $data = stripslashes($data);
        $data = strip_tags($data);
        //$data = $connection->real_escape_string($data);

        return $data; 

    }

    //fetch the lecturer full name and encrypted id
    public function fetchLecturerData(){

        //require the env library
       require('../../vendorEnv/autoload.php');

       $user_db_conn_env = Dotenv\Dotenv::createImmutable(__DIR__, '../../env/db-conn-var.env');
       $user_db_conn_env->load();

       // user db connection
       include('../../resources/database/users-db-connection.php');

       $fetch_lecturer_data_query = "SELECT * FROM lecturers WHERE encrypted_id = 
       '$this->lecturer_session'";

       $this->fetch_lecturer_data_result = $conn1->query($fetch_lecturer_data_query);


    }

    // cache the lecturer data
    public function cachedLecturerDataQuery(){

        CacheManager::setDefaultConfig(new ConfigurationOption([
            'path' => '', // or in windows "C:/tmp/"
        ]));
        
        $InstanceCache = CacheManager::getInstance('files');
        
        $key = "lecturer_data";
        $Cached_lecturer_data_result = $InstanceCache->getItem($key);
        
        if (!$Cached_lecturer_data_result->isHit()) {
            $Cached_lecturer_data_result->set($this->fetch_lecturer_data_result)->expiresAfter(1);//in seconds, also accepts Datetime
            $InstanceCache->save($Cached_lecturer_data_result); // Save the cache item just like you do with doctrine and entities
        
            $this->cached_lecturer_result = $Cached_lecturer_data_result->get();
            //echo 'FIRST LOAD // WROTE OBJECT TO CACHE // RELOAD THE PAGE AND SEE // ';
        
        } else {
            
            $this->cached_lecturer_result = $Cached_lecturer_data_result->get();
            //echo 'READ FROM CACHE // ';
        
            $InstanceCache->deleteItem($key);
        }

    }

    //defining the lecturer data
    public function definingLecturerData(){

        $this->lecturer_data_row = $this->cached_lecturer_result->fetch_assoc();

        $this->lecturer_title = $this->lecturer_data_row['title'];

        $this->lecturer_fullname = $this->lecturer_data_row['fullname'];

        $avatar_filename = $this->lecturer_data_row['avatar'];

        $this->lecturer_avatar = '../../resources/avatars/'.$avatar_filename;

        $this->lecturer_encrypted_id = $this->lecturer_data_row['encrypted_id'];

    }

    //define other auto generated data
    public function autoGeneratedData(){

        $random_number = rand();

        $this->course_id = substr($random_number, 0, -5);

        $this->course_encrypted_id = md5($random_number);

        date_default_timezone_set("Africa/Lagos");

        $this->date_created = date("Y/m/d");

        $this->time_created = date("h:i:sa");

        $this->status = "active";

    }

    //create the neccessary course database
    public function neccessasryCourseTable(){

        // user db connection
        include('../../resources/database/courses-students-enrolled-db-connection.php');

        $course_table_query = "CREATE TABLE enrolled_students_of_course_".$this->course_encrypted_id."
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

        if($conn9->query($course_table_query)){

            echo "course table created";

        }else{

           echo "Could not create the course enrolled table";
           echo $conn9->error;

        }

        mysqli_close($conn9);

    }

    public function courseClassTable(){

        // user db connection
        include('../../resources/database/courses-classes-db-connection.php');

        $course_classes_table_query = "CREATE TABLE classes_of_course_".$this->course_encrypted_id."
        (
         id int NOT NULL AUTO_INCREMENT,
         course_code text,
         class_topic text,
         about_class text,
         instructional_material text,
         class_note text,
         type text,
         class_encrypted_id text,
         lecturer_encrypted_id text,
         class_status text,
         date_created text,
         time_created text,
         assignment_topic text,
         assignment_type text,
         assignment_status text,
         date_assignment_created text,
         time_assignmnet_created text,
         deadline text,
         PRIMARY KEY(id)
        )";

        if($conn10->query($course_classes_table_query)){

            //echo "Class table created";

        }else{

            //echo "could not create course class";
            //echo $conn10->error;

        }
        
        mysqli_close($conn10);
    }

    //create the course outline file
    public function createCourseOutlineFile(){

        $random_number = rand();

        $this->course_outline_file_path = "../../resources/course-outlines/".$random_number.".txt";

        $course_outline_file = fopen($this->course_outline_file_path,"w");

        fwrite($course_outline_file, $this->sanitized_course_outline);

    }

    //insert the data into the course database
    public function insertIntoCourseTable(){

        //require the env library
        require('../../vendorEnv/autoload.php');

        $user_db_conn_env = Dotenv\Dotenv::createImmutable(__DIR__, '../../env/db-conn-var.env');
        $user_db_conn_env->load();

        // user db connection
        include('../../resources/database/courses-db-connection.php');

        $insert_into_course_query = "INSERT INTO courses 
        (course_code,course_title,about_course,course_outline,course_id,course_encrypted_id,level,lecturer_in_charge,lecturer_avatar,
        lecturer_encrypted_id,date_created,time_created,status) 
        VALUES(
            '$this->sanitized_course_code','$this->sanitized_course_title','$this->sanitized_about_course','$this->course_outline_file_path',
            '$this->course_id','$this->course_encrypted_id','$this->sanitized_level',
            '$this->lecturer_title-$this->lecturer_fullname','$this->lecturer_avatar',
            '$this->lecturer_encrypted_id','$this->date_created','$this->time_created','$this->status'
        )";

        if($conn8->query($insert_into_course_query)){

           //echo "Inserted into courses table";

        }else{

           //echo "Could not insert into courses table";

        }

        mysqli_close($conn8);
            
    }

    //perform some administrative functions

    //redirect to the course dashboard
    public function redirect(){

        header("location:../../courses/courses-dashboard/index.php?id=$this->course_encrypted_id");

    }


}



?>