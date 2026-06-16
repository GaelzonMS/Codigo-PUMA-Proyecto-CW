<?php
    session_start();

    if (!isset($_SESSION['idUsuario'])) //si no existe una sesion con id usuario
    {
        header("Location: ../../index.php"); // se regresa a index y pide que inicie sesion
        exit(); // se sale de dashboard.php
    }
    include 'conexion.php';

////////////verifica si le llego un metodo ? get
    if (isset($_GET['idModulo']))
    {
        $idModuloSeleccionado = mysqli_real_escape_string($conn, $_GET['idModulo']); //evitamos una inyeccion de datos
    } //si no encuentra el modulo por la url se regresa a index
    else
    {
        header("Location: ../../index.html");
        exit();
    }
    include 'encabezadoFooter.php';
    echo $encabezado;
?>
<body class="centrarContenido">
    <div class="encabezado">
        <h1>Crear una nueva Actividad</h1>
    </div>
    <div class="contenedorBusqueda">
        <div class="formulario">
            <h2>Añadir una nueva actividad al módulo</h2>
            <form action="guardarActividad.php" method="POST" class="contenedorBusqueda">
                
                <input type="hidden" name="idModulo" value="<?php echo $idModuloSeleccionado; ?>">

                <label>Título de la actividad:</label>
                <input type="text" name="tituloActividad" required>
                <label>Descripcion:</label>
                <textarea name="descrpcion" rows="4" required></textarea>
                <label>Fecha y hora límite de entrega:</label>
                <input type="datetime-local" name="fechaLimite" required>
                <label>Puntaje maximo:</label>
                <input type="number" name="puntosMax" min="0" max="100" step="0.1" required> 
                <label>Archivo(opcional): </label>
                <input type='file' name='archivoAdjunto'>
                <label>Tipo de actividad: </label>
                <select name="TipoActividad_idTipoActividad" required>
                    <option value="" disabled>Seleccione un tipo de actividad: </option>
                    <?php
                    //Desplegamos la listade tipo de actividad que puedan existir
                        $sqlTiposActividad = "SELECT * FROM TipoActividad";
                        $consultaTiposActividad = mysqli_query($conn, $sqlTiposActividad);
                        $listaTipoActividad = array();
                        if($consultaTiposActividad)
                        {
                            while ( $fila = mysqli_fetch_assoc($consultaTiposActividad) )
                            {
                                $listaTipoActividad[]= $fila;
                            }
                        }
                        if(count($listaTipoActividad)>0)
                        {
                            foreach($listaTipoActividad as $tipoActividad)
                            {
                                $idTipoActividad = $tipoActividad["idTipoActividad"];
                                echo "<option value='$idTipoActividad'> " . $tipoActividad['nombre'] . " </option>";
                            }
                        }
                    ?>
                </select>
                <input type="submit" value="Guardar actividad">
            </form>
        </div>
    </div>
</body>
</html>