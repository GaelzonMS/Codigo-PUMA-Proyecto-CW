<?php
    session_start();

    if (!isset($_SESSION['idUsuario'])) //si no existe una sesion con id usuario
    {
        header("Location: ../../index.php"); // se regresa a index y pide que inicie sesion
        exit(); // se sale de dashboard.php
    }
    include 'conexion.php';

    if(isset($_GET['idMateria'])) // si si recibio un ?idmateria
    {
        $idMateriaSeleccionada = mysqli_real_escape_string($conn, $_GET['idMateria']); // para evitar inyecciones de sql
        $idAlumnoLogueado = $_SESSION['idUsuario']; //obtiene de la varianle global session el id del usuario
        
        // Obtener nombre de la materia
        $sqlMateria = "SELECT nombre FROM materia WHERE idMateria='$idMateriaSeleccionada'";
        $resultadoMateria = mysqli_query($conn, $sqlMateria);
        if($resultadoMateria && mysqli_num_rows($resultadoMateria) > 0)
        {
            $datosMateria = mysqli_fetch_assoc($resultadoMateria);
            $nombreMateria = $datosMateria['nombre'];
        }
        else
        {
            header("Location: dashboard.php");
            exit();
        }

        // Obtener Profesor asignado
        $sqlProfesor = "SELECT u.nombre, u.apellidoPaterno, u.apellidoMaterno /*va a seleccionr el nombre completo del usuario que vamos a hacerle join */
                        FROM inscripcion i_alumno /*va a partir de inscripcion y le damos un alias de i_alumno (como si fuera 'inscripcion del alumno')*/
                        INNER JOIN /*vamos a buscar coincidiencias exactas en ambas tablas */
                        inscripcion i_profesor /*alias 'inscripcion del profesor'*/ON i_alumno.Materia_idMateria = i_profesor.Materia_idMateria /*vamos a buscar en inscripcion una coincidiencia exacta donde el profesor y el alumno tengan en comun el id de la materia */
                        AND i_alumno.Grupo_idGrupo = i_profesor.Grupo_idGrupo /*Y la incripcion tiene que tener un id de un grupo igual */
                        INNER JOIN usuario u ON i_profesor.Usuario_idUsuario = u.idUsuario /*hacemos otra coincidencia exacta donde el iduser en la inscripcion sea igual a el id de un usuario */
                        WHERE i_alumno.Usuario_idUsuario = '$idAlumnoLogueado' /*creamos la condicion de que la coincidencia tiene que ser con el alumno que esta en la sesion activa */
                        AND i_alumno.Materia_idMateria = '$idMateriaSeleccionada' /*y a su ves que sea en la materia correspondiente a su vista */
                        AND u.Rol_idRol = 2"; /*y finalizamos depurando la busqueda del usuario que tiene que tener el rol de 2 (profesor) */
        //consulta
        $consultaSqlProfe = mysqli_query($conn, $sqlProfesor);
        //texto default por si no encuentra una coincidencia
        $nombreCompletoProfesor = "No asignado";
        //desmenusamos la consulta
        if($consultaSqlProfe && mysqli_num_rows($consultaSqlProfe) > 0)
        {
            $profesor = mysqli_fetch_assoc($consultaSqlProfe); //array asociativo del query
            $nombreCompletoProfesor = $profesor['nombre'] . " " . $profesor['apellidoPaterno'] . " " . $profesor['apellidoMaterno'];
        }

        // Obtener Horario y Salón
        $sqlClase = "SELECT c.horaInicio, c.horaFin, c.salon, c.dia 
                    FROM inscripcion i
                    INNER JOIN clase c ON i.Grupo_idGrupo = c.Grupo_idGrupo
                    WHERE i.Usuario_idUsuario = '$idAlumnoLogueado' 
                    AND i.Materia_idMateria = '$idMateriaSeleccionada'";
        //consulta
        $consultaClase = mysqli_query($conn, $sqlClase);
        //textos por default
        $textoHorarios = "Por asignar";
        $textoSalon = "Por asignar"; 
        //desmenusamos
        if($consultaClase && mysqli_num_rows($consultaClase) > 0)
        {
            $listaHorarios = []; //creamos arrays por si sale mas de un dia o mas de un salon
            $listaSalones = [];
            
            while($clase = mysqli_fetch_assoc($consultaClase))//le hacemos un while al array asociativo que creamos
            {
                $inicio = date("H:i", strtotime($clase['horaInicio']));
                $fin = date("H:i", strtotime($clase['horaFin']));
                $dia = $clase['dia'];
                $listaHorarios[] = "$dia de $inicio a $fin";
                $listaSalones[] = $clase['salon'];
            }
            $textoHorarios = implode("<br>", $listaHorarios); //conv a string
            $textoSalon = implode(", ", array_unique($listaSalones));
        }
    } 
    else // si no existe el ?materia lo regresa a dashboard.php
    {
        header("Location: dashboard.php");
        exit();
    }
    //consulta de modulo por id materia
    $sqlModulos = "SELECT idModulo, nombreModulo, numModulo FROM modulo WHERE Materia_idMateria = '$idMateriaSeleccionada' ORDER BY numModulo ASC";
    $consultaModulos = mysqli_query($conn, $sqlModulos);
