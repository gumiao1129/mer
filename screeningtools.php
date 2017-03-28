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
        $physician_id = $_SESSION['physician_id'];
        $info = new CI_model_TBS();
        if(isset($_GET['st']))
        {
            $st = $_GET['st'];

            if($st == "activity")
            {
                $table = "st_usage, st_category";
                $columns = "*";
                $content = "st_usage.physician_id = '$physician_id' AND st_usage.st_category_id = st_category.st_category_id ORDER BY st_usage_id DESC LIMIT $st_num_item_list";
                $activity = $info->TBS_select($table, $columns, $content); 
                if($activity == null)
                {
                   // $inner_template4 	= "views/screen_tool_activity_layout.html";
                }
                else
                {
                    $st_activity_id = $activity[$st_num_item_list-1]["st_usage_id"];
                    $inner_template4 	= "views/screen_tool_activity_layout.html";
                }
            }
            else if($st == "recommendation")
            {
                $table = "st_category";
                $columns = "*";
                $alltools = $info->TBS_select($table, $columns,''); 

                if($alltools == null)
                {
                    $inner_template4   = "";
                }
                else
                {
                    $inner_template4 	= "views/screeningtools_recommendation_layout.html";
                }            
            }
            else if($st == "alltools")
            {
                $table = "st_category";
                $columns = "*";
                $alltools = $info->TBS_select($table, $columns,''); 
                
                $inner_template4 	= "views/screening_tool_all_tools_layout.html";
            }
            else
            {
                $table = "st_usage, st_category";
                $columns = "*";
                $content = "st_usage.physician_id = '$physician_id' AND st_usage.st_category_id = st_category.st_category_id ORDER BY st_usage_id DESC LIMIT $st_num_item_list";
                $activity = $info->TBS_select($table, $columns, $content); 
                
                if($activity == null)
                {
                   // $inner_template4 	= "views/screen_tool_activity_layout.html";
                }
                else
                {
                    $st_activity_id = $activity[$st_num_item_list-1]["st_usage_id"];
                    $inner_template4 	= "views/screen_tool_activity_layout.html";
                }
            }
        }
        else if(isset($_GET['st_req']))
        {
            $st_req = $_GET['st_req'];

            $table = "st_category";
            $columns = "*";
            $content = "screeningtool_name_md5 = '$st_req'";
            $st_result= $physicianInfo->dbSelect($table, $columns, $content);

            if($st_result != null)
            {
                $st_name = strtolower($st_result['screeningtool_name']);
                $_SESSION['st_category_id']    = $st_result['st_category_id'];

                    if(isset($_SESSION['score']))
                    {
                        $st_score= $st_name."score.php";
                        include_once("/addons/$st_name/$st_score");
                    }
                    else
                    {
                        include_once("/addons/$st_name/$st_name.php");
                    }
            }
        }
        else
        {
                $table = "st_usage, st_category";
                $columns = "*";
                $content = "st_usage.physician_id = '$physician_id' AND st_usage.st_category_id = st_category.st_category_id ORDER BY st_usage_id DESC LIMIT $st_num_item_list";
                $activity = $info->TBS_select($table, $columns, $content); 
                
                if($activity == null)
                {
                   // $inner_template4 	= "views/screen_tool_activity_layout.html";
                }
                else
                {
                    $st_activity_id = $activity[$st_num_item_list-1]["st_usage_id"];
                    $inner_template4 	= "views/screen_tool_activity_layout.html";
                }
        }
}

