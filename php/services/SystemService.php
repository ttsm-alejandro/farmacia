<?php

/*
 * Company:    Tokyo Boeki Techno-System de Mexico S.A. de C.V. 
 * Programmer: Alejandro Aguayo Acosta
 */

/**
 * Description of personService:
 *          Service for the table "Person"
 *
 * @author alejandro aguayo
 */
class SystemService {
    //get all
    static function getAll( $link ){
        $query = " SELECT "
                    . " id, "
                    . " serial_number, "
                    . " id_company, "
                    . " id_plant, "
                    . " id_department, "
                    . " installation_date, "
                    . " last_calibration_date, "
                    . " next_calibration_date, "
                    . " detail, "
                    . " comment "
                . "FROM"
                    . " system "
                . "ORDER BY "
                    . "serial_number";
        $result = mysqli_query( $link , $query );
        return SystemService::getArrayByResult( $result );
    }
    
    //get by ID
    static function getById( $link , $id ){
        $query = " SELECT "
                    . " id, "
                    . " serial_number, "
                    . " id_company, "
                    . " id_plant, "
                    . " id_department, "
                    . " installation_date, "
                    . " last_calibration_date, "
                    . " next_calibration_date, "
                    . " detail, "
                    . " comment "
                . " FROM "
                    . " system "
                . " WHERE "
                    . " id=$id";
        $result = mysqli_query( $link , $query );
        $row = mysqli_fetch_row( $result );
        return SystemService::getElementByRow( $row );
    }
    
    //post new Element
    static function save( $link , $newElement ){
        $saveOrUpdate = "";
        if( $newElement->id == "--" ){
            $saveOrUpdate = "INSERT";
            $query = "INSERT INTO "
                        . " system ("
                            . " serial_number, "
                            . " id_company, "
                            . " id_plant, "
                            . " id_department, "
                            . " installation_date, "
                            . " last_calibration_date, "
                            . " next_calibration_date, "
                            . " detail, "
                            . " comment "
                        . ")"
                    . " VALUES("
                        . "'$newElement->serialNumber',"
                        . "$newElement->idCompany,"
                        . "$newElement->idPlant,"
                        . "$newElement->idDepartment,"
                        . "'$newElement->installationDate',"
                        . "'$newElement->lastCalibrationDate',"
                        . "'$newElement->nextCalibrationDate',"
                        . "'$newElement->detail',"
                        . "'$newElement->comment'"
                    . ")";
        }else{
            $saveOrUpdate = "UPDATE";
            $query = "UPDATE "
                        . " system "
                    . " SET "
                        . " serial_number='$newElement->serialNumber', "
                        . " id_company=$newElement->idCompany, "
                        . " id_plant=$newElement->idPlant, "
                        . " id_department=$newElement->idDepartment, "
                        . " installation_date='$newElement->installationDate', "
                        . " last_calibration_date='$newElement->lastCalibrationDate', "
                        . " next_calibration_date='$newElement->nextCalibrationDate', "
                        . " detail='$newElement->detail', "
                        . " comment='$newElement->comment' "
                    . " WHERE "
                        . "id=$newElement->id";
        }
        mysqli_query( $link , $query );
        $query = "SELECT id FROM system WHERE serial_number='$newElement->serialNumber'";
        $result = mysqli_query( $link , $query );
        $row = mysqli_fetch_row( $result );
        
        return $saveOrUpdate . " " . $row[0];
    }
    
    //Delete Element
    static function delete( $link , $id ){
        $query = "DELETE FROM "
                    . " system "
                . " WHERE "
                    . "id=$id";
        mysqli_query( $link , $query );
        return "DELETE " . $id;
    }

    //transform a $row into a $object
    static function getElementByRow( $row ){
        $newRow = new SystemModel( 
                $row[0],
                $row[1],
                $row[2],
                $row[3],
                $row[4],
                $row[5],
                $row[6],
                $row[7],
                $row[8],
                $row[9]
                );
        return $newRow;
        
    }
    
    //transform a $result into a $arrayObject
    static function getArrayByResult( $result ){
        $arrayResult = new ArrayObject();
        while( $row = mysqli_fetch_row( $result ) ){
            $newRow = SystemService::getElementByRow( $row );
            $arrayResult->append( $newRow );
        }
        return $arrayResult;
    }
}