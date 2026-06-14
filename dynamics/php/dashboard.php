<?php
    session_start();

    if (!isset($_SESSION['idUsuario'])) //si no existe una sesion con id usuario
    {
        header("Location: ../../index.php"); // se regresa a index y pide que inicie sesion
        exit(); // se sale de dashboard.php
    }

    include 'encabezadoFooter.php';
    include 'conexion.php';
    echo $encabezado;
    echo "<h1 class='titulo cienCentrado'>Bienvenidx ". $_SESSION['nombre'] ." </h1>";
    $idAlumno = $_SESSION['idUsuario'];

    $sqlMaterias = "
                    SELECT m.idMateria, m.nombre AS nombreMateria
                    FROM inscripcion i
                    INNER JOIN materia m ON i.Materia_idMateria = m.idMateria
                    WHERE i.Usuario_idUsuario = '$idAlumno'";

    $resultadoSqlMaterias = mysqli_query($conn, $sqlMaterias);

    
?>
    <main>
        
        <section id="seccionDeAvisosPaginaPrincipal" class="seccionDeAvisos">
            
        </section>
        <section id="contenedorEnlaces">
            <section id="seccionPaginaEtes">
                <a href="https://www.ete.enp.unam.mx/" target="_blank" rel="noopener noreferrer" class="cajaBlanca bordeNegro crezca cajaRedondeadaConPadding textoNaranja sinDelineado"> 
                <!-- Referencia: https://www.freecodecamp.org/espanol/news/como-usar-html-para-abrir-un-link-en-un-tab-nuevo-->
                        <h6>Visita la pagina de las ETES</h6>
                        <img id="imagenLogoSitioEtes" src="../../statics/media/EteLogoPagina.png">
                </a>
            </section>
            <section id="paginaSIAE">
                <a href="https://www.dgae-siae.unam.mx/www_gate.php" target="_blank" rel="noopener noreferrer" class="cajaBlanca bordeNegro crezca cajaRedondeadaConPadding sinDelineado">
                        <h6>Consulta tus califiaciones finales :0</h6>
                        <img id="imagenLogoSitioEtes" src="../../statics/media/siaeLogo.png">
                </a>
            </section>
        </section>
        
        <section id="seccionMateriasInscritas" class="cajaRedondeadaConPadding">
            <h4 class="textoBlanco glow">Materias inscritas</h4>
            <div id="materiasInscritas">
                
                <?php
                    if ($resultadoSqlMaterias && mysqli_num_rows($resultadoSqlMaterias) > 0) 
                    {
                    
                        while ($materia = mysqli_fetch_assoc($resultadoSqlMaterias)) 
                        {
                            $idMateria = $materia['idMateria'];
                            $nombreMateria = $materia['nombreMateria'];
                        
                            echo "
                            <a href='materia.php?idMateria=$idMateria' rel='noopener noreferrer' class='previewMateria bordeNegro redondeo25px textoNgero sinDelineado crezca'>
                                <h4>$nombreMateria</h4>                            
                            </a>";
                        }
                    }
                    else 
                    {
                    echo "<p>Este alumno no tiene materias inscritas en la tabla 'inscripcion'.</p>";
                    }
                ?>
            </div>
        </section>
    </main>
    <?php
        echo $footer;
    ?>
