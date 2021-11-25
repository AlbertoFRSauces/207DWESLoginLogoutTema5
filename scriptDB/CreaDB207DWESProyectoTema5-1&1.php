<?php

/**
 * Author:  Alberto Fernandez Ramirez
 * Created: 3 nov. 2021
 * Script creación de base de datos y usuario
 */
//Incluyo las variables de la conexion
require_once '../config/configDBPDO.php';

try {
    //Hago la conexion con la base de datos
    $DB207DWESProyectoTema5 = new PDO(HOST, USER, PASSWORD);

    // Establezco el atributo para la aparicion de errores con ATTR_ERRMODE y le pongo que cuando haya un error se lance una excepcion con ERRMODE_EXCEPTION
    $DB207DWESProyectoTema5->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    //Consulta para crear las tablas
    $consulta = <<<CONSULTA
                CREATE TABLE IF NOT EXISTS dbs4868800.T01_Usuario(
                    T01_CodUsuario varchar(10) PRIMARY KEY,
                    T01_Password varchar(64) NOT NULL,
                    T01_DescUsuario varchar(255) NOT NULL,
                    T01_NumConexiones int DEFAULT 0,
                    T01_FechaHoraUltimaConexion int,
                    T01_Perfil enum('administrador', 'usuario') DEFAULT 'usuario',
                    T01_ImagenUsuario mediumblob NULL
                )engine=innodb;
                CREATE TABLE IF NOT EXISTS DB207DWESProyectoTema5.T02_Departamento(
                    T02_CodDepartamento varchar(3) PRIMARY KEY,
                    T02_DescDepartamento varchar(255) NOT NULL,
                    T02_FechaCreacionDepartamento int NULL,
                    T02_VolumenDeNegocio float NULL,
                    T02_FechaBajaDepartamento date NULL
                )engine=innodb;
                CONSULTA;

    $DB207DWESProyectoTema5->exec($consulta); //Ejecuto la consulta

    echo '<a class="exitoInsercion">Tablas creadas con éxito.</a>';
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