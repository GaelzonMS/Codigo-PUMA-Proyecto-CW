<?php
    session_start();

    if (!isset($_SESSION['idUsuario'])) //si no existe una sesion con id usuario
    {
        header("Location: ../../index.php"); // se regresa a index y pide que inicie sesion
        exit(); // se sale de dashboard.php
    }
    include 'conexion.php';

//////////// verifica si le llego el idModulo por GET
    if(isset($_GET['idModulo']))
    {
        $idModuloSeleccionado = mysqli_real_escape_string($conn, $_GET['idModulo']); //evitamos una inyeccion de datos
        
        $sqlModulo = "SELECT nombreModulo, numModulo, descripcion, Materia_idMateria FROM modulo WHERE idModulo='$idModuloSeleccionado'";
        $resultadoModulo = mysqli_query($conn, $sqlModulo); 
        
        if($resultadoModulo && mysqli_num_rows($resultadoModulo) > 0)
        {
            $datosModulo = mysqli_fetch_assoc($resultadoModulo); 
            $nombreModulo = $datosModulo['nombreModulo']; 
            $numModulo    = $datosModulo['numModulo'];
            $descripcion  = $datosModulo['descripcion'];
            $idMateriaSeleccionada = $datosModulo['Materia_idMateria'];
        }
        else
        { //si no encuentra el modulo por el sql te regresa al dashboard
            header("Location: dashboardProf.php");
            exit();
        }
    } 
    else
    {
        header("Location: ../../index.html");
        exit();
    }

    ///////////////// CONSULTA DE ALUMNOS
    $sqlAlumnos = "SELECT u.idUsuario, u.nombre, u.apellidoPaterno, u.apellidoMaterno,
                (SELECT COUNT(*) FROM asistencia a WHERE a.Usuario_idUsuario = u.idUsuario AND a.campo = 'Ausente') AS totalFaltas
                FROM inscripcion i INNER JOIN usuario u 
                ON i.Usuario_idUsuario = u.idUsuario 
                WHERE i.Materia_idMateria = '$idMateriaSeleccionada' 
                ORDER BY u.apellidoPaterno, u.apellidoMaterno ASC";                    
    $resultadoAlumnos = mysqli_query($conn, $sqlAlumnos);
    $totalAlumnos = mysqli_num_rows($resultadoAlumnos);
    
    ///////////// Logica de clases totales de la materia
    $sqlTotalClases = "SELECT COUNT(DISTINCT a.fecha) AS totalClases
                        FROM asistencia a INNER JOIN inscripcion i
                        ON a.Usuario_idUsuario = i.Usuario_idUsuario
                        WHERE i.Materia_idMateria = '$idMateriaSeleccionada'";
    $consultaSqlTotalClases = mysqli_query($conn, $sqlTotalClases);
    $fetchConsultaSqlTotalClases = mysqli_fetch_assoc($consultaSqlTotalClases);
    $totalClases = ($fetchConsultaSqlTotalClases['totalClases'] > 0) ? $fetchConsultaSqlTotalClases['totalClases'] : 1;

    // Arreglo multidimensional para guardar alumnos procesados
    $alumnosProcesados = [];
    $porcentajeAlumnoDesercion = 0; 
    if($totalAlumnos > 0)
    {
        $iRiesgo = 0; 
        while($alumno = mysqli_fetch_assoc($resultadoAlumnos))
        {
            $nombreCompleto = $alumno['apellidoPaterno']. " " . $alumno['apellidoMaterno'] . " " . $alumno['nombre'];
            $faltas = $alumno['totalFaltas']; 
            $idAlumno = $alumno['idUsuario']; 
            $promedio = "-";
            $promedioAEvaluar = 0;
            //calculamos el promedio por las actividades rel al modulo actual
            $sqlPromedio = "SELECT AVG(calificacion) AS promedioAlumno 
                            FROM entrega INNER JOIN actividad 
                            ON entrega.Actividad_idActividad = actividad.idActividad
                            WHERE Usuario_idUsuario='$idAlumno'
                            AND actividad.Modulo_idModulo = '$idModuloSeleccionado'";
            $resultadoPromedio = mysqli_query($conn, $sqlPromedio);
            if($resultadoPromedio)
            {
                $fetchPromedio = mysqli_fetch_assoc($resultadoPromedio);
                if($fetchPromedio['promedioAlumno'] !== null)
                {   
                    $promedio = round($fetchPromedio['promedioAlumno'], 1);
                    $promedioAEvaluar = $promedio; 
                }
            }
            // condicion de desercion basada en las faltas generales y promedio
            $porcentajeFaltas = ($faltas / $totalClases) * 100;
            $enRiesgo = false; 
            if($porcentajeFaltas > 20 || $promedioAEvaluar < 8) 
            {
                $enRiesgo = true; 
                $iRiesgo++;
            }
            $alumnosProcesados[] = 
            [
                'nombre' => $nombreCompleto, 
                'faltas' => $faltas,
                'promedio' => $promedio,
                'riesgo' => $enRiesgo
            ];
        }
        $porcentajeAlumnoDesercion = round(($iRiesgo / $totalAlumnos) * 100, 1); 
    }
    $sqlActividades = "SELECT idActividad, titulo 
                    FROM actividad WHERE Modulo_idModulo = '$idModuloSeleccionado' 
                    ORDER BY idActividad DESC"; //de mas a menos recientes

