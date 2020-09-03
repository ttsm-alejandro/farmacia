<?php

/*
 * Company:    Tokyo Boeki Techno-System de Mexico S.A. de C.V. 
 * Programmer: Alejandro Aguayo Acosta
 */

/**
 * Description of Security
 *
 * @author alejandro aguayo
 */
class Security {
    
    //check if "user" and "token" match in user table, for security reasons...
    static function checkUserAndToken( $link , $user , $token ){
        $return = false;
        $query = "SELECT user,token FROM user";
        $result = mysqli_query( $link , $query );
        while( $row = mysqli_fetch_row( $result ) ){
            if( ($row[0] == $user) && ($row[1] == $token ) ){
                $return = true;
            }
        }
        return $return;
    }
}
