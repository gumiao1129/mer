<?php
session_start();

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

$limited = " LIMIT 3";
if(isset($_SESSION['patient_id']))
{
     $patient_id = $_SESSION['patient_id'];
    
        //Screeningtool Gadget info
        $ST_info_TBS = new CI_model_TBS();
        $table = "st_usage, st_category";
        $columns = "*";
        $content = "st_usage.patient_id = '$patient_id' AND st_usage.st_category_id = st_category.st_category_id ORDER BY st_usage_id DESC LIMIT 3";
        $ST_info = $ST_info_TBS->TBS_select($table, $columns, $content);
        
        
        //doctors gadget info
        $dr_info_TBS = new CI_model_search_engine();
            $dr_search_table = "physician";
                $dr_search_table_left_join="physician_patient_relationships";
                $dr_search_table_session_id_field = "patient_id_one";
                $dr_search_table_left_join_id_field = "physician_id_one";
                $dr_session_id = $_SESSION['patient_id'];
                $dr_id_field = "physician_id";
                $dr_info = $dr_info_TBS->search_widget_diff_cate($dr_search_table, $dr_search_table_left_join, $dr_search_table_left_join_id_field, $dr_id_field, $dr_session_id,$dr_search_table_session_id_field); 
            
       //patients gadget info
       $pa_info_TBS = new CI_model_search_engine();
            $pa_search_table = "patient";
                $pa_search_table_left_join="patient_patient_relationships";
                $pa_search_table_session_id_field = "patient_id_one";
                $pa_search_table_left_join_id_field = "patient_id_two";
                $pa_session_id = $_SESSION['patient_id'];
                $pa_id_field = "patient_id";
                $pa_info = $pa_info_TBS->search_widget_same_cate($pa_search_table, $pa_search_table_left_join, $pa_search_table_left_join_id_field, $pa_id_field, $pa_session_id,$pa_search_table_session_id_field); 
                   
        //message gadge info
        $pa_message_TBS = new CI_model_search_engine();
            $to_id = $_SESSION['patient_id'];;
            $to_id_cate = "patient";
            $limit_msg = "";
            $message_info = $pa_message_TBS->search_messages($to_id, $to_id_cate, $limited, $limit_msg);

            
       $inner_template3 = "views/patient_home.html";
    
}

