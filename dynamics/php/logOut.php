<?php
    session_start(); //abrimos la sesion para poder trabajar con ella
    //tenemos que borrrar todas las materias por lo que
    //vaciamos nuestra variable global $_session con un 
    //array vacio
    $_SESSION = [""];
    // destruimos la cookie
    if (ini_get("session.use_cookies")) 
    {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        );
    }

    session_destroy();
    //redirigimos a index
    header("Location: ../../index.php");
    exit();
?>