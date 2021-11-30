<?php
/*
 * @author: Alberto Fernandez Ramirez
 * @since: 25/11/2021
 * @version: 1.0 Realizacion del index
 * @copyright: Copyright (c) 2021, Alberto Fernandez Ramirez
 * Index para entrar al login o salir hacia el index del Tema 5
 */

//Comprobar si se ha pulsado el boton salir
if (isset($_REQUEST['salir'])) {
    header('Location: ../207DWESProyectoTema5/indexProyectoTema5.php');
    exit;
}
//Comprobar si se ha pulsado el boton iniciar sesion
if (isset($_REQUEST['iniciarsesion'])) {
    header('Location: ../207DWESLoginLogoutTema5/codigoPHP/login.php');
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
        <link href="../207DWESLoginLogoutTema5/webroot/css/estilo.css" rel="stylesheet" type="text/css">
        <link rel="icon" href="../207DWESLoginLogoutTema5/webroot/css/img/home.png" type="image/x-icon">
        <title>Index Login y Logout Tema 5</title>
    </head>
    <body>
        <div class="containerIndex">
            <header>
                <h1>207DWESLoginLogoutTema5</h1>
            </header>
            <article class="segundot">
                <h2>LoginLogoutTema5 - TEMA 5</h2>
            </article>
            <form class="formularioIndex">
                <input type="submit" value="Iniciar sesión" name="iniciarsesion" class="iniciarsesion"/>
                <input type="submit" value="SALIR" name="salir" class="salir"/>
            </form>
        
            <footer class="piepagina">
                <a href="../207DWESProyectoTema5/indexProyectoTema5.php"><img src="../207DWESLoginLogoutTema5/webroot/css/img/atras.png" class="imageatras" alt="IconoAtras" /></a>
                <a href="https://github.com/AlbertoFRSauces/207DWESLoginLogoutTema5" target="_blank"><img src="../207DWESLoginLogoutTema5/webroot/css/img/github.png" class="imagegithub" alt="IconoGitHub" /></a>
                <p><a>&copy;</a>Alberto Fernández Ramírez 29/09/2021 Todos los derechos reservados.</p>
                <p>Ultima actualización: 25/11/2021 19:00</p>
            </footer>
        </div>
    </body>
</html>
