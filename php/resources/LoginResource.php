<?php

/* 
 * Company:    Tokyo Boeki Techno-System de Mexico S.A. de C.V. 
 * Programmer: Alejandro Aguayo Acosta
 */
    //must be send the USER and PASS via POST
    if( isset( $_POST[ "user" ] ) && isset( $_POST[ "pass" ] ) ){
        $user = $_POST[ "user" ];
        $pass = $_POST[ "pass" ];
        
        //connect to database, check if "user" and "pass" match
        session_start();
        require "../util/Constants.php";
        
        //
        if( checkUser( $user , $pass ) ){
            header( "Location: ../../main_page.php" );
        }else{
            header( "Location: ../../index.php?invalid=1" );
        }
    }else{
        header( "Location: ../../index.php" );
    }
    
    
    //connect to database, check if "user" and "pass" match -> set "token" in FIELD and the $_SESSIONS
    function checkUser( $user , $pass ){
        $return = FALSE;
        $link = mysqli_connect( Constants::$host , Constants::$user, Constants::$pass, Constants::$dataBase );
        $query = " SELECT "
                    . " user,"
                    . " password, "
                    . " rol "
                . " FROM "
                    . " user ";
        $result = mysqli_query( $link , $query );
        while( $row = mysqli_fetch_row( $result ) ){
            if( $user == $row[ 0 ]
                    && $pass == $row[ 1 ] 
                    ){
                $token = insertToken( $row[ 0 ] , $link );
                //set the $_SESSION array
                $_SESSION[ "user" ] = $user;
                $_SESSION[ "rol" ] = $row[ 2 ];
                $_SESSION[ "token" ] = $token;
                
                $return = TRUE;
            }
        }
        return $return;
    }
    
    //function
    function insertToken( $user , $link ){
        $token = getToken();
        $query = "UPDATE user SET token='$token' WHERE user='$user'";
        mysqli_query( $link , $query );
        return $token;
    }
    
    //define the token as current YYYY/MM/DD-HH:MM
    function getToken(){
        $token = getdate();
        //token = YYYY/MM/DD-HH:MM
        $token = $token[ year ] . "/" .$token[ month ] . "/" .$token[ mday ] 
                . "-"
                .$token[ hours ] . ":" . $token[ minutes ];
        return $token;
    }