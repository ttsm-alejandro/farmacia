 <?php 
    session_start();
    if( isset( $_SESSION[ "user" ] ) ){
        header( "Location: main_page.php" );
    }
?>
<!DOCTYPE html>
<!--
Company:    Tokyo Boeki Techno-System de Mexico S.A. de C.V. 
Programmer: Alejandro Aguayo Acosta
-->
<html>
    <head>
        <title>Login</title>
        <link rel="icon" href="images/util/logo.png">
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        
        <!-- Bootstrap -->
        <link rel="stylesheet" href="css/bootstrap.min.css">
    </head>
    
    <body>
        <div class="container">
            <form action="php/resources/LoginResource.php" method="post">
                <div class="row">
                    <div class="col-md-4"></div>
                    <div class="col-md-4 text-center"><img src="images/util/logo.png"></div>
                    <div class="col-md-4"></div>
                </div>    
                <div class="row">
                    <div class="col-md-4"></div>
                    <div class="col-md-4"><h3>Login</h3></div>
                    <div class="col-md-4"></div>
                </div>    
                <div class="row" style="height: 10px;"></div>
                <div class="row">
                    <div class="col-md-4"></div>
                    <div class="col-md-4 text-center"><input type="text" class="form-control" name="user" placeholder="username"></div>
                    <div class="col-md-4"></div>
                </div>    
                <div class="row" style="height: 10px;"></div>
                <div class="row">
                    <div class="col-md-4"></div>
                    <div class="col-md-4 text-center"><input type="password" class="form-control" name="pass" placeholder="password"></div>
                    <div class="col-md-4"></div>
                </div>
                
                <?php
                    $actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
                    if( strpos( $actual_link , "invalid=1" ) !== false ){
                ?>
                <div class="row" style="height: 10px;"></div>
                <div class="row">
                    <div class="col-md-4"></div>
                    <div class="col-md-4 text-center">
                                
                                <label class="label label-danger">Usuario / Contraseña invalido</label>
                    </div>
                </div>    
                <?php
                    }
                ?>
                <div class="row" style="height: 10px;"></div>
                <div class="row">
                    <div class="col-md-4"></div>
                    <div class="col-md-4 text-center"><input type="submit" value="Enter" class="btn btn-block btn-primary"></div>
                    <div class="col-md-4"></div>
                </div>    
            </form>
        </div>
    </body>
</html>