if(isset($_SESSION['patient_id']))
{
        $patient_id = $_SESSION['patient_id'];
        $info = new CI_model_TBS();
        if(isset($_GET['st']))
        {
            $st = $_GET['st'];


            if($st == "activity")
            {
                $table = "st_usage, st_category";
                $columns = "*";
                $content = "st_usage.patient_id = '$patient_id' AND st_usage.st_category_id = st_category.st_category_id ORDER BY st_usage_id DESC LIMIT $st_num_item_list";
                $activity = $info->TBS_select($table, $columns, $content); 
                
                if($activity == null)
                {
                   // $inner_template4 	= "views/screen_tool_activity_layout.html";
                }
                else
                {
                    $st_activity_id = $activity[$st_num_item_list-1]["st_usage_id"];
                    $inner_template4 	= "views/screen_tool_activity_layout.html";
                }
            }
            else if($st == "recommendation")
            {
                $table = "st_category, st_physician_recommand_to_patient, physician";
                $columns = "*";
                $otherReq = "st_category.st_category_id = st_physician_recommand_to_patient.st_category_id
                            AND
                            st_physician_recommand_to_patient.physician_id = physician.physician_id
                            AND
                            st_physician_recommand_to_patient.patient_id = $patient_id
                            ORDER BY
                            st_physician_recommand_to_patient.date_created DESC";
                
                $alltools = $info->TBS_select($table, $columns, $otherReq);
                if($alltools == null)
                {
                    $inner_template4 	= "";
                }
                else
                {
                    $inner_template4 	= "views/screeningtools_be_recommended_layout.html";
                } 
            }
            else if($st == "alltools")
            {
                $inner_template4 	= "views/screening_tool_all_tools_layout.html";

                $table = "st_category";
                $columns = "*";
                $alltools = $info->TBS_select($table, $columns,''); 
            }
            else
            {
                $table = "st_usage, st_category";
                $columns = "*";
                $content = "st_usage.patient_id = '$patient_id' AND st_usage.st_category_id = st_category.st_category_id ORDER BY st_usage_id DESC LIMIT $st_num_item_list";
                $activity = $info->TBS_select($table, $columns, $content); 
                
                if($activity == null)
                {
                   //$inner_template4 	= "views/screen_tool_activity_layout.html";
                }
                else
                {
                    $st_activity_id = $activity[$st_num_item_list-1]["st_usage_id"];
                    $inner_template4 	= "views/screen_tool_activity_layout.html";
                }
            }
        }
        else if(isset($_GET['st_req']))
        {
            $st_req = $_GET['st_req'];

            $table = "st_category";
            $columns = "*";
            $content = "screeningtool_name_md5 = '$st_req'";
            $st_result= $patientInfo->dbSelect($table, $columns, $content);

            if($st_result != null)
            {
                $st_name = strtolower($st_result['screeningtool_name']);
                $_SESSION['st_category_id']    = $st_result['st_category_id'];

                    if(isset($_SESSION['score']))
                    {
                        $st_score= $st_name."score.php";
                        include_once("addons/$st_name/$st_score");
                    }
                    else
                    {
                        include_once("addons/$st_name/$st_name.php");
                    }

            }

        }
        else
        {
                $table = "st_usage, st_category";
                $columns = "*";
                $content = "st_usage.patient_id = '$patient_id' AND st_usage.st_category_id = st_category.st_category_id ORDER BY st_usage_id DESC LIMIT $st_num_item_list";
                $activity = $info->TBS_select($table, $columns, $content);
                
                if($activity == null)
                {
                   //$inner_template4 	= "views/screen_tool_activity_layout.html";
                }
                else
                {
                    $st_activity_id = $activity[$st_num_item_list-1]["st_usage_id"];
                    $inner_template4 	= "views/screen_tool_activity_layout.html";
                }
        }
        
        
}

            //Go to home page
            $template 		= "views/main_1.htm";
            $inner_template1 	= "views/nav_main_manu.html";
            $inner_screeningtool_sub = "views/inner_screeningtool_sub.html";
            //$inner_template2 	= "views/nav_side_manu.html";
            $inner_template3 	= "views/screening_tools_layout.html";
            $TBS 		= new clsTinyButStrong;
            $TBS->NoErr 	= true;

            $TBS->LoadTemplate("$template");

            
if(isset($activity) && $activity!=null)  
{           
            $TBS->MergeBlock('activity',$activity);
}

if(isset($alltools) && $alltools!=null)  
{           
            $TBS->MergeBlock('alltools',$alltools);
}

            $TBS->Render 	= TBS_OUTPUT;
            $TBS->Show();
            
            @mysql_close();
            die();
            
            
?>
