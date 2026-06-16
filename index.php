<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="autor" content="Equipo 9 CW 2026">
    <meta name="descripcion" content="Pagina web para angi jeje">
    <link rel="stylesheet" href="./statics/styles/inicioDeSesion.css">
    <title>Iniciar Sesion</title>
</head>

<body id="inicioDeSesionBody">


    <nav id="navInicioSesion">
        <div id="logosContenedor">
            <img src="https://yosoycide.com/wp-content/uploads/2020/03/unam-escudo.png" id="logoUnam" class="logo">
            <img src="https://www.ete.enp.unam.mx/images/header_footer/logo_ete.svg" id="logoETE" class="logo">
        </div>
        <h1 id="tituloPaginaInicioDeSesion" class="titulo1">ETECHELP</h1>
    </nav>


    <main>

        <section id="sectionIniciarSesion">
            <h3 class="titulo1 textoBlanco">Bienvenidx a ETECHELP!</h3>


            <div id="contendorFormularioInicioSesion" class="textoBlanco cajaGris animar-aparicion">
                <p class="glow">Inicio de Sesion</p>



                <form id="formularioInicioSesion" action="dynamics/php/validarLogin.php" method="POST">
                    <div>
                        <label>Usuario:</label>
                        <input type="email" name="correo" placeholder="ejemplo@gmail.com" required class="inputLogin">
                    </div>
                    <div>
                        <label>Contraseña:</label>
                        <input type="password" name="password" placeholder="introduzca su contraseña" required class="inputLogin">
                    </div>
                    
                    <?php
                        if(isset($_GET['error']) && $_GET['error'] == 'incorrecto')
                        {
                            echo '
                            <div>
                                <small class="mensajeErrorLogIn">Usuario o contraseña incorrectos</small>
                            </div>
                            ';
                        }
                        
                    ?>

                    <input type="submit" value="INICIAR SESION" class="botonTipo1">
                </form>



            </div>
        </section>

    </main>
</body>
</html>