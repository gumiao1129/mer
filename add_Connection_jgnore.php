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


if(isset($_SESSION['patient_id']))
{
       $patient_id=$_SESSION['patient_id'];
       if(isset($_POST["add_cate"])&&isset($_POST["add_id"]))
       {
           $add_cate=$_POST["add_cate"];
           $add_id = $_POST["add_id"];
           
           if($add_cate=="physician")
           {
               
                $model = new CI_model();
                $table = "physician_patient_relationships";
                
                $check_columns = "patient_id_one, physician_id_one";
                $check_content = "patient_id_one = '$patient_id' AND physician_id_one = '$add_id'";
                $result = $model->dbSelect($table, $check_columns, $check_content);

                if($result!=null)
                {
                    $deleteContent = "patient_id_one = '$patient_id' AND physician_id_one = '$add_id'";
                    $model->dbDelete($table, $deleteContent);
                    
                    $check_content = "patient_id_one = '$patient_id' AND physician_id_one = '$add_id'";
                    $result = $model->dbSelect($table, $check_columns, $check_content);
                    if($result == null)
                    {
                        echo "confirmed";
                    }
                    else
                    {
                        echo "try_again";
                    }                   
                }
                else
                {
                    echo "try_again";
                }
           }
           else if($add_cate=="patient")
           {
                $model = new CI_model();
                $table = "patient_patient_relationships";
                $check_columns = "patient_id_one, patient_id_two";
                $check_content = "((patient_id_one = '$patient_id' AND patient_id_two = '$add_id') OR (patient_id_one = '$add_id'  AND patient_id_two = '$patient_id'))";
                $result = $model->dbSelect($table, $check_columns, $check_content);
                if($result!=null)
                {
                    $deleteContent = "((patient_id_one = '$patient_id' AND patient_id_two = '$add_id') OR (patient_id_one = '$add_id'  AND patient_id_two = '$patient_id'))";
                    $model->dbDelete($table, $deleteContent);
                    
                    $check_content = "((patient_id_one = '$patient_id' AND patient_id_two = '$add_id') OR (patient_id_one = '$add_id'  AND patient_id_two = '$patient_id'))";
                    $result = $model->dbSelect($table, $check_columns, $check_content);
                    if($result == null)
                    {
                        echo "confirmed";
                    }
                    else
                    {
                        echo "try_again";
                    }   
                }
                else
                {
                    echo "try_again";                    
                }
               
           }
           else
           {
               echo "try_again";
           }
           
       }
           
           
    
}

    if(isset($_SESSION['physician_id']))
    {
       $physician_id=$_SESSION['physician_id'];
       if(isset($_POST["add_cate"])&&isset($_POST["add_id"]))
       {
           $add_cate=$_POST["add_cate"];
           $add_id = $_POST["add_id"];
           
           if($add_cate=="patient")
           {
                $model = new CI_model();
                $table = "physician_patient_relationships";
                
                $check_columns = "patient_id_one, physician_id_one";
                $check_content = "physician_id_one = '$physician_id' AND patient_id_one = '$add_id'";
                $result = $model->dbSelect($table, $check_columns, $check_content);

                if($result!=null)
                {
                    $deleteContent = "physician_id_one = '$physician_id' AND patient_id_one = '$add_id'";
                    $model->dbDelete($table, $deleteContent);
                    
                    $check_content = "physician_id_one = '$physician_id' AND patient_id_one = '$add_id'";
                    $result = $model->dbSelect($table, $check_columns, $check_content);
                    if($result == null)
                    {
                        echo "confirmed";
                    }
                    else
                    {
                        echo "try_again";
                    }
                    
                    echo "try_again";
                }
                else
                {
                    echo "try_again";
                }
           }
           else if($add_cate=="physician")
           {
                $model = new CI_model();
                $table = "physician_physician_relationships";
                $check_columns = "physician_id_one, physician_id_two";
                $check_content = "((physician_id_one = '$physician_id' AND physician_id_two = '$add_id') OR (physician_id_one = '$add_id'  AND physician_id_two = '$physician_id'))";
                $result = $model->dbSelect($table, $check_columns, $check_content);
                if($result!=null)
                {
                    $deleteContent = "((physician_id_one = '$physician_id' AND physician_id_two = '$add_id') OR (physician_id_one = '$add_id'  AND physician_id_two = '$physician_id'))";
                    $model->dbDelete($table, $deleteContent);
                    
                    $check_content = "((physician_id_one = '$physician_id' AND physician_id_two = '$add_id') OR (physician_id_one = '$add_id'  AND physician_id_two = '$physician_id'))";
                    $result = $model->dbSelect($table, $check_columns, $check_content);
                    if($result == null)
                    {
                        echo "confirmed";
                    }
                    else
                    {
                        echo "try_again";
                    }
                    
                    
                }
                else
                {
                    echo "try_again";
                }
               
           }
           else
           {
               echo "try_again";
           }
           
       }
           
    }
?>
