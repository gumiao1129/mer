<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of CI_model_join
 *
 * @author Gu Miao
 */
include_once('models/CI_model.php'); 
class CI_model_join extends CI_model
{
    
    public function dbSelectJoin($table, $attributes, $otherReq)
    {
        try
        {
            if($otherReq==null)
            {
                $statement =  $this->_dbh->prepare("SELECT $attributes FROM $table");
                $statement->execute();    
            }
            else
            {
                $statement =  $this->_dbh->prepare("SELECT $attributes FROM $table WHERE $otherReq");
                $statement->execute();
            }    
            $row = $statement->fetch();
            return $row;
        }
        catch (PDOException $e)
        {
          echo $e->getMessage();
        }
    }
}

?>
