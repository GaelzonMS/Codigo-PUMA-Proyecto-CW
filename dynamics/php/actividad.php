<?php
    include 'encabezadoFooter.php';
?>
<?php
    echo $encabezado;
?>
    <main id="mainActividadVisualizacion">
        <div class="flexCentradoTitulo">
            <h3 class="textoNormal">ACTIVIDAD: nombre actividad</h3>
        </div>
        <section id="seccionInformacionActividadNOMBRE">
            <p>Descripcion general de la actividad</p>
            <p>lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsumlorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsumlorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsumlorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsumlorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum</p>
        </section>
        esto va a ser opcional si la actividad se tiene que subir algo
        <section id="seccionSubirArchivosActividadNOMBRE">
            <div>
                <p>Subir archivos:</p>
                <form>
                    <input type="file">
                </form>
            </div>
        </section>
        <section id="seccionTablaDatosActividaDNOMBRE">
            tabla que muestra los datos de la actividad
            <table style>
                <tr>
                    <td>Fecha limite</td>
                    <td>aqui va la fecha</td>
                </tr>
                <tr>
                    <td>estatus</td>
                    <td>entregado o no entregado</td>
                </tr>
                <tr>
                    <td>califiacion</td>
                    <td>0/10</td>
                </tr>
            </table>
        </section>
    </main>
<?php
    echo $footer;
?>