$resultadoActividades = mysqli_query($conn, $sqlActividades);
$totalActividades = mysqli_num_rows($resultadoActividades);

    include 'encabezadoFooter.php';
    echo $encabezado;
?>
<link rel="stylesheet" href="../../statics/styles/vistaProfeMateria.css">
    
    <div class="contenedorMateria">
        <header class="materiaHeader">
            <h1>
                Módulo <?php echo $numModulo; ?>: <?php echo $nombreModulo; ?>
            </h1>
            <p style="color: #666; margin-top: 5px; font-style: italic;"><?php echo $descripcion; ?></p>
        </header>

        <div class="seccionEstadisticas">
            
            <details class="bloquedesplegable">
                <summary class="bloqueEstadistico">
                    <p class="numeroDato">num. alumnos = <?php echo $totalAlumnos;?></p>
                    <p class="etiquetaDato">Alumnos</p>
                    <p class="texto">Ver lista</p>
                </summary>
                
                <div class="contenedorTabla">
                    <h3>Lista de Alumnos</h3>
                    <table class="tablaAlumnos">
                        <thead>
                            <tr>
                                <th>Nombre</th>
                                <th>Faltas (Materia)</th>
                                <th>Promedio del Módulo</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                if(!empty($alumnosProcesados))
                                {
                                    foreach($alumnosProcesados as $eachAlumno)
                                    {
                                        echo "
                                                <tr>
                                                    <td>{$eachAlumno['nombre']}</td>
                                                    <td>{$eachAlumno['faltas']}</td>
                                                    <td>{$eachAlumno['promedio']}</td>
                                                </tr>
                                            ";
                                    }
                                }
                                else
                                {
                                    echo "<tr><td colspan='3' style='text-align:center;'>No hay alumnos evaluados en este módulo.</td></tr>";
                                }
                            ?>
                        </tbody>
                    </table>
                </div>
            </details>

            <details class="bloquedesplegable">
                <summary class="bloqueEstadistico alerta">
                    <p class="numeroDato"><?php echo $porcentajeAlumnoDesercion;?>%</p>
                    <p class="etiquetaDato">Alumnos en riesgo de deserción</p>
                    <p class="textoAyuda">Ver</p>
                </summary>
                
                <div class="contenedorTabla">
                    <h3> Asistencia de menos de 80% y promedio menor a 8</h3>
                    <table class="tablaAlumnos">
                        <thead>
                            <tr>
                                <th>Nombre</th>
                                <th>Faltas</th>
                                <th>Promedio Módulo</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                $hayAlumnosEnRiesgo = false;
                                if(!empty($alumnosProcesados))
                                {
                                    foreach($alumnosProcesados as $eachAlumno)
                                    {
                                        if($eachAlumno['riesgo'] === true)
                                        {
                                            $hayAlumnosEnRiesgo = true;
                                            echo "
                                                <tr class='altoriesgo'>
                                                    <td>{$eachAlumno['nombre']}</td>
                                                    <td>{$eachAlumno['faltas']}</td>
                                                    <td>{$eachAlumno['promedio']}</td>
                                                </tr>
                                            ";
                                        }
                                    }
                                }
                                if(!$hayAlumnosEnRiesgo)
                                {
                                    echo "<tr><td colspan='3' style='text-align:center; color: green;'>Excelente: Ningún alumno está en riesgo en este módulo.</td></tr>";
                                }
                            ?>
                        </tbody>
                    </table>
                </div>
            </details>

            <div class="bloquedesplegable">
                <div class="bloqueEstadistico neutro">
                    <p class="numeroDato">
                        <?php 
                            if(!empty($alumnosProcesados))
                            {
                                $i = 0;
                                $sumaPromedios = 0;
                                foreach($alumnosProcesados as $eachAlumno)
                                {
                                    if(is_numeric($eachAlumno['promedio']))
                                    {
                                        $sumaPromedios += $eachAlumno['promedio'];
                                        $i++;
                                    }
                                }
                                $promedioGrupo = ($i > 0) ? round($sumaPromedios / $i, 1) : "-";
                                echo $promedioGrupo;
                            }
                            else
                            {
                                echo "-";
                            }
                        ?>
                    </p>
                    <p class="etiquetaDato">Promedio General Módulo</p>
                </div>
            </div>
        </div>
        <div class="pantallaDividida">
            <section class="columnaModulos">
                <h2>Actividades del Módulo</h2>
                <div class="modulos">
                    <a href="crearActividad.php?idModulo=<?php echo $idModuloSeleccionado; ?>" class="btn-crear" style="margin-bottom: 15px; display: inline-block;"> + Crear nueva actividad </a>
                    <p>Aquí crear actividades para este modulo.</p>
                    <hr>
                    <div class="lista-actividades">
                        <?php
                            if($totalActividades > 0)
                            {
                                while($actividad = mysqli_fetch_assoc($resultadoActividades))
                                {
                                    echo"
                                        <div class='tarjetaModulo'>
                                            <a href='actividadProfe.php?idActividad=".$actividad['idActividad']."' rel='noopener noreferrer' class='sinDelineado textoNaranja'>".$actividad['titulo']."</a>
                                        </div>
                                        ";
                                        
                                }
                            }
                            else
                            {
                                echo "<p>Este módulo aún no tiene actividades asignadas.</p>";
                            }
                        ?>
                    </div>
                </div>
            </section>
        </div>
    </div>
</body>
</html>