/* 
 * Company:    Tokyo Boeki Techno-System de Mexico S.A. de C.V. 
 * Programmer: Alejandro Aguayo Acosta
 */

var miApp = angular.module( "miApp" , [] );
miApp.controller( 'systemCtrl'  ,['$scope' , '$http' , '$window' , function( $scope , $http , $window ){
    /**** variables ****/
    //URL
    var systemResourceUrl = serviceUrl + "php/resources/catalogs/SystemResource.php";
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
        "serialNumber" : "",
        "idCompany" : "",
        "idPlant" : "",
        "idDepartment" : "",
        "installationDate" : "",
        "lastCalibrationDate" : "",
        "nextCalibrationDate" : "",
        "detail" : "",
        "comment" : ""
    };
    
    //catalogs
    $scope.companyCatalog = [];
    $scope.plantCatalog = [];
    $scope.departmentCatalog = [];

    //table
    $scope.filterSystemSerialNumber = "";
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
            url: systemResourceUrl + "?user=" + $scope.user + "&token=" + $scope.token,
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
            swal( { icon : error , text : "ERROR" } );
            
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
            url: systemResourceUrl + "?user=" + $scope.user + "&token=" + $scope.token + "&id=" + id,
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
        $scope.details.serialNumber = param.serialNumber;
        $scope.details.idCompany = param.idCompany;
        $scope.details.idPlant = param.idPlant;
        $scope.details.idDepartment = param.idDepartment;
        $scope.details.installationDate = new Date( param.installationDate );
        $scope.details.lastCalibrationDate = new Date( param.lastCalibrationDate );
        $scope.details.nextCalibrationDate = new Date( param.nextCalibrationDate );
        $scope.details.detail = param.detail;
        $scope.details.comment = param.comment;
    };
    
    //new row
    $scope.newRow = function(){
        $scope.cleanDetails();
    };
    
    //POST
    $scope.updateOrSaveRow = function(){
        if( $scope.isDetailsDataOk() ){
            
            //waiting screen
            $('#myLoadingModal').modal('show'); 
            $scope.isWaitingServerResponse = true;
            
            $http({
                url: systemResourceUrl + "?user=" + $scope.user + "&token=" + $scope.token,
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
                    serialNumber : $scope.details.serialNumber,
                    idCompany : $scope.details.idCompany,
                    idPlant : $scope.details.idPlant,
                    idDepartment : $scope.details.idDepartment,
                    installationDate : $scope.details.installationDate,
                    lastCalibrationDate : $scope.details.lastCalibrationDate,
                    nextCalibrationDate : $scope.details.nextCalibrationDate,
                    detail : $scope.details.detail,
                    comment : $scope.details.comment
                }
            );
        }
        if( responseData.includes( "UPDATE" ) ){
            for( var index in $scope.tableContent ){
                if( $scope.tableContent[ index ].id == id ){
                    $scope.tableContent[ index ].serialNumber = $scope.details.serialNumber;
                    $scope.tableContent[ index ].idCompany = $scope.details.idCompany;
                    $scope.tableContent[ index ].idPlant = $scope.details.idPlant;
                    $scope.tableContent[ index ].idDepartment = $scope.details.idDepartment;
                    $scope.tableContent[ index ].installationDate = $scope.details.installationDate;
                    $scope.tableContent[ index ].lastCalibrationDate = $scope.details.lastCalibrationDate;
                    $scope.tableContent[ index ].nextCalibrationDate = $scope.details.nextCalibrationDate;
                    $scope.tableContent[ index ].detail = $scope.details.detail;
                    $scope.tableContent[ index ].comment = $scope.details.comment;
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
                            serialNumber : temporalTable[ index ].serialNumber,
                            idCompany : temporalTable[ index ].idCompany,
                            idPlant : temporalTable[ index ].idPlant,
                            idDepartment : temporalTable[ index ].idDepartment,
                            installationDate : temporalTable[ index ].installationDate,
                            lastCalibrationDate : temporalTable[ index ].lastCalibrationDate,
                            nextCalibrationDate : temporalTable[ index ].nextCalibrationDate,
                            detail : temporalTable[ index ].detail,
                            comment : temporalTable[ index ].comment
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
            url: systemResourceUrl + "?user=" + $scope.user + "&token=" + $scope.token + "&id=" + $scope.details.id + "&delete=ok",
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
    
    //
    $scope.cleanDetails = function(){
        $scope.details.id = "--";
        $scope.details.serialNumber = "";
        $scope.details.idCompany = "";
        $scope.details.idPlant = "";
        $scope.details.idDepartment = "";
        $scope.details.installationDate = "";
        $scope.details.lastCalibrationDate = "";
        $scope.details.nextCalibrationDate = "";
        $scope.details.detail = "";
        $scope.details.comment = "";
    };

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
    
    //
    $scope.isDetailsDataOk = function(){
        var returnData = true;
        var errorText = "";
        if( $scope.details.serialNumber === "" ){ returnData = false; errorText += "Input SERIAL NUMBER, "; }
        if( $scope.details.idCompany === "" ){ returnData = false; errorText += "Select COMPANY, "; }
        if( $scope.details.idPlant === "" ){ returnData = false; errorText += "Select PLANT, "; }
        if( $scope.details.idDepartment === "" ){ returnData = false; errorText += "Select DEPARTMENT, "; }
        
        if( !$scope.isDataCombinationOk() ){ returnData = false; errorText += "Already exist a System with the same Serial Number, "; }
        
        if( !returnData ){
            swal({"text":errorText,"icon":"error"});
        }
        return returnData;
    };
    
    //Cannot exist 2 systems with the same name in the same plant
    $scope.isDataCombinationOk = function(){
        var returnData = true;
        for( var index in $scope.tableContent ){
            if( $scope.tableContent[ index ].id != $scope.details.id ){
                if( $scope.tableContent[ index ].serialNumber == $scope.details.serialNumber ){
                    returnData = false;
                }
            }
        }
        return returnData;
    };
    
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
