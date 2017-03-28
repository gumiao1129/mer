<?php

include_once('classes/config.php');
include_once('classes/sessions.php');
include_once('classes/tbs_class_php5.php');
include_once('lang/en.php');
include_once('configData.php');     
include_once('classes/validation.php');     
include_once('models/CI_model.php'); 
include_once('models/CI_model_TBS.php'); 
include_once('models/CI_model_search_engine.php'); 
include_once('classes/random_code.php'); 


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
        
        $profile_pic = $result['thumbnail_pic_name'];
        if($profile_pic == "")
        {
            $profile_pic = "lib/img/placeholder.gif";
        }
    }
        
        
        $inner_template2 	= "views/nav_side_manu_2.html";
}


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
        
        $profile_pic = $result['thumbnail_pic_name'];
        if($profile_pic == "")
        {
            $profile_pic = "lib/img/placeholder.gif";
        }
    }
   
        $inner_template2 	= "views/nav_side_manu_1.html";
     
}

?>
