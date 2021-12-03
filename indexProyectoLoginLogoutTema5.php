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
//
if (!isset($_COOKIE['idioma'])){
    setcookie("idioma", "es", time()+2000002); //Pongo el idioma en español y el tiempo de expiracion en +2000002
    header('Location: ../207DWESLoginLogoutTema5/indexProyectoLoginLogoutTema5.php'); 
    exit;
}
//
if(isset($_REQUEST['idiomaBotonSeleccionado'])){
    setcookie("idioma", $_REQUEST['idiomaBotonSeleccionado'], time()+2000002);//Ponemos que el idioma sea el seleccionado en el boton
}

$aIdioma['es'] = [ //Array de los datos en español
    'bienvenido' => 'Bienvenid@'
];

$aIdioma['en'] = [ //Array de los datos en ingles
   'bienvenido' => 'Welcome'
];

$aIdioma['pt'] = [ //Array de los datos en ingles
   'bienvenido' => 'Receber'
];

//$aIdiomas = array(
//    'es' => array('bienvenido' => "Bienvenid@"),
//    'en' => array('bienvenido' => "Welcome"),
//    'pt' => array('bienvenido' => 'Receber')
//);

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
            <form class="formularioIdioma">
                <button type="submit" value="es" name="idiomaBotonSeleccionado" class="idiomaBoton"><img src="../207DWESLoginLogoutTema5/webroot/css/img/es.png" class="es" alt="imagenes"></button>
                <button type="submit" value="en" name="idiomaBotonSeleccionado" class="idiomaBoton"><img src="../207DWESLoginLogoutTema5/webroot/css/img/en.png" class="en" alt="imagenen"></button>
                <button type="submit" value="pt" name="idiomaBotonSeleccionado" class="idiomaBoton"><img src="../207DWESLoginLogoutTema5/webroot/css/img/pt.png" class="pt" alt="imagenpt"></button>
            </form>
            <form class="formularioIndex">
                <input type="submit" value="Iniciar sesión" name="iniciarsesion" class="iniciarsesion"/>
                <input type="submit" value="SALIR" name="salir" class="salir"/>
            </form>
            <footer class="piepagina">
                <a href="../207DWESProyectoTema5/indexProyectoTema5.php"><img src="../207DWESLoginLogoutTema5/webroot/css/img/atras.png" class="imageatras" alt="IconoAtras" /></a>
                <a href="https://github.com/AlbertoFRSauces/207DWESLoginLogoutTema5" target="_blank"><img src="../207DWESLoginLogoutTema5/webroot/css/img/github.png" class="imagegithub" alt="IconoGitHub" /></a>
                <p><a>&copy;</a><a href="http://daw207.ieslossauces.es/index.php">Alberto Fernández Ramírez</a> 29/09/2021 Todos los derechos reservados.</p>
                <p>Ultima actualización: 03/12/2021 13:09 - Release v1.1</p>
            </footer>
        </div>
    </body>
</html>
