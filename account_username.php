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


if(isset($_GET['get_note'])&&isset($_SESSION['patient_id']))
{
    if($_GET['get_note']=="username")
    {
        
            $template 		= "views/account_username_edited.html";
            $TBS 			= new clsTinyButStrong;
            $TBS->NoErr 	= true;
            $TBS->LoadTemplate("$template");
            $TBS->Render 	= TBS_OUTPUT;
            $TBS->Show();
            
            @mysql_close();
            die();
    }
    else if($_GET['get_note']=="password")
    {
            $template 		= "views/account_password_edited.html";
            $TBS 			= new clsTinyButStrong;
            $TBS->NoErr 	= true;
            $TBS->LoadTemplate("$template");
            $TBS->Render 	= TBS_OUTPUT;
            $TBS->Show();
            
            @mysql_close();
            die();
    }
    else
    {

    }
        
}
else if(isset($_GET['get_note'])&&isset($_SESSION['physician_id']))
{
    if($_GET['get_note']=="username")
    {
            $template 		= "views/account_username_edited.html";
            $TBS 			= new clsTinyButStrong;
            $TBS->NoErr 	= true;
            $TBS->LoadTemplate("$template");
            $TBS->Render 	= TBS_OUTPUT;
            $TBS->Show();
            
            @mysql_close();
            die();
    }
    else if($_GET['get_note']=="password")
    {
            $template 		= "views/account_password_edited.html";
            $TBS 			= new clsTinyButStrong;
            $TBS->NoErr 	= true;
            $TBS->LoadTemplate("$template");
            $TBS->Render 	= TBS_OUTPUT;
            $TBS->Show();
            
            @mysql_close();
            die();
    }
    else
    {
        
    }
}
else
{
    
}




?>
