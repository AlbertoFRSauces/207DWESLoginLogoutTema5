<?php
/*
 * @author: Alberto Fernandez Ramirez
 * @since: 29/11/2021
 * @version: 1.0 Realizacion de programa y el logout
 * @copyright: Copyright (c) 2021, Alberto Fernandez Ramirez
 * Programa para ver el nombre del usuario que inicio sesion, sus conexiones y la ultima conexion, dos botones, uno de cerrar sesion y uno de detalle
 */

session_start(); //Creo una nueva sesion o recupero una existente

if (!isset($_SESSION['usuarioDAW207AppLoginLogout'])) { //Coimprobar si el usuario no se ha autentificado
        header('Location: ../codigoPHP/login.php'); //Redirijo al usuario al login.php para que se autentifique
        exit;
    }

if (isset($_REQUEST['cerrarsesion'])) { //Comprobar si se ha pulsado el boton volver
    session_destroy(); //Elimino todos los datos que contiene la sesion
    header('Location: ../indexProyectoLoginLogoutTema5.php'); //Vuelvo al login
    exit;
}

if (isset($_REQUEST['detalle'])) {//Comprobar si se ha pulsado el boton detalle
    header('Location: ../codigoPHP/detalle.php'); //Entro a detalle
    exit;
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

require_once '../core/libreriaValidacion.php'; //Incluyo la libreria de validacion
require_once '../config/configDBPDO.php'; //Incluyo las variables de la conexion

try{
    $DB207DWESProyectoTema5 = new PDO(HOST, USER, PASSWORD); //Hago la conexion con la base de datos
    $DB207DWESProyectoTema5 -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); //Establezco el atributo para la aparicion de errores con ATTR_ERRMODE y le pongo que cuando haya un error se lance una excepcion con ERRMODE_EXCEPTION
        
    $consulta = "SELECT T01_DescUsuario, T01_NumConexiones FROM T01_Usuario WHERE T01_CodUsuario=:CodUsuario"; //Consulta para seleccionar la descripcion del usuario y el numero total de conexiones
    $resultadoConsulta = $DB207DWESProyectoTema5->prepare($consulta); //Preparo la consulta antes de ejecutarla
    $parametros = [ //guardo en un parametro el usuario obtenido en la sesion del login
        ":CodUsuario" => $_SESSION['usuarioDAW207AppLoginLogout']
    ];
    $resultadoConsulta->execute($parametros);//Ejecuto la consulta con el array de parametros
    
    $oUsuario = $resultadoConsulta->fetchObject(); //Obtengo el primer registro de la consulta
    $nombreUsuario = $oUsuario->T01_DescUsuario; //Guardo en la variable nombreUsuario el nombre del usuario logeado con exito
    $conexionesUsuario = $oUsuario->T01_NumConexiones; //Guardo en la variable conexionesUsuario el total de conexiones realizadas del usuario logeado con exito
    
    $ultimaConexionUsuario = $_SESSION['fechaHoraUltimaConexionAnteriorDAW207AppLoginLogout']; //Guardo en la variable ultimaConexionUsuario la fecha de la ultima conexion del usuario logeado con exito
    
}catch(PDOException $excepcion){//Codigo que se ejecuta si hay algun error
    $errorExcepcion = $excepcion->getCode();//Obtengo el codigo del error y lo almaceno en la variable errorException
    $mensajeException = $excepcion->getMessage();//Obtengo el mensaje del error y lo almaceno en la variable mensajeException
    echo "<p style='color: red'>Codigo del error: </p>" . $errorExcepcion;//Muestro el codigo del error
    echo "<p style='color: red'>Mensaje del error: </p>" . $mensajeException;//Muestro el mensaje del error
}finally{
    unset($DB207DWESProyectoTema5);//Cierro la conexion
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
            <?php //Si el usuario nunca se ha conectado muestro el siguiente mensaje
            if($conexionesUsuario <= 1){?>
                <h1 class="usuario"><?php  echo $aIdioma[$_COOKIE['idioma']]['bienvenido'] . " " . $nombreUsuario //Muestro la bienvenida en el idioma selecionado en el index ?></h1>
                <h3 class="conexiones"><?php  echo "Esta es la primera vez que te conectas!" ?></h3>
            <?php
            }else{
                //Si el usuario se ha conectado mas veces muestro el siguiente mensaje
            ?>
                <h1 class="usuario"><?php  echo $aIdioma[$_COOKIE['idioma']]['bienvenido'] . " " . $nombreUsuario ?></h1>
                <h3 class="conexiones"><?php  echo "Es la " . $conexionesUsuario . " vez que te conectas." ?></h3>
                <h3 class="ultimaConexion"><?php  echo "Tu ultima conexion fue el " . date('d/m/Y H:i:s',$ultimaConexionUsuario) ?></h3>
            <?php
            }
            ?>    
            <form class="formularioPrograma">
                <input type="submit" value="CERRAR SESION" name="cerrarsesion" class="cerrarsesion"/>
                <input type="submit" value="DETALLE" name="detalle" class="detalle"/>
            </form>
            <footer class="piepagina">
                <a href="../codigoPHP/login.php"><img src="../webroot/css/img/atras.png" class="imageatras" alt="IconoAtras" /></a>
                <a href="https://github.com/AlbertoFRSauces/207DWESLoginLogoutTema5" target="_blank"><img src="../webroot/css/img/github.png" class="imagegithub" alt="IconoGitHub" /></a>
                <p><a>&copy;</a><a href="http://daw207.ieslossauces.es/index.php">Alberto Fernández Ramírez</a> 29/09/2021 Todos los derechos reservados.</p>
                <p>Ultima actualización: 04/12/2021 20:40 - Release 1.2</p>
            </footer>
        </div>
    </body>
</html>

