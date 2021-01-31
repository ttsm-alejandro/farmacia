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
        
        
        <title>System</title>
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
        <script src="../../js/catalogs/systemCtrl.js"></script>
    </head>
    
    <body ng-app="miApp" >
        <div class="container" ng-controller="systemCtrl" 
             ng-init="
                 
                 user='<?php echo $_SESSION["user"]; ?>';
                 token='<?php echo $_SESSION["token"]; ?>';
                 <?php 
                    if( isset( $_GET[ "id" ] ) ){
                        if( !($_GET["id"] == "--") ){
                            echo "getDataById(" . $_GET[ "id" ] .  ");";
                        }
                    }else{
                        echo "getData();";
                    } 
                 ?>
                 getCatalogData();
                                           ">
            <!-- *A Begin : PHP line for block elements when "?id=" is present in the URL -->
            <?php if( !isset( $_GET[ "id" ] ) ){ ?>            
                <!-- Header -->
                <div ng-include="'../util/header.html'"></div>
                
                <!-- MODALS -->
                <div ng-include="'../util/modal/modal_loading.html'"></div>
                
                <!-- Title -->
                <h2>
                    System Catalog.
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
                            <input class="form-control" ng-model="filterSystemSerialNumber">
                            <span ng-click="filterSystemSerialNumber = ''" class="input-group-addon"><i class="glyphicon glyphicon-repeat"></i></span>
                        </div>
                        
                        <!-- LISTA FILTRADA -->
                        <div style="overflow: scroll; max-height: {{ userScreenHeight }};">
                            <table class="table table-hover">
                                <tr>
                                    <th>#</th>
                                    <th>Serial Number</th>
                                </tr>
                                <tr ng-repeat="row in tableContent | filter : { serialNumber : filterSystemSerialNumber } " 
                                    ng-click="getDetails( row )"
                                    style="{{ isRowSelected(row) }}"
                                    >
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ row.serialNumber }}</td>
                                </tr>
                            </table>
                        </div>
                        <button class="btn btn-success" ng-click="newRow()">New</button> 
                    </div>
                
                <!-- *B End : -->
                <?php } ?>
                 
                <div class="col-lg-9">
                    <h4>DETALLES</h4>
                    <table class="table table-striped">
                        <tr><th>ID:</th><td>{{ details.id }}</td></tr>
                        <tr><th>Serial Number:</th><td><input ng-model="details.serialNumber" class="form-control"></td></tr>
                        <tr><th>Company:</th><td><select ng-change="details.idPlant = '';details.idDepartment = ''" ng-model="details.idCompany" ng-options=" x.id as x.fullName for x in companyCatalog" class="form-control"></select></td></tr>
                        <tr><th>Plant:</th><td><select ng-disabled="details.idCompany == ''" ng-model="details.idPlant" ng-options=" x.id as x.name for x in plantCatalog | filter : { idCompany : details.idCompany } : true " class="form-control"></select></td></tr>
                        <tr><th>Department:</th><td><select ng-disabled="details.idPlant == ''" ng-model="details.idDepartment" ng-options=" x.id as x.name for x in departmentCatalog | filter : { idCompany : details.idCompany , idPlant : details.idPlant } : true " class="form-control"></select></td></tr>
                        <tr><th>Installation Date:</th><td><input type="date" ng-model="details.installationDate" class="form-control"></td></tr>
                        <tr><th>Last Calibration Date:</th><td><input type="date" ng-model="details.lastCalibrationDate" class="form-control"></td></tr>
                        <tr><th>Next Calibration Date:</th><td><input type="date" ng-model="details.nextCalibrationDate" class="form-control"></td></tr>
                        <tr><th>Details:</th><td><textarea rows="5" ng-model="details.detail" class="form-control"></textarea></td></tr>
                        <tr><th>Comments:</th><td><textarea rows="5" ng-model="details.comment" class="form-control"></textarea></td></tr>
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