<?php
    session_start();

    if (!isset($_SESSION['idUsuario'])) //si no existe una sesion con id usuario
    {
        header("Location: ../../index.php"); // se regresa a index y pide que inicie sesion
        exit(); // se sale de dashboard.php
    }
    include 'conexion.php';
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="autor" content="Equipo 9 CW 2026">
    <meta name="descripcion" content="Pagina web para angi jeje">
    <link rel="stylesheet" href="../../statics/styles/inicioDeSesion.css">
    <link rel="stylesheet" href="../../statics/styles/vistaProfeMateria.css">
    <title>vistaProfesorMateria</title>
</head>
<body id="dashboarBody">
    <nav id="navInicioSesion">
        <div id="logosContenedor">
            <img src="https://yosoycide.com/wp-content/uploads/2020/03/unam-escudo.png" id="logoUnam" class="logo">
            <img src="https://www.ete.enp.unam.mx/images/header_footer/logo_ete.svg" id="logoETE" class="logo">
        </div>
        <h1 id="tituloPaginaInicioDeSesion" clasS="titulo1">ETECHELP</h1>
    </nav>
    
    <div class="contenedorMateria">
        
        <header class="materiaHeader">
            <h1>NOMBRE MATERIA</h1>
        </header>

        <div class="seccionEstadisticas">
            
            <details class="bloquedesplegable">
                <summary class="bloqueEstadistico">
                    <p class="numeroDato">num. alumnos</p>
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
                            <tr><td>nombre</td><td>faltas</td><td>promedio</td></tr>
                            <tr><td>nombre</td><td>faltas</td><td>promedio</td></tr>
                            <tr><td>nombre</td><td>faltas</td><td>promedio</td></tr>
                        </tbody>
                    </table>
                </div>
            </details>

            <details class="bloquedesplegable">
                <summary class="bloqueEstadistico alerta">
                    <p class="numeroDato">--%</p>
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
                            <tr class="altoriesgo"><td>nombre</td><td>faltas</td><td>promedio</td></tr>
                            <tr class="altoriesgo"><td>nombre</td><td>faltas</td><td>promedio</td></tr>
                        </tbody>
                    </table>
                </div>
            </details>

            <div class="bloquedesplegable">
                <div class="bloqueEstadistico neutro">
                    <p class="numeroDato">promedio</p>
                    <p class="etiquetaDato">Promedio general del grupo</p>
                </div>
            </div>

        </div>

        <div class="pantallaDividida">
            <section class="columnaModulos">
                <h2>Modulos</h2>
                <div class="modulos">
                    <div class="tarjetaModulo"><h3>Modulo 1</h3></div>
                    <div class="tarjetaModulo"><h3>Modulo 2</h3></div>
                    <div class="tarjetaModulo"><h3>Modulo 3</h3></div>
                    <div class="tarjetaModulo"><h3>Modulo 4</h3></div>
                </div>
            </section>
        </div>

    </div>
</body>
</html>

