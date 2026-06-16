<?php
    session_start();

    if (!isset($_SESSION['idUsuario'])) //si no existe una sesion con id usuario
    {
        header("Location: ../../index.php"); // se regresa a index y pide que inicie sesion
        exit(); // se sale de dashboard.php
    }
    include 'conexion.php';
////////////verifica si le llego un metodo ? get
    if(isset($_GET['idMateria']))
    {
        $idMateriaSeleccionada = mysqli_real_escape_string($conn, $_GET['idMateria']); //evitamos una inyeccion de datos
        //prompt para consulta sql obtiene nombre de la materia
        $sqlMateria = "SELECT nombre FROM materia WHERE idMateria='$idMateriaSeleccionada'";
        //consulta 
        $resultadoMateria = mysqli_query($conn, $sqlMateria); 
        //verifiacion de que si se hizo bn el query, demenusacion jeje
        if($resultadoMateria)
        {
            $datosMateria =mysqli_fetch_assoc($resultadoMateria); // array asociativo del nombre de la materia
            $nombreMateria = $datosMateria['nombre']; //guardamos el nombre en una variable para despues deslpegarla como titulo
        }
        else
        { //si no encuentra la materia por el sql te regresa al dashboard
            header("Location: dashboardProf.php");
            exit();
        }
    } //si no encuentra la materia por la url se regresa a index
    else
    {
        header("Location: ../../index.html");
        exit();
    }
    ///////////////// CONSULTA DE ALUMNOS que esten inscritos en la materia y a su ves tengan como profe al user (en este caso al meter la condicion con la materia ya los relacionamos con el profe)
    $sqlAlumnos = "SELECT u.idUsuario, u.nombre, u.apellidoPaterno, u.apellidoMaterno,
                (SELECT COUNT(*) FROM asistencia a WHERE a.Usuario_idUsuario = u.idUsuario AND a.campo = 'Ausente') AS totalFaltas
                FROM inscripcion i INNER JOIN usuario u 
                ON i.Usuario_idUsuario = u.idUsuario 
                WHERE i.Materia_idMateria = '$idMateriaSeleccionada' 
                ORDER BY u.apellidoPaterno, u.apellidoMaterno ASC";                    
    $resultadoAlumnos = mysqli_query($conn, $sqlAlumnos);
    $totalAlumnos = mysqli_num_rows($resultadoAlumnos);
/////////////logica de como obtener las faltas
////////hacemos un count de cuantas fechas hay en asistencia
    $sqlTotalClases = "SELECT COUNT(DISTINCT a.fecha) AS totalClases
                        FROM asistencia a INNER JOIN inscripcion i
                        ON a.Usuario_idUsuario = i.Usuario_idUsuario
                        WHERE i.Materia_idMateria = '$idMateriaSeleccionada'";
////////consulta y asociacion
    $consultaSqlTotalClases =  mysqli_query($conn, $sqlTotalClases);
    $fetchConsultaSqlTotalClases = mysqli_fetch_assoc($consultaSqlTotalClases);
    //condicionar terniaria!!, verifica si el total de las clases es mayor a 0, sino le va asignar uno como default, esto para no tener problemas al momento de dividirlo mas adelante
    $totalClases = ($fetchConsultaSqlTotalClases['totalClases']>0) ? 
                    $fetchConsultaSqlTotalClases['totalClases'] : 1;
    //arreglo multidimensional para guardar alumnos
    $alumnosProcesados = [];
    $porcentajeAlumnoDesercion = 0; //la inicializamos por si llega a no haber alumnos 
    //verificacion si es que hay alumnos
    if($totalAlumnos > 0)
    {
        $iRiesgo = 0; //vairable bandera para sacar el % de alumnos en desercion
        //mientras que se cree el arreglo asociativo de la consulta de alumnos
        while($alumno = mysqli_fetch_assoc($resultadoAlumnos))
        {
            //creamos momentaneamente el nombre completo del alumno de esa iteracion
            $nombreCompleto = $alumno['apellidoPaterno']. " " . $alumno['apellidoMaterno'] . " " . $alumno['nombre'];
            $faltas = $alumno['totalFaltas']; //igual las faltas 
            $idAlumno = $alumno['idUsuario']; // y su usuario
            //inicializamos variables de promedio
            $promedio = "-";
            $promedioAEvaluar = 0;
            //consulta de promedio de c/alumno
            $sqlPromedio="SELECT AVG(calificacion) AS promedioAlumno FROM entrega 
                            INNER JOIN actividad ON entrega.Actividad_idActividad = actividad.idActividad
                            INNER JOIN modulo On actividad.Modulo_idModulo = modulo.idModulo
                            WHERE Usuario_idUsuario='".$alumno['idUsuario']."'
                            AND modulo.Materia_idMateria = '$idMateriaSeleccionada'";
            //consulta
            $resultadoPromedio = mysqli_query($conn, $sqlPromedio);
            //si se hizo bien la consulta
            if($resultadoPromedio)
            {
                //crea el arreglo asociativo
                $fetchPromedio = mysqli_fetch_assoc($resultadoPromedio);
                //verifica que no sea null
                if($fetchPromedio['promedioAlumno'] !== null)
                {   //guarda en la variable promedio el redondeo a un decimal del promedio del alumno de esa iteracion
                    $promedio = round($fetchPromedio['promedioAlumno'], 1);
                    $promedioAEvaluar = $promedio; 
                }
            }
            //!seguimos en la misma iteracion
            //creamos la condicion de desercion, primero creamos un porcentaje de cuantas faltas tiene sobre el total de las clases
            $porcentajeFaltas = ($faltas/$totalClases)*100;
            $promedioAEvaluar = ($promedio !== null)? $promedio : 0; //si no tiene promedio le ponemos 0
            $enRiesgo = false; //variable bandera para mas adelante ocuparla como indicador en el html
            if($porcentajeFaltas > 20 && $promedioAEvaluar<8) //tomamos que si el porcentaje de faltas sube de 20% y mas aparte el promedio es menos de 8, hay una probabilidad de desercion;
            {
                $enRiesgo = true; // el indicador cambia a tru
                $iRiesgo++;
            }
            //guardamos info en el gran array jeje, guardamos todo lo que generamos en esta iteracion del while
            $alumnosProcesados[]=
            [
                'nombre' => $nombreCompleto, 
                'faltas' => $faltas,
                'promedio' => $promedio,
                'riesgo' => $enRiesgo
            ];
        }//fin del while
        $porcentajeAlumnoDesercion= round(($iRiesgo/$totalAlumnos)*100, 1); //creamos este porcentaje para imprimirlo abajo, indicara cuantos alumnos de los totales estan en riesgo de desertar
    };//fin verificacion de si hay alumnos
