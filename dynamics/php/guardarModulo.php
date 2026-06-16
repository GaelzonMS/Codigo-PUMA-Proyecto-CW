<?php
    session_start();

    if (!isset($_SESSION['idUsuario'])) 
    {
        header("Location: ../../index.php"); 
        exit(); 
    }
    
    include 'conexion.php';

    // Si la petición viene de un formulario (POST) procesamos y redireccionamos con variables
    if ($_SERVER["REQUEST_METHOD"] == 'POST')
    {
        // limpieza de datos
        $numModulo = intval($_POST["numModulo"]);
        $materiaIdMateria = mysqli_real_escape_string($conn, $_POST["idMateria"]); 
        $nombreModulo = mysqli_real_escape_string($conn, $_POST["nombreModulo"]);
        $descripcionModulo = mysqli_real_escape_string($conn, $_POST["descripcion"]);
        
        // Consulta SQL limpia para insertar en la tabla 'modulo'
        $sqlQueryText = "INSERT INTO modulo (nombreModulo, numModulo, descripcion, Materia_idMateria)
                        VALUES ('$nombreModulo', $numModulo, '$descripcionModulo', '$materiaIdMateria')";
        
        $queryCreacionModulo = mysqli_query($conn, $sqlQueryText);
        if ($queryCreacionModulo)
        {
            header("Location: visualizacionProfesor.php?idMateria=" . $materiaIdMateria . "&status=exito");
            exit();
        }
        else
        {
            header("Location: visualizacionProfesor.php?idMateria=" . $materiaIdMateria . "&status=error");
            exit();
        }
    }
    else
    {
        // Si intentan entrar directo escribiendo la URL en el navegador (GET), los botamos al buscador
        header("Location: index.html");
        exit();
    }
?>