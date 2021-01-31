<?php

/*
 * Company:    Tokyo Boeki Techno-System de Mexico S.A. de C.V. 
 * Programmer: Alejandro Aguayo Acosta
 */

/**
 * Model of the table System
 *
 * @author alejandro aguayo
 */
class SystemModel {
    //variables
    var $id;
    var $serialNumber;
    var $idCompany;
    var $idPlant;
    var $idDepartment;
    var $installationDate;
    var $lastCalibrationDate;
    var $nextCalibrationDate;
    var $detail;
    var $comment;
    
    //constructor
    function SystemModel(
            $id,
            $serialNumber,
            $idCompany,
            $idPlant,
            $idDepartment,
            $installationDate,
            $lastCalibrationDate,
            $nextCalibrationDate,
            $detail,
            $comment
            ){
        $this->id = $id;
        $this->serialNumber = $serialNumber;
        $this->idCompany = $idCompany;
        $this->idPlant = $idPlant;
        $this->idDepartment = $idDepartment;
        $this->installationDate = $installationDate;
        $this->lastCalibrationDate = $lastCalibrationDate;
        $this->nextCalibrationDate = $nextCalibrationDate;
        $this->detail = $detail;
        $this->comment = $comment;
    }
}