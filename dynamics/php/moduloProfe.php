<?php
    session_start();

    if (!isset($_SESSION['idUsuario'])) //si no existe una sesion con id usuario
    {
        header("Location: ../../index.php"); // se regresa a index y pide que inicie sesion
        exit(); // se sale de dashboard.php
    }
    include 'conexion.php';
/* --------------------------------------------------------------------------------- */
//////////// verifica si le llego el idModulo por GET el id del modulo que viene desde visualizacionProfesor.php
    if(isset($_GET['idModulo']))
    {
        $idModuloSeleccionado = mysqli_real_escape_string($conn, $_GET['idModulo']); //evitamos una inyeccion de datos
        //consulta para obtener el modulo que seleccionamos en la materia
        $sqlModulo = "SELECT nombreModulo, numModulo, descripcion, Materia_idMateria FROM modulo WHERE idModulo='$idModuloSeleccionado'";
        $resultadoModulo = mysqli_query($conn, $sqlModulo); 
        ///// desmenuzamos
        if($resultadoModulo && mysqli_num_rows($resultadoModulo) > 0) // verificamos que la consulta si se haya hecho bien
        {
            $datosModulo = mysqli_fetch_assoc($resultadoModulo); //arreglo asociativo 
            //guardamos los datos del arreglo asociativo en diferentes variables
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
    else //si no encuentra el modulo desde el url te regresa a index
    {
        header("Location: ../../index.html");
        exit();
    }
    ///////////////// CONSULTA DE ALUMNOS
    $sqlAlumnos = "SELECT u.idUsuario, u.nombre, u.apellidoPaterno, u.apellidoMaterno, u.tipoAprendizaje_idtipoAprendizaje  , (SELECT COUNT(*) FROM asistencia a WHERE a.Usuario_idUsuario = u.idUsuario AND a.campo = 'Ausente') AS totalFaltas /*subconsulta que cuenta de la columna campo, cuantos ausentes tiene y lo guarda como totalFaltas */
                FROM inscripcion i INNER JOIN usuario u /**relacionamos la inscripcion con el usuario */
                ON i.Usuario_idUsuario = u.idUsuario 
                WHERE i.Materia_idMateria = '$idMateriaSeleccionada' /** condicion que tiene que ser la materia de la vista la que tiene que buscar */ 
                ORDER BY u.apellidoPaterno, u.apellidoMaterno ASC";  // ordenamos por apellido de A-Z
    //consulta            
    $resultadoAlumnos = mysqli_query($conn, $sqlAlumnos);
    $totalAlumnos = mysqli_num_rows($resultadoAlumnos);

    ///////////// Logica de clases totales de la materia
    $sqlTotalClases = "SELECT COUNT(DISTINCT a.fecha) AS totalClases /*le decimos que queremos buscar, contar las fechas y guardarlas como total Clases */
                        FROM asistencia a INNER JOIN inscripcion i /*para esto relacionamos la asistencia con la inscripcion */
                        ON a.Usuario_idUsuario = i.Usuario_idUsuario  
                        WHERE i.Materia_idMateria = '$idMateriaSeleccionada'"; // la condicion que lo tiene q buscar en base a la materia seleccionada, lo relacionamos con la materia pq en nuestra base de datos la asistencia no la relacionamos con el modulo en si, sino con la materia
    //consulta
    $consultaSqlTotalClases = mysqli_query($conn, $sqlTotalClases);
    $fetchConsultaSqlTotalClases = mysqli_fetch_assoc($consultaSqlTotalClases);
    //condicion terniaria, el total de las clases es mayor a 0?, sisi guarda la variable en totalClases, de lo contrario guarda el valor de 1, esto para evitar bugs o errores por si una materia es nueva
    $totalClases = ($fetchConsultaSqlTotalClases['totalClases'] > 0) ? $fetchConsultaSqlTotalClases['totalClases'] : 1;
    ////////////////////////////////////
    // Arreglo multidimensional para guardar alumnos procesados
    $alumnosProcesados = [];
    $porcentajeAlumnoDesercion = 0; 
    /////////////////logica de como se calcula el promedio, y como se determina si esta en riesgo de desertar
    if($totalAlumnos > 0)
    {
        $iRiesgo = 0; 
        while($alumno = mysqli_fetch_assoc($resultadoAlumnos))
        {
            $estiloDeAprendizajeAlumno = "sin asignar";
            //inicializamos las variables para cada alumno
            $nombreCompleto = $alumno['apellidoPaterno']. " " . $alumno['apellidoMaterno'] . " " . $alumno['nombre'];
            $faltas = $alumno['totalFaltas']; 
            $idAlumno = $alumno['idUsuario']; 
            $IdestiloDeAprendizajeAlumno = $alumno['tipoAprendizaje_idtipoAprendizaje'];
            $sqlNombreEstiloAprendizaje = "SELECT nombreAprendizaje FROM tipoAprendizaje 
                                                WHERE idtipoAprendizaje='$IdestiloDeAprendizajeAlumno' ";
            $consultaNombreEstiloAprendizaje = mysqli_query($conn, $sqlNombreEstiloAprendizaje);
            $fetchEstiloAprendizaje = mysqli_fetch_assoc($consultaNombreEstiloAprendizaje);
            $estiloDeAprendizajeAlumno = $fetchEstiloAprendizaje['nombreAprendizaje'];
            $promedio = 0;
            //calculamos el promedio por las actividades rel al modulo actual
            $sqlPromedio = "SELECT AVG(calificacion) AS promedioAlumno /*usamos avg que significa average = promedio, lo que hace es que promedia la columna de las coincidencias (en esta consulta, las calificaciones xd) */
                            FROM entrega INNER JOIN actividad 
                            ON entrega.Actividad_idActividad = actividad.idActividad
                            WHERE Usuario_idUsuario='$idAlumno'
                            AND actividad.Modulo_idModulo = '$idModuloSeleccionado'";
            $resultadoPromedio = mysqli_query($conn, $sqlPromedio);
            //verificamos que si sido exitoso el query del promedio
            if($resultadoPromedio)
            {
                $fetchPromedio = mysqli_fetch_assoc($resultadoPromedio); //arreglo asociativo
                if($fetchPromedio['promedioAlumno'] !== null) // si el promedio del alumno no es null
                {   
                    $promedio = round($fetchPromedio['promedioAlumno'], 1); //redondea el promedio y lo guarda en promedio, a 1 decimal
                }
            }
            // condicion de desercion basada en las faltas generales y promedio
            $porcentajeFaltas = ($faltas / $totalClases) * 100; //calculamos y convertimos a porcentaje, dividimos el total de las faltas entre todas las clases
            $enRiesgo = false; //variable bandera para poder diferenciar mejor a los que estan en riesgo de los que no
            if($porcentajeFaltas > 20 || $promedio < 8) //si se cumplen estos dos factores de desercion
            {
                $enRiesgo = true; //volteamos la bandera de riesgo a tru
                $iRiesgo++; //sumamos iRiesgo (esto es para poder sacar un porcentaje sobre todo el grupo de cuantos podrian desertar)
            }
            $alumnosProcesados[] = //guardamos todo lo que procesamos para 1 alumno en esta iteracion del while en el arreglo multi
            [
                'nombre' => $nombreCompleto, 
                'faltas' => $faltas,
                'promedio' => $promedio,
                'riesgo' => $enRiesgo,
                'tipoAprendizaje_idtipoAprendizaje' => $estiloDeAprendizajeAlumno
            ];
        }
        //calculamos el porcentaje de cuantos alumnos sobre el grupo estan en riesgo de desertar
        $porcentajeAlumnoDesercion = round(($iRiesgo / $totalAlumnos) * 100, 1); 
    }
    // consulta para obtener las actividades del modulo y poder desplegarlas
    $sqlActividades = "SELECT idActividad, titulo 
                    FROM actividad WHERE Modulo_idModulo = '$idModuloSeleccionado' 
                    ORDER BY idActividad DESC"; //de mas a menos recientes
    $resultadoActividades = mysqli_query($conn, $sqlActividades);
    $totalActividades = mysqli_num_rows($resultadoActividades);
    //imprimimos el nav
    include 'encabezadoFooter.php';
    echo $encabezado;
?>
<link rel="stylesheet" href="../../statics/styles/vistaProfeMateria.css">
    <div class="contenedorMateria">

        <header class="materiaHeader">
            <h1>
                Módulo <?php echo $numModulo; ?>: <?php echo $nombreModulo; ?>
            </h1>
            <p><?php echo $descripcion; ?></p>
        </header>

        <div class="seccionEstadisticas">

            <details class="bloquedesplegable"> <!-- etiqueta que permite deslegar el contenido con un click -->
                <summary class="bloqueEstadistico"> <!-- lo que aparece cuando no se la hado click -->
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
                                <th>Estilo de aprendizaje</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                if(!empty($alumnosProcesados))
                                {
                                    foreach($alumnosProcesados as $eachAlumno)
                                    {       /**para no concatenar con soble punto, y como son arreglos asociativos, ocupamos la estructura de {} */
                                        echo "
                                                <tr>
                                                    <td>{$eachAlumno['nombre']}</td>
                                                    <td>{$eachAlumno['faltas']}</td>
                                                    <td>{$eachAlumno['promedio']}</td>
                                                    <td>{$eachAlumno['tipoAprendizaje_idtipoAprendizaje']}</td>
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
                                <th>Estilo de aprendizaje</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                $hayAlumnosEnRiesgo = false;
                                if(!empty($alumnosProcesados)) //imprimimos a los alumnos que tienen en su bandera de riesgo que tengan true
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
                                                    <td>{$eachAlumno['tipoAprendizaje_idtipoAprendizaje']}</td>
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
                            if(!empty($alumnosProcesados)) //imprimimos el promedio general del grupo
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
                    <a href="crearActividad.php?idModulo=<?php echo $idModuloSeleccionado; ?>" class="textoChiquito textoNegro"> + Crear nueva actividad </a>
                    <br>
                    <p>crear actividades para este modulo.</p>
                    <br>
                    <div class="lista-actividades">
                        <?php
                            if($totalActividades > 0) //desplegamos las actividades existentes
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
                        <?php
                            //logica para obtener la variable get del estatus enviado cuando creamos una actividad
                            if (isset($_GET['status']))
                            {   
                                echo "<div class='contenedorMensajeSistema'>";
                                if ($_GET['status'] === 'exito')
                                {
                                    echo "<p class='mensajeExito'><strong>Se creo la actividad</strong></p>";
                                }
                                else if ($_GET['status'] === 'error')
                                {
                                    echo "<p class='mensajeError'><strong>Hubo un problema al intentar crear la actividad (flop).</strong></p>";
                                }
                                echo "</div>";
                            }
                        ?>
                    </div>
                </div>
            </section>
        </div>
    </div>
</body>
</html>