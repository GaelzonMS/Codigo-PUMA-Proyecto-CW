<?php
    session_start();

    if (!isset($_SESSION['idUsuario'])) //si no existe una sesion con id usuario
    {
        header("Location: ../../index.php"); // se regresa a index y pide que inicie sesion
        exit(); // se sale de dashboard.php
    }
    include 'conexion.php';
    include 'encabezadoFooter.php';
    echo $encabezado;
    echo "<h1 class='titulo cienCentrado'>Bienvenidx ". $_SESSION['nombre'] ." </h1>";

    $idProfesor = $_SESSION['idUsuario'];

    $sqlMateria = "SELECT m.idMateria, m.nombre AS nombreMateria
                    FROM inscripcion i
                    INNER JOIN materia m ON i.Materia_idMateria = m.idMateria
                    WHERE i.Usuario_idUsuario = '$idProfesor'";
    
    $resultado = mysqli_query($conn, $sqlMateria);
?>
    <main id="mainDashboardProf" class='contenedorOcupaTodo'>
        <p>Materias:</p>
        <div class='contenedorDeBotonesModulo'>
            <!-- <a href='modulo.php' rel="noopener noreferrer"  id="botonModuloNombre" class='contenedorNombreModuloVistaMateria crezca'>
                <h3>Nombre del modulo</h3>
            </a> -->
            <?php
                if($resultado && mysqli_num_rows($resultado)>0)
                {
                    while($materia = mysqli_fetch_assoc($resultado))
                    {
                        $idMateria = $materia['idMateria'];
                        $nombreMateria = $materia['nombreMateria'];

                        echo "
                                <a href='visualizacionProfesor.php?idMateria=$idMateria' rel='noopener noreferrer' class='contendorNombreModuloVistaMateria crezca'>
                                    <h4>$nombreMateria</h4>                            
                                </a>
                            ";
                    }
                }
            ?>
        </div>
    </main>