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
        
    } //si no encuentra la materia por la url se regresa a index
    else
    {
        header("Location: ../../index.html");
        exit();
    }
    include 'encabezadoFooter.php';
    echo $encabezado;
?>
<!-- ----------------------FORMULARIO CREAR UN NUEVO MODULO------------------------------------------ -->
<body class="centrarContenido">
    <div class="encabezado">
        <h1>Crear nuevo modulo</h1>
    </div>
    <div class="contenedorBusqueda">
        <div class="formulario">
            <h2>Añadir un nuevo modulo</h2>
            <!--formlario que redirigue a guardaModulo.php , empaqueta los datos en metodo POST-------------------------------------------------------------------------------------------------- -->
            <form action="guardarModulo.php" method="POST" class="contenedorBusqueda">
                <!-- mandamos un input vacio para empaquetar la materia a la que pertenece este modulo que estamos creando -->
                <input type="hidden" name="idMateria" value="<?php echo $idMateriaSeleccionada; ?>">
                <!-- --------- -->
                <label>Numero del modulo:</label>
                <input type="text" name="numModulo" required>
                <label>Nombre del modulo:</label>
                <input type="text" name="nombreModulo" required>
                <label>Descripcion:</label>
                <input type="text" name="descripcion" required> 
                <input type="submit" value="Guardar modulo">
            </form>
            <!-- ------------------------------------------------------------------------------------------------------ -->
        </div>
    </div>
</body>
</html>