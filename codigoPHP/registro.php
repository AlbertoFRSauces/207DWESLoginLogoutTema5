<?php
/*
 * @author: Alberto Fernandez Ramirez
 * @since: 06/12/2021
 * @version: 1.0 Realizacion del registro
 * @copyright: Copyright (c) 2021, Alberto Fernandez Ramirez
 * Pagina para registrar un nuevo usuario
 */
//Comprobar si se ha pulsado el boton volver
if (isset($_REQUEST['cancelar'])) { //Si se ha pulsado vuelvo a el index de la web
    header('Location: login.php'); //Redireccion a el index mediante el header
    exit;
}

require_once '../core/libreriaValidacion.php'; //Incluyo la libreria de validacion
require_once '../config/configDBPDO.php'; //Incluyo las variables de la conexion

define("OBLIGATORIO", 1);//Variable obligatorio inicializada a 1
$entradaOK = true;//Variable de entrada correcta inicializada a true
            
$aErrores = [ //Creo el array de errores y lo inicializo a null
    'CodUsuario' => null,
    'Password' => null,
    'DescUsuario' => null,
    'RepetirPassword' => null
];
            
$aRespuestas = [ //Creo el array de respuestas y lo incializo a null
    'CodUsuario' => null,
    'Password' => null,
    'DescUsuario' => null,
    'RepetirPassword' => null
];

