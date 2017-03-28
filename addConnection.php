<?php
session_start();
error_reporting (0);
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

if(isset($_GET["profile_img"])&&isset($_GET["firstname"])&&isset($_GET["lastname"])&&isset($_GET["id"])&&isset($_GET["add_cate"]))
{
    $profile_img=$_GET["profile_img"];
    $firstname=$_GET["firstname"];
    $lastname=$_GET["lastname"];
    $id=$_GET["id"];
    $add_cate=$_GET["add_cate"];
    
    if(isset($_SESSION['physician_id']))
    {
        if($add_cate=="patient")
        {
            $insert_des = $lang_insert_des.$firstname.' '.$lastname. $lang_insert_des_pa; 
        }
        if($add_cate=="physician")
        {
            $insert_des = $lang_insert_des.$firstname.' '.$lastname. $lang_insert_des_fr; 
        }
    }
    
        
    if(isset($_SESSION['patient_id']))
    {
        if($add_cate=="patient")
        {
            $insert_des = $lang_insert_des.$firstname.' '.$lastname. $lang_insert_des_fr; 
        }
        if($add_cate=="physician")
        {
            $insert_des = $lang_insert_des.$firstname.' '.$lastname. $lang_insert_des_dr;
        }
    }


//Go to home page
            $template 		= "views/add_connection_tag.html";
            $TBS 			= new clsTinyButStrong;
            $TBS->NoErr 	= true;
            $TBS->LoadTemplate("$template");
            $TBS->Render 	= TBS_OUTPUT;
            $TBS->Show();
            
            @mysql_close();
            die();


}
?>
