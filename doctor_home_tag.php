<?php
error_reporting (0);
include_once('classes/config.php');
include_once('classes/sessions.php');
include_once('classes/tbs_class_php5.php');
include_once('lang/en.php');
include_once('configData.php');     
include_once('classes/validation.php');     
include_once('models/CI_model.php'); 
include_once('models/CI_model_TBS.php'); 
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
        
        $profile_pic = $result['thumbnail_pic_name'];
        if($profile_pic == "")
        {
            $profile_pic = "lib/img/placeholder.gif";
        }
    }
    
        //Screeningtool Gadget info
        $ST_info_TBS = new CI_model_TBS();
        $table = "st_usage, st_category";
        $columns = "*";
        $content = "st_usage.patient_id = '$patient_id' AND st_usage.st_category_id = st_category.st_category_id ORDER BY st_usage_id DESC LIMIT 3";
        $ST_info = $ST_info_TBS->TBS_select($table, $columns, $content); 
    
            $inner_template2 	= "views/nav_side_manu.html";
    
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
        
        $profile_pic = $result['thumbnail_pic_name'];
        if($profile_pic == "")
        {
            $profile_pic = "lib/img/placeholder.gif";
        }
    }
    
        //Screeningtool Gadget info
        $ST_info_TBS = new CI_model_TBS();
        $table = "st_usage, st_category";
        $columns = "*";
        $content = "st_usage.physician_id = '$physician_id' AND st_usage.st_category_id = st_category.st_category_id ORDER BY st_usage_id DESC LIMIT 3";
        $ST_info = $ST_info_TBS->TBS_select($table, $columns, $content); 
        
        $inner_template2 	= "views/nav_side_manu.html";
}


?>