//Comprobar si se ha pulsado el boton entrar
if (isset($_REQUEST['crear'])) { //Si le ha dado al boton de enviar valido los datos
    $aErrores['CodUsuario'] = validacionFormularios::comprobarAlfabetico($_REQUEST['CodUsuario'], 10, 1, OBLIGATORIO); //Compruebo si el nombre de usuario esta bien rellenado
    $aErrores['DescUsuario'] = validacionFormularios::comprobarAlfabetico($_REQUEST['DescUsuario'], 255, 1, OBLIGATORIO); //Compruebo si la descripcion del usuario esta bien rellenada
    $aErrores['Password'] = validacionFormularios::validarPassword($_REQUEST['Password'], 8, 1, 1, OBLIGATORIO); //Compruebo si la password esta bien rellenada
    $aErrores['RepetirPassword'] = validacionFormularios::validarPassword($_REQUEST['RepetirPassword'], 8, 1, 1, OBLIGATORIO); //Compruebo si la password repetida esta bien rellenada
    if ($aErrores['CodUsuario'] == null || $aErrores['DescUsuario'] == null || $aErrores['Password'] || $aErrores['RepetirPassword']){ //Compruebo que el codUsuario, descUsuario, password y la Password repetida tienen el formato correcto si el array de errores tiene null
        try {
            $DB207DWESProyectoTema5 = new PDO(HOST, USER, PASSWORD);//Hago la conexion con la base de datos
            $DB207DWESProyectoTema5 -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);// Establezco el atributo para la aparicion de errores con ATTR_ERRMODE y le pongo que cuando haya un error se lance una excepcion con ERRMODE_EXCEPTION

            $consulta = "SELECT * FROM T01_Usuario WHERE T01_CodUsuario=:CodUsuario"; //Creo la consulta y le paso el usuario a la consulta
            $resultadoConsulta=$DB207DWESProyectoTema5->prepare($consulta); // Preparo la consulta antes de ejecutarla
            $aParametros1 = [
                ":CodUsuario" => $_REQUEST['CodUsuario']
            ];
            $resultadoConsulta->execute($aParametros1);//Ejecuto la consulta con el array de parametros
            $oUsuario = $resultadoConsulta->fetchObject(); //Obtengo un objeto con el usuario
            
            if($resultadoConsulta->rowCount() > 0){ //Si la consulta es mayor que 0, el usuario ya existe en la DB
                $aErrores['CodUsuaro'] = "El usuario ya existe."; //Si el usuario ya existe, almaceno el error en el array de errores de CodUsuaro
            }
            if($aErrores['Password'] != $aErrores['RepetirPassword']){ //Compruebo si la password es distinta que la password repetida
                $aErrores['RepetirPassword'] = "Las passwords no coinciden."; //Si las passwords no son iguales, almaceno el error en el array de errores de RepetirPsasword
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
        
        $consultaInsert = "INSERT INTO T01_Usuario (T01_CodUsuario, T01_Password, T01_DescUsuario) VALUES (:CodUsuario, :Password, :DescUsuario)";
        $resultadoConsultaInsert = $DB207DWESProyectoTema5->prepare($consultaInsert); // Preparo la consulta antes de ejecutarla
        $aParametrosInsert = [ //Array de parametros para el insert
            ":CodUsuario" => $_REQUEST['CodUsuario'], //El usuario pasado en el formulario
            ":Password" => hash("sha256", ($_REQUEST['CodUsuario'].$_REQUEST['Password'])), //La password pasada en el formulario
            ":DescUsuario" => $_REQUEST['DescUsuario']
        ];
        $resultadoConsultaInsert->execute($aParametrosInsert);//Ejecuto la consulta con el array de parametros para meter el usuario nuevo a la DB
        
        $consultaUpdate = "UPDATE T01_Usuario SET T01_NumConexiones=:NumConexiones, T01_FechaHoraUltimaConexion=:FechaHoraUltimaConexion WHERE T01_CodUsuario=:CodUsuario"; //Consulta para actualizar el total de conexiones y la fechahora de la ultima conexion
        $resultadoConsultaUpdate = $DB207DWESProyectoTema5->prepare($consultaUpdate); // Preparo la consulta antes de ejecutarla
        
        $aParametrosUpdate = [ //Array de parametros para el update
            ":NumConexiones" => (1), //Le asigno la primera conexion
            ":FechaHoraUltimaConexion" => time(), //Asigno hora local actual con una marca temporal usando time()
            ":CodUsuario" => $_REQUEST['CodUsuario'] //El usuario nuevo pasado en el formulario
        ];
        $resultadoConsultaUpdate->execute($aParametrosUpdate);//Ejecuto la consulta con el array de parametros
        
        
        session_start(); //Creo una nueva sesion o recupero una existente
        $_SESSION['usuarioDAW207AppLoginLogout'] = $_REQUEST['CodUsuario']; //Almaceno el usuario en $_SESSION
        $_SESSION['fechaHoraUltimaConexionAnteriorDAW207AppLoginLogout'] = null; //Almaceno la ultima conexion en $_SESSION, en este caso es null ya que es un usuario nuevo

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
        <meta name="application-name" content="New user">
        <meta name="description" content="Registro de un nuevo usuario">
        <link href="../webroot/css/estiloejercicio.css" rel="stylesheet" type="text/css">
        <link rel="icon" href="../webroot/css/img/home.png" type="image/x-icon">
        <title>Registro Tema 5</title>
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
                height: 14px;
            }
        </style>
    </head>
    <body>
        <div class="container">
            <form name="formulario" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" class="form">
                    <fieldset>
                        <p class="titulo">Registro nuevo usuario<p>
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
                            <!--Campo Descripcion OBLIGATORIO-->
                            <li>
                                <div>
                                    <label for="DescUsuario"><strong>Descripcion*</strong></label>
                                    <input name="DescUsuario" id="DescUsuario" type="text" value="<?php 
                                    echo isset($_REQUEST['DescUsuario']) ? $_REQUEST['DescUsuario'] : null; ?>" 
                                    placeholder="Introduzca la descripcion">
                                    <?php echo '<p class="errores"><span>' . $aErrores['DescUsuario'] . '</span></p>' ?>
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
                           <!--Campo Password Repetir OBLIGATORIO-->
                            <li>
                                <div>
                                    <label for="RepetirPassword"><strong>Repetir Password*</strong></label>
                                    <input name="RepetirPassword" id="RepetirPassword" type="password" value="<?php 
                                    echo isset($_REQUEST['RepetirPassword']) ? $_REQUEST['RepetirPassword'] : null; ?>" 
                                    placeholder="Introduzca la password de nuevo">
                                    <?php echo '<p class="errores"><span>' . $aErrores['RepetirPassword'] . '</span></p>' ?>
                                </div>
                            </li>
                            
                            <!--Campo Botones Crear y Cancelar-->
                            <li>
                                <input type="submit" value="CREAR" name="crear" class="crear"/>
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
                <p>Ultima actualización: 06/12/2021 19:33 - Release 2.0</p>
            </footer>
        </div>
    </body>
</html>