//////////////////////////////////////////
////// consulta para sacar los modulos de la materia para despues imprimirlos
    $sqlModulos = "SELECT idModulo, nombreModulo, numModulo FROM modulo WHERE Materia_idMateria = '$idMateriaSeleccionada' ORDER BY numModulo ASC";
    $resultadoModulos = mysqli_query($conn, $sqlModulos);
////incluimos nuestra minibiblioteca para poner el nav y el footer y datos html
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
                                <th>Faltas</th>
                                <th>Promedio</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            // logica para imprimir a todos los alumnos como lista
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
                                    echo "<tr><td colspan='3' style='text-align:center;'>No hay alumnos inscritos en esta materia.</td></tr>";
                                }
                            ?>
                        </tbody>
                    </table>
                </div>
            </details>

            <details class="bloquedesplegable">
                <summary class="bloqueEstadistico alerta">
                    <p class="numeroDato">
                        <?php echo $porcentajeAlumnoDesercion;?>    
                    %</p>
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
                            <?php
                            //logica de imprimir los alumnos en riesgo dedesercion
                                $hayAlumnosEnRiesgo = false; //establecer  una variable para un if para saber si si existen alumnos en riesgo o no y de ahi poder desplegar el "riesgo"
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
                                // Si recorrimos el arreglo pero nadie cumplió la condición de riesgo imprimimos que no hay alumnos en riesgo
                                if(!$hayAlumnosEnRiesgo)
                                {
                                    echo "<tr><td colspan='3' style='text-align:center; color: green;'>Excelente: No hay alumnos en riesgo de deserción.</td></tr>";
                                }
                            ?>
                        </tbody>
                    </table>
                </div>
            </details>

            <div class="bloquedesplegable">
                <div class="bloqueEstadistico neutro">
                    <p class="numeroDato">promedio</p>
                    <p class="etiquetaDato">
                        <?php 
                            if(!empty($alumnosProcesados))
                            {
                                $i = 0;
                                $sumaPromedios = 0;
                                foreach($alumnosProcesados as $eachAlumno)
                                {
                                    if(is_numeric($eachAlumno['promedio']))
                                    {
                                        $sumaPromedios= $sumaPromedios + $eachAlumno['promedio'];
                                        $i++;
                                    }
                                }
                                $promedioGrupo = ($i > 0) ? round($sumaPromedios/ $i, 1) : "-";
                                echo $promedioGrupo;
                            }
                            else
                            {
                                echo "No existen alumnos registrados para obtener el promedio";
                            }
                            
                        ?>
                    </p>
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
                                        <a href='moduloProfe.php?idModulo=$idModulo' rel='noopener noreferrer' class='sinDelineado textoNaranja'>Módulo $numModulo: $nombreModulo</a>
                                    </div>
                                ";
                            }
                        }
                        else
                        {
                            echo "<p>No se han registrado modulos para esta asignatura todavía.</p>";
                        }
                    ?>
                    <a href='crearModulo.php?idMateria=<?php echo $idMateriaSeleccionada?>' rel='noopener noreferrer'>crear nuevo modulo</a>
                    <?php
                            //logica para cachar la variable get del estatus enviado por el procesador de calificaciones
                        if (isset($_GET['status']))
                        {
                            echo "<div class='contenedorMensajeSistema'>";
                            
                            if ($_GET['status'] === 'exito')
                            {
                                echo "<p class='mensajeExito'><strong>Se creo la actividad</strong></p>";
                            }
                            else 
                                if ($_GET['status'] === 'error')
                                {
                                    echo "<p class='mensajeError'><strong>Hubo un problema al intentar crear la actividad (flop).</strong></p>";
                                }                    
                        echo "</div>";
                        }
                    ?>
                </div>
            </section>
        </div>
    </div>
</body>
</html>

