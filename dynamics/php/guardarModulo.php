<?php
    session_start();
    if (!isset($_SESSION['idUsuario'])) // si no existe una sesion con id usuario
    {
        header("Location: ../../index.php"); // se regresa a index y pide que inicie sesion
        exit(); // se sale
    }
    include 'conexion.php'; // Asegúrate de que aquí adentro tu variable de conexión sea $conn
    $queryCreacionModulo = false; 
    if($_SERVER["REQUEST_METHOD"] == 'POST')
    {
        // Sanitización y formateo de datos
        $numModulo         = intval($_POST["numModulo"]);
        $Materia_idMateria = $_POST["idMateria"]; 
        
        $nombreModulo      = mysqli_real_escape_string($conn, $_POST["nombreModulo"]);
        $descripcionModulo = mysqli_real_escape_string($conn, $_POST["descripcion"]);
        
        // Consulta SQL limpia para insertar en la tabla 'modulo'
        $sql_query_text = "INSERT INTO modulo (nombreModulo, numModulo, descripcion, Materia_idMateria)
                            VALUES ('$nombreModulo', $numModulo, '$descripcionModulo', $Materia_idMateria)";
        
        $queryCreacionModulo = mysqli_query($conn, $sql_query_text);
    }
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Guardar Módulo - LEGOD</title>
    <link rel="stylesheet" href="./css/styles.css">
</head>
<body>

    <div class="encabezado">
        <h1>LEG<span>O</span>D</h1>
    </div>

    <div class="menu-navegacion">
        <a href="index.html" class="btn-menu">Buscar</a>
        <a href="crear.php" class="btn-menu">Crear</a>
    </div>

    <div class="mensaje">
        <h3>Resultado de la inserción:</h3>
        <p>
        <?php 
            if($queryCreacionModulo)
            {
                echo "¡EL MÓDULO SE GUARDÓ CON ÉXITO :D!";
            }
            else
            {
                echo "<strong>Flop informático.</strong><br>";
                // Muestra el detalle exacto de MySQL en caso de errores inesperados de sintaxis o conexión
                echo "Detalle del error: " . mysqli_error($conn);
            }
        ?>
        </p>
        <br>
        <a href="crear.php" style="color: #000; font-weight:bold;">Añadir otro módulo</a> | 
        <a href="index.html" style="color: #000; font-weight:bold;">Ir al buscador</a>
    </div>

</body>
</html>