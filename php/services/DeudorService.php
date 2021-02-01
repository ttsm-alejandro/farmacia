<?php

/*
 * Company:    Tokyo Boeki Techno-System de Mexico S.A. de C.V. 
 * Programmer: Alejandro Aguayo Acosta
 */

/**
 * Description of personService:
 *          Service for the table "Deudor"
 *
 * @author alejandro aguayo
 */
class DeudorService {
    
    static $echoQuery = false;
    
    //get all
    static function getAll( $link ){
        $query = " SELECT "
                    . " id, "
                    . " razon_social, "
                    . " rfc, "
                    . " direccion, "
                    . " nombre_contacto, "
                    . " telefono "
                . "FROM"
                    . " deudor "
                . "ORDER BY "
                    . "rfc";
        if( DeudorService::$echoQuery ){ echo "<br>getAll -> Query: ".$query; }
        $result = mysqli_query( $link , $query );
        return DeudorService::getArrayByResult( $result );
    }
    
    //get by ID
    static function getById( $link , $id ){
        $query = " SELECT "
                    . " id, "
                    . " razon_social, "
                    . " rfc, "
                    . " direccion, "
                    . " nombre_contacto, "
                    . " telefono "
                . " FROM "
                    . " deudor "
                . " WHERE "
                    . " id=$id";
        if( DeudorService::$echoQuery ){ echo "<br>getById -> Query: ".$query; }
        $result = mysqli_query( $link , $query );
        $row = mysqli_fetch_row( $result );
        return DeudorService::getElementByRow( $row );
    }
    
    //post new Element
    static function save( $link , $newElement ){
        $saveOrUpdate = "";
        if( $newElement->id == "--" ){
            $saveOrUpdate = "INSERT";
            $query = "INSERT INTO "
                        . " deudor ("
                            . " razon_social, "
                            . " rfc, "
                            . " direccion, "
                            . " nombre_contacto, "
                            . " telefono "
                        . ")"
                    . " VALUES("
                        . "'$newElement->razonSocial',"
                        . "'$newElement->rfc',"
                        . "'$newElement->direccion',"
                        . "'$newElement->nombreContacto',"
                        . "'$newElement->telefono'"
                    . ")";
        }else{
            $saveOrUpdate = "UPDATE";
            $query = "UPDATE "
                        . " deudor "
                    . " SET "
                        . " razon_social='$newElement->razonSocial', "
                        . " rfc='$newElement->rfc', "
                        . " direccion='$newElement->direccion', "
                        . " nombre_contacto='$newElement->nombreContacto', "
                        . " telefono='$newElement->telefono' "
                    . " WHERE "
                        . "id=$newElement->id";
        }
        if( DeudorService::$echoQuery ){ echo "<br>save -> Query: ".$query; }
        mysqli_query( $link , $query );
        $query = "SELECT id FROM deudor WHERE rfc='$newElement->rfc'";
        $result = mysqli_query( $link , $query );
        $row = mysqli_fetch_row( $result );
        
        return $saveOrUpdate . " " . $row[0];
    }
    
    //Delete Element
    static function delete( $link , $id ){
        $query = "DELETE FROM "
                    . " deudor "
                . " WHERE "
                    . "id=$id";
        if( DeudorService::$echoQuery ){ echo "<br>delete -> Query: ".$query; }
        mysqli_query( $link , $query );
        return "DELETE " . $id;
    }

    //transform a $row into a $object
    static function getElementByRow( $row ){
        $newRow = new DeudorModel( 
                $row[0],
                $row[1],
                $row[2],
                $row[3],
                $row[4],
                $row[5]
                );
        return $newRow;
        
    }
    
    //transform a $result into a $arrayObject
    static function getArrayByResult( $result ){
        $arrayResult = new ArrayObject();
        while( $row = mysqli_fetch_row( $result ) ){
            $newRow = DeudorService::getElementByRow( $row );
            $arrayResult->append( $newRow );
        }
        return $arrayResult;
    }
}