if(isset($_SESSION['physician_id']))
{
     $physician_id = $_SESSION['physician_id'];
  
        //Screeningtool Gadget info
        $ST_info_TBS = new CI_model_TBS();
        $table = "st_usage, st_category";
        $columns = "*";
        $content = "st_usage.physician_id = '$physician_id' AND st_usage.st_category_id = st_category.st_category_id ORDER BY st_usage_id DESC LIMIT 3";
        $ST_info = $ST_info_TBS->TBS_select($table, $columns, $content); 
        
        //patient gadget info
        $pa_info_TBS = new CI_model_search_engine();
        $pa_search_table = "patient";
        $pa_search_table_left_join="physician_patient_relationships";
        $pa_search_table_session_id_field = "physician_id_one";
        $pa_search_table_left_join_id_field = "patient_id_one";
        $pa_session_id = $physician_id;
        $pa_id_field = "patient_id";
        $pa_info = $pa_info_TBS->search_widget_diff_cate($pa_search_table, $pa_search_table_left_join, $pa_search_table_left_join_id_field, $pa_id_field, $pa_session_id,$pa_search_table_session_id_field); 
            
       //physician gadget info
       $dr_info_TBS = new CI_model_search_engine();
       $dr_search_table = "physician";
       $dr_search_table_left_join="physician_physician_relationships";
       $dr_search_table_session_id_field = "physician_id_one";
       $dr_search_table_left_join_id_field = "physician_id_two";
       $dr_session_id = $physician_id;
       $dr_id_field = "physician_id";
       $dr_info = $dr_info_TBS->search_widget_same_cate($dr_search_table, $dr_search_table_left_join, $dr_search_table_left_join_id_field, $dr_id_field, $dr_session_id,$dr_search_table_session_id_field); 
           
       
               //message gadge info
        $dr_message_TBS = new CI_model_search_engine();
            $to_id = $_SESSION['physician_id'];;
            $to_id_cate = "physician";
            $limit_msg = "";
            $message_info = $dr_message_TBS->search_messages($to_id, $to_id_cate, $limited, $limit_msg);
            
      $inner_template3 	= "views/physician_home.html";
}

           
            foreach ($message_info as &$value) {
               $value['message'] = str_replace("\r\n",'',$value['message'] );
               $value['message'] = str_replace("\n",'',$value['message'] );
            }
            //Go to home page
            $template 		= "views/main_1.htm";
            $inner_template1 	= "views/nav_main_manu.html";

            $TBS 			= new clsTinyButStrong;
            $TBS->NoErr 	= true;

            $TBS->LoadTemplate("$template");

            $TBS->Render 	= TBS_OUTPUT;
            
            $TBS->MergeBlock('ST_info',$ST_info);
            $TBS->MergeBlock('dr_info',$dr_info);
            $TBS->MergeBlock('pa_info',$pa_info);
            $TBS->MergeBlock('activity',$message_info);
            $TBS->Show();
            

            
    function add_physicians_tag_widget($BlockName,&$CurrRec,$RecNum)
    {
            $confirmed = 'Confirmed';
            $confirm_or_not = "Comfirm";
            $sent_request = "Request sent";
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
            $CurrRec['level2'] = "<a style='color:#F9F9F9;' href='ignoreConnection.php?height=180&width=350&modal=true&profile_img=$CurrRec[thumbnail_pic_name]&firstname=$CurrRec[firstname]&lastname=$CurrRec[lastname]&id=$CurrRec[physician_id]&add_cate=physician' class='thickbox' id='add_tag' title='Add as Doctor'>$ignore</a>";
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
            $CurrRec['level2'] = "<a style='color:#F9F9F9;' href='ignoreConnection.php?height=180&width=350&modal=true&profile_img=$CurrRec[thumbnail_pic_name]&firstname=$CurrRec[firstname]&lastname=$CurrRec[lastname]&id=$CurrRec[physician_id]&add_cate=physician' class='thickbox' id='add_tag' title='Add as Doctor'>$ignore</a>";
        }
    }
            
            
        
      function add_patients_tag_widget($BlockName,&$CurrRec,$RecNum){
            $confirmed = 'Confirmed';
            $confirm_or_not = "Confirm";
            $sent_request = "Request sent.";
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
            $CurrRec['level2'] = "<a style='color:#F9F9F9;' href='ignoreConnection.php?height=180&width=350&modal=true&profile_img=$CurrRec[thumbnail_pic_name]&firstname=$CurrRec[firstname]&lastname=$CurrRec[lastname]&id=$CurrRec[patient_id]&add_cate=patient' class='thickbox' id='add_tag' title='Add as Doctor'>$ignore</a>";
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
            $CurrRec['level2'] = "<a style='color:#F9F9F9;' href='ignoreConnection.php?height=180&width=350&modal=true&profile_img=$CurrRec[thumbnail_pic_name]&firstname=$CurrRec[firstname]&lastname=$CurrRec[lastname]&id=$CurrRec[patient_id]&add_cate=patient' class='thickbox' id='add_tag' title='Add as Doctor'>$ignore</a>";
         }
          
        }
        
    function read_unread($BlockName,&$CurrRec,$RecNum){
         if ($CurrRec['opened']!=null&&$CurrRec['opened']==0)
         {
             $CurrRec['level'] = "#E6E6E6";
         }
        else if($CurrRec['opened']!=null&&$CurrRec['opened']==1)
        {
            $CurrRec['level'] = "";
        }

        $CurrRec['message_details'] = "message_details.php?width=400&modal=true&profile_img=$CurrRec[thumbnail_pic_name]&firstname=$CurrRec[firstname]&lastname=$CurrRec[lastname]&message_id=$CurrRec[message_id]&message_recipient_id=$CurrRec[id]";
        }                 

            @mysql_close();
            die();
?>
