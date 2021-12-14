<?php
/*
 * @author: Alberto Fernandez Ramirez
 * @since: 29/11/2021
 * @version: 1.0 Realizacion del login
 * @copyright: Copyright (c) 2021, Alberto Fernandez Ramirez
 * Pagina para iniciar sesion
 */
//Comprobar si se ha pulsado el boton volver
if (isset($_REQUEST['volver'])) { //Si se ha pulsado vuelvo a el index de la web
    header('Location: ../indexProyectoLoginLogoutTema5.php'); //Redireccion a el index mediante el header
    exit;
}

if (isset($_REQUEST['registrarse'])) { //Si se ha pulsado entro en registrarme
    header('Location: registro.php'); //Redireccion a registro mediante el header
    exit;
}

require_once '../config/configAPP.php'; //Incluyo el array de idiomas para la COOKIE
require_once '../core/libreriaValidacion.php'; //Incluyo la libreria de validacion
require_once '../config/configDBPDO.php'; //Incluyo las variables de la conexion

define("OBLIGATORIO", 1);//Variable obligatorio inicializada a 1
$entradaOK = true;//Variable de entrada correcta inicializada a true
            
$aErrores = [ //Creo el array de errores y lo inicializo a null
    'CodUsuario' => null,
    'Password' => null
];
            
$aRespuestas = [ //Creo el array de respuestas y lo incializo a null
    'CodUsuario' => null,
    'Password' => null
];

