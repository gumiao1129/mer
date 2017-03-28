<?php
session_start();
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

$activity_dr = "";
$activity_pa = "";

if(isset($_GET["st_id"]) && $_GET["st_id"]!=null && $_GET["st_id"]!=0)
{
        $_SESSION["st_id"] = $_GET["st_id"];
        $st_category_id = $_GET["st_id"];
                    if(isset($_SESSION['physician_id']))
                    {
                        $template 	= "views/patient_recommandation_list.html";
                        $physician_id = $_SESSION['physician_id'];

                            //patient gadget info
                            $pa_info_TBS = new CI_model_search_engine();

                                 $pa_search_table1 = "patient";
                                    $pa_search_table2 = "physician_patient_relationships";
                                    $pa_search_table3 = "st_physician_recommand_to_patient";
                                    $pa_search_table_session_id_field = "physician_id_one";
                                    $pa_search_table_left_join_id_field = "patient_id_one";
                                    $pa_session_id = $physician_id;
                                    $pa_id_field = "patient_id";
                                    $other = " AND physician_patient_relationships.status=1";
                                    $activity_pa = $pa_info_TBS->search_recommendation_list($pa_search_table1, $pa_search_table2, $pa_search_table3, $st_category_id, $pa_search_table_left_join_id_field, $pa_id_field, $pa_session_id,$pa_search_table_session_id_field, $other); 
                    }
//Go to home page
            //$template 		= "views/view_message_detail.html";
            $TBS 			= new clsTinyButStrong;
            $TBS->NoErr 	= true;
            $TBS->LoadTemplate("$template");
            
            $TBS->Render 	= TBS_OUTPUT;
            //$TBS->MergeBlock('activity_dr',$activity_dr);
            $TBS->MergeBlock('activity_pa',$activity_pa);
            
            $TBS->Show();     
            
                                    
            //Event functions
       function add_recommendation_tag($BlockName,&$CurrRec,$RecNum){
        //$BlockName : name of the block that calls the function (read only)
        //$CurrRec   : array that contains columns of the current record (read/write)
        //$RecNum    : number of the current record (read only)
        if(isset($_SESSION['physician_id']))
        {
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
       }
            
            @mysql_close();
            die();
}
?>
