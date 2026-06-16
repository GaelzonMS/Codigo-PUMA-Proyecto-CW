//filtro de entrada 
     //Sanitizacion de entgrada
     <?php
     function sanitizaEntrada($conexion, $datos){
        //quitamos espacion en blaNCO VACIOS AL INICIO  y al final
        $datos = trim($datos);

        //si meten "--", lo cambiamos "".
        $datos = str_replace('--', '', $datos);

        //si metes "/*", lo cambiamos por "".
        $datos = str_replace('/*', '', $datos);

        //si metes "*/", lo cambiamos por "".
        $datos = str_replace('*/', '', $datos);

        //busca cimillas simples (') o dobles (") y les pones una diagonal inversa (\) anates.
        //Así la base de datos sabe que es parte del nom. y NO un comnado SQL.
        $datosLimpio = mysqli_real_escape_string($conexion, $datos);

        return $datosLimpio;
     }
     ?>

     //correo electronico y no. enetro cvalido.
     <?php
     function validaCorreo($email){
        if(filter_var($email, FILTER_VALIDATE_EMAIL))
            echo "El correo '$email' es válida. \n";
     }

     function validaNumero($edad){
        if(filter_var($edad, FILTER_SANITIZE_NUMBER_INT))
            echo "El edad '$edad' es válida. \n";
     }
     ?>

     //verificar si la contraseña es segura
     <?php
     function esPasswordSegura($pass){
        if(strlen($pass) < 6)
            return false;
        $tieneMayus = false;
        $tieneNum = false;
        
        for($i = 0; $i < strlen($pass); $i++){
            if(ctype_upper($pass[$i]))
                $tieneMayus = true;
            if(ctype_upper($pass[$i]))
                $tieneNum = true;
        }
        return($tieneMayus && $tieneNum);
     }
     ?>

     //Verifica que la opcion seleccionada sí forme parte de nuestras opciones
     <?php
     function esOpcionValida($genero){
        //Opciones permitidas en el SELECT de Género
        $generosPermitidos = ['Masculino', 'Femenino', 'Otro'];

        //Si lo que mandaron NO está en nuestra lista secreta de PHP, regresamos falso.
        if(!in_array($genero, $generosPermitidos))
            return false;

        return true;
     }
     ?>

     //Hasheo de contrtaseñas
     <?php
     function hasheaPassword($pass){

     //Generamos el Hash
     $passwordHasheada = password_hash($pass, PASSWORD_DEFAULT);

     return $passwordHasheada;
     }
     ?>

     //Verificar contraseña
     <?php
     function validarPassword($passLogin){
        //Traemos el hash que está guardado en la base de datos parac ese Usuario.
        //(Imaginemos que ya hicimos el SELECT y lo guardamos en esta variable)
        $hashDeLaBaseDeDatos = '$2y$10$abcdefghijklmnopqrstuvwxyz1234567890...';

        //Comapara la contraseña limpia con el Hash
        if(password_verify($passLogin, $hashDeLaBaseDeDatos))
            echo "¡Contraseña correcta! Bienvenido al sistema.";
        else
             echo "¡Contraseña incorrecta! Intentalo de nuevo.";
     }
     ?>