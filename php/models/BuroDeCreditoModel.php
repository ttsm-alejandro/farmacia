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
    
    //constructor
    function BuroDeCreditoModel(
            $id,
            $idAsociado,
            $idDeudor,
            $monto,
            $fecha
            ){
        $this->id = $id;
        $this->idAsociado = $idAsociado;
        $this->idDeudor = $idDeudor;
        $this->monto = $monto;
        $this->fecha = $fecha;
    }
}