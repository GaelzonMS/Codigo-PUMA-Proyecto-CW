<?php
    session_start();

    if (!isset($_SESSION['idUsuario'])) 
    {
        header("Location: ../../index.php"); 
        exit(); 
    }
    
    include 'conexion.php';

////////////verifica si le llego un metodo ? get
    if (isset($_GET['idActividad']))
    {
        $idActividadSeleccionada = mysqli_real_escape_string($conn, $_GET['idActividad']); 
        
        //prompt para consulta sql obtiene los datos de la actividad y el modulo al que pertenece
        //ocupamos un inner join para vincular la actividad con su modulo correspondiente por medio del idModulo
        $sqlActividad = "SELECT a.titulo, a.descrpcion, a.fechaLimite, m.nombreModulo, m.numModulo, m.Materia_idMateria 
                        FROM actividad a 
                        INNER JOIN modulo m ON a.Modulo_idModulo = m.idModulo 
                        WHERE a.idActividad = '$idActividadSeleccionada'";
        //consulta
        $resultadoActividad = mysqli_query($conn, $sqlActividad);
        if ($resultadoActividad && mysqli_num_rows($resultadoActividad) > 0)
        {
            $datosActividad = mysqli_fetch_assoc($resultadoActividad);
            $nombreActividad = $datosActividad['titulo'];
            $descripcionActividad = $datosActividad['descrpcion'];
            $fechaLimite = $datosActividad['fechaLimite'];
            $nombreModulo = $datosActividad['nombreModulo'];
            $numModulo = $datosActividad['numModulo'];
            $idMateriaSeleccionada = $datosActividad['Materia_idMateria'];
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
        exit();
    }
    ///////////////// CONSULTA DE ALUMNOS INSCRITOS Y SUS ENTREGAS
    //ocupamos un left join con la tabla entrega para traer a todos los alumnos inscritos , sin importar si han entregado o no la actividad
    //si el idEntrega es nulo significa que el alumno no ha subido ningun archivo para esta actividad especifica
    $sqlEntregas = "SELECT u.idUsuario, u.nombre, u.apellidoPaterno, u.apellidoMaterno, e.idEntrega, e.calificacion, (CASE WHEN e.idEntrega IS NOT NULL THEN 'Sí' ELSE 'No' END) AS obtuvoEntrega
                    FROM inscripcion i INNER JOIN usuario u
                    ON i.Usuario_idUsuario = u.idUsuario 
                    LEFT JOIN entrega e ON e.Usuario_idUsuario = u.idUsuario 
                    AND e.Actividad_idActividad = '$idActividadSeleccionada'
                    WHERE i.Materia_idMateria = '$idMateriaSeleccionada'
                    ORDER BY u.apellidoPaterno, u.apellidoMaterno ASC";
    $resultadoEntregas = mysqli_query($conn, $sqlEntregas);
    $totalAlumnos = mysqli_num_rows($resultadoEntregas);
    /////////////logica para obtener estadisticas
    $totalEntregaron = 0;
    $totalNoEntregaron = 0;
    $sumaCalificaciones = 0;
    $alumnosConCalificacion = 0;
    $alumnosProcesados = [];
    if ($totalAlumnos > 0)
    {
        while ($entrega = mysqli_fetch_assoc($resultadoEntregas))
        {
            $nombreCompleto = $entrega['apellidoPaterno'] . " " . $entrega['apellidoMaterno'] . " " . $entrega['nombre'];
            if ($entrega['obtuvoEntrega'] == 'Sí')
            {
                $totalEntregaron = $totalEntregaron + 1;
            }
            else
            {
                $totalNoEntregaron = $totalNoEntregaron + 1;
            }
            if ($entrega['calificacion'] !== null)
            {
                $sumaCalificaciones = $sumaCalificaciones + $entrega['calificacion'];
                $alumnosConCalificacion = $alumnosConCalificacion + 1;
                $calificacionAlumno = $entrega['calificacion'];
            }
            else
            {
                $calificacionAlumno = "";
            }
            //guardamos la informacion en el arreglo asociativo para ocuparlo abajo
            $alumnosProcesados[] = 
            [
                'idUsuario' => $entrega['idUsuario'],
                'nombre' => $nombreCompleto,
                'entrego' => $entrega['obtuvoEntrega'],
                'calificacion' => $calificacionAlumno
            ];
        }
    }
    //condicion terniaria para evitar divisiones entre cero al sacar el promedio grupal
    $promedioActividad = ($alumnosConCalificacion > 0) ? round($sumaCalificaciones / $alumnosConCalificacion, 1) : 0;
    include 'encabezadoFooter.php';
    echo $encabezado;
?>
<link rel="stylesheet" href="../../statics/styles/vistaProfeMateria.css">
<div class="contenedorMateria">
    <header class="materiaHeader">
        <h1>Actividad: <?php echo $nombreActividad; ?></h1>
        <p>
            Módulo <?php echo $numModulo; ?>: <?php echo $nombreModulo; ?>
        </p>
        <p>
            <?php echo $descripcionActividad; ?>
        </p>
        <p>
            Fecha de entrega: <?php echo $fechaLimite; ?>
        </p>
    </header>

    <section class="seccionEstadisticas">
        <div class="bloquedesplegable">
            <div class="bloqueEstadistico">
                <p class="numeroDato"><?php echo $totalAlumnos; ?></p>
                <p class="etiquetaDato">Total de alumnos</p>
            </div>
        </div>
        <div class="bloquedesplegable">
            <div class="bloqueEstadistico">
                <p class="numeroDato"><?php echo $totalEntregaron; ?></p>
                <p class="etiquetaDato">Entregaron</p>
            </div>
        </div>
        <div class="bloquedesplegable">
            <div class="bloqueEstadistico alerta">
                <p class="numeroDato"><?php echo $totalNoEntregaron; ?></p>
                <p class="etiquetaDato">No entregaron</p>
            </div>
        </div>
        <div class="bloquedesplegable">
            <div class="bloqueEstadistico neutro">
                <p class="numeroDato"><?php echo $promedioActividad; ?></p>
                <p class="etiquetaDato">Promedio de calificación</p>
            </div>
        </div>
    </section>

    <form action="actualizarCalificaciones.php" method="POST">
        <input type="hidden" name="idActividad" value="<?php echo $idActividadSeleccionada; ?>">
        <div class="contenedorTabla">
            <h3>Registro de entregas y calificaciones</h3>
            <table class="tablaAlumnos">
                <thead>
                    <tr>
                        <th>Alumno</th>
                        <th>Entregó</th>
                        <th>Calificación</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        if (!empty($alumnosProcesados))
                        {
                            foreach ($alumnosProcesados as $cadaAlumno)
                            {
                                echo "
                                    <tr>
                                        <td>{$cadaAlumno['nombre']}</td>
                                        <td>{$cadaAlumno['entrego']}</td>
                                        <td>
                                            <input type='number' min='0' max='10' step='0.1' name='calificaciones[{$cadaAlumno['idUsuario']}]' value='{$cadaAlumno['calificacion']}' class='inputCalificacion'>
                                        </td>
                                    </tr>
                                ";
                            }
                        }
                        else
                        {
                            echo "<tr><td colspan='3'>No hay alumnos inscritos en esta actividad.</td></tr>";
                        }
                    ?>
                </tbody>
            </table>
            
        </div>
        <?php
            //logica para cachar la variable get del estatus enviado por el procesador de calificaciones
            if (isset($_GET['status']))
            {
                echo "<div class='contenedorMensajeSistema'>";
                
                if ($_GET['status'] === 'exito')
                {
                    echo "<p class='mensajeExito'><strong>¡Las calificaciones se actualizaron con éxito en el sistema! :D</strong></p>";
                }
                else if ($_GET['status'] === 'error')
                {
                    echo "<p class='mensajeError'><strong>Hubo un problema al intentar guardar las calificaciones (flop).</strong></p>";
                }
                
                echo "</div>";
            }
        ?>
        <div class="contenedorBotonGuardar">
            <button type="submit" class="btnGris">Guardar calificaciones</button>
        </div>
    </form>
</div>
</body>
</html>