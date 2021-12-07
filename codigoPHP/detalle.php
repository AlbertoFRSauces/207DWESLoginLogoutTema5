<?php
/*
 * @author: Alberto Fernandez Ramirez
 * @since: 30/11/2021
 * @version: 1.0 Realizacion del detalle
 * @copyright: Copyright (c) 2021, Alberto Fernandez Ramirez
 * Pagina de detalle para mostrar las variables $_session $_cookie $_server
 */

session_start(); //Creo una nueva sesion o recupero una existente

if (!isset($_SESSION['usuarioDAW207AppLoginLogout'])) { //Comprobar si el usuario no se ha autentificado
    header('Location: ../codigoPHP/login.php'); //Redirijo al usuario al login.php para que se autentifique
    exit;
}

//Comprobar si se ha pulsado el boton iniciar sesion
if (isset($_REQUEST['volver'])) {
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
            <form class="buttonback">
                <input type="submit" value="Volver" name="volver" class="volver"/>
            </form>
            <!–– Muestra del contenido de la variable $_SESSION con foreach()––>
            <?php if(!empty($_SESSION)){?>
                <h2>Mostrar $_SESSION con foreach()</h2>
                <table class="tablavariable"><tr><th class="cajas">Clave</th><th class="cajas">Valor</th></tr>
                <?php foreach ($_SESSION as $clave => $valor){?>
                    <tr>
                        <td><strong><?php echo $clave?></strong></td>
                        <td><?php echo $valor?></td>
                    </tr>
                <?php
                    }
                ?>
                </table>
                <br>
            <?php
            }
            ?>
                
            <!–– Muestra del contenido de la variable $_COOKIE con foreach()––>
            <?php if(!empty($_COOKIE)){?>
                <h2>Mostrar $_COOKIE con foreach()</h2>
                <table class="tablavariable"><tr><th class="cajas">Clave</th><th class="cajas">Valor</th></tr>
                <?php foreach ($_COOKIE as $clave => $valor){?>
                    <tr>
                        <td><strong><?php echo $clave?></strong></td>
                        <td><?php echo $valor?></td>
                    </tr>
                <?php
                    }
                ?>
                </table>
                <br>
            <?php
            }
            ?>
                
            <!–– Muestra del contenido de la variable $_SERVER con foreach()––>
            <?php if(!empty($_SERVER)){?>
                <h2>Mostrar $_SERVER con foreach()</h2>
                <table class="tablavariable"><tr><th class="cajas">Clave</th><th class="cajas">Valor</th></tr>
                <?php foreach ($_SERVER as $clave => $valor){?>
                    <tr>
                        <td><strong><?php echo $clave?></strong></td>
                        <td><?php echo $valor?></td>
                    </tr>
                <?php
                    }
                ?>
                </table>
                <br>
            <?php
            }
            ?>
            <footer class="piepagina">
                <a href="../codigoPHP/programa.php"><img src="../webroot/css/img/atras.png" class="imageatras" alt="IconoAtras" /></a>
                <a href="https://github.com/AlbertoFRSauces/207DWESLoginLogoutTema5" target="_blank"><img src="../webroot/css/img/github.png" class="imagegithub" alt="IconoGitHub" /></a>
                <p><a>&copy;</a><a href="http://daw207.ieslossauces.es/index.php">Alberto Fernández Ramírez</a> 29/09/2021 Todos los derechos reservados.</p>
                <p>Ultima actualización: 01/12/2021 20:06 - Release 2.0</p>
            </footer>
        </div>
    </body>
</html>

