<?php
session_start();
error_reporting (0);
include_once('classes/config.php');
include_once('classes/sessions.php');
include_once('classes/tbs_class_php5.php');
include_once('lang/en.php');
include_once('configData.php');     
include_once('classes/validation.php');     
include_once('models/CI_model.php'); 
include_once('classes/random_code.php'); 

if(isset($_SESSION['patient_id']))
{
    $patient_id = $_SESSION['patient_id'];
    $patientInfo = new CI_model();
    $table = "patient";
    $columns = "*";
    $content = "patient_id = '$patient_id'";
    $result = $patientInfo->dbSelect($table, $columns, $content);
    
    if($result != null)
    {
        $name = $result['lastname']." ".$result['firstname'];
        $date_of_birth = $result['dob'];
        $current_location = $result['city']." ".$result['country_code'];
        $profile_pic = $result['thumbnail_pic_name'];
        if($profile_pic == "")
        {
            $profile_pic = "lib/img/placeholder.gif";
        }
    }
}

if(isset($_SESSION['physician_id']))
{
    $physician_id = $_SESSION['physician_id'];
    $physicianInfo = new CI_model();
    $table = "physician";
    $columns = "*";
    $content = "physician_id = '$physician_id'";
    $result = $physicianInfo->dbSelect($table, $columns, $content);
    
    if($result != null)
    {
        $name = $result['lastname']." ".$result['firstname'];
        $date_of_birth = $result['dob'];
        $current_location = $result['city']." ".$result['country_code'];
        $profile_pic = $result['thumbnail_pic_name'];
        if($profile_pic == "")
        {
            $profile_pic = "lib/img/placeholder.gif";
        }
    }
}


            //Go to home page
            $template 		= "views/main_1.htm";
            $inner_template1 	= "views/nav_main_manu.html";
            $inner_template2 	= "views/profile_side_nav_form.html";
            $inner_template3 	= "views/user_account.html";
            $TBS 			= new clsTinyButStrong;
            $TBS->NoErr 	= true;

            $TBS->LoadTemplate("$template");

            $TBS->Render 	= TBS_OUTPUT;
            $TBS->Show();
            
            @mysql_close();
            die();

?>
