<?php

/*
 * Company:    Tokyo Boeki Techno-System de Mexico S.A. de C.V. 
 * Programmer: Alejandro Aguayo Acosta
 */

/**
 * Description of personService:
 *          Service for the table "User"
 *
 * @author alejandro aguayo
 */
class UserService {
    
    static $echoQuery = false;
    
    //get all
    static function getAll( $link ){
        $query = " SELECT "
                    . " id, "
                    . " user, "
                    . " password, "
                    . " rol "
                . "FROM"
                    . " user "
                . "ORDER BY "
                    . "user";
        if( UserService::$echoQuery ){ echo "<br>getAll -> Query: ".$query; }
        $result = mysqli_query( $link , $query );
        return UserService::getArrayByResult( $result );
    }
    
    //get by ID
    static function getById( $link , $id ){
        $query = " SELECT "
                    . " id, "
                    . " user, "
                    . " password, "
                    . " rol "
                . "FROM"
                    . " user "
                . " WHERE "
                    . " id=$id";
        if( UserService::$echoQuery ){ echo "<br>getById -> Query: ".$query; }
        $result = mysqli_query( $link , $query );
        $row = mysqli_fetch_row( $result );
        return UserService::getElementByRow( $row );
    }
    
    //post new Element
    static function save( $link , $newElement ){
        $saveOrUpdate = "";
        if( $newElement->id == "--" ){
            $saveOrUpdate = "INSERT";
            $query = "INSERT INTO "
                        . " user ("
                            . " user, "
                            . " password, "
                            . " rol "
                        . ")"
                    . " VALUES("
                        . "'$newElement->user',"
                        . "'$newElement->password',"
                        . "'$newElement->rol'"
                    . ")";
        }else{
            $saveOrUpdate = "UPDATE";
            $query = "UPDATE "
                        . " user "
                    . " SET "
                        . " user='$newElement->user', "
                        . " password='$newElement->password', "
                        . " rol='$newElement->rol' "
                    . " WHERE "
                        . "id=$newElement->id";
        }
        if( UserService::$echoQuery ){ echo "<br>save -> Query: ".$query; }
        mysqli_query( $link , $query );
        $query = "SELECT id FROM user WHERE user='$newElement->user'";
        $result = mysqli_query( $link , $query );
        $row = mysqli_fetch_row( $result );
        
        return $saveOrUpdate . " " . $row[0];
    }
    
    //Delete Element
    static function delete( $link , $id ){
        $query = "DELETE FROM "
                    . " user "
                . " WHERE "
                    . "id=$id";
        if( UserService::$echoQuery ){ echo "<br>delete -> Query: ".$query; }
        mysqli_query( $link , $query );
        return "DELETE " . $id;
    }

    //transform a $row into a $object
    static function getElementByRow( $row ){
        $newRow = new UserModel( 
                $row[0],
                $row[1],
                $row[2],
                $row[3]
                );
        return $newRow;
        
    }
    
    //transform a $result into a $arrayObject
    static function getArrayByResult( $result ){
        $arrayResult = new ArrayObject();
        while( $row = mysqli_fetch_row( $result ) ){
            $newRow = UserService::getElementByRow( $row );
            $arrayResult->append( $newRow );
        }
        return $arrayResult;
    }
}