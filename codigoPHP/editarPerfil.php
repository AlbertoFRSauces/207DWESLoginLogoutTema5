<?php
/*
 * @author: Alberto Fernandez Ramirez
 * @since: 07/12/2021
 * @version: 1.0 Realizacion de editar perfil
 * @copyright: Copyright (c) 2021, Alberto Fernandez Ramirez
 * Pagina para editar un usuario o borrar su cuenta
 */

session_start(); //Creo una nueva sesion o recupero una existente

if (!isset($_SESSION['usuarioDAW207AppLoginLogout'])) { //Comprobar si el usuario no se ha autentificado
    header('Location: ../codigoPHP/login.php'); //Redirijo al usuario al login.php para que se autentifique
    exit;
}

if (isset($_REQUEST['cancelar'])) { //Si se ha pulsado cancelar vuelvo al programa
    header('Location: programa.php'); //Redireccion al programa mediante el header
    exit;
}

if (isset($_REQUEST['cambiarpassword'])) { //Si se ha pulsado cambiar password voy a cambiarPassword
    header('Location: cambiarPassword.php'); //Redireccion a cambiarPassword mediante el header
    exit;
}

require_once '../config/configAPP.php'; //Incluyo el array de idiomas para la COOKIE
require_once '../core/libreriaValidacion.php'; //Incluyo la libreria de validacion
require_once '../config/configDBPDO.php'; //Incluyo las variables de la conexion

define("OBLIGATORIO", 1);//Variable obligatorio inicializada a 1
$entradaOK = true; //Variable de entrada correcta inicializada a true
$eliminar = false;
            
$aErrores = [ //Creo el array de errores y lo inicializo a null
    'DescUsuario' => null
];
            
$aRespuestas = [ //Creo el array de respuestas y lo incializo a null
    'DescUsuario' => null
];

try { //Mostrar los datos del usuario actual en el formulario
    $DB207DWESProyectoTema5 = new PDO(HOST, USER, PASSWORD);//Hago la conexion con la base de datos
    $DB207DWESProyectoTema5 -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);// Establezco el atributo para la aparicion de errores con ATTR_ERRMODE y le pongo que cuando haya un error se lance una excepcion con ERRMODE_EXCEPTION

    $consulta = "SELECT * FROM T01_Usuario WHERE T01_CodUsuario=:CodUsuario"; //Creo la consulta y le paso el usuario a la consulta
    $resultadoConsulta=$DB207DWESProyectoTema5->prepare($consulta); // Preparo la consulta antes de ejecutarla
    $aParametrosMostrar = [ //Array de parametros con el usuario de la sesion
        ":CodUsuario" => $_SESSION['usuarioDAW207AppLoginLogout'] //El usuario de la sesion acutal
    ];
    $resultadoConsulta->execute($aParametrosMostrar);//Ejecuto la consulta con el array de parametrosMostrar
    $oUsuario = $resultadoConsulta->fetchObject(); //Obtengo un objeto con el usuario
            
    $descripcionUsuarioActual = $oUsuario->T01_DescUsuario; //Almaceno la descripcion del usuario actual para mostrarla en el formulario de editar
    $conexionesUsuarioActual = $oUsuario->T01_NumConexiones; //Almaceno el numero de conexiones del usuario actual para mostrarla en el formulario de editar
    $fechaUltimaConexionActual  = $oUsuario->T01_FechaHoraUltimaConexion; //Almaceno la fechahora de la ultima conexion del usuario actual para mostrarla en el formulario de editar
    $passwordActual  = $oUsuario->T01_Password; //Almaceno la password del usuario actual para mostrarla en el formulario de editar
    $imagenUsuarioActual  = $oUsuario->T01_ImagenUsuario; //Almaceno la imagen del usuario actual para mostrarla en el formulario de editar
}catch(PDOException $excepcion){//Codigo que se ejecuta si hay algun error
    $errorExcepcion = $excepcion->getCode();//Obtengo el codigo del error y lo almaceno en la variable errorException
    $mensajeException = $excepcion->getMessage();//Obtengo el mensaje del error y lo almaceno en la variable mensajeException
    echo "<p style='color: red'>Codigo del error: </p>" . $errorExcepcion;//Muestro el codigo del error
    echo "<p style='color: red'>Mensaje del error: </p>" . $mensajeException;//Muestro el mensaje del error
}finally{
    unset($DB207DWESProyectoTema5);//Cierro la conexion
}

