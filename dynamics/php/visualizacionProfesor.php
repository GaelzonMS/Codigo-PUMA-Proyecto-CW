<?php
    session_start();

    if (!isset($_SESSION['idUsuario'])) //si no existe una sesion con id usuario
    {
        header("Location: ../../index.php"); // se regresa a index y pide que inicie sesion
        exit(); // se sale de dashboard.php
    }
    include 'conexion.php';

    if(isset($_GET['idMateria']))
    {
        $idMateriaSeleccionada = mysqli_real_escape_string($conn, $_GET['idMateria']);

        $sqlMateria = "SELECT nombre FROM materia WHERE idMateria='$idMateriaSeleccionada'";
        $resultadoMateria = mysqli_query($conn, $sqlMateria);

        if($resultadoMateria && mysqli_num_rows($resultadoMateria) >  0)
        {
            $datosMateria =mysqli_fetch_assoc($resultadoMateria);
            $nombreMateria = $datosMateria['nombre'];
        }
        else
        {
            header("Location: dashboardProf.php");
            exit();
        }
    }
    else
    {
        header("Location: ../../index.html");
    }

    $sqlAlumnos = " SELECT u.idUsuario, u.nombre, u.apellidoPaterno, u.apellidoMaterno FROM inscripcion i INNER JOIN usuario u ON i.Usuario_idUsuario = u.idUsuario WHERE i.Materia_idMateria = '$idMateriaSeleccionada' ORDER BY u.apellidoPaterno, u.apellidoMaterno ASC";
    $resultadoAlumnos = mysqli_query($conn, $sqlAlumnos);
    $totalAlumnos = mysqli_num_rows($resultadoAlumnos);

    $sqlModulos = "SELECT idModulo, nombreModulo, numModulo FROM modulo WHERE Materia_idMateria = '$idMateriaSeleccionada' ORDER BY numModulo ASC";
    $resultadoModulos = mysqli_query($conn, $sqlModulos);

    include 'encabezadoFooter.php';
    echo $encabezado;
?>
<!-- ---------------------------------------------------------------------------------------- -->
    <link rel="stylesheet" href="../../statics/styles/vistaProfeMateria.css">
    
    <div class="contenedorMateria">
        <header class="materiaHeader">
            <h1>
                Materia: <?php echo $nombreMateria; ?>
            </h1>
        </header>

        <div class="seccionEstadisticas">
            
            <details class="bloquedesplegable">
                <summary class="bloqueEstadistico">
                    <p class="numeroDato">num. alumnos</p>
                    <p class="etiquetaDato">Alumnos</p>
                    <p class="texto">Ver lista</p>
                </summary>
                
                <div class="contenedorTabla">
                    <h3>Lista de Alumnos</h3>
                    <table class="tablaAlumnos">
                        <thead>
                            <tr>
                                <th>Nombre</th>
                                <th>Faltas</th>
                                <th>Promedio</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                if($totalAlumnos > 0)
                                {
                                    while($alumno = mysqli_fetch_assoc($resultadoAlumnos))
                                    {
                                        $nombreCompleto = $alumno['apellidoPaterno'] . " " . $alumno['apellidoMaterno'] . " " . $alumno['nombre'];

                                        echo "
                                            <tr><td>$nombreCompleto</td><td>faltas</td><td>promedio</td></tr>
                                        ";
                                    }
                                }
                                else
                                {
                                    echo "<tr><td colspan='3' style='text-align:center;'>No hay alumnos inscritos en esta materia.</td></tr>";
                                }
                            ?>
                        </tbody>
                    </table>
                </div>
            </details>

            <details class="bloquedesplegable">
                <summary class="bloqueEstadistico alerta">
                    <p class="numeroDato">--%</p>
                    <p class="etiquetaDato">Alumnos de desercion</p>
                    <p class="textoAyuda">Ver los alumnos en riesgo</p>
                </summary>
                
                <div class="contenedorTabla">
                    <h3>Alumnos en Riesgo de Desercion</h3>
                    <table class="tablaAlumnos">
                        <thead>
                            <tr>
                                <th>Nombre</th>
                                <th>Faltas</th>
                                <th>Promedio</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="altoriesgo"><td>nombre</td><td>faltas</td><td>promedio</td></tr>
                            <tr class="altoriesgo"><td>nombre</td><td>faltas</td><td>promedio</td></tr>
                        </tbody>
                    </table>
                </div>
            </details>

            <div class="bloquedesplegable">
                <div class="bloqueEstadistico neutro">
                    <p class="numeroDato">promedio</p>
                    <p class="etiquetaDato">Promedio general del grupo</p>
                </div>
            </div>

        </div>

        <div class="pantallaDividida">
            <section class="columnaModulos">
                <h2>Modulos</h2>
                <div class="modulos">
                    <?php 
                        if($resultadoModulos && mysqli_num_rows($resultadoModulos) > 0)
                        {

                            while($modulo = mysqli_fetch_assoc($resultadoModulos))
                            {
                                $numModulo = $modulo['numModulo'];
                                $nombreModulo = $modulo['nombreModulo'];
                                $idModulo = $modulo['idModulo'];

                                echo "
                                    <div class='tarjetaModulo'>
                                        <h3>Módulo $numModulo: $nombreModulo</h3>
                                    </div>
                                ";
                            }
                        }
                        else
                        {
                            echo "<p>No se han registrado modulos para esta asignatura todavía.</p>";
                        }
                    ?>
                </div>
            </section>
        </div>

    </div>
</body>
</html>

