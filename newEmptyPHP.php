<?php
include_once('classes/random_code.php');
            $characters         = 15;
            $random_code	= new random_code($characters);
            $generateCode       = $random_code->generateCode();
            echo $generateCode;
?>