if (isset($_REQUEST['eliminarcuenta'])) { //Si se ha pulsado eliminarcuenta elimino la cuenta del usuario actual
    
        try {
            $DB207DWESProyectoTema5 = new PDO(HOST, USER, PASSWORD);//Hago la conexion con la base de datos
            $DB207DWESProyectoTema5 -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);// Establezco el atributo para la aparicion de errores con ATTR_ERRMODE y le pongo que cuando haya un error se lance una excepcion con ERRMODE_EXCEPTION

            $consulta = "DELETE FROM T01_Usuario WHERE T01_CodUsuario=:CodUsuario"; //Creo la consulta y le paso el usuario a la consulta
            $resultadoConsulta=$DB207DWESProyectoTema5->prepare($consulta); // Preparo la consulta antes de ejecutarla
            $aParametrosEliminar = [ //Array de parametros con el usuario de la sesion
                ":CodUsuario" => $_SESSION['usuarioDAW207AppLoginLogout'] //El usuario de la sesion acutal
            ];
            $resultadoConsulta->execute($aParametrosEliminar);//Ejecuto la consulta con el array de parametrosEliminar para eliminar el usuario

            session_destroy(); //Elimino todos los datos que contiene la sesion
            header('Location: ../indexProyectoLoginLogoutTema5.php'); //Redireccion a el index mediante el header
            exit;
        }catch(PDOException $excepcion){//Codigo que se ejecuta si hay algun error
            $errorExcepcion = $excepcion->getCode();//Obtengo el codigo del error y lo almaceno en la variable errorException
            $mensajeException = $excepcion->getMessage();//Obtengo el mensaje del error y lo almaceno en la variable mensajeException
            echo "<p style='color: red'>Codigo del error: </p>" . $errorExcepcion;//Muestro el codigo del error
            echo "<p style='color: red'>Mensaje del error: </p>" . $mensajeException;//Muestro el mensaje del error
        }finally{
            unset($DB207DWESProyectoTema5);//Cierro la conexion
        }
    
}

