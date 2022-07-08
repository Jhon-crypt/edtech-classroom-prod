<?php

/**Author : Oladele John

** © 2022 Oladele John

** Web application

** File name : help/index.php

** About : this module displays the feedback forms

*/

/**PSUEDO ALGORITHM
 * *
 * define the feedback class
 * include the header
 * display the feed back form
 * cache the feedback form
 * 
 * *
 */

//cache library
require('../vendorPhpfastcache/autoload.php');
use Phpfastcache\CacheManager;
use Phpfastcache\Config\ConfigurationOption;

//define the feedback class
class feedback{

    public $feedback_subheading;

    public $feedback_form;


    public $success_feedback_alert;

    public $empty_feedback_alert;

    //include the header
    public function header(){

        include('header/header.php');

    }

    //display the feed back form
    public function feedbackSubheading(){

        $this->feedback_subheading = '

        <br>
        
        <div class="intro" style="border-radius:10% 10% 45% 44% / 10% 10% 44% 46%;background-color:#1d007e;" 
          align="center">
  
              <br><br><br>
  
              <h1 class="text-light" style="font-weight:bolder;font-family:monospace;">
              
                Feedbacks <i class="fa fa-comment"></i>
              
              </h1>
  
              <br>
  
        </div>
  
        <br><br>
        ';

    }

    public function feedbackForm(){

        $this->feedback_form = '
        <div class="container">

        <div align="center">

        <div class="shadow p-4 mb-4 bg-light" style="width:300px;">

            <form action="submit-feedback.php" method="POST">

                <input class="form-control" type="email" name="email" placeholder="Email" 
                required style="height:40px;">

                <br>

                <textarea class="form-control" name="feedback" rows="6" 
                placeholder="Tell us about your experience as a user"></textarea>

                <br>

                <button type="submit" class="btn btn-md text-light" style="background-color:#1d007e;">
                  Submit <i class="fa fa-comment"></i>
                </button>

            </form>

        </div>
        <!-- login container -->

        </div>
        
        ';

    }

    public function successFeedBackAlert(){

        echo $this->success_feedback_alert = '
        
        <div align="center">

        <div class="alert alert-info" style="width:200px;height:80px;">

                <div class="row">

                    <div class="col">
                   
                        Feedback Submitted 

                    </div>

                    <div class="col">

                       <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>

                    </div>

               </div>

            </div>

       </div>
        
        ';

    }

    public function emptyFeedBackAlert(){

        echo $this->empty_feedback_alert = '
        
        <div align="center">

        <div class="alert alert-info" style="width:200px;height:80px;">

                <div class="row">

                    <div class="col">
                   
                        Empty feedback 

                    </div>

                    <div class="col">

                       <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>

                    </div>

               </div>

            </div>

       </div>
        
        ';

    }

    //cache the feedback subheading
    public function cacheFeedbackSubheading(){

        CacheManager::setDefaultConfig(new ConfigurationOption([
            'path' => '', // or in windows "C:/tmp/"
        ]));
        
        $InstanceCache = CacheManager::getInstance('files');
        
        $key = "feedback_form_page";
        $Cached_page = $InstanceCache->getItem($key);
        
        if (!$Cached_page->isHit()) {
            $Cached_page->set($this->feedback_subheading)->expiresAfter(1);//in seconds, also accepts Datetime
          $InstanceCache->save($Cached_page); // Save the cache item just like you do with doctrine and entities
        
            echo $Cached_page->get();
            //echo 'FIRST LOAD // WROTE OBJECT TO CACHE // RELOAD THE PAGE AND SEE // ';
        
        } else {
            
            echo $Cached_page->get();
            //echo 'READ FROM CACHE // ';
        
            $InstanceCache->deleteItem($key);
        }

    }

    //cache the feedback form
    public function cacheFeedbackForm(){

        CacheManager::setDefaultConfig(new ConfigurationOption([
            'path' => '', // or in windows "C:/tmp/"
        ]));
        
        $InstanceCache = CacheManager::getInstance('files');
        
        $key = "feedback_form";
        $Cached_page = $InstanceCache->getItem($key);
        
        if (!$Cached_page->isHit()) {
            $Cached_page->set($this->feedback_form)->expiresAfter(1);//in seconds, also accepts Datetime
          $InstanceCache->save($Cached_page); // Save the cache item just like you do with doctrine and entities
        
            echo $Cached_page->get();
            //echo 'FIRST LOAD // WROTE OBJECT TO CACHE // RELOAD THE PAGE AND SEE // ';
        
        } else {
            
            echo $Cached_page->get();
            //echo 'READ FROM CACHE // ';
        
            $InstanceCache->deleteItem($key);
        }

    }

}

$feedback_controller = new feedback();

$feedback_controller->header();

$feedback_controller->feedbackSubheading();

$feedback_controller->feedbackForm();

$feedback_controller->cacheFeedbackSubheading();

if(!empty($_GET['alert'])){

    $feedback_controller->successFeedBackAlert();

}elseif(!empty($_GET['emptyAlert'])){

    $feedback_controller->emptyFeedBackAlert();

}

$feedback_controller->cacheFeedbackForm();

?>