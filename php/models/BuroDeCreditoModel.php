<?php

/*
 * Company:    Tokyo Boeki Techno-System de Mexico S.A. de C.V. 
 * Programmer: Alejandro Aguayo Acosta
 */

/**
 * Model of the table BuroDeCredito
 *
 * @author alejandro aguayo
 */
class BuroDeCreditoModel {
    //variables
    var $id;
    var $idAsociado;
    var $idDeudor;
    var $monto;
    var $fecha;
    
    //extra data
    var $rfcAsociado;
    var $rfcDeudor;
    var $razonSocialAsociado;
    var $razonSocialDeudor;
    
    //constructor
    function BuroDeCreditoModel(
            $id,
            $idAsociado,
            $idDeudor,
            $monto,
            $fecha,
            $rfcAsociado,
            $rfcDeudor,
            $razonSocialAsociado,
            $razonSocialDeudor
            ){
        $this->id = $id;
        $this->idAsociado = $idAsociado;
        $this->idDeudor = $idDeudor;
        $this->monto = $monto;
        $this->fecha = $fecha;
        $this->rfcAsociado = $rfcAsociado;
        $this->rfcDeudor = $rfcDeudor;
        $this->razonSocialAsociado = $razonSocialAsociado;
        $this->razonSocialDeudor = $razonSocialDeudor;
    }
}