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
include_once('classes/random_code.php'); 


if(isset($_SESSION['physician_id']))
{
        if(isSet($_POST['last_st_activity']))
        {
            $last_st_activity=$_POST['last_st_activity'];
            $info = new CI_model_TBS();
            $table = "st_usage, st_category";
            $columns = "*";
            $content = "st_usage.physician_id = '$physician_id' AND st_usage.st_category_id = st_category.st_category_id AND st_usage.st_usage_id< $last_st_activity ORDER BY st_usage_id DESC LIMIT $st_num_item_list";
            $activity = $info->TBS_select($table, $columns, $content); 
                
            if($activity == null)
            {
                // $inner_template4 	= "views/screen_tool_activity_layout.html";
            }
            else
            {
                $st_activity_id = $activity[$st_num_item_list-1]["st_usage_id"];
                $template 	= "views/screen_tool_activity_loadmore_layout.html";
            }
            
                    //Go to home page

            $TBS 		= new clsTinyButStrong;
            $TBS->NoErr 	= true;

            $TBS->LoadTemplate("$template");
            
            if(isset($activity) && $activity!=null)  
            {           
                $TBS->MergeBlock('activity',$activity);
            }
            
            $TBS->Render 	= TBS_OUTPUT;
            $TBS->Show();
            
            @mysql_close();
            die();
            
        }
    
}



if(isset($_SESSION['patient_id']))
{
        if(isSet($_POST['last_st_activity']))
        {
            $last_st_activity=$_POST['last_st_activity'];
            $info = new CI_model_TBS();
            $table = "st_usage, st_category";
            $columns = "*";
            $content = "st_usage.patient_id = '$patient_id' AND st_usage.st_category_id = st_category.st_category_id AND st_usage.st_usage_id< $last_st_activity ORDER BY st_usage_id DESC LIMIT $st_num_item_list";
            $activity = $info->TBS_select($table, $columns, $content); 
                
            if($activity == null)
            {
                // $inner_template4 	= "views/screen_tool_activity_layout.html";
            }
            else
            {
                $st_activity_id = $activity[$st_num_item_list-1]["st_usage_id"];
                $template 	= "views/screen_tool_activity_loadmore_layout.html";
            }
            
                    //Go to home page

            $TBS 		= new clsTinyButStrong;
            $TBS->NoErr 	= true;

            $TBS->LoadTemplate("$template");
            
            if(isset($activity) && $activity!=null)  
            {           
                $TBS->MergeBlock('activity',$activity);
            }
            
            $TBS->Render 	= TBS_OUTPUT;
            $TBS->Show();
            
            @mysql_close();
            die();
        }
}
            


?>
