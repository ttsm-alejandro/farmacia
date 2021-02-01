<?php
    session_start();
    if( !isset( $_SESSION[ "user" ] ) ){
        header( "Location: ../../index.php" );
        end();
    }
?>
<!DOCTYPE html>
<!--
Company:    Tokyo Boeki Techno-System de Mexico S.A. de C.V. 
Programmer: Alejandro Aguayo Acosta
-->
<html>
    <head>
        
        
        <title>Usuario</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        
        <!-- JQuery -->
        <script src="../../js/lib/jquery-3.2.1.min.js"></script>
        
        <!-- Bootstrap -->
        <link rel="stylesheet" href="../../css/bootstrap.min.css">
        <script src="../../js/lib/bootstrap.min.js"></script>

        <!-- AngularJS libraries -->
        <script src="../../js/lib/angular.min.js"></script>
        
        <!-- SWEET ALERT -->
        <script src="../../js/lib/sweetalert.min.js"></script>

        <!-- Initial configuration, always BEFORE the controller -->
        <script src="../../js/initConfig.js"></script>
        
        <!-- Controller -->
        <script src="../../js/pages/userPageCtrl.js"></script>
    </head>
    
    <body ng-app="miApp" >
        <div class="container" ng-controller="userPageCtrl" 
             ng-init="
                 
                 user='<?php echo $_SESSION["user"]; ?>';
                 token='<?php echo $_SESSION["token"]; ?>';
                                           ">
                <!-- Header -->
                <div ng-include="'../util/header.html'"></div>
                
                <!-- MODALS -->
                <div ng-include="'../util/modal/modal_loading.html'"></div>
                
                <!-- Title -->
                <h2>
                    Informacion del usuario.
                </h2>
            
            <!-- LEYENDA POR FAVOR ESPERE, MOSTRAR CUANDO SE ESPERA RESPUESTA DEL SERVIDOR  -->
            <div class="row" style="text-align: center" ng-show="isWaitingServerResponse">
                <h1>POR FAVOR ESPERE...</h1>
            </div>
            
            <!-- CONTENIDO DE LA PAGINA, MOSTRAR SI NO SE ESTA ESPERANDO RESPUESTA DEL SERVIDOR -->
            <div class="row" ng-show="!isWaitingServerResponse">

                <div class="col-lg-9">
                    <h4>DETALLES</h4>
                    <table class="table table-striped">
                        <tr><th>Nombre de Usuario:</th><td><?php echo $_SESSION["user"]; ?></td></tr>
                    </table>
                    <h4>ACTUALIZAR CONTRASEÑA</h4>
                    <table class="table table-striped">
                        <tr><th>Contraseña actual:</th><td><input type="password" ></td></tr>
                        <tr><th>Nueva contraseña:</th><td><input type="password" ></td></tr>
                        <tr><th>Repetir nueva contraseña:</th><td><input type="password" ></td></tr>
                    </table>
                    <button class="btn btn-info" ng-click="updateOrSave()" >GUARDAR</button>
                </div>
            </div>
        </div>
    </body>
</html>