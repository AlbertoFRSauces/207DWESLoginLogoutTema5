<?php
/*
 * @author: Alberto Fernandez Ramirez
 * @since: 07/12/2021
 * @version: 1.0 Realizacion de cambiar password
 * @copyright: Copyright (c) 2021, Alberto Fernandez Ramirez
 * Pagina para cambiar la password
 */

session_start(); //Creo una nueva sesion o recupero una existente

if (!isset($_SESSION['usuarioDAW207AppLoginLogout'])) { //Comprobar si el usuario no se ha autentificado
    header('Location: ../codigoPHP/login.php'); //Redirijo al usuario al login.php para que se autentifique
    exit;
}

if (isset($_REQUEST['cancelar'])) { //Si se ha pulsado cancelar vuelvo a editarPerfil
    header('Location: editarPerfil.php'); //Redireccion a editarPerfil mediante el header
    exit;
}

require_once '../config/configAPP.php'; //Incluyo el array de idiomas para la COOKIE
require_once '../core/libreriaValidacion.php'; //Incluyo la libreria de validacion
require_once '../config/configDBPDO.php'; //Incluyo las variables de la conexion

define("OBLIGATORIO", 1);//Variable obligatorio inicializada a 1
$entradaOK = true;//Variable de entrada correcta inicializada a true
            
$aErrores = [ //Creo el array de errores y lo inicializo a null
    'PasswordActual' => null,
    'PasswordNueva' => null,
    'RepetirPasswordNueva' => null
];
            
$aRespuestas = [ //Creo el array de respuestas y lo incializo a null
    'PasswordActual' => null,
    'PasswordNueva' => null,
    'RepetirPasswordNueva' => null
];


//Comprobar si se ha pulsado el boton aceptar
if (isset($_REQUEST['aceptar'])) { //Si le ha dado al boton de aceptar valido los datos
    $aErrores['PasswordActual'] = validacionFormularios::validarPassword($_REQUEST['PasswordActual'], 8, 1, 1, OBLIGATORIO); //Compruebo si la password esta bien rellenada
    $aErrores['PasswordNueva'] = validacionFormularios::validarPassword($_REQUEST['PasswordNueva'], 8, 1, 1, OBLIGATORIO); //Compruebo si la password esta bien rellenada
    $aErrores['RepetirPasswordNueva'] = validacionFormularios::validarPassword($_REQUEST['RepetirPasswordNueva'], 8, 1, 1, OBLIGATORIO); //Compruebo si la password repetida esta bien rellenada
    try{
        $DB207DWESProyectoTema5 = new PDO(HOST, USER, PASSWORD);//Hago la conexion con la base de datos
        $DB207DWESProyectoTema5 -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);// Establezco el atributo para la aparicion de errores con ATTR_ERRMODE y le pongo que cuando haya un error se lance una excepcion con ERRMODE_EXCEPTION
        
        $consulta = "SELECT * FROM T01_Usuario WHERE T01_CodUsuario = :CodUsuario AND T01_Password = :Password ";
        $resultadoConsulta = $DB207DWESProyectoTema5->prepare($consulta); // Preparo la consulta antes de ejecutarla
        $aParametros = [ //Array de parametros para el update
            ":CodUsuario" => $_SESSION['usuarioDAW207AppLoginLogout'], //El usuario de la sesion actual
            ":Password" => hash("sha256", ($_SESSION['usuarioDAW207AppLoginLogout'] . $_REQUEST['PasswordActual'])) //Encripto la password introducida en el formulario
        ];
        $resultadoConsulta->execute($aParametros);//Ejecuto la consulta con el array de parametros para actualizar la descripcion del usuaro en la DB
        $oUsuario = $resultadoConsulta->fetchObject(); //Obtengo un objeto del primer registro
        
        if($resultadoConsulta->rowCount() == 0){ //Si la consulta no tiene ningun registro es que no esta bien el usuario o la password
                $aErrores['PasswordActual'] = "Password incorrecta!"; //Si no es correcta, almaceno el error en el array de errores
            }

        if($_REQUEST['PasswordNueva'] != $_REQUEST['RepetirPasswordNueva']){ //Compruebo si la password nueva coincide con la password nueva repetida
            $aErrores['PasswordNueva'] = "Las passwords no coinciden!";
            $aErrores['RepetirPasswordNueva'] = "Las passwords no coinciden!";
        }
    }catch(PDOException $excepcion){//Codigo que se ejecuta si hay algun error
        $errorExcepcion = $excepcion->getCode();//Obtengo el codigo del error y lo almaceno en la variable errorException
        $mensajeException = $excepcion->getMessage();//Obtengo el mensaje del error y lo almaceno en la variable mensajeException
        echo "<p style='color: red'>Codigo del error: </p>" . $errorExcepcion;//Muestro el codigo del error
        echo "<p style='color: red'>Mensaje del error: </p>" . $mensajeException;//Muestro el mensaje del error
    }finally{
        unset($DB207DWESProyectoTema5);//Cierro la conexion
    }

    //Comprobar si algun campo del array de errores ha sido rellenado
    foreach ($aErrores as $campo => $error) {//recorro el array errores
        if ($error != null) {//Compruebo si hay algun error
            $_REQUEST[$campo] = '';//Limpio el campo del formulario
            $entradaOK = false;//Le doy el valor false a entradaOK
        }
    }
}else{ //Si el usuario no le ha dado al boton de entrar
    $entradaOK = false; //Le doy el valor false a entradaOK y se vuelve a mostrar el formulario
}

