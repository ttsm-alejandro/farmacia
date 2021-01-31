/* 
 * Company:    Tokyo Boeki Techno-System de Mexico S.A. de C.V. 
 * Programmer: Alejandro Aguayo Acosta
 */
var miApp = angular.module( "miApp" , [] );
miApp.controller( 'mainPageCtrl' , function( $scope ){
    //DEVELOPMENT
    $scope.showConsole = false;
    
    //URL
    $scope.relativeUrl = "";
    
    //variables
    $scope.user = "";
    $scope.token = "";
    
    //
    $scope.arrayDatos = [
        "Nombre",
        "Direccion",
        "Telefono",
        "Datos" 
    ];
    
    $scope.modelo = "";
    
});