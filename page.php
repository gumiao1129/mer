<?php
session_start();

include_once('classes/tbs_class_php5.php');
include_once('lang/en.php');
include_once('configData.php');     
 
            if( (!isset($_SESSION['patient_id'])) && (!isset($_SESSION['physician_id'])))
            {
                    $template = "views/main_0.htm"; 
                    $inner_template1 	= "views/login_form.html";                
            }
            else
            {
                    $template = "views/main_1.htm";
                    $inner_template1 	= "views/nav_main_manu.html";
            }
            
            
            
            if(isset($_GET['page']) && $_GET['page'] == 1)
            {
                $inner_template3 	= "views/about.html";                
            }
            else if(isset($_GET['page']) && $_GET['page'] == 2)
            {
                $inner_template3 	= "views/contact_us.html"; 
            }
            else if(isset($_GET['page']) && $_GET['page'] == 3)
            {
                $inner_template3 	= "views/terms.html";
            }
            else if(isset($_GET['page']) && $_GET['page'] == 4)
            {
                $inner_template3 	= "views/careers.html";
            }
            else
            {
                $inner_template3 	= "views/error404.html";
            }
            $TBS 		= new clsTinyButStrong;
            $TBS->NoErr 	= true;

            $TBS->LoadTemplate("$template");
            
                        $TBS->Render 	= TBS_OUTPUT;
            $TBS->Show();
            
            @mysql_close();
            die();
            
?>
