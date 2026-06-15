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
    <main id="mainActividadVisualizacion">
        <div class="flexCentradoTitulo">
            <h3 class="textoNormal">PERFIL</h3>
        </div>
        <section id="seccionPerfilInformacion" class="textoNormal">
            <img src="../statics/media/pfpDefault.jpg" id="fotoPerfil" class="fotoPerfilGrande">
            <h4 class="textoNormal">Informacion Personal</h4>
            <div>
                <p>Nombre: Gael Vazquez Moreno</p>
                <p>Correo: 324256350@alumno.enp.unam.mx</p>
                <p>Numero de Cuenta: 324256350</p>
                <div style="display: flex; flex-direction: row; gap: 10px;">
                    <p>Estilo de aprendizaje: Tipo de aprendizaje</p>
                    <button>Editar</button>
                </div>
                
                <h6>por razones de seguridad, si requiere algun cambio en su informacion personal, por favor mandar correo a: correo@example.com</h6>
            </div>
        </section>
    </main>
<?php
    echo $footer;
?>