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
        
        <title>Clientes / Deudores</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="icon" href="../../images/util/logo.png">
        
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
        <script src="../../js/catalogs/deudorCtrl.js"></script>
    </head>
    
    <body ng-app="miApp" >
        <div class="container" ng-controller="deudorCtrl" 
             ng-init="
                 
                 user='<?php echo $_SESSION["user"]; ?>';
                 token='<?php echo $_SESSION["token"]; ?>';
                 rol='<?php echo $_SESSION["rol"]; ?>';
                 getData();
                 <?php 
                    if( isset( $_GET[ "id" ] ) ){
                        if( !($_GET["id"] == "--") ){
                            echo "getDataById(" . $_GET[ "id" ] .  ");";
                        }
                    }
                 ?>
                                           ">
            <!-- *A Begin : PHP line for block elements when "?id=" is present in the URL -->
            <?php if( !isset( $_GET[ "id" ] ) ){ ?>            
                <!-- Header -->
                <div ng-include="'../util/header.html'"></div>
                
                <!-- MODALS -->
                <div ng-include="'../util/modal/modal_loading.html'"></div>
                
                <!-- Title -->
                <h2>
                    Catalogo de Clientes/Deudores.
                </h2>
            <!-- *A End : -->
            <?php } ?>
            
            <!-- LEYENDA POR FAVOR ESPERE, MOSTRAR CUANDO SE ESPERA RESPUESTA DEL SERVIDOR  -->
            <div class="row" style="text-align: center" ng-show="isWaitingServerResponse">
                <h1>POR FAVOR ESPERE...</h1>
            </div>
            
            <!-- CONTENIDO DE LA PAGINA, MOSTRAR SI NO SE ESTA ESPERANDO RESPUESTA DEL SERVIDOR -->
            <div class="row" ng-show="!isWaitingServerResponse">

                <!-- *B Begin: PHP line for block elements when "?id=" is present in the URL -->
                <?php if( !isset( $_GET[ "id" ] ) ){ ?>
                
                    <!-- LISTA LATERAL IZQUIERDA-->
                    <div class="col-lg-3">
                        <!-- FILTROS -->
                        <div class="input-group">
                            <!--select ng-change="filterPlant = ''" ng-model="filterCompany" ng-options=" x.id as x.shortName for x in companyCatalog" class="form-control"></select>
                            <select ng-model="filterPlant" ng-options=" x.id as x.name for x in plantCatalog | filter : { idCompany : filterCompany } : true" class="form-control"></select-->
                            <input class="form-control" ng-model="filterDeudorRFC">
                            <span ng-click="filterDeudorRFC = ''" class="input-group-addon"><i class="glyphicon glyphicon-repeat"></i></span>
                        </div>
                        
                        <!-- LISTA FILTRADA -->
                        <div style="overflow: scroll; max-height: {{ userScreenHeight }};">
                            <table class="table table-hover">
                                <tr>
                                    <th>#</th>
                                    <th>RFC</th>
                                </tr>
                                <tr ng-repeat="row in tableContent | filter : { rfc : filterDeudorRFC } " 
                                    ng-click="getDetails( row )"
                                    style="{{ isRowSelected(row) }}"
                                    >
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ row.rfc }}</td>
                                </tr>
                            </table>
                        </div>
                        <button class="btn btn-success" ng-click="newRow()">Nuevo</button> 
                    </div>
                
                <!-- *B End : -->
                <?php } ?>
                 
                <div class="col-lg-9">
                    <h4>DETALLES DEL CLIENTE / DEUDOR</h4>
                    <table class="table table-striped">
                        <tr><th>ID:</th><td>{{ details.id }}</td></tr>
                        <tr><th>RFC:</th><td><input ng-model="details.rfc" class="form-control"></td></tr>
                        <tr><th>Razon Social:</th><td><input ng-model="details.razonSocial" class="form-control"></td></tr>
                        <tr><th>Direccion:</th><td><textarea rows="5" ng-model="details.direccion" class="form-control"></textarea></td></tr>
                        <tr><th>Nombre del Contacto:</th><td><input ng-model="details.nombreContacto" class="form-control"></td></tr>
                        <tr><th>Telefono:</th><td><input ng-model="details.telefono" class="form-control"></td></tr>
                    </table>
                    <button class="btn btn-success" ng-click="updateOrSaveRow( true )" >Guardar y Nuevo</button>
                    <button class="btn btn-info" ng-click="updateOrSaveRow( false )" >Guardar</button>
                    
                    <!-- *C Begin: PHP line for block elements when "?id=" is present in the URL -->
                    <?php if( !isset( $_GET[ "id" ] ) ){ ?>
                    <button class="btn btn-danger" ng-click="deleteRow()">Borrar</button>
                    <!-- *C Begin: PHP line for block elements when "?id=" is present in the URL -->
                    <?php } ?>
                    
                    <!-- *D Begin: PHP line for block elements when "?id=" is present in the URL -->
                    <?php if( isset( $_GET[ "id" ] ) ){ ?>
                    <button class="btn btn-danger" ng-click="closeThisWindow()">Cerrar</button>
                    <!-- *D Begin: PHP line for block elements when "?id=" is present in the URL -->
                    <?php } ?>
                </div>
            </div>
        </div>
    </body>
</html>