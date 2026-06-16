<?php
    session_start();

    if (!isset($_SESSION['idUsuario'])) //si no existe una sesion con id usuario
    {
        header("Location: ../../index.php"); // se regresa a index y pide que inicie sesion
        exit(); // se sale de dashboard.php
    }
    include 'conexion.php';
    include 'encabezadoFooter.php';

    $sqlUsuario = "SELECT correo,
                        tipoAprendizaje_idtipoAprendizaje
                        FROM usuario WHERE idUsuario = '". $_SESSION['idUsuario'] ."'";
    //consulta
    $consultaSqlUsuario = mysqli_query($conn, $sqlUsuario);
    //lo desebramos juju
    if($consultaSqlUsuario && mysqli_num_rows($consultaSqlUsuario)>0)
    {
        $datosUsario = mysqli_fetch_assoc($consultaSqlUsuario);
        $correoUsuario = $datosUsario['correo'];
        $tipoAprendizajeUsuario = $datosUsario['tipoAprendizaje_idtipoAprendizaje'];
    }
    else
    {
        echo "Error en algun parametro del usuario";
    }
                
?>
<?php
    echo $encabezado;
?>
    <main id="mainActividadVisualizacion">
        <div class="flexCentradoTitulo">
            <h3 class="textoNormal">PERFIL</h3>
        </div>
        <section id="seccionPerfilInformacion" class="textoNormal">
            <img src="../statics/media/pfpDefault.jpg" id="fotoPerfil" class="fotoPerfilGrande">
            <h4 class="textoNormal">Informacion Personal</h4>
            <div class= 'separarDatos'>
                <p>Nombre: <?php echo $_SESSION['nombre'];?></p>
                <p>Correo: <?php echo $correoUsuario;?></p>
                <?php 
                    if($_SESSION['idRol'] == '3')
                    {
                        $numCuenta= strstr($correoUsuario, '@', true); // https://www.php.net/manual/es/function.strstr.php
                        //tenemos que hacer un join pq $tipoAprendizaje nos da el id
                        /* $sqlNombreEstiloAprendizaje= "SELECT t.nombre AS nombreAprendizaje
                                                            FROM usuario u
                                                            INNER JOIN tipoAprendizaje t
                                                            ON u.tipoAprendizaje_idtipoAprendizaje = t.idtipoAprendizaje
                                                            WHERE u.idUsuario =".$_SESSION['idUsuario'].""; */
                        $sqlNombreEstiloAprendizaje = "SELECT nombreAprendizaje from tipoAprendizaje WHERE idtipoAprendizaje=$tipoAprendizajeUsuario";
                        $consultaSqlUsuarioEstApren = mysqli_query($conn, $sqlNombreEstiloAprendizaje);
                        $aNombreEstiloAprendizaje = mysqli_fetch_assoc($consultaSqlUsuarioEstApren);
                        $NombreEstiloAprendizaje = $aNombreEstiloAprendizaje['nombreAprendizaje'];

                        echo "<p>Numero de Cuenta: ". $numCuenta ."</p>";
                        echo "<div style='display: flex; flex-direction: row; gap: 10px;'>
                            <p>Estilo de aprendizaje: ". $NombreEstiloAprendizaje."</p>
                            <a href='cuestionarioPersonalidad.php' class='btnEditar'>editar</a>
                        </div>";
                    }
                ?>
                
                <h6>por razones de seguridad, si requiere algun cambio en su informacion personal, por favor mandar correo a: correo@example.com</h6>
                <div clasS="centrarTomandoTodoElAnchoRow">
                    <a href='logOut.php' class="textoChiquito textoBlanco redondeo25px bordeNegro sinDelineado crezca btnGris">cerrar sesion</a> 
                </div>
                
            </div>
        </section>
    </main>
<?php
    echo $footer;
?>