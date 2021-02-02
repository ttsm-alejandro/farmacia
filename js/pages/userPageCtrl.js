/* 
 * Company:    Tokyo Boeki Techno-System de Mexico S.A. de C.V. 
 * Programmer: Alejandro Aguayo Acosta
 */

var miApp = angular.module( "miApp" , [] );
miApp.controller( 'userPageCtrl'  ,['$scope' , '$http' , '$window' , function( $scope , $http , $window ){
    /**** variables ****/
    //URL
    var userPageResourceUrl = serviceUrl + "php/resources/catalogs/DeudorResource.php";
    $scope.relativeUrl = "../../";
    
    //oficial colors
    $scope.oficialBlueColor = oficialBlueColor ;
    $scope.oficialGrayColor = oficialGrayColor ; 
    
    //security
    $scope.user = "";
    $scope.token = "";
    
    //user screen
    $scope.userScreenHeight = "";
    
    //flags
    $scope.isWaitingServerResponse = false;
    
    //Message texts
    $scope.deleteWarningTitle = "Are you sure?";
    $scope.deleteWarningText = "Once deleted, you will not be able to recover the data!";
    $scope.deleteSuccess = "Data deleted!";
    $scope.deleteFailure = "Something went wrong!";
    
    //forms
    $scope.details = {
        "id" : "--",
        "razonSocial" : "",
        "rfc" : "",
        "direccion" : "",
        "nombreContacto" : "",
        "telefono" : ""
    };
    
    //catalogs
    /*$scope.companyCatalog = [];
    $scope.plantCatalog = [];
    $scope.departmentCatalog = [];
    */

    //table
    $scope.filterDeudorRFC = "";
    $scope.tableContent = [];
    
    /**** functions ****/
    //
    $scope.getUserScreenHeight = function(){
        //screen resolution
        $scope.userScreenHeight = $(document).height();
        $scope.userScreenHeight = $scope.userScreenHeight - 275;
        $scope.userScreenHeight = $scope.userScreenHeight + "px";
    };
        
    //
    $scope.getData = function(){
        $scope.getUserScreenHeight();
        $scope.tableContent = [];
        
        ////waiting screen
        $('#myLoadingModal').modal('show'); 
        $scope.isWaitingServerResponse = true;
        
        $http({
            url: userPageResourceUrl + "?user=" + $scope.user + "&token=" + $scope.token,
            method: "GET"
        })
        .then(function(response) {
            if(response.data == "ACCESS DENIED" ){
                swal( { icon : error , text : "ACCESS DENIED" } );
            }
            for( var index in response.data ){
                $scope.tableContent[ index ] = response.data[ index ] ;
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
    
    //
    $scope.getDataById = function( id ){
        //waiting screen
        $('#myLoadingModal').modal('show'); 
        $scope.isWaitingServerResponse = true;
        
        $http({
            url: userPageResourceUrl + "?user=" + $scope.user + "&token=" + $scope.token + "&id=" + id,
            method: "GET"
        })
        .then(function(response) {
            if(response.data == "ACCESS DENIED" ){
                swal( { icon : error , text : "ACCESS DENIED" } );
            }else{
                $scope.getDetails( response.data );
            }
            //waiting screen
            $('#myLoadingModal').modal('hide'); 
            $scope.isWaitingServerResponse = false;
        }, 
        function(response) { // optional
            swal( { icon : error , text : "ERROR" } );
            
            //waiting screen
            $('#myLoadingModal').modal('hide'); 
            $scope.isWaitingServerResponse = false;
        });
    };
    
    //clic on a ROW of the table
    $scope.getDetails = function( param ){
        $scope.details.id = param.id;
        $scope.details.razonSocial = param.razonSocial;
        $scope.details.rfc = param.rfc;
        $scope.details.direccion = param.direccion;
        $scope.details.nombreContacto = param.nombreContacto;
        $scope.details.telefono = param.telefono;
    };
    
    //new row
    $scope.newRow = function(){
        $scope.cleanDetails();
    };
    
    //POST
    $scope.updateOrSave = function(){
        if( $scope.isDetailsDataOk() ){
            
            //waiting screen
            $('#myLoadingModal').modal('show'); 
            $scope.isWaitingServerResponse = true;
            
            $http({
                url: userPageResourceUrl + "?user=" + $scope.user + "&token=" + $scope.token,
                method: "POST",
                data: $scope.details
            })
            .then(function(response) {
                $scope.updateLocalData( response.data );
                if( response.data.includes( "INSERT" ) ){ 
                    swal( { text: "INSERT DONE", icon: "success" } );
                }else{
                    swal( { text: "UPDATE DONE", icon: "success" } );
                }
            }, 
            function(response) { // optional
                swal( { text: "FAIL", icon: "error" } );
                
                //waiting screen
                $('#myLoadingModal').modal('hide'); 
                $scope.isWaitingServerResponse = false;
            });
        }
    };
    
    //UPDATE LOCAL DATA
    $scope.updateLocalData = function( responseData ){
        var id = responseData.split( " " )[1];
        if( responseData.includes( "INSERT" ) ){
            $scope.details.id = id;
            $scope.tableContent.push(
                {
                    id : id,
                    razonSocial : $scope.details.razonSocial,
                    rfc : $scope.details.rfc,
                    direccion : $scope.details.direccion,
                    nombreContacto : $scope.details.nombreContacto,
                    telefono : $scope.details.telefono
                }
            );
        }
        if( responseData.includes( "UPDATE" ) ){
            for( var index in $scope.tableContent ){
                if( $scope.tableContent[ index ].id == id ){
                    $scope.tableContent[ index ].razonSocial = $scope.details.razonSocial;
                    $scope.tableContent[ index ].rfc = $scope.details.rfc;
                    $scope.tableContent[ index ].direccion = $scope.details.direccion;
                    $scope.tableContent[ index ].nombreContacto = $scope.details.nombreContacto;
                    $scope.tableContent[ index ].telefono = $scope.details.telefono;
                }
            }
        }
        if( responseData.includes( "DELETE" ) ){
            var temporalTable = $scope.tableContent;
            $scope.tableContent = [];
            for( var index in temporalTable ){
                if( temporalTable[ index ].id !== id ){
                    $scope.tableContent.push(
                        {
                            id : temporalTable[ index ].id,
                            razonSocial : temporalTable[ index ].razonSocial,
                            rfc : temporalTable[ index ].rfc,
                            direccion : temporalTable[ index ].direccion,
                            nombreContacto : temporalTable[ index ].nombreContacto,
                            telefono : temporalTable[ index ].telefono
                        }
                    );
                }
            }
        }
        
        //waiting screen
        $('#myLoadingModal').modal('hide'); 
        $scope.isWaitingServerResponse = false;
    };
    
    //DELETE
    $scope.deleteRow = function(){
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
            url: userPageResourceUrl + "?user=" + $scope.user + "&token=" + $scope.token + "&id=" + $scope.details.id + "&delete=ok",
            method: "DELETE"
        })
        .then(function(response) {
            $scope.cleanDetails();
            $scope.updateLocalData( response.data );
            swal( {icon : "info", text : "Delete Done"} );
        }, 
        function(response) { // optional
            swal( {icon : "error", text : "Delete Failed, Try again later"} );
            
            //waiting screen
            $('#myLoadingModal').modal('hide'); 
            $scope.isWaitingServerResponse = false; 
        });
    };
    
    //Limpia los detalles
    $scope.cleanDetails = function(){
        $scope.details.id = "--";
        $scope.details.razonSocial = "";
        $scope.details.rfc = "";
        $scope.details.direccion = "";
        $scope.details.nombreContacto = "";
        $scope.details.telefono = "";
    };

    /*
    //    
    $scope.getCatalogData = function(){
        $scope.getCatalogDataByTable( "Company" );
        $scope.getCatalogDataByTable( "Plant" );
        $scope.getCatalogDataByTable( "Department" );
    };
    
    //
    $scope.getCatalogDataByTable = function( tableName ){
        var tableServiceUrl = serviceUrl + "php/resources/catalogs/" + tableName + "Resource.php";
        
        //clean previuos data
        if( tableName == "Company" ){ $scope.companyCatalog = []; }
        if( tableName == "Plant" ){ $scope.plantCatalog = []; }
        if( tableName == "Department" ){ $scope.departmentCatalog = []; }
        
        $('#myLoadingModal').modal('show'); 
        $http({
            url: tableServiceUrl + "?user=" + $scope.user + "&token=" + $scope.token,
            method: "GET"
        })
        .then(function(response) {
            if(response.data == "ACCESS DENIED" ){
                swal( { icon : error , text : "NO ACCESS" } );
            }
            for( var index in response.data ){
                if( tableName == "Company" ){ $scope.companyCatalog[ index ] = response.data[ index ]; }
                if( tableName == "Plant" ){ $scope.plantCatalog[ index ] = response.data[ index ]; }
                if( tableName == "Department" ){ $scope.departmentCatalog[ index ] = response.data[ index ]; }
            }
            $('#myLoadingModal').modal('hide'); 
        }, 
        function(response) { // optional
            swal( { icon : error , text : "ERROR" } );
            $('#myLoadingModal').modal('hide'); 
        });
    };
    */
    
    //
    $scope.isDetailsDataOk = function(){
        var returnData = true;
        var errorText = "";
        
        $scope.details.rfc = $scope.details.rfc.toUpperCase();
        
        if( $scope.details.razonSocial === "" ){ returnData = false; errorText += "Debe escribir una RAZON SOCIAL, "; }
        if( $scope.details.rfc === "" ){ returnData = false; errorText += "Escriba el RFC, "; }
        if( $scope.details.direccion === "" ){ returnData = false; errorText += "Escriba la DIRECCION, "; }
        if( $scope.details.nombreContacto === "" ){ returnData = false; errorText += "Escriba el NOMBRE DEL CONTACTO, "; }
        if( $scope.details.telefono === "" ){ returnData = false; errorText += "Escriba el TELEFONO, "; }
        
        if( !$scope.isDataCombinationOk() ){ returnData = false; errorText += "Ya existe un registro con el mismo RFC, "; }
        
        if( !$scope.isRFCMoralOk() ){ returnData = false; errorText += "El RFC no es valido, "; }
        
        
        
        if( !returnData ){
            swal({"text":errorText,"icon":"error"});
        }
        return returnData;
    };
    
    //No pueden existir 2 userPages con el mismo RFC
    $scope.isDataCombinationOk = function(){
        var returnData = true;
        for( var index in $scope.tableContent ){
            if( $scope.tableContent[ index ].id != $scope.details.id ){
                if( $scope.tableContent[ index ].rfc == $scope.details.rfc ){
                    returnData = false;
                }
            }
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
    $scope.isRowSelected = function( row ){
        var style = "";
        if( row.id === $scope.details.id ){
            style = "background-color:" + $scope.oficialBlueColor + "; color: white;";
        }
        return style;
    };
    
    //
    $scope.closeThisWindow = function(){
        $window.close();
    };
    
    //Open new TAB
    $scope.openCatalogWindow = function( param , id ){
        if( id == "" || id==null ){
            swal({ text : "Select a option to EDIT" , icon : "error" });
        }else{
            $window.open( $scope.relativeUrl + "html/catalogs/" + param + ".php?id=" + id  , "" , "top=0,left=0,width=800,height=600" );
        }
    };
}]);