<?php
    include 'encabezadoFooter.php';
?>
<?php
    echo $encabezado;
?>
    <main>

        <div class="flexCentradoTitulo">
            <h3 class="textoNormal">NOMBRE MODULO</h3>
        </div>

        <section id="seccionTemasNOMBREMODULO">
            <div class="despliegeDeTema">
                <h4 class="textoNormal">Nombre del tema</h4>
                <section id="seccionMateriales">
                    <!--aqui va el php para imprimir lista de actividades y recursos------->
                    <ul>
                        <li><a href='actividad.php' rel="noopener noreferrer">Actividad</a></li>
                    </ul>
                </section>
            </div>
        </section>
    </main>
<?php
    echo $footer;
?>