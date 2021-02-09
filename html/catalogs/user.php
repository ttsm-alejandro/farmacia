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
        
        <title>Usuarios</title>
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
        <script src="../../js/catalogs/userCtrl.js"></script>
    </head>
    
    <body ng-app="miApp" >
        <div class="container" ng-controller="userCtrl" 
             ng-init="
                 
                 user='<?php echo $_SESSION["user"]; ?>';
                 token='<?php echo $_SESSION["token"]; ?>';
                 rol='<?php echo $_SESSION["rol"]; ?>';
                 <?php 
                    if( isset( $_GET[ "id" ] ) ){
                        if( !($_GET["id"] == "--") ){
                            echo "getDataById(" . $_GET[ "id" ] .  ");";
                        }
                    }else{
                        echo "getData();";
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
                    Catalogo de Usuarios.
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
                            <input class="form-control" ng-model="filterUserName">
                            <span ng-click="filterUserName = ''" class="input-group-addon"><i class="glyphicon glyphicon-repeat"></i></span>
                        </div>
                        
                        <!-- LISTA FILTRADA -->
                        <div style="overflow: scroll; max-height: {{ userScreenHeight }};">
                            <table class="table table-hover">
                                <tr>
                                    <th>#</th>
                                    <th>Usuario</th>
                                </tr>
                                <tr ng-repeat="row in tableContent | filter : { user : filterUserName } " 
                                    ng-click="getDetails( row )"
                                    style="{{ isRowSelected(row) }}"
                                    >
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ row.user }}</td>
                                </tr>
                            </table>
                        </div>
                        <button class="btn btn-success" ng-click="newRow()">Nuevo</button> 
                    </div>
                
                <!-- *B End : -->
                <?php } ?>
                 
                <div class="col-lg-9">
                    <h4>DETALLES</h4>
                    <table class="table table-striped">
                        <tr><th>ID:</th><td>{{ details.id }}</td></tr>
                        <tr><th>Nombre de Usuario:</th><td><input ng-model="details.user" class="form-control"></td></tr>
                        <tr><th>Contraseña:</th><td><input ng-model="details.password" class="form-control"></td></tr>
                        <tr><th>Rol:</th><td>
                                <select ng-model="details.rol" ng-options=" x.name as x.name for x in rolCatalog " class="form-control"></select>
                    </table>
                    <button class="btn btn-info" ng-click="updateOrSaveRow()" >Save</button>
                    
                    <!-- *C Begin: PHP line for block elements when "?id=" is present in the URL -->
                    <?php if( !isset( $_GET[ "id" ] ) ){ ?>
                    <button class="btn btn-danger" ng-click="deleteRow()">Delete</button>
                    <!-- *C Begin: PHP line for block elements when "?id=" is present in the URL -->
                    <?php } ?>
                    
                    <!-- *D Begin: PHP line for block elements when "?id=" is present in the URL -->
                    <?php if( isset( $_GET[ "id" ] ) ){ ?>
                    <button class="btn btn-danger" ng-click="closeThisWindow()">Close</button>
                    <!-- *D Begin: PHP line for block elements when "?id=" is present in the URL -->
                    <?php } ?>
                    
                </div>
            </div>
        </div>
    </body>
</html>