/* 
 * Company:    Tokyo Boeki Techno-System de Mexico S.A. de C.V. 
 * Programmer: Alejandro Aguayo Acosta
 */

var miApp = angular.module( "miApp" , [] );
miApp.controller( 'buroDeCreditoCtrl'  ,['$scope' , '$http' , '$window' , function( $scope , $http , $window ){
    /**** variables ****/
    //URL
    var buroDeCreditoResourceUrl = serviceUrl + "php/resources/catalogs/BuroDeCreditoResource.php";
    $scope.relativeUrl = "../../";
    
    //oficial colors
    $scope.oficialBlueColor = oficialBlueColor ;
    $scope.oficialGrayColor = oficialGrayColor ; 
    
    //security
    $scope.user = "";
    $scope.token = "";
    $scope.rol = "";
    
    //user screen
    $scope.userScreenHeight = "";
    $scope.halfUserScreenHeight = "";
    
    //flags
    $scope.isWaitingServerResponse = false;
    $scope.showCrearNuevoRegistro = false;
    $scope.showListadoBuroDeCredito = true;
    $scope.showAsociadoInBuroDeCreditoListaFiltrada = false;
    
    
    //Message texts
    $scope.deleteWarningTitle = "¿Esta seguro?";
    $scope.deleteWarningText = "Una vez la informacion sea borrada, ¡no se podra recuperar!";
    $scope.deleteSuccess = "¡Informacion eliminada!";
    $scope.deleteFailure = "Algo salio mal, por favor intente nuevamente";
    
    //forms
    $scope.details = {
        "id" : "--",
        "idAsociado" : "",
        "idDeudor" : "",
        "monto" : "",
        "fecha" : "",
        "rfcAsociado" : "",
        "rfcDeudor" : "",
        "razonSocialAsociado" : "",
        "razonSocialDeudor" : "",
        "showDatosAsociado" : false,
        "disable" : true
    };
    
    //catalogs
    $scope.asociadoCatalog = [];
    $scope.deudorCatalog = [];
    $scope.fechaCatalog = [
        {
            id : "1",
            name : "1 - 3 meses"
        },
        {
            id : "2",
            name : "4 - 8 meses"
        },
        {
            id : "3",
            name : "9 - 12 meses"
        },
        {
            id : "4",
            name : "12+ meses"
        }
    ];

    //table
    $scope.filterAsociadoRFC = "";
    $scope.filterDeudorRFC = "";
    $scope.isAsociadoFilterDisabled = false;
    $scope.isDeudorFilterDisabled = false;
    
    $scope.buroDeCreditoCatalog = [];
    
    /**** functions ****/
    //
    $scope.getUserScreenHeight = function(){
        //screen resolution
        $scope.userScreenHeight = $(document).height();
        $scope.userScreenHeight = $scope.userScreenHeight - 275;
        //half 
        $scope.halfUserScreenHeight = $scope.userScreenHeight / 2;
        $scope.halfUserScreenHeight = $scope.halfUserScreenHeight - 100;
        //
        $scope.userScreenHeight = $scope.userScreenHeight + "px";
        $scope.halfUserScreenHeight = $scope.halfUserScreenHeight + "px";
    };
    
    //
    $scope.montoChange = function( monto ){
        var returnData = "";
        for( var index in monto ){
            if( $scope.isCharNumber( monto[ index ] ) ){
                returnData += monto[ index ];
            }
        }
        $scope.details.monto = returnData;
        return returnData;
    }
    
    //12345678 -> $12,345,678
    $scope.montoAsCurrency = function( monto ){
        var returnData = "$ ";
        var longitudMonto = monto.length - 1;
        var comasTotales = Math.floor( longitudMonto / 3 );
        var letrasEnMonto = monto.split( "" );
        var tienePunto = monto.includes( "." );
        var lugarPrimerComa = ( longitudMonto + 1 ) - ( comasTotales * 3 );
        
        for( var index = 0 ; index <= longitudMonto ; index++ ){
            if( index == lugarPrimerComa ){
                lugarPrimerComa += 3;
                if( !((lugarPrimerComa >= longitudMonto)
                        && (tienePunto))
                        ){
                    returnData += ",";
                }
            }
            returnData += letrasEnMonto[ index ];
        }
        return returnData;
    }
        
    //
    $scope.getData = function(){
        $scope.getUserScreenHeight();
        $scope.buroDeCreditoCatalog = [];
        
        ////waiting screen
        $('#myLoadingModal').modal('show'); 
        $scope.isWaitingServerResponse = true;
        
        $http({
            url: buroDeCreditoResourceUrl + "?user=" + $scope.user + "&token=" + $scope.token,
            method: "GET"
        })
        .then(function(response) {
            if(response.data == "ACCESS DENIED" ){
                swal( { icon : error , text : "ACCESS DENIED" } );
            }
            for( var index in response.data ){
                $scope.buroDeCreditoCatalog[ index ] = response.data[ index ] ;
                
                //extra data
                $scope.buroDeCreditoCatalog[ index ].disable = true ;
            }
            
            //waiting screen
            $('#myLoadingModal').modal('hide'); 
            $scope.isWaitingServerResponse = false;
            
        }, 
        function(response) { // optional
            swal( { icon : error , text : "Error en el servidor, por favor intente mas tarde" } );
            
            //waiting screen
            $('#myLoadingModal').modal('hide'); 
            $scope.isWaitingServerResponse = false;
        });
    };
    
    //clic on a ROW
    $scope.selectAsociadoDeudor = function( param , isAsociado ){
        if( isAsociado ){
            $scope.details.idAsociado = param.id;
            $scope.details.rfcAsociado = param.rfc;
            $scope.details.razonSocialAsociado = param.razonSocial;
            $scope.filterAsociadoRFC = param.rfc;
            $scope.isAsociadoFilterDisabled = true;
            if( $scope.isDeudorFilterDisabled ){ $scope.checkIfSelectedAsociadoDeudorExist(); }
        }else{
            $scope.details.idDeudor = param.id;
            $scope.details.rfcDeudor = param.rfc;
            $scope.details.razonSocialDeudor = param.razonSocial;
            $scope.filterDeudorRFC = param.rfc;
            $scope.isDeudorFilterDisabled = true;
            if( $scope.isAsociadoFilterDisabled ){ $scope.checkIfSelectedAsociadoDeudorExist(); }
        }
    };
    
    //
    $scope.selectBuroDeCredito = function( param ){
        $scope.details.id = param.id;
        $scope.details.idAsociado = param.idAsociado;
        $scope.details.idDeudor = param.idDeudor;
        $scope.details.monto = param.monto;
        $scope.details.fecha = param.fecha;
        $scope.details.rfcAsociado = param.rfcAsociado;
        $scope.details.rfcDeudor = param.rfcDeudor;
        $scope.details.razonSocialAsociado = param.razonSocialAsociado;
        $scope.details.razonSocialDeudor = param.razonSocialDeudor;
        
    };
    
    //
    $scope.checkIfSelectedAsociadoDeudorExist = function(){
        
        for(var index in $scope.buroDeCreditoCatalog ){
            if( ( $scope.buroDeCreditoCatalog[ index ].idAsociado === $scope.details.idAsociado )
                    && ( $scope.buroDeCreditoCatalog[ index ].idDeudor === $scope.details.idDeudor )
                    ){
                $scope.details.id = $scope.buroDeCreditoCatalog[ index ].id;
                $scope.details.monto = $scope.buroDeCreditoCatalog[ index ].monto;
                $scope.details.fecha = $scope.buroDeCreditoCatalog[ index ].fecha;
            }
        }
        
    }
    
    //
    $scope.updateRow = function( param ){
        $scope.details.id = param.id;
        $scope.details.idAsociado = param.idAsociado;
        $scope.details.idDeudor = param.idDeudor;
        $scope.details.monto = param.monto;
        $scope.details.fecha = param.fecha;
        $scope.updateOrSave();
    };
    
    //POST
    $scope.updateOrSave = function(){
        if( $scope.isDetailsDataOk() ){
            
            //waiting screen
            $('#myLoadingModal').modal('show'); 
            $scope.isWaitingServerResponse = true;
            
            $http({
                url: buroDeCreditoResourceUrl + "?user=" + $scope.user + "&token=" + $scope.token,
                method: "POST",
                data: $scope.details
            })
            .then(function(response) {
                $scope.updateLocalData( response.data );
                if( response.data.includes( "INSERT" ) ){ 
                    swal( { text: "INSERT DONE", icon: "success" } );
                }
                if( response.data.includes( "UPDATE" ) ){ 
                    swal( { text: "UPDATE DONE", icon: "success" } );
                }
                if( response.data.includes( "ALREADYEXIST" ) ){ 
                    swal( { text: "REGISTRO CREADO POR OTRO USUARIO", icon: "success" } );
                }
            }, 
            function(response) { // optional
                swal( { text: "Falla en el servidor, por favor intente mas tarde", icon: "error" } );
                
                //waiting screen
                $('#myLoadingModal').modal('hide'); 
                $scope.isWaitingServerResponse = false;
            });
        }
    };
    
    //UPDATE LOCAL DATA
    $scope.updateLocalData = function( responseData ){
        var id = responseData.split( " " )[1];
        if( responseData.includes( "ALREADYEXIST" ) ){
            $scope.getData();
        }
        if( responseData.includes( "INSERT" ) ){
            $scope.details.id = id;
            $scope.buroDeCreditoCatalog.push(
                {
                    id : id,
                    idAsociado : $scope.details.idAsociado,
                    idDeudor : $scope.details.idDeudor,
                    monto : $scope.details.monto,
                    fecha : $scope.details.fecha,
                    rfcAsociado : $scope.details.rfcAsociado,
                    rfcDeudor : $scope.details.rfcDeudor,
                    razonSocialAsociado : $scope.details.razonSocialAsociado,
                    razonSocialDeudor : $scope.details.razonSocialDeudor,
                    
                    showDatosAsociado : true,
                    disable : true
                }
            );
        }
        if( responseData.includes( "UPDATE" ) ){
            for( var index in $scope.buroDeCreditoCatalog ){
                if( $scope.buroDeCreditoCatalog[ index ].id == id ){
                    $scope.buroDeCreditoCatalog[ index ].idAsociado = $scope.details.idAsociado;
                    $scope.buroDeCreditoCatalog[ index ].idDeudor = $scope.details.idDeudor;
                    $scope.buroDeCreditoCatalog[ index ].monto = $scope.details.monto;
                    $scope.buroDeCreditoCatalog[ index ].fecha = $scope.details.fecha;
                    $scope.buroDeCreditoCatalog[ index ].rfcAsociado = $scope.details.rfcAsociado;
                    $scope.buroDeCreditoCatalog[ index ].rfcDeudor = $scope.details.rfcDeudor;
                    $scope.buroDeCreditoCatalog[ index ].razonSocialAsociado = $scope.details.razonSocialAsociado;
                    $scope.buroDeCreditoCatalog[ index ].razonSocialDeudor = $scope.details.razonSocialDeudor;
                    
                    //extra data
                    $scope.buroDeCreditoCatalog[ index ].showDatosAsociado = true;
                    $scope.buroDeCreditoCatalog[ index ].disable = true;
                }
            }
        }
        if( responseData.includes( "DELETE" ) ){
            var temporalTable = $scope.buroDeCreditoCatalog;
            $scope.buroDeCreditoCatalog = [];
            for( var index in temporalTable ){
                if( temporalTable[ index ].id !== id ){
                    $scope.buroDeCreditoCatalog.push(
                        {
                            id : temporalTable[ index ].id,
                            idAsociado : temporalTable[ index ].idAsociado,
                            idDeudor : temporalTable[ index ].idDeudor,
                            monto : temporalTable[ index ].monto,
                            fecha : temporalTable[ index ].fecha,
                            rfcAsociado : temporalTable[ index ].rfcAsociado,
                            rfcDeudor : temporalTable[ index ].rfcDeudor,
                            razonSocialAsociado : temporalTable[ index ].razonSocialAsociado,
                            razonSocialDeudor : temporalTable[ index ].razonSocialDeudor,
                            
                            showDatosAsociado :  temporalTable[ index ].disable,
                            disable : temporalTable[ index ].disable
                        }
                    );
                }
            }
        }
        
        //waiting screen
        $('#myLoadingModal').modal('hide'); 
        $scope.isWaitingServerResponse = false;
        
        //
        $scope.showCrearNuevoRegistro = false;
        $scope.showListadoBuroDeCredito = true;
        $scope.filterAsociadoRFC = "";
        $scope.filterDeudorRFC = "";
    };
    
    //get RFC by id
    $scope.getRfcById = function( isAsociado , id ){
        var returnData = "";
        if( isAsociado ){
            for( var index in $scope.asociadoCatalog ){
                if( $scope.asociadoCatalog[ index ].id == id ){
                    returnData = $scope.asociadoCatalog[ index ].rfc;
                    break;
                }
            }
        }else{
            console.log( "es deudor" );
            for( var index in $scope.deudorCatalog ){
                if( $scope.deudorCatalog[ index ].id == id ){
                    returnData = $scope.deudorCatalog[ index ].rfc;
                    break;
                }
            }
        }
        console.log( returnData );
        return returnData;
    }
    
    //
    $scope.cleanDetails = function( isReturnToBuroDeCredito ){
        if( isReturnToBuroDeCredito ){
            
        }
    }
    
    //DELETE
    $scope.deleteRow = function( param ){
        
        $scope.details.id = param.id;
        $scope.details.idAsociado = param.idAsociado;
        $scope.details.idDeudor = param.idDeudor;
        $scope.details.monto = param.monto;
        $scope.details.fecha = param.fecha;
        
        if( $scope.details.id == "--" ){
            swal( { text :  "Select System" , icon : "error" } );
        }else{
            swal({
                title: $scope.deleteWarningTitle ,
                text: $scope.deleteWarningText,
                icon: "warning",
                buttons: true,
                dangerMode: true,
            })
            .then((willDelete) => {
                if (willDelete) {
                    //Proceed to make the http request to delete in the data base
                    $scope.deleteInDB();
              } else {
                    //swal("Your imaginary file is safe!"); 
              }
            });
        }
    };
     
    //Real DELETE on DB
    $scope.deleteInDB = function(){
        
        //waiting screen
        $('#myLoadingModal').modal('show'); 
        $scope.isWaitingServerResponse = true;
        
        $http({
            url: buroDeCreditoResourceUrl + "?user=" + $scope.user + "&token=" + $scope.token + "&id=" + $scope.details.id + "&delete=ok",
            method: "DELETE"
        })
        .then(function(response) {
            $scope.cleanDetails();
            $scope.updateLocalData( response.data );
            swal( {icon : "info", text : "Borrado"} );
        }, 
        function(response) { // optional
            swal( {icon : "error", text : "Delete Failed, Try again later"} );
            
            //waiting screen
            $('#myLoadingModal').modal('hide'); 
            $scope.isWaitingServerResponse = false; 
        });
    };
    
    //Limpia los detalles
    $scope.releaseSelectedAsociadoDeudor = function( isAsociado ){
        if( isAsociado ){
            $scope.details.idAsociado = "";
            $scope.isAsociadoFilterDisabled = false;
        }else{
            $scope.details.idDeudor = "";
            $scope.isDeudorFilterDisabled = false;
        }
        $scope.details.id = "--";
        $scope.details.monto = "";
        $scope.details.fecha = "";
    };

    //    
    $scope.getCatalogData = function(){
        $scope.getCatalogDataByTable( "Asociado" );
        $scope.getCatalogDataByTable( "Deudor" );
    };
    
    //
    $scope.getCatalogDataByTable = function( tableName ){
        var tableServiceUrl = serviceUrl + "php/resources/catalogs/" + tableName + "Resource.php";
        
        //clean previuos data
        if( tableName == "Asociado" ){ $scope.asociadoCatalog = []; }
        if( tableName == "Deudor" ){ $scope.deudorCatalog = []; }
        
        $('#myLoadingModal').modal('show');
        $scope.isWaitingServerResponse = true;
        
        $http({
            url: tableServiceUrl + "?user=" + $scope.user + "&token=" + $scope.token,
            method: "GET"
        })
        .then(function(response) {
            if(response.data == "ACCESS DENIED" ){
                swal( { icon : error , text : "NO ACCESS" } );
            }
            for( var index in response.data ){
                if( tableName == "Asociado" ){ $scope.asociadoCatalog[ index ] = response.data[ index ]; }
                if( tableName == "Deudor" ){ $scope.deudorCatalog[ index ] = response.data[ index ]; }
            }
            $('#myLoadingModal').modal('hide'); 
            $scope.isWaitingServerResponse = false;
        }, 
        function(response) { // optional
            swal( { icon : error , text : "ERROR" } );
            $('#myLoadingModal').modal('hide'); 
            $scope.isWaitingServerResponse = false;
        });
    };
    
    //
    $scope.isDetailsDataOk = function(){
        var returnData = true;
        var errorText = "";
        
        if( $scope.details.idAsociado === "" ){ returnData = false; errorText += "Seleccione un ASOCIADO, "; }
        if( $scope.details.idDeudor === "" ){ returnData = false; errorText += "Seleccione un CLIENTE / DEUDOR, "; }
        if( $scope.details.monto === "" ){ returnData = false; errorText += "Ingrese MONTO, "; }
        if( $scope.details.fecha === "" ){ returnData = false; errorText += "Ingrese FECHA, "; }
        
        if( !returnData ){
            swal({"text":errorText,"icon":"error"});
        }
        return returnData;
    };
    
    //checar que el RFC cumpla con la homoclave
    //12 caracteres : 3 letras + 6 numeros + 3 numero y letras
    //$scope.details.rfc.toUpperCase();
    $scope.isRFCMoralOk = function(){
        var returnData = true;
        //es de 12 caracteres?
        if( $scope.details.rfc.length != 12 ){
            returnData = false;
        }
        if( returnData ){
            var charArray = $scope.details.rfc.split('');
            if( !$scope.isCharLetter( charArray[0] ) ){ returnData = false; }
            if( !$scope.isCharLetter( charArray[1] ) ){ returnData = false; }
            if( !$scope.isCharLetter( charArray[2] ) ){ returnData = false; }
            
            if( !$scope.isCharNumber( charArray[3] ) ){ returnData = false; }
            if( !$scope.isCharNumber( charArray[4] ) ){ returnData = false; }
            if( !$scope.isCharNumber( charArray[5] ) ){ returnData = false; }
            if( !$scope.isCharNumber( charArray[6] ) ){ returnData = false; }
            if( !$scope.isCharNumber( charArray[7] ) ){ returnData = false; }
            if( !$scope.isCharNumber( charArray[8] ) ){ returnData = false; }
            
            if( !$scope.isCharLetter( charArray[9] ) && !$scope.isCharNumber( charArray[9] ) ){ returnData = false; }
            if( !$scope.isCharLetter( charArray[10] ) && !$scope.isCharNumber( charArray[10] ) ){ returnData = false; }
            if( !$scope.isCharLetter( charArray[11] ) && !$scope.isCharNumber( charArray[11] ) ){ returnData = false; }
        }
        return returnData;
    };
    
    //verifica que el char c sea numero
    $scope.isCharNumber = function( c ){
        var returnData = false;
        if (c >= '0' && c <= '9') {
            returnData = true;
        }
        return returnData;
    }
    
    //verifica que el char c sea letra
    $scope.isCharLetter = function( c ){
        var returnData = false;
        if (c >= 'A' && c <= 'Z') {
            returnData = true;
        }
        if (c >= 'a' && c <= 'z') {
            returnData = true;
        }
        return returnData;
    }
    
    
    //
    $scope.isRowSelected = function( row , isAsociado , isBuroDeCredito ){
        var style = "";
        
        if( isBuroDeCredito ){
            if( row.id === $scope.details.id ){
                style = "background-color: lightblue;";// + $scope.oficialBlueColor + "; ";
            }
        }else{
            if( isAsociado ){
                if( row.id === $scope.details.idAsociado ){
                    style = "background-color:" + $scope.oficialBlueColor + "; color: white;";
                }
            }else{
                if( row.id === $scope.details.idDeudor ){
                    style = "background-color:" + $scope.oficialBlueColor + "; color: white;";
                }
            }
        }
        
        return style;
    };
    
    //
    $scope.closeThisWindow = function(){
        $window.close();
    };
    
    //Open new TAB
    $scope.openCatalogWindow = function( param ){
        $window.open( $scope.relativeUrl + "html/catalogs/" + param + ".php?id=--"  , "" , "top=250,left=250,width=800,height=600" );
    };
    
    //
    $scope.clicGuardarNuevoRegistro = function(){
        if( $scope.isDetailsDataOk() ){
            $scope.isAsociadoFilterDisabled = false;
            $scope.isDeudorFilterDisabled = false;
            $scope.updateOrSave();
        }else{
            $scope.updateOrSave();
        }
    }
    
    //
    $scope.clicCancelarNuevoRegistro = function(){
        $scope.filterAsociadoRFC='';
        $scope.filterDeudorRFC='';
        $scope.isAsociadoFilterDisabled = false;
        $scope.isDeudorFilterDisabled = false;
        $scope.showCrearNuevoRegistro = false; 
        $scope.showListadoBuroDeCredito = true;
    }
    
    //mostrar modal
    $scope.showModal = function( param ){
        if( param == "development" ){
            $('#myDevelopment').modal('show'); 
        }
    }
    
    
}]);
