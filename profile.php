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

$result = '';

//print_r($_POST);
$month = array('-1'=>'Month', '01'=>'Jan', '02'=>'Feb','03'=>'Mar', '04'=>'Apr', '05'=>'May', '06'=>'Jun', '07'=>'Jul', '08'=>'Aug', '09'=>'Sep', '10'=>'Oct', '11'=>'Nov', '12'=>'Dec');



$gender_r = array('-1'=>"Gender", 'male'=>'Male', 'female'=>'Female');


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
        $firstname = $result['firstname'];
        $lastname= $result['lastname'];
        $date_of_birth = $result['dob'];
        
        $date_of_birth = explode("-", $date_of_birth);
        
        $year_value   = $date_of_birth[0];
        $month_index  = $date_of_birth[1];
        $month_value  = $month[$month_index];
        
        $day_value    = $date_of_birth[2];
        
        $current_location = $result['city']." ".$result['country_code'];
        $profile_pic = $result['thumbnail_pic_name'];
        
        $gender_index = $result['sex'];
        $gender_value = $gender_r[$gender_index];
        
        if($profile_pic == "")
        {
            $profile_pic = "lib/img/placeholder.gif";
        }
    }
    $inner_template3 	= "views/patient_profile_form.html";
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
        $date_of_birth = explode("-", $date_of_birth);
        
        $year_value   = $date_of_birth[0];
        $month_index  = $date_of_birth[1];
        $month_value  = $month[$month_index];
        
        $day_value    = $date_of_birth[2];
        
        $current_location = $result['city']." ".$result['country_code'];
        $profile_pic = $result['thumbnail_pic_name'];
        
        $gender_index = $result['sex'];
        $gender_value = $gender_r[$gender_index];
        
        if($profile_pic == "")
        {
            $profile_pic = "lib/img/placeholder.gif";
        }
    }
    $inner_template3 	= "views/physician_profile_form.html";
}

            //Go to home page
            $template 		= "views/main_1.htm";
            $inner_template1 	= "views/nav_main_manu.html";
            $inner_template2 	= "views/profile_side_nav_form.html";

            $TBS 			= new clsTinyButStrong;
            $TBS->NoErr 	= true;

            $TBS->LoadTemplate("$template");
            
            
            
            $TBS->Render 	= TBS_OUTPUT;
            
            
            $TBS->Show();
            
            @mysql_close();
            die();


?>
