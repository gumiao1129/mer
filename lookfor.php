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
include_once('models/CI_model_search_engine.php'); 
include_once('classes/random_code.php'); 

    if(isset($_GET['search_patients'])&&$_GET['search_patients']=="true")
    {
        $inner_template3 =  "views/search_patients_layout.html";
    }
    else if(isset($_GET['search_physicians'])&&$_GET['search_physicians']=="true")
    {
        $inner_template3 =  "views/search_physicians_layout.html";
    }
    else
    {
        $inner_template3 = null;
    }
    $activity_dr=null;
    $activity_pa=null;
    $search_keys="";
   
    $info = new CI_model_search_engine(); 
    if(isset($_GET['search_physicians'])&&($_GET['search_physicians']=="true")&&isset($_GET['search_key'])&&isset($_GET['search_category']))
    {
        $search_keys = $_GET['search_key'];
        $search_category = $_GET['search_category'];
        if($search_keys==null)
        {
                    if(isset($_SESSION['patient_id']))
                    {
                        $inner_template4 	= "views/search_physicians_result.html";
                        $patient_id = $_SESSION['patient_id'];
                        
                        //doctors gadget info
                        $dr_info_TBS = new CI_model_search_engine();
                        
                        if($search_category == "doctor")
                        {
                            $dr_search_table = "physician";
                                    $dr_search_table_left_join="physician_patient_relationships";
                                    $dr_search_table_session_id_field = "patient_id_one";
                                    $dr_search_table_left_join_id_field = "physician_id_one";
                                    $dr_session_id = $_SESSION['patient_id'];
                                    $dr_id_field = "physician_id";
                                    $other = " AND physician_patient_relationships.status=1";
                                    $activity_dr = $dr_info_TBS->search_diff_cate($dr_search_table, $dr_search_table_left_join, $dr_search_table_left_join_id_field, $dr_id_field, $dr_session_id,$dr_search_table_session_id_field, $other);                        
                   
                        }
                        else if($search_category == "sent")
                        {
                            $dr_search_table = "physician";
                                    $dr_search_table_left_join="physician_patient_relationships";
                                    $dr_search_table_session_id_field = "patient_id_one";
                                    $dr_search_table_left_join_id_field = "physician_id_one";
                                    $dr_session_id = $_SESSION['patient_id'];
                                    $dr_id_field = "physician_id";
                                    $other = " AND physician_patient_relationships.status=0 AND physician_patient_relationships.require_to = 'physician'";
                                    $activity_dr = $dr_info_TBS->search_diff_cate($dr_search_table, $dr_search_table_left_join, $dr_search_table_left_join_id_field, $dr_id_field, $dr_session_id,$dr_search_table_session_id_field, $other);                        
                   
                        }
                        else if($search_category == "request")
                        {
                            $dr_search_table = "physician";
                                    $dr_search_table_left_join="physician_patient_relationships";
                                    $dr_search_table_session_id_field = "patient_id_one";
                                    $dr_search_table_left_join_id_field = "physician_id_one";
                                    $dr_session_id = $_SESSION['patient_id'];
                                    $dr_id_field = "physician_id";
                                    $other = " AND physician_patient_relationships.status=0 AND physician_patient_relationships.require_to = 'patient'";
                                    $activity_dr = $dr_info_TBS->search_diff_cate($dr_search_table, $dr_search_table_left_join, $dr_search_table_left_join_id_field, $dr_id_field, $dr_session_id,$dr_search_table_session_id_field, $other);                        
                   
                        }
                        else
                        {
                            
                        }
                            
                   }

                    if(isset($_SESSION['physician_id']))
                    {
                        $inner_template4 	= "views/search_physicians_result.html";
                        $physician_id = $_SESSION['physician_id'];                       

                           //physician gadget info
                           $dr_info_TBS = new CI_model_search_engine();
                           
                            if($search_category == "doctor")
                            {
                                $dr_search_table = "physician";
                                   $dr_search_table_left_join="physician_physician_relationships";
                                   $dr_search_table_session_id_field = "physician_id_one";
                                   $dr_search_table_left_join_id_field = "physician_id_two";
                                   $dr_session_id = $physician_id;
                                   $dr_id_field = "physician_id";
                                   $other = " AND physician_physician_relationships.status=1 ";
                                   $activity_dr = $dr_info_TBS->search_same_cate($dr_search_table, $dr_search_table_left_join, $dr_search_table_left_join_id_field, $dr_id_field, $dr_session_id,$dr_search_table_session_id_field, $other); 

                            }
                            else if($search_category == "sent")
                            {
                                $dr_search_table = "physician";
                                   $dr_search_table_left_join="physician_physician_relationships";
                                   $dr_search_table_session_id_field = "physician_id_one";
                                   $dr_search_table_left_join_id_field = "physician_id_two";
                                   $dr_session_id = $physician_id;
                                   $dr_id_field = "physician_id";
                                   $other = " AND physician_physician_relationships.status=0 AND physician_physician_relationships.physician_id_one = $physician_id AND physician_physician_relationships.physician_id_two IS NOT NULL";
                                   $activity_dr = $dr_info_TBS->search_same_cate($dr_search_table, $dr_search_table_left_join, $dr_search_table_left_join_id_field, $dr_id_field, $dr_session_id,$dr_search_table_session_id_field, $other); 

                            }
                            else if($search_category == "request")
                            {
                               $dr_search_table = "physician";
                                   $dr_search_table_left_join="physician_physician_relationships";
                                   $dr_search_table_session_id_field = "physician_id_one";
                                   $dr_search_table_left_join_id_field = "physician_id_two";
                                   $dr_session_id = $physician_id;
                                   $dr_id_field = "physician_id";
                                   $other = " AND physician_physician_relationships.status=0 AND physician_physician_relationships.physician_id_two = $physician_id AND physician_physician_relationships.physician_id_one IS NOT NULL";
                                   $activity_dr = $dr_info_TBS->search_same_cate($dr_search_table, $dr_search_table_left_join, $dr_search_table_left_join_id_field, $dr_id_field, $dr_session_id,$dr_search_table_session_id_field, $other); 

                            }
                            else
                            {

                            }
                           
                    }
        }
        else
        {
            
            if(isset($_SESSION['patient_id']))
            {
                $inner_template4 	= "views/search_physicians_result.html";
                $search_table1 = "physician";
                $search_table_left_join="physician_patient_relationships";
                $search_table_session_id_field = "patient_id_one";
                $search_table_left_join_id_field = "physician_id_one";
                $session_id = $_SESSION['patient_id'];
                $search_field_array = array('physician.lastname', 'physician.firstname', 'physician.email', 'physician.specialty');
                $id_field = "physician_id";
                $activity_dr = $info->search_engine($search_keys, $search_table1, $search_table_left_join, $search_table_left_join_id_field, $search_field_array, $id_field, $session_id,$search_table_session_id_field); 
            }
            else if(isset($_SESSION['physician_id']))
            {
                $inner_template4 	= "views/search_physicians_result.html";
                $search_table1 = "physician";
                $search_table_left_join="physician_physician_relationships";
                $search_table_left_join_id_field = "physician_id_two";
                $search_table_session_id_field = "physician_id_one";
                $session_id = $_SESSION['physician_id'];
                $search_field_array = array('physician.lastname', 'physician.firstname', 'physician.email', 'physician.specialty');
                $id_field = "physician_id";
                $activity_dr = $info->search_engine_without_itself($search_keys, $search_table1, $search_table_left_join, $search_table_left_join_id_field, $search_field_array, $id_field, $session_id,$search_table_session_id_field);          
            }         
        }
    }
    else if(isset($_GET['search_patients'])&&($_GET['search_patients']=="true")&&isset($_GET['search_key'])&&isset($_GET['search_category']))
    {
        $search_keys = $_GET['search_key'];
        $search_category = $_GET['search_category'];
        if($search_keys==null)
        {
                    if(isset($_SESSION['patient_id']))
                    {
                        $inner_template4 	= "views/search_patients_result.html";
                        $patient_id = $_SESSION['patient_id'];
                       
                           //patients gadget info
                           $pa_info_TBS = new CI_model_search_engine();
                           
                           if($search_category == "doctor")
                            {
                                 $pa_search_table = "patient";
                                    $pa_search_table_left_join="patient_patient_relationships";
                                    $pa_search_table_session_id_field = "patient_id_one";
                                    $pa_search_table_left_join_id_field = "patient_id_two";
                                    $pa_session_id = $_SESSION['patient_id'];
                                    $pa_id_field = "patient_id";
                                    $other = " AND patient_patient_relationships.status=1";
                                    $activity_pa = $pa_info_TBS->search_same_cate($pa_search_table, $pa_search_table_left_join, $pa_search_table_left_join_id_field, $pa_id_field, $pa_session_id,$pa_search_table_session_id_field, $other); 

                            }
                            else if($search_category == "sent")
                            {
                                 $pa_search_table = "patient";
                                    $pa_search_table_left_join="patient_patient_relationships";
                                    $pa_search_table_session_id_field = "patient_id_one";
                                    $pa_search_table_left_join_id_field = "patient_id_two";
                                    $pa_session_id = $_SESSION['patient_id'];
                                    $pa_id_field = "patient_id";
                                    $other = " AND patient_patient_relationships.status=0 AND patient_patient_relationships.patient_id_one = $patient_id AND patient_patient_relationships.patient_id_two IS NOT NULL";
                                    $activity_pa = $pa_info_TBS->search_same_cate($pa_search_table, $pa_search_table_left_join, $pa_search_table_left_join_id_field, $pa_id_field, $pa_session_id,$pa_search_table_session_id_field, $other); 

                            }
                            else if($search_category == "request")
                            {
                                $pa_search_table = "patient";
                                    $pa_search_table_left_join="patient_patient_relationships";
                                    $pa_search_table_session_id_field = "patient_id_one";
                                    $pa_search_table_left_join_id_field = "patient_id_two";
                                    $pa_session_id = $_SESSION['patient_id'];
                                    $pa_id_field = "patient_id";
                                    $other = " AND patient_patient_relationships.status=0 AND patient_patient_relationships.patient_id_two = $patient_id AND patient_patient_relationships.patient_id_one IS NOT NULL";
                                    $activity_pa = $pa_info_TBS->search_same_cate($pa_search_table, $pa_search_table_left_join, $pa_search_table_left_join_id_field, $pa_id_field, $pa_session_id,$pa_search_table_session_id_field, $other); 
                            }
                            else
                            {

                            }
      
                    }

                    if(isset($_SESSION['physician_id']))
                    {
                        $inner_template4 	= "views/search_patients_result.html";
                        $physician_id = $_SESSION['physician_id'];

                            //patient gadget info
                            $pa_info_TBS = new CI_model_search_engine();
                            
                            if($search_category == "patient")
                            {
                                 $pa_search_table = "patient";
                                    $pa_search_table_left_join="physician_patient_relationships";
                                    $pa_search_table_session_id_field = "physician_id_one";
                                    $pa_search_table_left_join_id_field = "patient_id_one";
                                    $pa_session_id = $physician_id;
                                    $pa_id_field = "patient_id";
                                    $other = " AND physician_patient_relationships.status=1";
                                    $activity_pa = $pa_info_TBS->search_diff_cate($pa_search_table, $pa_search_table_left_join, $pa_search_table_left_join_id_field, $pa_id_field, $pa_session_id,$pa_search_table_session_id_field, $other); 

                            }
                            else if($search_category == "sent")
                            {
                                 $pa_search_table = "patient";
                                    $pa_search_table_left_join="physician_patient_relationships";
                                    $pa_search_table_session_id_field = "physician_id_one";
                                    $pa_search_table_left_join_id_field = "patient_id_one";
                                    $pa_session_id = $physician_id;
                                    $pa_id_field = "patient_id";
                                    $other = " AND physician_patient_relationships.status=0 AND physician_patient_relationships.require_to = 'patient'";
                                    $activity_pa = $pa_info_TBS->search_diff_cate($pa_search_table, $pa_search_table_left_join, $pa_search_table_left_join_id_field, $pa_id_field, $pa_session_id,$pa_search_table_session_id_field, $other); 

                            }
                            else if($search_category == "request")
                            {
                                $pa_search_table = "patient";
                                    $pa_search_table_left_join="physician_patient_relationships";
                                    $pa_search_table_session_id_field = "physician_id_one";
                                    $pa_search_table_left_join_id_field = "patient_id_one";
                                    $pa_session_id = $physician_id;
                                    $pa_id_field = "patient_id";
                                    $other = " AND physician_patient_relationships.status=0 AND physician_patient_relationships.require_to = 'physician'";
                                    $activity_pa = $pa_info_TBS->search_diff_cate($pa_search_table, $pa_search_table_left_join, $pa_search_table_left_join_id_field, $pa_id_field, $pa_session_id,$pa_search_table_session_id_field, $other);                                    
                            }
                            else
                            {

                            }
  
                    }
        }
        else
        {
            if(isset($_SESSION['patient_id']))
            {
                $inner_template4 	= "views/search_patients_result.html";
                $search_table1 = "patient";
                $search_table_left_join="patient_patient_relationships";
                $search_table_left_join_id_field = "patient_id_two";
                $search_table_session_id_field = "patient_id_one";
                $session_id = $_SESSION['patient_id'];
                $search_field_array = array('patient.lastname', 'patient.firstname', 'patient.email');
                $id_field = "patient_id";
                $activity_pa = $info->search_engine_without_itself($search_keys, $search_table1, $search_table_left_join, $search_table_left_join_id_field, $search_field_array, $id_field, $session_id,$search_table_session_id_field); 
            }
            else if(isset($_SESSION['physician_id']))
            {
                $inner_template4 	= "views/search_patients_result.html";
                $search_table1 = "patient";
                $search_table_left_join="physician_patient_relationships";
                $search_table_left_join_id_field = "patient_id_one";
                $search_table_session_id_field = "physician_id_one";
                $session_id = $_SESSION['physician_id'];
                $search_field_array = array('patient.lastname', 'patient.firstname', 'patient.email');
                $id_field = "patient_id";
                $activity_pa = $info->search_engine($search_keys, $search_table1, $search_table_left_join, $search_table_left_join_id_field, $search_field_array, $id_field, $session_id,$search_table_session_id_field);          
            }  
            
        }
    }
            //Go to home page
            $template 		= "views/main_1.htm";
            $inner_template1 	= "views/nav_main_manu.html";

            $TBS 			= new clsTinyButStrong;
            $TBS->NoErr 	= true;

            $TBS->LoadTemplate("$template");

            $TBS->Render 	= TBS_OUTPUT;
            $TBS->MergeBlock('activity_dr',$activity_dr);
            $TBS->MergeBlock('activity_pa',$activity_pa);
            
            $TBS->Show();

                        
            //Event functions
       function add_physicians_tag($BlockName,&$CurrRec,$RecNum){
            $confirmed = 'Confirmed';
            $confirm_or_not = "Please click to confirm.";
            $sent_request = "Request has been sent.";
            $ignore = "Cancel";
        //$BlockName : name of the block that calls the function (read only)
        //$CurrRec   : array that contains columns of the current record (read/write)
        //$RecNum    : number of the current record (read only)
        if(isset($_SESSION['patient_id']))
        {
            $add_or_not = "Add as doctor";
             if ($CurrRec['patient_id_one']==$_SESSION['patient_id']&&$CurrRec['status']==0&&$CurrRec['require_to']=="patient")
             {
                 $CurrRec['level'] = "<a href='confirmConnection.php?height=180&width=350&modal=true&profile_img=$CurrRec[thumbnail_pic_name]&firstname=$CurrRec[firstname]&lastname=$CurrRec[lastname]&id=$CurrRec[physician_id]&add_cate=physician' class='thickbox' id='add_tag'>$confirm_or_not</a>";
             }
            else if($CurrRec['patient_id_one']==$_SESSION['patient_id']&&$CurrRec['status']==0&&$CurrRec['require_to']=="physician")
            {
                $CurrRec['level'] = "<div id='add_tag' title='Add as Doctor'>$sent_request</div>";
          
            }
            else if($CurrRec['status']==1)
            {
                $CurrRec['level'] = "<div id='add_tag' title='Add as Doctor'>$confirmed</div>";
            }
            else
            {
                $CurrRec['level'] = "<a href='addConnection.php?height=180&width=350&modal=true&profile_img=$CurrRec[thumbnail_pic_name]&firstname=$CurrRec[firstname]&lastname=$CurrRec[lastname]&id=$CurrRec[physician_id]&add_cate=physician' class='thickbox' id='add_tag' title='Add as Doctor'>$add_or_not</a>";
            }
            $CurrRec['level2'] = "<a style='color:#F9F9F9;' href='ignoreConnection.php?height=180&width=350&modal=true&profile_img=$CurrRec[thumbnail_pic_name]&firstname=$CurrRec[firstname]&lastname=$CurrRec[lastname]&id=$CurrRec[physician_id]&add_cate=physician' class='thickbox' title='$ignore'>$ignore</a>";
        }
        
        if(isset($_SESSION['physician_id']))
        {
            $add_or_not = "Add as friend";
             if ($CurrRec['physician_id_two']==$_SESSION['physician_id']&&$CurrRec['status']==0&&$CurrRec['physician_id_one']!=null)
             {
                 $CurrRec['level'] = "<a href='confirmConnection.php?height=180&width=350&modal=true&profile_img=$CurrRec[thumbnail_pic_name]&firstname=$CurrRec[firstname]&lastname=$CurrRec[lastname]&id=$CurrRec[physician_id]&add_cate=physician' class='thickbox' id='add_tag'>$confirm_or_not</a>";
             }
            else if($CurrRec['physician_id_one']==$_SESSION['physician_id']&&$CurrRec['status']==0&&$CurrRec['physician_id_two']!=null)
            {
                $CurrRec['level'] = "<div id='add_tag' title='Add as Doctor'>$sent_request</div>";
          
            }
            else if($CurrRec['status']==1)
            {
                $CurrRec['level'] = "<div id='add_tag' title='Add as Doctor'>$confirmed</div>";
            }
            else
            {
                $CurrRec['level'] = "<a href='addConnection.php?height=180&width=350&modal=true&profile_img=$CurrRec[thumbnail_pic_name]&firstname=$CurrRec[firstname]&lastname=$CurrRec[lastname]&id=$CurrRec[physician_id]&add_cate=physician' class='thickbox' id='add_tag' title='Add as Doctor'>$add_or_not</a>";
            }
            $CurrRec['level2'] = "<a style='color:#F9F9F9;' href='ignoreConnection.php?height=180&width=350&modal=true&profile_img=$CurrRec[thumbnail_pic_name]&firstname=$CurrRec[firstname]&lastname=$CurrRec[lastname]&id=$CurrRec[physician_id]&add_cate=physician' class='thickbox' title='$ignore'>$ignore</a>";
        }
    }
            
            
        
    function add_patients_tag($BlockName,&$CurrRec,$RecNum){
            $confirmed = 'Confirmed';
            $confirm_or_not = "Please click to confirm.";
            $sent_request = "Request has been sent.";
            $ignore = "Cancel";
        //$BlockName : name of the block that calls the function (read only)
        //$CurrRec   : array that contains columns of the current record (read/write)
        //$RecNum    : number of the current record (read only)
         if(isset($_SESSION['patient_id']))
         {
             $add_or_not = "Add as friend";
             if ($CurrRec['patient_id_two']==$_SESSION['patient_id']&&$CurrRec['status']==0&&$CurrRec['patient_id_one']!=null)
             {
                 $CurrRec['level'] = "<a href='confirmConnection.php?height=180&width=350&modal=true&profile_img=$CurrRec[thumbnail_pic_name]&firstname=$CurrRec[firstname]&lastname=$CurrRec[lastname]&id=$CurrRec[patient_id]&add_cate=patient' class='thickbox' id='add_tag' title='Add as Doctor'>$confirm_or_not</a>";
             }
            else if($CurrRec['patient_id_one']==$_SESSION['patient_id']&&$CurrRec['status']==0&&$CurrRec['patient_id_two']!=null)
            {
              $CurrRec['level'] = "<div id='add_tag' title='Add as Doctor'>$sent_request</div>";
            }
            else if($CurrRec['status']==1)
            {
                $CurrRec['level'] = "<div id='add_tag' title='Add as Doctor'>$confirmed</div>";
            }
            else
            {
              $CurrRec['level'] = "<a href='addConnection.php?height=180&width=350&modal=true&profile_img=$CurrRec[thumbnail_pic_name]&firstname=$CurrRec[firstname]&lastname=$CurrRec[lastname]&id=$CurrRec[patient_id]&add_cate=patient' class='thickbox' id='add_tag'>$add_or_not</a>";
            }
            $CurrRec['level2'] = "<a style='color:#F9F9F9;' href='ignoreConnection.php?height=180&width=350&modal=true&profile_img=$CurrRec[thumbnail_pic_name]&firstname=$CurrRec[firstname]&lastname=$CurrRec[lastname]&id=$CurrRec[patient_id]&add_cate=patient' class='thickbox' title='$ignore'>$ignore</a>";   
         }
         if(isset($_SESSION['physician_id']))
         {
             $add_or_not = "Add as patient";
             if ($CurrRec['physician_id_one']==$_SESSION['physician_id']&&$CurrRec['status']==0&&$CurrRec['require_to']=="physician")
             {
                 $CurrRec['level'] = "<a href='confirmConnection.php?height=180&width=350&modal=true&profile_img=$CurrRec[thumbnail_pic_name]&firstname=$CurrRec[firstname]&lastname=$CurrRec[lastname]&id=$CurrRec[patient_id]&add_cate=patient' class='thickbox' id='add_tag' title='Add as Patient'>$confirm_or_not</a>";
             }
            else if($CurrRec['physician_id_one']==$_SESSION['physician_id']&&$CurrRec['status']==0&&$CurrRec['require_to']=="patient")
            {
              $CurrRec['level'] = "<div id='add_tag' title='Add as Doctor'>$sent_request</div>";
          
            }
            else if($CurrRec['status']==1)
            {
                $CurrRec['level'] = "<div id='add_tag' title='Add as Doctor'>$confirmed</div>";
            }
            else
            {
              $CurrRec['level'] = "<a href='addConnection.php?height=180&width=350&modal=true&profile_img=$CurrRec[thumbnail_pic_name]&firstname=$CurrRec[firstname]&lastname=$CurrRec[lastname]&id=$CurrRec[patient_id]&add_cate=patient' class='thickbox' id='add_tag' title='Add as Patient'>$add_or_not</a>";
            }
            $CurrRec['level2'] = "<a style='color:#F9F9F9;' href='ignoreConnection.php?height=180&width=350&modal=true&profile_img=$CurrRec[thumbnail_pic_name]&firstname=$CurrRec[firstname]&lastname=$CurrRec[lastname]&id=$CurrRec[patient_id]&add_cate=patient' class='thickbox' title='$ignore'>$ignore</a>";
         }
          
        }
            
            @mysql_close();
            die();

?>
