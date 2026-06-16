<?php
    session_start();

    if (!isset($_SESSION['idUsuario'])) 
    {
        header("Location: ../../index.php"); 
        exit(); 
    }
    
    include 'conexion.php';
    
    $queryCreacionActividad = false; 
    $idModulo = "";

    if ($_SERVER["REQUEST_METHOD"] == 'POST')
    {
        // limpia de datos recibidos del formulario
        $idModulo = mysqli_real_escape_string($conn, $_POST["idModulo"]); 
        $tituloActividad = mysqli_real_escape_string($conn, $_POST["tituloActividad"]);
        $descripcion = mysqli_real_escape_string($conn, $_POST["descrpcion"]);
        $fechaLimite = mysqli_real_escape_string($conn, $_POST["fechaLimite"]);
        $puntosMax = floatval($_POST["puntosMax"]);
        $tipoActividadId = intval($_POST["TipoActividad_idTipoActividad"]);
        
        //El archivo adjunto existe? si: lo limpia, no: lo pone null;
        $archivoAdjunto = isset($_POST["archivoAdjunto"]) ? mysqli_real_escape_string($conn, $_POST["archivoAdjunto"]) : null;
        //El archivo esta vacio? o no tiene nada?, si: lo ponemos null, no: guarda ruta
        $valorArchivo = ($archivoAdjunto === null || $archivoAdjunto === "") ? "NULL" : "'$archivoAdjunto'";

        // Generamos un identificador unico universal de 36 caracteres para cumplir con la llave primaria char(36)
        $idActividadNueva = bin2hex(random_bytes(16));
        $fechaCreacion = date("Y-m-d H:i:s");

        ///////////////// LOGICA PARA OBTENER EL IDCLASE RELACIONADO AL MODULO
        //hacemos una consulta con inner joins para rastrear la clase vinculada a la materia del modulo actual
        $sqlBuscarClase = "SELECT c.idClase 
                            FROM modulo m INNER JOIN inscripcion i 
                            ON m.Materia_idMateria = i.Materia_idMateria
                            INNER JOIN clase c 
                            ON i.Grupo_idGrupo = c.Grupo_idGrupo
                            WHERE m.idModulo = '$idModulo' 
                            LIMIT 1";
        $resultadoClase = mysqli_query($conn, $sqlBuscarClase);
        //verificamos que la consulta si hay asido exitosa
        if ($resultadoClase && mysqli_num_rows($resultadoClase) > 0)
        {
            $filaClase = mysqli_fetch_assoc($resultadoClase);
            $claseIdReal = $filaClase['idClase'];
            // Consulta para insertar los datos en la tabla 'actividad'
            $sqlQueryText = "INSERT INTO actividad (idActividad, titulo, fechaCreacion, fechaLimite, descrpcion, archivoAdjunto, puntosMax, TipoActividad_idTipoActividad, Modulo_idModulo, Clase_idClase)
                            VALUES ('$idActividadNueva', '$tituloActividad', '$fechaCreacion', '$fechaLimite', '$descripcion', $valorArchivo, $puntosMax, $tipoActividadId, '$idModulo', $claseIdReal)";
            $queryCreacionActividad = mysqli_query($conn, $sqlQueryText);
        }
        // Redireccionamiento directo pasando estatus e identificadores por url
        if ($queryCreacionActividad)
        {
            header("Location: moduloProfe.php?idModulo=" . $idModulo . "&status=exito");
            exit();
        }
        else
        {
            header("Location: moduloProfe.php?idModulo=" . $idModulo . "&status=error");
            exit();
        }
    }
?>