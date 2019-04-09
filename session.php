<?php
/**
 * Created by PhpStorm.
 * User: msantolo
 * Date: 15/10/2018
 * Time: 16:11
 */

   session_start();

   if(!isset($_SESSION['user_id'])){
       header("location:index.php");
   }
