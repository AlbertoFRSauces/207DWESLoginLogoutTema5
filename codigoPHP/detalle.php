<?php
/*
 * @author: Alberto Fernandez Ramirez
 * @since: 30/11/2021
 * @version: 1.0 Realizacion del index
 * @copyright: Copyright (c) 2021, Alberto Fernandez Ramirez
 * Index para entrar al login o salir hacia el index del Tema 5
 */

//Comprobar si se ha pulsado el boton salir
if (isset($_REQUEST['aceptar'])) {
    header('Location: ../codigoPHP/programa.php');
    exit;
}
//Comprobar si se ha pulsado el boton iniciar sesion
if (isset($_REQUEST['volver'])) {
    header('Location: ../codigoPHP/programa.php');
    exit;
}
//Comprobar si se ha pulsado el boton iniciar sesion
if (isset($_REQUEST['cancelar'])) {
    header('Location: ../codigoPHP/programa.php');
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
        <title>Detalle Tema 5</title>
    </head>
    <body>
        <div class="container">
            <form >
                <input type="submit" value="Aceptar" name="aceptar" class="aceptar"/>
                <input type="submit" value="Volver" name="volver" class="volver"/>
                <input type="submit" value="Cancelar" name="cancelar" class="cancelar"/>
            </form>
        
            <footer class="piepagina">
                <a href="../207DWESProyectoTema5/indexProyectoTema5.php"><img src="../webroot/css/img/atras.png" class="imageatras" alt="IconoAtras" /></a>
                <a href="https://github.com/AlbertoFRSauces/207DWESLoginLogoutTema5" target="_blank"><img src="../webroot/css/img/github.png" class="imagegithub" alt="IconoGitHub" /></a>
                <p><a>&copy;</a>Alberto Fernández Ramírez 29/09/2021 Todos los derechos reservados.</p>
                <p>Ultima actualización: 25/11/2021 19:00</p>
            </footer>
        </div>
    </body>
</html>