//Comprobar si se ha pulsado el boton aceptar
if (isset($_REQUEST['aceptar'])) { //Si le ha dado al boton de aceptar valido los datos
    $aErrores['DescUsuario'] = validacionFormularios::comprobarAlfabetico($_REQUEST['DescUsuario'], 255, 1, OBLIGATORIO); //Compruebo si la descripcion del usuario esta bien rellenada
    
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
        
        $consultaUpdate = "UPDATE T01_Usuario SET T01_DescUsuario = :DescUsuario WHERE T01_CodUsuario = :CodUsuario";
        $resultadoConsultaUpdate = $DB207DWESProyectoTema5->prepare($consultaUpdate); // Preparo la consulta antes de ejecutarla
        $aParametrosUpdate = [ //Array de parametros para el update
            ":CodUsuario" => $_SESSION['usuarioDAW207AppLoginLogout'], //El usuario pasado en el formulario
            ":DescUsuario" => $_REQUEST['DescUsuario'] //La descripcion pasada en el formulario
        ];
        $resultadoConsultaUpdate->execute($aParametrosUpdate);//Ejecuto la consulta con el array de parametros para actualizar la descripcion del usuaro en la DB

        header('Location: programa.php'); //Mando al usuario a la pagina programa.php
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
        <meta name="application-name" content="Edit user">
        <meta name="description" content="Editar un usuario con una cuenta existente o eliminar su cuenta">
        <link href="../webroot/css/estiloejercicio.css" rel="stylesheet" type="text/css">
        <link rel="icon" href="../webroot/css/img/home.png" type="image/x-icon">
        <title>Editar Perfil Tema 5</title>
        <style>
            form{
                margin-top: 15px;
                margin-bottom: 70px;
            }
            fieldset{
                border: 2px solid black;
                width: 375px;
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
                height: 8px;
            }
        </style>
        <script>
            //Funcion para mostrar un mensaje de confirmacion antes de eliminar el usuario
            function confirmarEliminar(){
                var respuesta = confirm("¿Estas seguro que quieres eliminar el usuario?");
                if(respuesta == true){
                    return true;
                }else{
                    return false;
                }
            }
        </script>
    </head>
    <body>
        <div class="container">
            <header class="tituloaplicacion">
                <h1>207DWESLoginLogoutTema5</h1>
            </header>
            <article class="titulopagina">
                <h2><?php  echo $aIdioma[$_COOKIE['idioma']]['editarusuario'] //Muestro editar usuario en el idioma selecionado en el index ?></h2>
            </article>
            <form name="formulario" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" class="form">
                    <fieldset>
                        <p class="titulo">Editar Perfil de Usuario<p>
                        <ul>
                            <!--Campo Usuario OBLIGATORIO -->
                            <li>
                                <div>
                                    <label for="CodUsuario"><strong>Usuario</strong></label>
                                    <input name="CodUsuario" id="CodUsuario" type="text" value="<?php 
                                    echo $_SESSION['usuarioDAW207AppLoginLogout'] ?>" readonly disabled>
                                </div>
                            </li>
                            <!--Campo Descripcion OBLIGATORIO-->
                            <li>
                                <div>
                                    <label for="DescUsuario"><strong>Descripcion*</strong></label>
                                    <input name="DescUsuario" id="DescUsuario2" type="text" value="<?php 
                                    echo isset($_REQUEST['DescUsuario']) ? $_REQUEST['DescUsuario'] : $descripcionUsuarioActual; ?>" 
                                    placeholder="Introduzca la descripcion">
                                    <?php echo '<p class="errores"><span>' . $aErrores['DescUsuario'] . '</span></p>' ?>
                                </div>
                            </li>
                            <!--Campo Numero Conexiones OBLIGATORIO-->
                            <li>
                                <div>
                                    <label for="NumConexiones"><strong>Numero Conexiones</strong></label>
                                    <input name="NumConexiones" id="NumConexiones2" type="text" value="<?php 
                                    echo $conexionesUsuarioActual ?>" readonly disabled>
                                </div>
                            </li>
                            <!--Campo Fecha Ultima Conexion OBLIGATORIO-->
                            <li>
                                <div>
                                    <label for="FechaHoraUltimaConexion"><strong>Fecha Ultima Conexion</strong></label>
                                    <input name="FechaHoraUltimaConexion" id="FechaHoraUltimaConexion2" type="text" value="<?php 
                                    echo date('d/m/Y H:i:s',$fechaUltimaConexionActual) ?>" readonly disabled>
                                </div>
                            </li>
                            <!--Campo Password OBLIGATORIO-->
                            <li>
                                <div>
                                    <label for="Password"><strong>Password</strong></label>
                                    <input name="Password" id="Password2" type="password" value="<?php 
                                    echo $passwordActual ?>" readonly disabled>
                                </div>
                            </li>
                            <!--Campo Imagen OBLIGATORIO-->
                            <li>
                                <div>
                                    <label for="ImagenUsuario"><strong>Imagen</strong></label>
                                    <input name="ImagenUsuario" id="ImagenUsuario2" type="text" value="<?php 
                                    echo $imagenUsuarioActual ?>" readonly disabled>
                                </div>
                            </li>
                            
                            <!--Campo Boton Cambiar Password-->
                            <li>
                                <input type="submit" value="CAMBIAR PASSWORD" name="cambiarpassword" class="cambiarpassword"/>
                            </li>
                            
                            <!--Campo Botones Aceptar, Cancelar y Eliminar Cuenta-->
                            <li>
                                <input type="submit" value="ACEPTAR" name="aceptar" class="aceptar"/>
                                <input type="submit" value="CANCELAR" name="cancelar" class="cancelarperfil"/>
                                <input type="submit" value="ELIMINAR USUARIO" name="eliminarcuenta" class="eliminarcuenta" onclick="return confirmarEliminar()"/>
                                
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

