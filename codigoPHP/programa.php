<?php
/*
 * @author: Alberto Fernandez Ramirez
 * @since: 29/11/2021
 * @version: 1.0 Realizacion del index
 * @copyright: Copyright (c) 2021, Alberto Fernandez Ramirez
 * Index para entrar al login o salir hacia el index del Tema 5
 */

//Comprobar si se ha pulsado el boton volver
if (isset($_REQUEST['cerrarsesion'])) {
    header('Location: ../codigoPHP/login.php');
    exit;
}
//Comprobar si se ha pulsado el boton entrar
if (isset($_REQUEST['detalle'])) {
    header('Location: ../codigoPHP/detalle.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="author" content="Alberto Fernandez Ramirez">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="robots" content="index, follow">
        <meta name="application-name" content="Login y logout">
        <meta name="description" content="Control de acceso e identificación de un usuario">
        <link href="../webroot/css/estiloejercicio.css" rel="stylesheet" type="text/css">
        <link rel="icon" href="../webroot/css/img/home.png" type="image/x-icon">
        <title>Programa Tema 5</title>
    </head>
    <body>
        <div class="container">
            <form>
                <input type="submit" value="CERRAR SESION" name="cerrarsesion" class="cerrarsesion"/>
                <input type="submit" value="DETALLE" name="detalle" class="detalle"/>
            </form>
        
            <footer class="piepagina">
                <a href="../codigoPHP/login.php"><img src="../webroot/css/img/atras.png" class="imageatras" alt="IconoAtras" /></a>
                <a href="https://github.com/AlbertoFRSauces/207DWESLoginLogoutTema5" target="_blank"><img src="../webroot/css/img/github.png" class="imagegithub" alt="IconoGitHub" /></a>
                <p><a>&copy;</a>Alberto Fernández Ramírez 29/09/2021 Todos los derechos reservados.</p>
                <p>Ultima actualización: 25/11/2021 19:00</p>
            </footer>
        </div>
    </body>
</html>

