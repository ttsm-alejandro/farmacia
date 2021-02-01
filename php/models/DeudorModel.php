<?php

/*
 * Company:    Tokyo Boeki Techno-System de Mexico S.A. de C.V. 
 * Programmer: Alejandro Aguayo Acosta
 */

/**
 * Model of the table Deudor
 *
 * @author alejandro aguayo
 */
class DeudorModel {
    //variables
    var $id;
    var $razonSocial;
    var $rfc;
    var $direccion;
    var $nombreContacto;
    var $telefono;
    
    //constructor
    function DeudorModel(
            $id,
            $razonSocial,
            $rfc,
            $direccion,
            $nombreContacto,
            $telefono
            ){
        $this->id = $id;
        $this->razonSocial = $razonSocial;
        $this->rfc = $rfc;
        $this->direccion = $direccion;
        $this->nombreContacto = $nombreContacto;
        $this->telefono = $telefono;
    }
}