?>
<<<<<<< HEAD
<?php 
    include 'encabezadoFooter.php';
    echo $encabezado; 
?>
<main>
    <div class="flexCentradoTitulo">
        <h3 class="titulo">
            <?php echo $nombreMateria; ?>
        </h3>
    </div>
    <section id="seccionDeAvisosMateriaNOMBRE" class="seccionDeAvisos">
        
    </section>
    <section id="seccionInformacionDeLaMateriaNOMBRE" class="seccionInformacionDeLaMateria">

        <div id="contenedorDatosDeLaMateriaNOMBRE">
            <table id='tablaDatosMateriaNOMBRE' class='tablaDatosMateria tablaDatosMateriaBorde'>
                <tr>
                    <td>Profesor:</td>
                    <td><strong><?php echo $nombreCompletoProfesor; ?></strong></td>
                </tr>
                <tr>
                    <td>Salon:</td>
                    <td><strong><?php echo $textoSalon; ?></strong></td> 
                </tr>
                <tr>
                    <td>Horario:</td>
                    <td><strong><?php echo $textoHorarios; ?></strong></td> 
                </tr>
            </table>
=======
    <?php
        echo $encabezado;
    ?>
    <main>
        <section id='regresarACasa'>
            
        </section>
        <div class="flexCentradoTitulo">
            <h3 class="titulo">NOMBRE MATERIA</h3>
>>>>>>> d639874 (guardar avance)
        </div>
        <a id='hrefPlanEstudiosNOMBRE' href="https://" target="_blank" rel="noopener noreferrer" class="crezca textoBlanco sinDelineado hrefPlanEstudios"> 
            <h6 class="glow">Plan de estudios</h6>
            <img src="../../statics/media/planEstudiosLogo.svg" width="60px" class='fillBlanco glow'>
        </a>
    </section>
    <section id="seccionModulosNOMBREMATERIA" class="seccionModulos">
        <p class="textoNormal">MODULOS</p>
        <div id="contenedorDeBotonesModuloNOMBREMATERIA" class='contenedorDeBotonesModulo'>
            
            <?php 
                //desmenusar
                if($consultaModulos && mysqli_num_rows($consultaModulos)>0)
                {   //arreglo asociativo
                    while($modulo = mysqli_fetch_assoc($consultaModulos))
                    {
                        $numModulo = $modulo['numModulo'];
                        $nombreModulo = $modulo['nombreModulo'];
                        $idModulo = $modulo['idModulo'];

<<<<<<< HEAD
                        echo "<a href='modulo.php?idModulo=$idModulo' rel='noopener noreferrer' id='botonModuloNombre' class='contenedorNombreModuloVistaMateria crezca'>
                                <h3>".$numModulo." ".$nombreModulo."</h3>
                            </a>";
                    }
                }
            ?>
        </div>
    </section>
</main>
<?php echo $footer; ?>
=======
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
>>>>>>> d639874 (guardar avance)
