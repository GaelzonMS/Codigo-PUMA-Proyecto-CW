<?php
session_start();

    if (!isset($_SESSION['idUsuario'])) //si no existe una sesion con id usuario
    {
        header("Location: ../../index.php"); // se regresa a index y pide que inicie sesion
        exit(); // se sale de dashboard.php
    }
    include 'conexion.php';
    //verificar si llego el ?idActividad = $idActividad;
    if(isset($_GET['idActividad']))
    {
        //prompt
        $idActividadSeleccionada = mysqli_real_escape_string($conn, $_GET['idActividad']); //evitamos inyecciones
        $sqlActividadDatos = "SELECT
                                    titulo,
                                    fechaCreacion,
                                    fechaLimite,
                                    descrpcion,
                                    archivoAdjunto,
                                    puntosMax
                                        FROM actividad WHERE idActividad='$idActividadSeleccionada'";
        //consulta query
        $consultaSqlActividadDatos = mysqli_query($conn, $sqlActividadDatos);
        
        // desmenusar consulta
        if($consultaSqlActividadDatos && mysqli_num_rows($consultaSqlActividadDatos)>0)
        {
            $datosActividad = mysqli_fetch_assoc($consultaSqlActividadDatos);
            $tituloActividad = $datosActividad['titulo'];
            $fechaCreacionActividad = $datosActividad['fechaCreacion'];
            $fechaLimiteActividad = $datosActividad['fechaLimite'];
            $descripcionActividad = $datosActividad['descrpcion'];
            $archivoAdjuntoActividad = $datosActividad['archivoAdjunto'];
            $puntosMaxActividad = $datosActividad['puntosMax'];
        }

        $sqlCalificacionAlumno = "SELECT calificacion FROM entrega
                                        WHERE Actividad_idActividad='$idActividadSeleccionada'
                                        AND Usuario_idUsuario='".$_SESSION['idUsuario']."'";
        $consultaCalificacionAlumno = mysqli_query($conn, $sqlCalificacionAlumno);
        if($consultaCalificacionAlumno && mysqli_num_rows($consultaCalificacionAlumno)>0)
        {
            $fetchCalificacion = mysqli_fetch_assoc($consultaCalificacionAlumno);
            $calificacionAlumno = $fetchCalificacion['calificacion'];
        }
        else
        {
            if(mysqli_num_rows($consultaCalificacionAlumno)==0)
            {
                $calificacionAlumno = "No existe entrega";
            }
            else
            {
                $calificacionAlumno = "sin evaluar";
            }
        }
    }
    else //si no regresar dashboard.php
    {
        header('Location: dashboard.php');
        exit();
    }

    include 'encabezadoFooter.php';
    
?>
<?php
    echo $encabezado;
?>
    <main id="mainActividadVisualizacion">
        <div class="flexCentradoTitulo">
            <h3 class="textoNormal">ACTIVIDAD: <?php echo $tituloActividad; ?></h3>
        </div>
        <section id="seccionInformacionActividadNOMBRE">
            <p>Descripcion:</p>
            <p><?php echo $descripcionActividad  ?></p>
        </section>
        <section id="seccionMostrarRecursos">
            <?php 
                if($archivoAdjuntoActividad !== null )
                {
                    echo "<img class='' src='".$archivoAdjuntoActividad."'>";
                }
                else
                {
                    echo "<p class='textoChiquito'>Sin archivo</p>";
                }
            ?>            
        </section>
        <section id="seccionTablaDatosActividad">
            <table style>
                <tr>
                    <td>Fecha creacion: </td>
                    <td><?php echo $fechaCreacionActividad ?></td>
                </tr>
                <tr>
                    <td>Fecha limite: </td>
                    <td><?php echo $fechaLimiteActividad?></td>
                </tr>
                <tr>
                    <td>puntaje:</td>
                    <td>
                    <?php 
                        if($puntosMaxActividad !== null)
                        {
                            echo $calificacionAlumno."/".$puntosMaxActividad;
                        }
                        else
                            echo "sin revisar";
                    ?></td>
                </tr>
            </table>
        </section>
    </main>
<?php
    echo $footer;
?>