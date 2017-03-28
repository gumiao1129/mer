<?php
session_start();
//error_reporting (0);
include_once('classes/config.php');
include_once('classes/sessions.php');
include_once('classes/tbs_class_php5.php');
include_once('lang/en.php');
include_once('side_nav.php');
include_once('configData.php');     
include_once('classes/validation.php');     
include_once('models/CI_model.php'); 
include_once('models/CI_model_TBS.php'); 
include_once('models/CI_model_search_engine.php'); 
include_once('classes/random_code.php'); 


if(isset($_GET["profile_img"])&&isset($_GET["firstname"])&&isset($_GET["lastname"])&&isset($_GET["message_id"])&&isset($_GET["message_recipient_id"]))
{
    $profile_img=$_GET["profile_img"];
    $firstname=$_GET["firstname"];
    $lastname=$_GET["lastname"];
    $message_id=$_GET["message_id"];
    $message_recipient_id=$_GET["message_recipient_id"];
    
    $model = new CI_model_search_engine();
    
    $message_details=$model->sent_signal_message($message_id);
    $message_subject=$message_details[0]['subject'];
    $message=$message_details[0]['message'];
    $_SESSION['message_details'] = $message_details;
}

//Go to home page
            $template 		= "views/view_resent_message_detail.html";
            $TBS 			= new clsTinyButStrong;
            $TBS->NoErr 	= true;
            $TBS->LoadTemplate("$template");
            $TBS->Render 	= TBS_OUTPUT;
            $TBS->MergeBlock('activity',$message_details);
            $TBS->Show();
            
            @mysql_close();
            die();

?>
