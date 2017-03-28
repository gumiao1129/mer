<?php
session_start();
error_reporting (0);
include_once('classes/config.php');
include_once('classes/sessions.php');
include_once('classes/tbs_class_php5.php');
include_once('lang/en.php');
include_once('configData.php');  
include_once('side_nav.php');
include_once('classes/validation.php');     
include_once('models/CI_model.php'); 
include_once('models/CI_model_TBS.php'); 
include_once('classes/random_code.php'); 

  
$info_details = new CI_model();
    
if(isset($_GET["info_cate"])&&isset($_GET["physician_info_num"])&&($_GET["info_cate"]=="physician")&&($_GET["physician_info_num"]!=null))
{
    $physician_info_id = $_GET["physician_info_num"];

    
    if(isset($_SESSION['patient_id']))
    {
        $patient_id = $_SESSION['patient_id'];
        
        $table="physician";
        $columns = "*";
        $content = "physician_id = '$physician_info_id'";
        $detail_result = $info_details->dbSelect($table, $columns, $content);

        $table="physician_patient_relationships";
        $columns = "*";
        $content = "physician_id_one = '$physician_info_id' AND patient_id_one = '$patient_id' AND status = 1";
        $info_result = $info_details->dbSelect($table, $columns, $content); 
        
        if($info_result != null)
        {
            $template 		= "views/physician_info_full.html";
            
        }
        else
        {
            $template 		= "views/physician_info_basic.html";
        }
             
    }
    else if(isset($_SESSION['physician_id']))
    {
        $physician_id = $_SESSION['physician_id'];
        
        $table="physician";
        $columns = "*";
        $content = "physician_id = '$physician_info_id'";
        $detail_result = $info_details->dbSelect($table, $columns, $content);

        $table="physician_physician_relationships";
        $columns = "*";
        $content = "(physician_id_one = '$physician_info_id' AND physician_id_two = '$physician_id' AND status = 1) OR (physician_id_one = '$physician_id' AND physician_id_two = '$physician_info_id' AND status = 1)";
        $info_result = $info_details->dbSelect($table, $columns, $content); 
        
        if($info_result != null)
        {
            $template 		= "views/physician_info_full.html";
            
        }
        else
        {
            $template 		= "views/physician_info_basic.html";
        }
    }
}
else if(isset($_GET["info_cate"])&&isset($_GET["patient_info_num"])&&($_GET["info_cate"]=="patient")&&($_GET["patient_info_num"]!=null))
{
    $patient_info_id = $_GET["patient_info_num"];

     
    if(isset($_SESSION['patient_id']))
    {
        $patient_id = $_SESSION['patient_id'];
        
        $table="patient";
        $columns = "*";
        $content = "patient_id = '$patient_info_id'";
        $detail_result = $info_details->dbSelect($table, $columns, $content);

        $table="patient_patient_relationships";
        $columns = "*";
        $content = "(patient_id_one = '$patient_id' AND patient_id_two = '$patient_info_id' AND status = 1) OR (patient_id_two = '$patient_id' AND patient_id_one = '$patient_info_id' AND status = 1)";
        $info_result = $info_details->dbSelect($table, $columns, $content); 
        
        if($info_result != null)
        {
            $template 		= "views/patient_info_full.html";
            
        }
        else
        {
            $template 		= "views/patient_info_basic.html";
        }

    }
    else if(isset($_SESSION['physician_id']))
    {
        $physician_id = $_SESSION['physician_id'];
        
        $table="patient";
        $columns = "*";
        $content = "patient_id = '$patient_info_id'";
        $detail_result = $info_details->dbSelect($table, $columns, $content);

        $table="physician_patient_relationships";
        $columns = "*";
        $content = "physician_id_one = '$physician_id' AND patient_id_one = '$patient_info_id' AND status = 1";
        $info_result = $info_details->dbSelect($table, $columns, $content); 
        
        if($info_result != null)
        {
            $template 		= "views/patient_info_full.html";
            
        }
        else
        {
            $template 		= "views/patient_info_basic.html";
        }
    }  
}
else
{
        
}   

            $TBS 		= new clsTinyButStrong;
            $TBS->NoErr 	= true;
            $TBS->LoadTemplate("$template");
            $TBS->Render 	= TBS_OUTPUT;
            $TBS->Show();
            
            @mysql_close();
            die();
?>
