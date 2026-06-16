<?php
    session_start(); //se inicia sesion
    if (!isset($_SESSION['idUsuario'])) //si no existe una sesion con id usuario
    {
        header("Location: ../../index.php"); // se regresa a index y pide que inicie sesion
        exit(); // se sale de dashboard.php
    }
    include 'conexion.php';
    include 'encabezadoFooter.php';
    echo $encabezado; // traemos el encabezado y le damos la bienvenida al usuario
    echo "<h1 class='titulo cienCentrado'>Bienvenidx ". $_SESSION['nombre'] ." </h1>";
    $idProfesor = $_SESSION['idUsuario']; // de la variable global de usuario sacamos el id
    //consulta donde encontramos que materias tienen relacion con el profe
    $sqlMateria = "SELECT m.idMateria, m.nombre AS nombreMateria 
                    FROM inscripcion i INNER JOIN materia m 
                    ON i.Materia_idMateria = m.idMateria
                    WHERE i.Usuario_idUsuario = '$idProfesor'";
    //consulta
    $resultado = mysqli_query($conn, $sqlMateria);
?>
    <main id="mainDashboardProf" class='contenedorOcupaTodo'>
        <p>Materias:</p>
        <div class='contenedorDeBotonesModulo'>
            <?php //logica para desplegar como links las materias
                if($resultado && mysqli_num_rows($resultado)>0)
                {
                    while($materia = mysqli_fetch_assoc($resultado))
                    {
                        $idMateria = $materia['idMateria'];
                        $nombreMateria = $materia['nombreMateria'];

                        echo "
                                <a href='visualizacionProfesor.php?idMateria=$idMateria' rel='noopener noreferrer' class='contendorNombreModuloVistaMateria crezca textoNegro sinDelineado btnGris redondeo25px bordeNegro'>
                                    <h4>$nombreMateria</h4>                            
                                </a>
                            ";
                    }
                }
            ?>
        </div>
    </main>