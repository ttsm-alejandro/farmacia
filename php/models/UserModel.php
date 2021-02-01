<?php

/*
 * Company:    Tokyo Boeki Techno-System de Mexico S.A. de C.V. 
 * Programmer: Alejandro Aguayo Acosta
 */

/**
 * Model of the table User
 *
 * @author alejandro aguayo
 */
class UserModel {
    //variables
    var $id;
    var $user;
    var $password;
    var $rol;
    
    //constructor
    function UserModel(
            $id,
            $user,
            $password,
            $rol
            ){
        $this->id = $id;
        $this->user = $user;
        $this->password = $password;
        $this->rol = $rol;
    }
}