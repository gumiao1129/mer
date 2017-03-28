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

if(isset($_SESSION["st_id"]))
{
    $st_id = $_SESSION["st_id"];
    
    if(isset($_SESSION['physician_id']))
    {
        $physician_id = $_SESSION['physician_id'];
        foreach($_POST as $name => $value) { 
            if($_POST["recommend_to_target"] == md5("patient"))
            {
                if(is_numeric($name))
                {
                    $model = new CI_model();
                    $table = "st_physician_recommand_to_patient";

                    $columns = "physician_id, patient_id, st_category_id, date_created, date_confirmed";
                    $content = "'$physician_id', '$name', '$st_id', NOW(), null";
                    $conditional = "physician_id = $physician_id AND patient_id = $name AND st_category_id = $st_id";
                    $model->dbInsert_non_duplicate($table, $columns, $content, $conditional);                             
                }
            }

            if($_POST["recommend_to_target"] == md5("physician"))
            {
                if(is_numeric($name))
                {
                    $model = new CI_model();
                    $table = "st_physician_recommand_to_physician";

                    $columns = "physician_id_one, physician_id_two, st_category_id, date_created, date_confirmed";
                    $content = "'$physician_id', '$name', '$st_id', NOW(), null";
                    $conditional = "physician_id_one = $physician_id AND physician_id_two = $name AND st_category_id = $st_id";
                    $model->dbInsert_non_duplicate($table, $columns, $content, $conditional);
                }
            }
        }  

        echo "success";
    }

    if(isset($_SESSION['patient_id']))
    {
            $patient_id = $_SESSION['patient_id'];
            foreach($_POST as $name => $value) { 
                if($_POST["recommend_to_target"] == md5("patient"))
                {
                    if(is_numeric($name))
                    {
                        $model = new CI_model();
                        $table = "st_patient_recommand_to_patient";

                        $columns = "patient_id_one, patient_id_two, st_category_id, date_created, date_confirmed";
                        $content = "'$patient_id', '$name', '$st_id', NOW(), null";
                        $conditional = "patient_id_one = $patient_id AND patient_id_two = $name AND st_category_id = $st_id";
                        $model->dbInsert_non_duplicate($table, $columns, $content, $conditional);


                    }
                }
            }
     }
     //unset($_SESSION["st_id"]);
}



?>
