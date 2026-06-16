<?php

    session_start();

    include("conexion.php");

    // Verifica que llegaron los datos
    if(isset($_POST['correo']) && isset($_POST['password']))
    {
        $correo = $_POST['correo'];
        $password = $_POST['password'];
        $sql = "SELECT * FROM Usuario WHERE correo = '$correo'";
        $resultado = mysqli_query($conn, $sql);

        if(mysqli_num_rows($resultado) > 0)
        {
            $usuario = mysqli_fetch_assoc($resultado);
            // Si las contraseñas están guardadas en texto plano
            if($password == $usuario['contraseña'])
            {
                $_SESSION['idUsuario'] = $usuario['idUsuario'];
                $_SESSION['nombre'] = $usuario['nombre'] . " " . $usuario['apellidoPaterno'];
                $_SESSION['idRol'] = $usuario['Rol_idRol'];

                if($usuario['Rol_idRol'] == 2 /*2=profesor*/) // si el rol del usuario es tal lo va a llevar a cierta vista
                {
                    header("Location: dashboardProf.php");
                    exit();
                }
                else
                {
                    if($usuario['Rol_idRol'] == 3 /*3=alumno*/)
                    {
                        header('Location: dashboard.php');
                        exit();
                    }
                    else
                    {
                        if($usuario['Rol_idRol'] == 1 /*1=administrador*/)
                        {
                            header('Location: vistaAdministrador.php');
                            exit();
                        }
                    }
                }
            }
            else
            {
                header("Location: ../../index.php?error=incorrecto");
                exit();
            }
        }
        else
        {
            header("Location: ../../index.php?error=incorrecto");
            exit();
        }
    }
    else
    {
        header('Location: ../../index.php?error=incorrecto');
        exit();
    }
?>