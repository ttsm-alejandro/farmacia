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
        
        <title>Buro de Credito</title>
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
        <script src="../../js/pages/buroDeCreditoCtrl.js"></script>
    </head>
    
    <body ng-app="miApp" >
        <div class="container" ng-controller="buroDeCreditoCtrl" 
             ng-init="
                 
                 user='<?php echo $_SESSION["user"]; ?>';
                 token='<?php echo $_SESSION["token"]; ?>';
                 rol='<?php echo $_SESSION["rol"]; ?>';
                 getData();
                 getCatalogData();
                                           ">
                <!-- Header -->
                <div ng-include="'../util/header.html'"></div>
                
                <!-- MODALS -->
                <div ng-include="'../util/modal/modal_loading.html'"></div>
                
                <!-- Title -->
                <h2 style="text-align: center">
                    Buro de Credito
                </h2>
            
            <!-- LEYENDA POR FAVOR ESPERE, MOSTRAR CUANDO SE ESPERA RESPUESTA DEL SERVIDOR  -->
            <div class="row" style="text-align: center" ng-show="isWaitingServerResponse">
                <h1>POR FAVOR ESPERE...</h1>
            </div>
            
            <!-- PANTALLA CREAR NUEVO REGISTRO -->
            <div ng-show="( showCrearNuevoRegistro ) && ( ( rol == 'admin' ) || ( rol == 'user' )  )">
                <!-- CONTENIDO DE LA PAGINA, MOSTRAR SI NO SE ESTA ESPERANDO RESPUESTA DEL SERVIDOR -->
                <div class="row" ng-show="!isWaitingServerResponse">
                    
                    <!-- LISTA DE ASOCIADOS -->
                    <h3>Seleccione un Asociados</h3>

                    <!-- FILTROS -->
                    <div class="input-group">
                        <span class="input-group-addon">Filtrar Ascociado por RFC</span>
                        <input ng-disabled="isAsociadoFilterDisabled" class="form-control" ng-model="filterAsociadoRFC" title="Filtar lista por RFC">
                        <span ng-click="filterAsociadoRFC = ''; releaseSelectedAsociadoDeudor( true );" class="input-group-addon" title="Clic para quitar filtros"><i class="glyphicon glyphicon-repeat"></i></span>
                    </div>


                    <!-- TABLA - LISTA FILTRADA -->
                    <div style="overflow: scroll; max-height: {{ halfUserScreenHeight }};">
                        <!-- TABLA -->
                        <table class="table table-hover table-condensed">
                            <tr>
                                <th>#</th>
                                <th>RFC</th>
                                <th>Razon Social</th>
                                <th>Nombre de Contacto</th>
                                <th>Telefono</th>
                            </tr>
                            <tr ng-repeat="row in asociadoCatalog | filter : { rfc : filterAsociadoRFC } " 
                                ng-click="selectAsociadoDeudor( row , true )"
                                style="{{ isRowSelected(row , true , false) }}"
                                title="Direccion: {{ row.direccion }}"
                                >
                                <td>{{ $index + 1 }}</td>
                                <td>{{ row.rfc }}</td>
                                <td>{{ row.razonSocial }}</td>
                                <td>{{ row.nombreContacto }}</td>
                                <td>{{ row.telefono }}</td>
                            </tr>
                            <tr ng-show="!isAsociadoFilterDisabled">
                                <td colspan="5" style="text-align: center; background-color: darkseagreen; color: white" ng-click="openCatalogWindow( 'asociado' )">Agregar nuevo Asociado</td>
                            </tr>
                            <tr ng-show="!isAsociadoFilterDisabled">
                                <td colspan="5" style="text-align: center; background-color: darkslateblue; color: white" ng-click="getCatalogDataByTable( 'Asociado' )">Recargar Catologo de Asociados</td>
                            </tr>
                        </table>
                    </div>


                    <!-- LISTA DE DEUDORES -->
                    <h3>Seleccione un Cliente / Deudor</h3>

                    <!-- FILTROS -->
                    <div class="input-group">
                        <span class="input-group-addon">Filtrar Deudor por RFC</span>
                        <input ng-disabled="isDeudorFilterDisabled" class="form-control" ng-model="filterDeudorRFC" title="Filtar lista por RFC">
                        <span ng-click="filterDeudorRFC = ''; releaseSelectedAsociadoDeudor( false );" class="input-group-addon" title="Clic para quitar filtros"><i class="glyphicon glyphicon-repeat"></i></span>
                    </div>


                    <!-- TABLA - LISTA FILTRADA -->
                    <div style="overflow: scroll; max-height: {{ halfUserScreenHeight }};">
                        <!-- TABLA -->
                        <table class="table table-hover table-condensed">
                            <tr>
                                <th>#</th>
                                <th>RFC</th>
                                <th>Razon Social</th>
                                <th>Nombre de Contacto</th>
                                <th>Telefono</th>
                            </tr>
                            <tr ng-repeat="row in deudorCatalog | filter : { rfc : filterDeudorRFC } " 
                                ng-click="selectAsociadoDeudor( row , false )"
                                style="{{ isRowSelected(row , false , false) }}"
                                title="Direccion: {{ row.direccion }}"
                                >
                                <td>{{ $index + 1 }}</td>
                                <td>{{ row.rfc }}</td>
                                <td>{{ row.razonSocial }}</td>
                                <td>{{ row.nombreContacto }}</td>
                                <td>{{ row.telefono }}</td>
                            </tr>
                            <tr ng-show="!isDeudorFilterDisabled">
                                <td colspan="5" style="text-align: center; background-color: darkseagreen; color: white" ng-click="openCatalogWindow( 'deudor' )">Agregar nuevo Cliente Deudor</td>
                            </tr>
                            <tr ng-show="!isDeudorFilterDisabled">
                                <td colspan="5" style="text-align: center; background-color: darkslateblue; color: white" ng-click="getCatalogDataByTable( 'Deudor' )">Recargar Catologo de Deudores</td>
                            </tr>
                        </table>
                    </div>
                
                    <!-- MONTO -->
                    <div class="row" ng-show="isDeudorFilterDisabled && isAsociadoFilterDisabled">
                        <h3>Monto adeudado</h3>
                        <input ng-model="details.monto">
                        <h3>Fecha del Adeudo</h3>
                        <select ng-model="details.fecha"
                                ng-options="x.id as x.name for x in fechaCatalog"
                                ></select>
                        <div class="btn btn-danger" ng-click="clicCancelarNuevoRegistro()">Cancelar</div>
                        <div class="btn btn-success" 
                             ng-click="clicGuardarNuevoRegistro()">Guardar</div>
                    </div>
                    
                    <div class="row" ng-show="!(isDeudorFilterDisabled && isAsociadoFilterDisabled)">
                        <div class="btn btn-danger" 
                             ng-click="clicCancelarNuevoRegistro()">Cancelar</div>
                    </div>
                    
                </div>
            </div>
                
            
            <!-- PANTALLA TABLA BURO DE CREDITO - LISTA FILTRADA -->
            <div ng-show="showListadoBuroDeCredito">
                <!-- FILTROS ASOCIADO -->
                <div class="input-group">
                    <span class="input-group-addon">Filtrar por RFC de Asociado</span>
                    <input class="form-control" ng-model="filterAsociadoRFC" title="Filtar lista por RFC">
                    <span ng-click="filterAsociadoRFC = '';" class="input-group-addon" title="Clic para quitar filtros"><i class="glyphicon glyphicon-repeat"></i></span>
                </div>
                
                <!-- FILTROS DEUDOR -->
                <div class="input-group">
                    <span class="input-group-addon">Filtrar por RFC de Deudor</span>
                    <input class="form-control" ng-model="filterDeudorRFC" title="Filtar lista por RFC">
                    <span ng-click="filterDeudorRFC = '';" class="input-group-addon" title="Clic para quitar filtros"><i class="glyphicon glyphicon-repeat"></i></span>
                </div>
                
                <!-- TABLA BURO DE CREDITO - LISTA FILTRADA -->
                <div style="overflow: scroll; max-height: {{ userScreenHeight }};">
                    <!-- TABLA -->
                    <table class="table table-hover table-condensed">
                        <tr>
                            <th>#</th>
                            <th>Cliente Deudor</th>
                            <th>Razon Social</th>
                            <th ng-show="showAsociadoInBuroDeCreditoListaFiltrada">
                                Asociado
                            </th>
                            <th ng-show="showAsociadoInBuroDeCreditoListaFiltrada">
                                Razon SocialAsociado
                            </th>
                            <th>Monto</th>
                            <th>Fecha</th>
                            <th title="Clic para mostrar / ocultar datos del Asociado">
                                <span ng-show="!showAsociadoInBuroDeCreditoListaFiltrada" ng-click="showAsociadoInBuroDeCreditoListaFiltrada=true" class="glyphicon glyphicon-eye-open"></span>
                                <span ng-show="showAsociadoInBuroDeCreditoListaFiltrada" ng-click="showAsociadoInBuroDeCreditoListaFiltrada=false" class="glyphicon glyphicon-eye-close"></span>
                            </th>
                            <th ng-show="( rol == 'admin' ) || ( rol == 'user' )">Accion</th>
                        </tr>
                        <tr ng-repeat="row in buroDeCreditoCatalog | filter : { rfcAsociado : filterAsociadoRFC , rfcDeudor : filterDeudorRFC }" 
                            ng-click="selectBuroDeCredito( row )"
                            style="{{ isRowSelected(row , false , true) }}"
                            >
                            <td>{{ $index + 1 }}</td>
                            <td>
                                <!--input ng-model="row.rfcDeudor" ng-disabled="true" class="form-control"-->
                                {{ row.rfcDeudor }}
                            </td>
                            <td>
                                <!--input ng-model="row.razonSocialDeudor" ng-disabled="true" class="form-control"-->
                                {{ row.razonSocialDeudor }}
                            </td>
                            <td ng-show="showAsociadoInBuroDeCreditoListaFiltrada">
                                <!--input ng-model="row.rfcAsociado" ng-disabled="true" class="form-control"-->
                                {{ row.rfcAsociado }}
                            </td>
                            <td ng-show="showAsociadoInBuroDeCreditoListaFiltrada">
                                <!--input ng-model="row.razonSocialAsociado" ng-disabled="true" class="form-control"-->
                                {{ row.razonSocialAsociado }}
                            </td>
                            <td>
                                <input ng-model="row.monto" ng-disabled="row.disable" ng-change="details.monto = row.monto">
                            </td>
                            <td>
                                <select ng-model="row.fecha"
                                        ng-options="x.id as x.name for x in fechaCatalog"
                                        ng-disabled="row.disable"
                                        ></select>
                            </td>
                            <td></td>
                            <td  ng-show="( rol == 'admin' ) || ( rol == 'user' )">
                                <div ng-show="!row.disable" class="btn btn-success btn-sm" ng-click="updateRow( row );">Guardar</div>
                                <div ng-show="!row.disable" class="btn btn-danger btn-sm" ng-click="deleteRow( row );"><span class="glyphicon glyphicon-trash"></span></div>
                                <div ng-show="row.disable" class="btn btn-primary btn-sm" ng-click="row.disable = false;">Editar</div>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="8" style="text-align: center; background-color: darkseagreen; color: white" 
                                ng-show="( rol == 'admin' ) || ( rol == 'user' )"
                                ng-click="showCrearNuevoRegistro=true;
                                    showListadoBuroDeCredito=false;
                                    details.id='--';
                                    details.idAsociado='';
                                    details.idDeudor='';
                                    details.monto='';
                                    details.fecha='';
                                    details.rfcAsociado='';
                                    details.rfcDeudor='';
                                    filterAsociadoRFC='';
                                    filterDeudorRFC=''
                                ">Agregar nuevo Registro</td>
                        </tr>
                    </table>
                </div>
                
            </div>
        </div>
    </body>
</html>