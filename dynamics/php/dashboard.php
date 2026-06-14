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
?>
    <main>
        
        <section id="seccionDeAvisosPaginaPrincipal" class="seccionDeAvisos">
                
        </section>
        <section id="contenedorEnlaces"
        >
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
                <a href="materia.php" class="previewMateria bordeNegro redondeo25px textoNgero sinDelineado crezca">
                    <p>materia</p>
                </a>
            </div>
        </section>
    </main>
    <?php
        echo $footer;
    ?>
