<?php

/*
 * Company:    Tokyo Boeki Techno-System de Mexico S.A. de C.V. 
 * Programmer: Alejandro Aguayo Acosta
 */

/**
 * Description of personService:
 *          Service for the table "BuroDeCredito"
 *
 * @author alejandro aguayo
 */
class BuroDeCreditoService {
    
    static $echoQuery = false;
    
    //get all
    static function getAll( $link ){
        $query = " SELECT "
                    . " id, "
                    . " id_asociado, "
                    . " id_deudor, "
                    . " monto, "
                    . " fecha "
                . "FROM"
                    . " buro_de_credito "
                . "ORDER BY "
                    . "id_asociado";
        if( BuroDeCreditoService::$echoQuery ){ echo "<br>getAll -> Query: ".$query; }
        $result = mysqli_query( $link , $query );
        return BuroDeCreditoService::getArrayByResult( $result );
    }
    
    //get by ID
    static function getById( $link , $id ){
        $query = " SELECT "
                    . " id, "
                    . " id_asociado, "
                    . " id_deudor, "
                    . " monto, "
                    . " fecha "
                . " FROM "
                    . " buro_de_credito "
                . " WHERE "
                    . " id=$id";
        if( BuroDeCreditoService::$echoQuery ){ echo "<br>getById -> Query: ".$query; }
        $result = mysqli_query( $link , $query );
        $row = mysqli_fetch_row( $result );
        return BuroDeCreditoService::getElementByRow( $row );
    }
    
    //post new Element
    static function save( $link , $newElement ){
        $saveOrUpdate = "";
        if( $newElement->id == "--" ){
            $saveOrUpdate = "INSERT";
            $query = "INSERT INTO "
                        . " buro_de_credito ("
                            . " id_asociado, "
                            . " id_deudor, "
                            . " monto, "
                            . " fecha "
                        . ")"
                    . " VALUES("
                        . "$newElement->idAsociado,"
                        . "$newElement->idDeudor,"
                        . "$newElement->monto,"
                        . "'$newElement->fecha'"
                    . ")";
        }else{
            $saveOrUpdate = "UPDATE";
            $query = "UPDATE "
                        . " buro_de_credito "
                    . " SET "
                        . " id_asociado=$newElement->idAsociado, "
                        . " id_deudor=$newElement->idDeudor, "
                        . " monto=$newElement->monto, "
                        . " fecha='$newElement->fecha' "
                    . " WHERE "
                        . "id=$newElement->id";
        }
        if( BuroDeCreditoService::$echoQuery ){ echo "<br>save -> Query: ".$query; }
        mysqli_query( $link , $query );
        $query = "SELECT id FROM buro_de_credito WHERE id_asociado=$newElement->idAsociado AND id_deudor=$newElement->idDeudor";
        $result = mysqli_query( $link , $query );
        $row = mysqli_fetch_row( $result );
        
        return $saveOrUpdate . " " . $row[0];
    }
    
    //Delete Element
    static function delete( $link , $id ){
        $query = "DELETE FROM "
                    . " buro_de_credito "
                . " WHERE "
                    . "id=$id";
        if( BuroDeCreditoService::$echoQuery ){ echo "<br>delete -> Query: ".$query; }
        mysqli_query( $link , $query );
        return "DELETE " . $id;
    }

    //transform a $row into a $object
    static function getElementByRow( $row ){
        $newRow = new BuroDeCreditoModel( 
                $row[0],
                $row[1],
                $row[2],
                $row[3],
                $row[4]
                );
        return $newRow;
        
    }
    
    //transform a $result into a $arrayObject
    static function getArrayByResult( $result ){
        $arrayResult = new ArrayObject();
        while( $row = mysqli_fetch_row( $result ) ){
            $newRow = BuroDeCreditoService::getElementByRow( $row );
            $arrayResult->append( $newRow );
        }
        return $arrayResult;
    }
}