if($entradaOK){ //Si la entrada es correcta
    try{
        $DB207DWESProyectoTema5 = new PDO(HOST, USER, PASSWORD);//Hago la conexion con la base de datos
        $DB207DWESProyectoTema5 -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);// Establezco el atributo para la aparicion de errores con ATTR_ERRMODE y le pongo que cuando haya un error se lance una excepcion con ERRMODE_EXCEPTION
        
        $consultaUpdate = "UPDATE T01_Usuario SET T01_Password = :Password WHERE T01_CodUsuario = :CodUsuario";
        $resultadoConsultaUpdate = $DB207DWESProyectoTema5->prepare($consultaUpdate); // Preparo la consulta antes de ejecutarla
        $aParametrosUpdate = [ //Array de parametros para el update
            ":CodUsuario" => $_SESSION['usuarioDAW207AppLoginLogout'], //El usuario de la sesion actual
            ":Password" => hash("sha256", ($_SESSION['usuarioDAW207AppLoginLogout'] . $_REQUEST['PasswordNueva'])) //La password nueva encriptada
        ];
        $resultadoConsultaUpdate->execute($aParametrosUpdate);//Ejecuto la consulta con el array de parametros para cambiar la password en la DB

        header('Location: editarPerfil.php'); //Mando al usuario a la pagina editarPerfil.php
        exit;
    }catch(PDOException $excepcion){//Codigo que se ejecuta si hay algun error
        $errorExcepcion = $excepcion->getCode();//Obtengo el codigo del error y lo almaceno en la variable errorException
        $mensajeException = $excepcion->getMessage();//Obtengo el mensaje del error y lo almaceno en la variable mensajeException
        echo "<p style='color: red'>Codigo del error: </p>" . $errorExcepcion;//Muestro el codigo del error
        echo "<p style='color: red'>Mensaje del error: </p>" . $mensajeException;//Muestro el mensaje del error
    }finally{
        unset($DB207DWESProyectoTema5);//Cierro la conexion
    }
}else{
?>
<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="author" content="Alberto Fernandez Ramirez">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="robots" content="index, follow">
        <meta name="application-name" content="Change password">
        <meta name="description" content="Cambiar la contraseña del usuario actual">
        <link href="../webroot/css/estiloejercicio.css" rel="stylesheet" type="text/css">
        <link rel="icon" href="../webroot/css/img/home.png" type="image/x-icon">
        <title>Cambiar Contraseña Tema 5</title>
        <style>
            form{
                margin-top: 15px;
                margin-bottom: 70px;
            }
            fieldset{
                border: 2px solid black;
                width: 431px;
                margin:auto;
                background-color:#EEEEEE;
            }
            ul{
                list-style: none;
            }
            ul li{
                padding-left: 15px;
                padding-bottom: 15px;
                padding-right: 15px;
                display: flex;
                flex-direction: row;
                justify-content: center;
                align-items: center;
            }
            .titulo{
               margin-left: 13px; 
            }
            span{
                font-size: 90%;
                color: red;
            }
            fieldset p:first-child{
                font-size: 190%;
                padding-top: 15px;
                padding-left: 15px;
                padding-bottom: 15px;
            }
            .errores{
                height: 14px;
            }
        </style>
    </head>
    <body>
        <div class="container">
            <header class="tituloaplicacion">
                <h1>207DWESLoginLogoutTema5</h1>
            </header>
            <article class="titulopagina">
                <h2><?php  echo $aIdioma[$_COOKIE['idioma']]['cambiarpassword'] //Muestro cambiar password en el idioma selecionado en el index ?></h2>
            </article>
            <form name="formulario" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" class="form">
                    <fieldset>
                        <p class="titulo">Cambiar Contraseña<p>
                        <ul>
                            <!--Campo Password Actual OBLIGATORIO-->
                            <li>
                                <div>
                                    <label for="PasswordActual"><strong>Password Actual*</strong></label>
                                    <input name="PasswordActual" id="PasswordActual" type="password" value="<?php 
                                    echo isset($_REQUEST['PasswordActual']) ? $_REQUEST['PasswordActual'] : null; ?>" 
                                    placeholder="Introduzca la password actual">
                                    <?php echo '<p class="errores"><span>' . $aErrores['PasswordActual'] . '</span></p>' ?>
                                </div>
                            </li>
                            <!--Campo Password Nueva OBLIGATORIO-->
                            <li>
                                <div>
                                    <label for="PasswordNueva"><strong>Nueva Password*</strong></label>
                                    <input name="PasswordNueva" id="PasswordNueva" type="password" value="<?php 
                                    echo isset($_REQUEST['PasswordNueva']) ? $_REQUEST['PasswordNueva'] : null; ?>" 
                                    placeholder="Introduzca la password nueva">
                                    <?php echo '<p class="errores"><span>' . $aErrores['PasswordNueva'] . '</span></p>' ?>
                                </div>
                            </li>
                           <!--Campo Password Nueva Repetir OBLIGATORIO-->
                            <li>
                                <div>
                                    <label for="RepetirPasswordNueva"><strong>Repetir Nueva Password*</strong></label>
                                    <input name="RepetirPasswordNueva" id="RepetirPasswordNueva" type="password" value="<?php 
                                    echo isset($_REQUEST['RepetirPasswordNueva']) ? $_REQUEST['RepetirPasswordNueva'] : null; ?>" 
                                    placeholder="Introduzca la password de nuevo">
                                    <?php echo '<p class="errores"><span>' . $aErrores['RepetirPasswordNueva'] . '</span></p>' ?>
                                </div>
                            </li>
                            
                            <!--Campo Botones Aceptar, Cancelar y Eliminar Cuenta-->
                            <li>
                                <input type="submit" value="ACEPTAR" name="aceptar" class="aceptar"/>
                                <input type="submit" value="CANCELAR" name="cancelar" class="cancelar"/>
                            </li>
                        </ul>
                    </fieldset>
                </form>
                <?php
                    }
                ?>
            <footer class="piepagina">
                <a href="../indexProyectoLoginLogoutTema5.php"><img src="../webroot/css/img/atras.png" class="imageatras" alt="IconoAtras" /></a>
                <a href="https://github.com/AlbertoFRSauces/207DWESLoginLogoutTema5" target="_blank"><img src="../webroot/css/img/github.png" class="imagegithub" alt="IconoGitHub" /></a>
                <p><a>&copy;</a><a href="http://daw207.ieslossauces.es/index.php">Alberto Fernández Ramírez</a> 29/09/2021 Todos los derechos reservados.</p>
                <p>Ultima actualización: 14/12/2021 17:27 - Release 2.2</p>
            </footer>
        </div>
    </body>
</html>

