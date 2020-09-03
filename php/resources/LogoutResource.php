<?php

/* 
 * Company:    Tokyo Boeki Techno-System de Mexico S.A. de C.V. 
 * Programmer: Alejandro Aguayo Acosta
 */

session_start();
cleanToken();
session_destroy();
header( "Location: ../../index.php" );


function cleanToken(){
    $user = $_SESSION[ "user" ];
    require "../util/Constants.php";
    $link = mysqli_connect( Constants::$host , Constants::$user , Constants::$pass, Constants::$dataBase );
    $query = "UPDATE user SET token='' WHERE user='$user'";
    mysqli_query( $link , $query );
}