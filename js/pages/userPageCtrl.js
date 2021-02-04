/* 
 * Company:    Tokyo Boeki Techno-System de Mexico S.A. de C.V. 
 * Programmer: Alejandro Aguayo Acosta
 */

var miApp = angular.module( "miApp" , [] );
miApp.controller( 'userPageCtrl'  ,['$scope' , '$http' , '$window' , function( $scope , $http , $window ){
    /**** variables ****/
    //URL
    var userPageResourceUrl = serviceUrl + "php/resources/catalogs/UserResource.php";
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
    
    //forms
    $scope.details = {
        "id" : "--",
        "user" : "",
        "password" : "",
        "newPassword" : "",
        "repeatNewPassword" : ""
    };
    
    //catalogs
    /*$scope.companyCatalog = [];
    $scope.plantCatalog = [];
    $scope.departmentCatalog = [];
    */

    /**** functions ****/
    //
    $scope.getUserScreenHeight = function(){
        //screen resolution
        $scope.userScreenHeight = $(document).height();
        $scope.userScreenHeight = $scope.userScreenHeight - 275;
        $scope.userScreenHeight = $scope.userScreenHeight + "px";
    };
        
    //POST
    $scope.updatePassword = function(){
        if( $scope.isDetailsDataOk() ){
            
            //waiting screen
            $('#myLoadingModal').modal('show'); 
            $scope.isWaitingServerResponse = true;
            
            $http({
                url: userPageResourceUrl + "?updatePassword=OK&user=" + $scope.user + "&token=" + $scope.token,
                method: "POST",
                data: $scope.details
            })
            .then(function(response) {
                if( response.data.includes( "OK" ) ){
                    swal( { text: "Contraseña actualizada", icon: "success" } );
                }else{
                    swal( { text: "La Contraseña no es valida", icon: "error" } );
                }
                
                //limpia los campos de la contraseña
                $scope.details.password = "";
                $scope.details.newPassword = "";
                $scope.details.repeatNewPassword = "";
                
                //waiting screen
                $('#myLoadingModal').modal('hide'); 
                $scope.isWaitingServerResponse = false;
                
            }, 
            function(response) { // optional
                swal( { text: "FAIL", icon: "error" } );
                
                //waiting screen
                $('#myLoadingModal').modal('hide'); 
                $scope.isWaitingServerResponse = false;
            });
        }
    };
    
    $scope.isDetailsDataOk = function(){
        var returnData = true;
        var errorText = "";
        
        if( $scope.details.user === "" ){ returnData = false; errorText += "Debe escribir un USUARIO, "; }
        if( $scope.details.password === "" ){ returnData = false; errorText += "Escriba la CONTRASEÑA ACTUAL, "; }
        if( $scope.details.newPassword === "" ){ returnData = false; errorText += "Escriba la NUEVA CONTRASEÑA, "; }
        if( $scope.details.repeatNewPassword === "" ){ returnData = false; errorText += "Repita la NUEVA CONTRASEÑA, "; }
        if( $scope.details.newPassword === $scope.details.password ){ returnData = false; errorText += "La CONTRASEÑA ACTUAL y la NUEVA CONTASEÑA son iguales, verifique, "; }
        
        if( !$scope.isDataCombinationOk() ){ returnData = false; errorText += "La NUEVA CONTRASEÑA NO COINCIDE, verifique, "; }
        
        if( !returnData ){
            swal({"text":errorText,"icon":"error"});
        }
        return returnData;
    };
    
    //No pueden existir 2 userPages con el mismo RFC
    $scope.isDataCombinationOk = function(){
        var returnData = false;
        if( $scope.details.newPassword == $scope.details.repeatNewPassword ){
                    returnData = true;
        }
        return returnData;
    };
}]);
