<?php

/*
 * Company:    Tokyo Boeki Techno-System de Mexico S.A. de C.V. 
 * Programmer: Alejandro Aguayo Acosta
 */

/**
 * Description of personService:
 *          Service for the table "Asociado"
 *
 * @author alejandro aguayo
 */
class AsociadoService {
    
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
                    . " asociado "
                . "ORDER BY "
                    . "rfc";
        if( AsociadoService::$echoQuery ){ echo "<br>getAll -> Query: ".$query; }
        $result = mysqli_query( $link , $query );
        return AsociadoService::getArrayByResult( $result );
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
                    . " asociado "
                . " WHERE "
                    . " id=$id";
        if( AsociadoService::$echoQuery ){ echo "<br>getById -> Query: ".$query; }
        $result = mysqli_query( $link , $query );
        $row = mysqli_fetch_row( $result );
        return AsociadoService::getElementByRow( $row );
    }
    
    //post new Element
    static function save( $link , $newElement ){
        $saveOrUpdate = "";
        
        $query = "SELECT count(id) FROM asociado WHERE rfc='$newElement->rfc'";
        $rowCountByRFC = mysqli_fetch_row( mysqli_query( $link, $query ) )[0];
        
        if( $newElement->id == "--" ){
            if( $rowCountByRFC == 0 ){
                $saveOrUpdate = "INSERT";
                $query = "INSERT INTO "
                            . " asociado ("
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
                $saveOrUpdate = "ALREADYEXIST";
            }
        }else{
            $saveOrUpdate = "UPDATE";
            $query = "UPDATE "
                        . " asociado "
                    . " SET "
                        . " razon_social='$newElement->razonSocial', "
                        . " rfc='$newElement->rfc', "
                        . " direccion='$newElement->direccion', "
                        . " nombre_contacto='$newElement->nombreContacto', "
                        . " telefono='$newElement->telefono' "
                    . " WHERE "
                        . "id=$newElement->id";
        }
        
        
        if( AsociadoService::$echoQuery ){ echo "<br>save -> Query: ".$query; }
        mysqli_query( $link , $query );
        $query = "SELECT id FROM asociado WHERE rfc='$newElement->rfc'";
        $result = mysqli_query( $link , $query );
        $row = mysqli_fetch_row( $result );
        
        return $saveOrUpdate . " " . $row[0];
    }
    
    //Delete Element
    static function delete( $link , $id ){
        $query = "DELETE FROM "
                    . " asociado "
                . " WHERE "
                    . "id=$id";
        if( AsociadoService::$echoQuery ){ echo "<br>delete -> Query: ".$query; }
        mysqli_query( $link , $query );
        return "DELETE " . $id;
    }

    //transform a $row into a $object
    static function getElementByRow( $row ){
        $newRow = new AsociadoModel( 
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
            $newRow = AsociadoService::getElementByRow( $row );
            $arrayResult->append( $newRow );
        }
        return $arrayResult;
    }
}