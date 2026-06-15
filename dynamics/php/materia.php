<?php
    session_start();

    if (!isset($_SESSION['idUsuario'])) //si no existe una sesion con id usuario
    {
        header("Location: ../../index.php"); // se regresa a index y pide que inicie sesion
        exit(); // se sale de dashboard.php
    }
    include 'conexion.php';
    
    include 'encabezadoFooter.php';
?>
    <?php
        echo $encabezado;
    ?>
    <main>
        <div class="flexCentradoTitulo">
            <h3 class="titulo">NOMBRE MATERIA</h3>
        </div>
        <section id="seccionDeAvisosMateriaNOMBRE" class="seccionDeAvisos">
            
        </section>
        <section id="seccionInformacionDeLaMateriaNOMBRE" class="seccionInformacionDeLaMateria">

            <div id="contenedorDatosDeLaMateriaNOMBRE">
                <table id='tablaDatosMateriaNOMBRE' class='tablaDatosMateria tablaDatosMateriaBorde'>
                    <tr>
                        <td>Profesor:</td>
                        <td>Nombre del profesor</td>
                    </tr>
                    <tr>
                        <td>Salon:</td>
                        <td>salon asignado</td>
                    </tr>
                    <tr>
                        <td>Horario:</td>
                        <td>Horario asignado</td>
                    </tr>
                </table>
            </div>
                <a id='hrefPlanEstudiosNOMBRE' href="https://" target="_blank" rel="noopener noreferrer" class="crezca textoBlanco sinDelineado hrefPlanEstudios"> 
                <!-- Referencia: https://www.freecodecamp.org/espanol/news/como-usar-html-para-abrir-un-link-en-un-tab-nuevo-->
                        <h6 class="glow">Plan de estudios</h6>
                        <img src="../../statics/media/planEstudiosLogo.svg" width="60px" class='fillBlanco glow'>
                </a>
        </section>
        <section id="seccionModulosNOMBREMATERIA" class="seccionModulos">

            <p class="textoNormal">MODULOS</p>

            <div id="contenedorDeBotonesModuloNOMBREMATERIA" class='contenedorDeBotonesModulo'>
                <a href='modulo.php' rel="noopener noreferrer"  id="botonModuloNombre" class='contenedorNombreModuloVistaMateria crezca'>
                    <h3>Nombre del modulo</h3>
                </a>  
            </div>
            
        </section>
    </main>
    <?php
        echo $footer;
    ?>