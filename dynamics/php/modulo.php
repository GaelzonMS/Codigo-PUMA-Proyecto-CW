<?php
    session_start();

    if (!isset($_SESSION['idUsuario'])) //si no existe una sesion con id usuario
    {
        header("Location: ../../index.php"); // se regresa a index y pide que inicie sesion
        exit(); // se sale de dashboard.php
    }
    include 'conexion.php';

    //confirmacion de que si haya llegado el ?idModulo=$idModulo
    if(isset($_GET['idModulo']))
    {
        //evitamos una inyeccion de sql
        $idModuloSeleccionado = mysqli_real_escape_string($conn, $_GET['idModulo']);
        $sqlDatosModulo = "SELECT 
                                nombreModulo,
                                numModulo,
                                descripcion,
                                Materia_idMateria FROM modulo WHERE idModulo='$idModuloSeleccionado'";
        //consulta
        $consultaSqlDatosModulo = mysqli_query($conn, $sqlDatosModulo);
        //desmenusamos
        if($consultaSqlDatosModulo && mysqli_num_rows($consultaSqlDatosModulo)>0)
        {//creamos el arreglo asociativo
            $DatosModulo = mysqli_fetch_assoc($consultaSqlDatosModulo);
            //Guardamos los datos en diferentes variables
            $nombreModulo = $DatosModulo['nombreModulo'];
            $numModulo = $DatosModulo['numModulo'];
            $descripcion = $DatosModulo['descripcion'];
        }
        //Obtener las actividades
        $sqlActividades = "SELECT   idActividad,
                                    titulo,
                                    fechaCreacion,
                                    fechaLimite,
                                    TipoActividad_idTipoActividad
                                    FROM actividad
                                    WHERE Modulo_idModulo='$idModuloSeleccionado'";    
        //consulta
        $consultaSqlActividades = mysqli_query($conn, $sqlActividades);
        //desmenusarlo
        if($consultaSqlActividades && mysqli_num_rows($consultaSqlActividades)>0)
        {
            $arregloActividades = [];
            while($actividad = mysqli_fetch_assoc($consultaSqlActividades) )
            {
                $listaActividades[] = $actividad; //creamos un arreglo de arreglos de cada row de la consulta
            }
        }
    }
    else //si no existe lo regresamos a el dashboard
    {
        header('Location: dashboard.php');
        exit();
    }

    include 'encabezadoFooter.php';
?>
<?php
    echo $encabezado;
?>
    <main>

        <div class="displayFlexalinearCentroConGap">
            <h3 class="textoNormal"><?php echo $numModulo.". ".$nombreModulo;?></h3>
            <p><?php echo $descripcion;?></p>
        </div>

        <section id="seccionTemasNOMBREMODULO">
            <div class="despliegeDeTema">
                <h4 class="textoNormal">Actividades:</h4>
                <section id="seccionMateriales">
                    <!--aqui va el php para imprimir lista de actividades y recursos------->
                    <?php 
                        // un foreach para recoger el arreglo, primero por seguridad del codigo tenemos que revisar que no este vacio
                        if(!empty($listaActividades))
                        {
                            foreach($listaActividades as $act) //le decimos que cada arreglo se va a guardar en act en cada iteracion
                            {
                                echo "
                                        <ul>
                                            <li>
                                                <a href='actividad.php?idActividad=".$act['idActividad']."' rel='noopener noreferrer'>
                                                ".$act['titulo']."
                                                </a>
                                            </li>
                                        </ul>";
                            }
                        }
                    ?>
                </section>
            </div>
        </section>
    </main>
<?php
    echo $footer;
?>