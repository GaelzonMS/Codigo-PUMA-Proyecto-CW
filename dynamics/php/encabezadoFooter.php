<?php

    /* DECLARACION DE LAS VARIABLES QUE CONTIENEN LA ESTRUCTURA DEL ENCABEZADO Y EL FOOTER */

    $encabezado = '
    <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <meta name="autor" content="Equipo 9 CW 2026">
            <meta name="descripcion" content="Pagina web para angi jeje">
            <link rel="stylesheet" href="../../statics/styles/style.css">
            <title>ETECHELP</title>
        </head>
        <body id="dashboardBody">

            <nav id="navGeneral">
                <div id="logosContenedor">
                    <img src="../../statics/media/unamEscudo.png" id="logoUnam" class="logo fillBlanco">
                    <img src="../../statics/media/logo_ete.png" id="logoETE" class="logo">
                </div>
                <h1 id="tituloPaginaInicioDeSesion" class="titulo1 glow">ETECHELP</h1>
                <section id="perfil" class="crezca">
                    <a href="perfil.php" class="circulo">
                        <img src="../../statics/media/pfpDefault.jpg" id="fotoPerfil" class="fotoPerfilChiquita">
                    </a>
                </section>
            </nav>';
    $footer = '
    <footer class="footerGeneral">
        <div id="logosFooter">
            <img src="../../statics/media/unamEscudo.png" id="logoUnamFooter" class="logo fillBlanco">
            <img src="../../statics/media/logo_ete.png" id="logoETEFooter" class="logo">
            <img src="../../statics/media/logo_enp.svg" style="width: 55px">
            <img src="../../statics/media/unamFraseLogo.svg" style="width: 55px">
        </div>
        
        <div id="divDirecciones">
            <p class="textoBlanco textoChiquito sinDelineado textoBlancoTransparente">Universidad Nacional Autónoma de México (UNAM)</p>
            <p class="textoBlanco textoChiquito sinDelineado textoBlancoTransparente">Para dudas y sugerencias mándanos un correo a: buzon.direccion.p6@enp.unam.mx</p>
        </div>
        
        <div id="divEnlacesAcuerdosYCreditos">
            <a href="/ENP6/_P6/AvisoPrivacidadIntegral.pdf" target="_blank" class="textoBlanco textoChiquito sinDelineado glow">
                <small>Aviso de privacidad integral</small>
            </a>
            <a href="/ENP6/_P6/AvisoPrivacidadSimplificado.pdf" target="_blank" class="textoBlanco textoChiquito sinDelineado glow">
                <small>Aviso de privacidad simplificado</small>
            </a>
            <a href="/ENP6/_P6/Politica_de_Cookies.php" class="textoBlanco textoChiquito sinDelineado glow">
                <small>Política de cookies</small>
            </a>
            <a href="/ENP6/_P6/mapaSitio.php" class="textoBlanco textoChiquito sinDelineado glow">
                <small>Mapa de Sitio</small>
            </a>
            <a href="/ENP6/_P6/creditos.php" class="textoBlanco textoChiquito sinDelineado glow">
                <small>Créditos</small>
            </a>
        </div>
    </footer>
    </body>
</html>
    ';
?>