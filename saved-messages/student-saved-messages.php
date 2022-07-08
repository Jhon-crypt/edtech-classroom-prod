<?php

/**Author : Oladele John

** © 2022 Oladele John

** Web application

** File name : saved messages/stduent-saved-messages.php

** About : this module fetches and displays the stduent saved messages from a course class chat

*/

/**PSUEDO ALGORITHM
 * *
 * define the student saved messages class
 * fetch the user session
 * display the header
 * define the student saved messages query
 * cache the query
 * define the student saved messages data and display the lecturer saved messages
 * 
 * *
 */

//cache library
require('../vendorPhpfastcache/autoload.php');
use Phpfastcache\CacheManager;
use Phpfastcache\Config\ConfigurationOption;

session_start();

//define the lecturer saved messages class
class studentSavedMessages{

    public $student_session;


    public $query_result;

    public $query_status;

    public $cached_saved_messages;

    public $chat_fullname;

    public $chat_username;

    public $chat_avatar;

    public $chat_message;

    public $chat_date;

    public $chat_time;

    //fetch the user session
    public function fetchUserSession(){

        $this->student_session = $_SESSION['student_session'];

    }

    //display the header
    public function heading(){

        include('header/header.php');
 
    }

    //define the student saved messages query
    public function studentSavedMessagesQuery(){

        //require the env library
       require('../vendorEnv/autoload.php');

       $user_db_conn_env = Dotenv\Dotenv::createImmutable(__DIR__, '../env/db-conn-var.env');
       $user_db_conn_env->load();

       // class chat db connection
       include('../resources/database/users-chat-save-db-connection.php');

       //conn14

       $query = "SELECT * FROM chat_save_of_user".$this->student_session." ORDER BY id DESC";

       $this->query_result = $conn14->query($query);

        if($this->query_result->num_rows > 0){

            $this->query_status = TRUE;

        }else{

            $this->query_status = FALSE;

        }

       mysqli_close($conn14);

    }

    //cache the query
    public function cacheQuery(){

        CacheManager::setDefaultConfig(new ConfigurationOption([
            'path' => '', // or in windows "C:/tmp/"
        ]));
        
        $InstanceCache = CacheManager::getInstance('files');
        
        $key = "student_saved_messages_result";
        $Cached_saved_messages_result = $InstanceCache->getItem($key);
        
        if (!$Cached_saved_messages_result->isHit()) {
            $Cached_saved_messages_result->set($this->query_result)->expiresAfter(1);//in seconds, also accepts Datetime
            $InstanceCache->save($Cached_saved_messages_result); // Save the cache item just like you do with doctrine and entities
        
            $this->cached_saved_messages = $Cached_saved_messages_result->get();
            //echo 'FIRST LOAD // WROTE OBJECT TO CACHE // RELOAD THE PAGE AND SEE // ';
        
        } else {
            
            $this->cached_saved_messages = $Cached_saved_messages_result->get();
            //echo 'READ FROM CACHE // ';
        
            $InstanceCache->deleteItem($key);
        }

    }

    //define the lecturer saved messages data and display the lecturer saved messages
    public function displayStudentSavedMessages(){

        echo $subheading = '
        
        <div style="border-radius:0% 0% 45% 44% / 0% 10% 44% 46%;background-color:#1d007e;" 
         align="center">
 
             <br>
 
             <h1 class="text-light" style="font-weight:bolder;font-family:monospace;">
    
                Saved Messages <i class="fa fa-comments"></i>
    
             </h1>
 
             <br>
 
        </div>
 
        <br><br><br>

        <div class="container">

            <h3>Your saved messages <i class="fa fa-mortar-comments"></i></h3>

            <hr>

            <div class="row">
        
        ';

        while($messages_row = $this->cached_saved_messages->fetch_assoc()){

            $this->chat_fullname = $messages_row['fullname'];

            $this->chat_username = $messages_row['username'];
    
            $this->chat_avatar = $messages_row['avatar'];
    
            $this->chat_encrypted_id = $messages_row['encrypted_id'];
    
            $this->chat_message = $messages_row['message'];
    
            $this->chat_date = $messages_row['date'];
    
            $this->chat_time = $messages_row['time'];

            $avatar_file = '../resources/avatars/'.$this->chat_avatar;

            echo '
            
            <div class="col">
            <small>
               <div class="d-flex shadow p-4 mb-4 bg-light" style="max-width:330px;">

                    <div class="flex-shrink-0">
                        <img class="rounded-circle" src='. $avatar_file.' 
                        style="border:5px solid white;padding:1px;max-height:60px;
                        border-radius:30px;max-width:60px;"/>
                    </div>

                    <div class="flex-grow-1 ms-3">

                       <h5 style="max-width:200px;white-space:nowrap;overflow:hidden;
                       text-overflow:ellipsis;text-align:left;">
                        <small>

                          '.$this->chat_fullname.'<br>
                          <span class="text-muted">@'.$this->chat_username.'</span>

                        </small>
                       </h5>

                       <span>
                          '.$this->chat_message.'
                       </span>

                       <small> 

                        <div class="row">

                            <div class="col">

                               '.$this->chat_time.'

                            </div>

                            <div class="col">

                               '.$this->chat_date.'

                            </div>

                            <div class="col">

                               
                            </div>

                        </div>
                        <!-- row 1 -->

                        </small>

                    </div>

                </div>
                </small>
                </div>
            
            ';

        }

    }

}

$student_saved_messages_controller = new studentSavedMessages();

if(isset($_SESSION['student_session'])){

    $student_saved_messages_controller->fetchUserSession();

    $student_saved_messages_controller->heading();

    $student_saved_messages_controller->studentSavedMessagesQuery();

    $student_saved_messages_controller->cacheQuery();

    $student_saved_messages_controller->displayStudentSavedMessages();

}else{



}

?>