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
    header('Location: ../indexProyectoLoginLogoutTema5.php');
    exit;
}

session_start(); //Creo una nueva sesion o recupero una existente

require_once '../core/libreriaValidacion.php'; //Incluyo la libreria de validacion
require_once '../config/configDBPDO.php'; //Incluyo las variables de la conexion

define("OBLIGATORIO", 1);//Variable obligatorio inicializada a 1
$entradaOK = true;//Variable de entrada correcta inicializada a true
            
//Creo el array de errores y lo inicializo a null
$aErrores = [
    'CodUsuario' => null,
    'Password' => null
];
            
//Creo el array de respuestas y lo incializo a null
$aRespuestas = [
    'CodUsuario' => null,
    'Password' => null
];

//Comprobar si se ha pulsado el boton entrar
if (isset($_REQUEST['entrar'])) { //Si le ha dado al boton de enviar valido los datos
    $aErrores['CodUsuario'] = validacionFormularios::comprobarAlfabetico($_REQUEST['CodUsuario'], 200, 1, OBLIGATORIO); //Compruebo si el nombre de usuario esta bien rellenado
    $aErrores['Password'] = validacionFormularios::validarPassword($_REQUEST['Password'], 8, 1, 1, OBLIGATORIO); //Compruebo si la password esta bien rellenada
    if($aErrores['CodUsuario'] == null || $aErrores['Password'] == null){
        try{
            $DB207DWESProyectoTema5 = new PDO(HOST, USER, PASSWORD);//Hago la conexion con la base de datos
            $DB207DWESProyectoTema5 -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);// Establezco el atributo para la aparicion de errores con ATTR_ERRMODE y le pongo que cuando haya un error se lance una excepcion con ERRMODE_EXCEPTION

            $consulta = "SELECT T01_NumConexiones, T01_FechaHoraUltimaConexion, T01_Password FROM T01_Usuario WHERE T01_CodUsuario=:CodUsuario"; //Creo la consulta y le paso el usuario a la consulta
            $resultadoConsulta=$DB207DWESProyectoTema5->prepare($consulta); // Preparo la consulta antes de ejecutarla
            $aParametros1 = [
                ":CodUsuario" => $_REQUEST['CodUsuario']
            ];
            $resultadoConsulta->execute($aParametros1);//Ejecuto la consulta con el array de parametros

            $oUsuario = $resultadoConsulta->fetchObject(); //Obtengo un objeto con el usuario y su password
            if($resultadoConsulta->rowCount()>0){ //Si la consulta tiene algun registro
                $passwordEncriptada=hash("sha256", ($_REQUEST['CodUsuario'].$_REQUEST['Password'])); //Encripto la password que ha introducido el usuario
                if($oUsuario->T01_Password != $passwordEncriptada){ //Compruebo si la password es correcta
                    $aErrores['Password'] = "Password incorrecta."; //Si no es correcta, almaceno el error en el array de errores
                }
            }else{ //Si no he recibido ningun registro no existe el usuario en la DB
                $aErrores['CodUsuario'] = "El usuario no existe."; //Si no es correcto, almaceno el error en el array de errores
            }
        }catch(PDOException $excepcion){//Codigo que se ejecuta si hay algun error
            $errorExcepcion = $excepcion->getCode();//Obtengo el codigo del error y lo almaceno en la variable errorException
            $mensajeException = $excepcion->getMessage();//Obtengo el mensaje del error y lo almaceno en la variable mensajeException
            echo "<p style='color: red'>Codigo del error: </p>" . $errorExcepcion;//Muestro el codigo del error
            echo "<p style='color: red'>Mensaje del error: </p>" . $mensajeException;//Muestro el mensaje del error
        }finally{
            //Cierro la conexion
            unset($DB207DWESProyectoTema5);
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
        
        $numeroConexiones = $oUsuario->T01_NumConexiones; //Guardo el numero de conexiones que tiene la base de datos en una variable
        $ultimaConexion = $oUsuario->T01_FechaHoraUltimaConexion; //Guardo la fechahora de la ultima conexion que tiene la base de datos en una variable
        
        $consultaUpdate = "UPDATE T01_Usuario SET T01_NumConexiones=:NumConexiones, T01_FechaHoraUltimaConexion=:FechaHoraUltimaConexion WHERE T01_CodUsuario=:CodUsuario"; //Consulta para actualizar el total de conexiones y la fechahora de la ultima conexion
        $resultadoConsultaUpdate = $DB207DWESProyectoTema5->prepare($consultaUpdate); // Preparo la consulta antes de ejecutarla
        
        $aParametros3 = [ //Array de parametros para el update
            ":NumConexiones" => ($numeroConexiones+1), //Le sumo al total de conexiones una mas para contar la actual
            ":FechaHoraUltimaConexion" => time(), //Asigno hora local actual con una marca temporal usando time()
            ":CodUsuario" => $_REQUEST['CodUsuario'] //El usuario pasado en el formulario
        ];
        $resultadoConsultaUpdate->execute($aParametros3);//Ejecuto la consulta con el array de parametros
        
        $_SESSION['usuarioDAW207AppLoginLogout'] = $_REQUEST['CodUsuario']; //Almaceno el usuario en $_SESSION
        $_SESSION['fechaDAW207AppLoginLogout'] = $ultimaConexion; //Almaceno la ultima conexion en $SESSION

        header('Location: programa.php'); //Mando a el usuario a la pagina programa.php
        exit;
    }catch(PDOException $excepcion){//Codigo que se ejecuta si hay algun error
        $errorExcepcion = $excepcion->getCode();//Obtengo el codigo del error y lo almaceno en la variable errorException
        $mensajeException = $excepcion->getMessage();//Obtengo el mensaje del error y lo almaceno en la variable mensajeException
        echo "<p style='color: red'>Codigo del error: </p>" . $errorExcepcion;//Muestro el codigo del error
        echo "<p style='color: red'>Mensaje del error: </p>" . $mensajeException;//Muestro el mensaje del error
    }finally{
        //Cierro la conexion
        unset($DB207DWESProyectoTema5);
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
                width: 475px;
                margin:auto;
            }
            ul{
                list-style: none;
            }
            ul li{
                padding-left: 15px;
                padding-bottom: 15px;
                padding-right: 15px;
            }
            span{
                font-size: 90%;
                color: red;
            }
            .entrar{
                width: 200px;
                font-size: 100%;
                padding: 5px;
                margin: 5px;
                text-align: center;
                background-color: #252525;
                color: white;
                text-transform: uppercase;
                cursor: pointer;
            }
            .volver{
                width: 200px;
                font-size: 100%;
                padding: 5px;
                margin: 5px;
                text-align: center;
                background-color: #252525;
                color: white;
                text-transform: uppercase;
                cursor: pointer;
            }
            div input{
                width: 350px;
            }
            fieldset p:first-child{
                font-size: 190%;
                padding-top: 15px;
                padding-left: 15px;
                padding-bottom: 15px;
            }
            .ejemplo{
                height: 15px;
            }
        </style>
    </head>
    <body>
        <div class="container">
            <form name="formulario" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" class="form">
                    <fieldset>
                        <p>Iniciar sesión<p>
                        <ul>
                            <!--Campo Usuario OBLIGATORIO -->
                            <li>
                                <div>
                                    <label for="CodUsuario"><strong>Usuario*</strong></label>
                                    <input name="CodUsuario" id="CodUsuario" type="text" value="<?php 
                                    echo isset($_REQUEST['CodUsuario']) ? $_REQUEST['CodUsuario'] : null; ?>" 
                                    placeholder="Introduzca el nombre de usuario">
                                    <?php echo '<p class="ejemplo"><span>' . $aErrores['CodUsuario'] . '</span></p>' ?>
                                </div>
                            </li>
                            <!--Campo Password OBLIGATORIO-->
                            <li>
                                <div>
                                    <label for="Password"><strong>Password*</strong></label>
                                    <input name="Password" id="Password" type="password" value="<?php 
                                    echo isset($_REQUEST['Password']) ? $_REQUEST['Password'] : null; ?>" 
                                    placeholder="Introduzca la password">
                                    <?php echo '<p class="ejemplo"><span>' . $aErrores['Password'] . '</span></p>' ?>
                                </div>
                            </li>
                            <!--Campo Botones Eentrar y Salir-->
                            <li>
                                <input type="submit" value="ENTRAR" name="entrar" class="entrar"/>
                                <input type="submit" value="VOLVER" name="volver" class="volver"/>
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
                <p><a>&copy;</a>Alberto Fernández Ramírez 29/09/2021 Todos los derechos reservados.</p>
                <p>Ultima actualización: 29/11/2021 22:55</p>
            </footer>
        </div>
    </body>
</html>


