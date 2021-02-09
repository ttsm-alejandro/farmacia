<?php
    session_start();
    if( !isset( $_SESSION[ "user" ] ) ){
        header( "Location: index.php" );
    }
?>
<!DOCTYPE html>
<!--
Company:    Tokyo Boeki Techno-System de Mexico S.A. de C.V. 
Programmer: Alejandro Aguayo Acosta
-->
<html>
    <head>
        
        
        <title>Gasucen</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="icon" href="images/util/logo.png">  
        
        <!-- JQuery -->
        <script src="js/lib/jquery-3.2.1.min.js"></script>
        
        <!-- Bootstrap -->
        <link rel="stylesheet" href="css/bootstrap.min.css">
        <script src="js/lib/bootstrap.min.js"></script>

        <!-- AngularJS libraries -->
        <script src="js/lib/angular.min.js"></script>
        
        <!-- SWEET ALERT -->
        <script src="js/lib/sweetalert.min.js"></script>

        <!-- Initial configuration, always BEFORE the controller -->
        <script src="js/initConfig.js"></script>
        
        <!-- Controller -->
        <script src="js/mainPageCtrl.js"></script>
    </head>
    
    <body ng-app="miApp" >
        <div ng-controller="mainPageCtrl"
             class="container"
            ng-init="
                user='<?php echo $_SESSION["user"]; ?>';
                token='<?php echo $_SESSION["token"]; ?>';
                rol='<?php echo $_SESSION["rol"]; ?>';
                initFunction( '<?php echo $_SESSION["user"]; ?>' , '<?php echo $_SESSION["token"]; ?>' );
            ">
            
            <!-- Header -->
            <div ng-include="'html/util/header.html'"></div>
            <h1>BIENVENIDO <span style="color: blue;">{{ user | uppercase }}</span></h1>
            
            <h2 style="color: white; background-color: darkblue; text-align: center;">SISTEMA</h2>
            
        </div>
    </body>
</html>