//Comprobar si se ha pulsado el boton entrar
if (isset($_REQUEST['entrar'])) { //Si le ha dado al boton de enviar valido los datos
    $aErrores['CodUsuario'] = validacionFormularios::comprobarAlfabetico($_REQUEST['CodUsuario'], 200, 1, OBLIGATORIO); //Compruebo si el nombre de usuario esta bien rellenado
    $aErrores['Password'] = validacionFormularios::validarPassword($_REQUEST['Password'], 8, 1, 1, OBLIGATORIO); //Compruebo si la password esta bien rellenada
    if ($aErrores['CodUsuario'] == null || $aErrores['Password'] == null){ //Compruebo que el codUsuario y la Password tienen el formato correcto si el array de errores tiene null
        try {
            $DB207DWESProyectoTema5 = new PDO(HOST, USER, PASSWORD);//Hago la conexion con la base de datos
            $DB207DWESProyectoTema5 -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);// Establezco el atributo para la aparicion de errores con ATTR_ERRMODE y le pongo que cuando haya un error se lance una excepcion con ERRMODE_EXCEPTION

            $consulta = "SELECT T01_FechaHoraUltimaConexion FROM T01_Usuario WHERE T01_CodUsuario=:CodUsuario AND T01_Password=:Password"; //Creo la consulta y le paso el usuario a la consulta
            $resultadoConsulta=$DB207DWESProyectoTema5->prepare($consulta); // Preparo la consulta antes de ejecutarla
            $aParametros1 = [
                ":CodUsuario" => $_REQUEST['CodUsuario'],
                ":Password" => hash("sha256", ($_REQUEST['CodUsuario'].$_REQUEST['Password']))
            ];
            $resultadoConsulta->execute($aParametros1);//Ejecuto la consulta con el array de parametros
            $oUsuario = $resultadoConsulta->fetchObject(); //Obtengo un objeto con el usuario y su password
            
            if($resultadoConsulta->rowCount() == 0){ //Si la consulta no tiene ningun registro es que no esta bien el usuario o la password
                $aErrores['Password'] = "Error en el login."; //Si no es correcto, almaceno el error en el array de errores
            }
            
            if (!$oUsuario) { //Si la consulta no devuelve ningun resultado, el usuario no existe o la password no coincide con el usuario introducido
                $entradaOK = false; //Le doy el valor false a la entrada
            }
        }catch(PDOException $excepcion){//Codigo que se ejecuta si hay algun error
            $errorExcepcion = $excepcion->getCode();//Obtengo el codigo del error y lo almaceno en la variable errorException
            $mensajeException = $excepcion->getMessage();//Obtengo el mensaje del error y lo almaceno en la variable mensajeException
            echo "<p style='color: red'>Codigo del error: </p>" . $errorExcepcion;//Muestro el codigo del error
            echo "<p style='color: red'>Mensaje del error: </p>" . $mensajeException;//Muestro el mensaje del error
        }finally{
            unset($DB207DWESProyectoTema5);//Cierro la conexion
        }
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
        
        $ultimaConexion = $oUsuario->T01_FechaHoraUltimaConexion; //Guardo la fechahora de la ultima conexion que tiene la base de datos en una variable
        
        $consultaUpdate = "UPDATE T01_Usuario SET T01_NumConexiones=T01_NumConexiones+1, T01_FechaHoraUltimaConexion=:FechaHoraUltimaConexion WHERE T01_CodUsuario=:CodUsuario"; //Consulta para actualizar el total de conexiones y la fechahora de la ultima conexion
        $resultadoConsultaUpdate = $DB207DWESProyectoTema5->prepare($consultaUpdate); // Preparo la consulta antes de ejecutarla
        
        $aParametros2 = [ //Array de parametros para el update
            ":FechaHoraUltimaConexion" => time(), //Asigno hora local actual con una marca temporal usando time()
            ":CodUsuario" => $_REQUEST['CodUsuario'] //El usuario pasado en el formulario
        ];
        $resultadoConsultaUpdate->execute($aParametros2);//Ejecuto la consulta con el array de parametros
        
        session_start(); //Creo una nueva sesion o recupero una existente
        $_SESSION['usuarioDAW207AppLoginLogout'] = $_REQUEST['CodUsuario']; //Almaceno el usuario en $_SESSION
        $_SESSION['fechaHoraUltimaConexionAnterior'] = $ultimaConexion; //Almaceno la ultima conexion en $_SESSION

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
        <meta name="application-name" content="Login y logout">
        <meta name="description" content="Control de acceso e identificación de un usuario">
        <link href="../webroot/css/estiloejercicio.css" rel="stylesheet" type="text/css">
        <link rel="icon" href="../webroot/css/img/home.png" type="image/x-icon">
        <title>Login Tema 5</title>
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
               margin-left: 44px; 
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
                height: 15px;
            }
            body{
                background: url("https://www.mascotahogar.com/1920x1080/dibujo-de-un-gato-como-wallpaper.jpg") no-repeat center center fixed;
            }
        </style>
    </head>
    <body>
        <div class="container">
            <header class="tituloaplicacion">
                <h1>207DWESLoginLogoutTema5</h1>
            </header>
            <article class="titulopagina">
                <h2><?php  echo $aIdioma[$_COOKIE['idioma']]['iniciarsesion'] //Muestro iniciar sesion en el idioma selecionado en el index ?></h2>
            </article>
            <form name="formulario" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" class="form">
                    <fieldset>
                        <p class="titulo">Iniciar sesión<p>
                        <ul>
                            <!--Campo Usuario OBLIGATORIO -->
                            <li>
                                <div>
                                    <label for="CodUsuario"><strong>Usuario*</strong></label>
                                    <input name="CodUsuario" id="CodUsuario" type="text" value="<?php 
                                    echo isset($_REQUEST['CodUsuario']) ? $_REQUEST['CodUsuario'] : null; ?>" 
                                    placeholder="Introduzca el nombre de usuario">
                                    <?php echo '<p class="errores"><span>' . $aErrores['CodUsuario'] . '</span></p>' ?>
                                </div>
                            </li>
                            <!--Campo Password OBLIGATORIO-->
                            <li>
                                <div>
                                    <label for="Password"><strong>Password*</strong></label>
                                    <input name="Password" id="Password" type="password" value="<?php 
                                    echo isset($_REQUEST['Password']) ? $_REQUEST['Password'] : null; ?>" 
                                    placeholder="Introduzca la password">
                                    <?php echo '<p class="errores"><span>' . $aErrores['Password'] . '</span></p>' ?>
                                </div>
                            </li>
                            <!--Campo Botones Entrar y Volver y registrarse-->
                            <li>
                                <input type="submit" value="ENTRAR" name="entrar" class="entrar"/>
                                <input type="submit" value="VOLVER" name="volver" class="volver"/>
                                <input type="submit" value="registrarse" name="registrarse" class="registrarse"/>
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


