<?php
    header( 'Access-Control-Allow-Origin: *' );
    header( 'Access-Control-Allow-Methods: POST, GET, DELETE, PUT' );
    header( 'Access-Control-Allow-Headers: X-Requested-With, Content-Type, Origin, Authorization,Accept, Client-Security-Token, Accept-Encoding, X-Auth-Token, content-type' );
    
/* 
 * Company:    Tokyo Boeki Techno-System de Mexico S.A. de C.V. 
 * Programmer: Alejandro Aguayo Acosta
 * 
 * Description:
 *              This "Resource" or "Rest Service" will prepare the Models, constants and services required according to 
 *          the request.
 * 
 *      Method GET:
 *              -no param -> getAll()
 *              -id -> getById()
 *      Method POST:
 */
    
//Prepare the connection and Security Filter
if( isset( $_GET[ "user" ] ) 
        && isset( $_GET[ "token" ] )
        ){
    //get the USER and TOKEN
    $user = $_GET[ "user" ];
    $token = $_GET[ "token" ];

    //prepare the connection
    importPhpFiles();
    $link = getLink();

    //Security filter
    if( Security::checkUserAndToken( $link , $user, $token ) ){
        
        $rol = Security::getRolByUser( $link , $user);
        
        //GET
        if( $_SERVER["REQUEST_METHOD"] === "GET" ){ 
            //Util::insertBitacore( $link , $user, "Person GET request" );
            returnDataGET( $link ); 
        }
        
        //POST
        if( $_SERVER["REQUEST_METHOD"] === "POST" ){ 
            //Util::insertBitacore( $link , $user, "Person POST request" );
            returnDataPOST( $link , $rol ); 
        }
        
        //DELETE
        if( $_SERVER["REQUEST_METHOD"] === "DELETE" ){ 
           // Util::insertBitacore( $link , $user, "Person DELETE request" );
            returnDataDELETE( $link , $rol ); 
        }
        mysqli_close( $link );
        
    //"user / token" are incorrect    
    }else{
        //Util::insertBitacore( $link , $user, "try to access but user/token not match" );
        returnError();
        mysqli_close( $link );
    }

//the requester didn't send "user / token"    
}else{
    require "../../util/Constants.php";
    require "../../util/Util.php";
    $link = getLink();
    //Util::insertBitacore( $link , "Anonimous" , "try to access but not send user/token" );
    mysqli_close( $link );
    returnError();
}
    
//import all the PHP files needed
function importPhpFiles(){
    //Constants and security
    require "../../util/Constants.php";
    require "../../util/Security.php";
    require "../../util/Util.php";
    //models
    require "../../models/BuroDeCreditoModel.php";
    //services
    require "../../services/BuroDeCreditoService.php";
}

//get the $link for the use of the SERVICES
function getLink(){
    $link = mysqli_connect( Constants::$host , Constants::$user , Constants::$pass, Constants::$dataBase );
    return $link;
}

//if all the security filters are pass
function returnDataDELETE( $link , $rol ){
    if( ($rol == "admin") || ($rol == "user") ){
        $id = $_GET[ "id" ];
        echo BuroDeCreditoService::delete( $link , $id );
    }else{
        returnError();
    }
}

//if all the security filters are pass
function returnDataPOST( $link , $rol ){
    if( ($rol == "admin") || ($rol == "user") ){
        //get the data in the json
        $datos = json_decode(file_get_contents('php://input'),true);
        $newElement = new BuroDeCreditoModel(
                $datos["id"],
                $datos["idAsociado"],
                $datos["idDeudor"],
                $datos["monto"],
                $datos["fecha"],
                "",
                "",
                "",
                ""
                );
        $returnInfo = BuroDeCreditoService::save( $link , $newElement );
        echo $returnInfo;
    }else{
        returnError();
    }
}

//if all the security filters are pass
function returnDataGET( $link ){
    if( isset( $_GET[ "id" ] ) ){
        $id = $_GET[ "id" ];
        echo json_encode( BuroDeCreditoService::getById( $link , $id ) );
    }else{
        echo json_encode( BuroDeCreditoService::getAll( $link ) );
    }
}

//
function returnError(){
    echo Constants::$noAccess;
}