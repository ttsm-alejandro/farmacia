<?php

/*
 * Company:    Tokyo Boeki Techno-System de Mexico S.A. de C.V. 
 * Programmer: Alejandro Aguayo Acosta
 */

/**
 * Description of Util
 *
 * @author alejandro aguayo
 */
class Util {
    
    //
    static function insertBitacore( $link , $user, $newContent ){
        $requestAddr = $_SERVER["REMOTE_ADDR"];
        $query = "INSERT INTO systemLog ( user , date , comment ) VALUES( '$user' , (SELECT now()) , '$requestAddr >> $newContent')";
        mysqli_query( $link , $query );
    }
}
