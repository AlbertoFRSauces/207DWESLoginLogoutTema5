<?php
/**
 * Author:  Alberto Fernandez Ramirez
 * Created: 3 nov. 2021
 * Script borrado de base de datos y usuario
 */
//Incluyo las variables de la conexion
require_once '../config/configDBPDO.php';

try {
    //Hago la conexion con la base de datos
    $DB207DWESProyectoTema5 = new PDO(HOST, USER, PASSWORD);

    // Establezco el atributo para la aparicion de errores con ATTR_ERRMODE y le pongo que cuando haya un error se lance una excepcion con ERRMODE_EXCEPTION
    $DB207DWESProyectoTema5 ->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    //Consulta para eliminar las tablas
    $consulta = <<<CONSULTA
                DROP TABLE IF EXISTS dbs4868800.T01_Usuario;
                DROP TABLE IF EXISTS dbs4868800.T02_Departamento;
                CONSULTA;

    $DB207DWESProyectoTema5->exec($consulta); //Ejecuto la consulta

    echo '<a class="exitoInsercion">Tablas eliminadas con éxito.</a>';
} catch (PDOException $excepcion) {//Codigo que se ejecuta si hay algun error
    $errorExcepcion = $excepcion->getCode(); //Obtengo el codigo del error y lo almaceno en la variable errorException
    $mensajeException = $excepcion->getMessage(); //Obtengo el mensaje del error y lo almaceno en la variable mensajeException
    echo "<span style='color: red'>Codigo del error: </span>" . $errorExcepcion; //Muestro el codigo del error
    echo "<span style='color: red'>Mensaje del error: </span>" . $mensajeException; //Muestro el mensaje del error
} finally {
    //Cierro la conexion
    unset($DB207DWESProyectoTema5);
}
?>