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
include_once('classes/random_code.php'); 



    if(isSet($_POST['last_msg_id']))
    {
            $last_msg_id=$_POST['last_msg_id'];
            $message_tag_TBS = new CI_model_search_engine();

            $to_id="";
            $to_id_cate="";

            if(isset($_SESSION['patient_id']))
            {
            $to_id=$_SESSION['patient_id'];
            $to_id_cate='patient';
            }
            else if(isset($_SESSION['physician_id']))
            {
            $to_id=$_SESSION['physician_id'];
            $to_id_cate= 'physician';
            }

            $limited = "LIMIT $msg_num_item_list";
            $limit_msg = "AND private_messages.message_id < $last_msg_id";
            //$limit_msg = "AND private_messages.message_id ";

            $activity = $message_tag_TBS->search_messages($to_id, $to_id_cate, $limited, $limit_msg);
            $msg_id = $activity[$msg_num_item_list-1]["message_id"];

            foreach ($activity as &$value) {
               $value['message'] = str_replace("\r\n",'',$value['message'] );
               $value['message'] = str_replace("\n",'',$value['message'] );
            }

            $template    =  "views/message_layout_more.html";
                    //Go to home page

            $TBS 		= new clsTinyButStrong;
            $TBS->NoErr 	= true;

            $TBS->LoadTemplate("$template");

            $TBS->Render 	= TBS_OUTPUT;


            
               
            
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
        
            $TBS->MergeBlock('activity',$activity);
            $TBS->Show();
            @mysql_close();
            die();
            
    }
    
    
    if(isSet($_POST['last_doctor_msg_id']))
    {
            $last_doctor_msg_id = $_POST['last_doctor_msg_id'];
            $message_tag_TBS = new CI_model_search_engine();

            $to_id="";
            $to_id_cate="";

            if(isset($_SESSION['patient_id']))
            {
            $to_id=$_SESSION['patient_id'];
            $to_id_cate='patient';
            }
            else if(isset($_SESSION['physician_id']))
            {
            $to_id=$_SESSION['physician_id'];
            $to_id_cate= 'physician';
            }

            $limited = "LIMIT $msg_num_item_list";
            $limit_msg = "AND private_messages.message_id < $last_doctor_msg_id";
            //$limit_msg = "AND private_messages.message_id ";

            $activity = $message_tag_TBS->search_messages_from_doctors($to_id, $to_id_cate, $limited, $limit_msg);
            $msg_id = $activity[$msg_num_item_list-1]["message_id"];

            foreach ($activity as &$value) {
               $value['message'] = str_replace("\r\n",'',$value['message'] );
               $value['message'] = str_replace("\n",'',$value['message'] );
            }

            $template    =  "views/message_layout_more.html";
                    //Go to home page

            $TBS 		= new clsTinyButStrong;
            $TBS->NoErr 	= true;

            $TBS->LoadTemplate("$template");

            $TBS->Render 	= TBS_OUTPUT;


            
               
            
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
        
            $TBS->MergeBlock('activity',$activity);
            $TBS->Show();
            @mysql_close();
            die();
            
    }
    
    
    if(isSet($_POST['last_patient_msg_id']))
    {
            $last_patient_msg_id = $_POST['last_patient_msg_id'];
            $message_tag_TBS = new CI_model_search_engine();

            $to_id="";
            $to_id_cate="";

            if(isset($_SESSION['patient_id']))
            {
            $to_id=$_SESSION['patient_id'];
            $to_id_cate='patient';
            }
            else if(isset($_SESSION['physician_id']))
            {
            $to_id=$_SESSION['physician_id'];
            $to_id_cate= 'physician';
            }

            $limited = "LIMIT $msg_num_item_list";
            $limit_msg = "AND private_messages.message_id < $last_patient_msg_id";
            //$limit_msg = "AND private_messages.message_id ";

            $activity = $message_tag_TBS->search_messages_from_patients($to_id, $to_id_cate, $limited, $limit_msg);
            $msg_id = $activity[$msg_num_item_list-1]["message_id"];

            foreach ($activity as &$value) {
               $value['message'] = str_replace("\r\n",'',$value['message'] );
               $value['message'] = str_replace("\n",'',$value['message'] );
            }

            $template    =  "views/message_layout_more.html";
                    //Go to home page

            $TBS 		= new clsTinyButStrong;
            $TBS->NoErr 	= true;

            $TBS->LoadTemplate("$template");

            $TBS->Render 	= TBS_OUTPUT;


            
               
            
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
        
            $TBS->MergeBlock('activity',$activity);
            $TBS->Show();
            @mysql_close();
            die();
            
    }
    

    if(isSet($_POST['last_send_msg_id']))
    {
            $last_send_msg_id = $_POST['last_send_msg_id'];
            $message_tag_TBS = new CI_model_search_engine();

            $to_id="";
            $to_id_cate="";

            if(isset($_SESSION['patient_id']))
            {
                $to_id=$_SESSION['patient_id'];
                $to_id_cate='patient';
            }
            else if(isset($_SESSION['physician_id']))
            {
                $to_id=$_SESSION['physician_id'];
                $to_id_cate= 'physician';
            }

            $limited = "LIMIT $msg_num_item_list";
            $limit_msg = "AND private_messages.message_id < $last_send_msg_id";
            //$limit_msg = "AND private_messages.message_id ";

            $activity = $message_tag_TBS->sent_messages($to_id, $to_id_cate, $limited, $limit_msg);
            $msg_id = $activity[$msg_num_item_list-1]["message_id"];

            foreach ($activity as &$value) {
                $value['message'] = str_replace("\r\n",'',$value['message'] );
            }


            $template    =  "views/message_layout_more.html";
            
            //Go to home page

            $TBS 		= new clsTinyButStrong;
            $TBS->NoErr 	= true;

            $TBS->LoadTemplate("$template");

            $TBS->Render 	= TBS_OUTPUT;


            
               
            
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
        
            $TBS->MergeBlock('activity',$activity);
            $TBS->Show();
            @mysql_close();
            die();
            
    }

?>
