<?php
    session_start();

    if (!isset($_SESSION['idUsuario'])) 
    {
        header("Location: ../../index.php"); 
        exit(); 
    }
    include 'conexion.php'; 
    
    $queryActualizacion = false;
    $idActividad = "";

    if ($_SERVER["REQUEST_METHOD"] == 'POST')
    {
        $idActividad = mysqli_real_escape_string($conn, $_POST["idActividad"]);
        
        //verificacion de que lleguen las calificaciones desde la estructura del formulario
        if (isset($_POST['calificaciones']) && is_array($_POST['calificaciones']))
        {
            $huboError = false;
            //recorremos el arreglo asociativo donde la llave es el idUsuario y el valor es la calificacion
            foreach ($_POST['calificaciones'] as $idUsuario => $calificacion)
            {
                $idUsuarioSanitizado = mysqli_real_escape_string($conn, $idUsuario);
                //si el input viene vacio se guarda como la palabra clave NULL pura para el query de SQL
                if ($calificacion === "" || $calificacion === null)
                {
                    $valorCalificacion = "NULL";
                }
                else
                {
                    // nos aseguramos de convertir la calificacion a numerico
                    $valorCalificacion = floatval($calificacion);
                }
                //verificamos si el alumno ya cuenta con un registro previo de entrega para esta actividad especifica
                $sqlCheck = "SELECT idEntrega FROM entrega 
                            WHERE Usuario_idUsuario = '$idUsuarioSanitizado' 
                            AND Actividad_idActividad = '$idActividad'";   
                $resultadoCheck = mysqli_query($conn, $sqlCheck);
                if ($resultadoCheck && mysqli_num_rows($resultadoCheck) > 0)
                {
                    //si el registro ya existe en la base de datos se actualiza el campo de la calificacion
                    $sqlUpdate = "UPDATE entrega SET calificacion = $valorCalificacion 
                                WHERE Usuario_idUsuario = '$idUsuarioSanitizado' 
                                AND Actividad_idActividad = '$idActividad'";      
                    $queryUpdate = mysqli_query($conn, $sqlUpdate);
                    if (!$queryUpdate)
                    {
                        $huboError = true;
                    }
                }
                else
                {
                    //si el registro no existe creamos la entrega desde cero asignando un identificador unico universal uuid
                    $idEntregaNueva = bin2hex(random_bytes(16)); 
                    $fechaActual = date("Y-m-d H:i:s");
                    $sqlInsert = "INSERT INTO entrega (idEntrega, fechaCreacion, archivo, calificacion, Actividad_idActividad, Usuario_idUsuario) 
                                VALUES ('$idEntregaNueva', '$fechaActual', NULL, $valorCalificacion, '$idActividad', '$idUsuarioSanitizado')";                 
                    $queryInsert = mysqli_query($conn, $sqlInsert);
                    if (!$queryInsert)
                    {
                        $huboError = true;
                    }
                }
            }
            // si al terminar todo el bucle no se registro ningun fallo en las consultas cambiamos el estatus a verdadero
            if (!$huboError)
            {
                $queryActualizacion = true;
            }
        }
    }
    //redireccion dinamica hacia la vista de la actividad pasando el estado por medio de una variable get
    if ($queryActualizacion)
    {
        header("Location: actividadProfe.php?idActividad=" . $idActividad . "&status=exito");
        exit();
    }
    else
    {
        header("Location: actividadProfe.php?idActividad=" . $idActividad . "&status=error");
        exit();
    }
?>