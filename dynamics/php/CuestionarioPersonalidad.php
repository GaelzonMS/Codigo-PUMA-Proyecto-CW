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
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../statics/styles/cuestionarioPersonalidad.css">
    <title>Document</title>
</head>
<body>
    <main class="container">
        <form method="POST" action="procesarTest.php">
            <input type="hidden" name="id_test" value="4613">
            <h1>Cuestionario de Tipo de Aprendizaje</h1>
            <div class="pregunta">
                <div class="titulopregunta">1. Me resultan más fáciles los exámenes:</div>
                <div class="opciones">
                    <div class="opcion">
                        <input type="radio" id="answer_1_1" name="answer_8029" value="32315">
                        <label for="answer_1_1">Prácticos.</label>
                    </div>
                    <div class="opcion">
                        <input type="radio" id="answer_1_2" name="answer_8029" value="32316">
                        <label for="answer_1_2">Teóricos escritos.</label>
                    </div>
                    <div class="opcion">
                        <input type="radio" id="answer_1_3" name="answer_8029" value="32317">
                        <label for="answer_1_3">Teóricos orales.</label>
                    </div>
                </div>
            </div>

            <div class="pregunta">
                <div class="titulopregunta">2. Recuerdo mejor:</div>
                <div class="opciones">
                    <div class="opcion">
                        <input type="radio" id="answer_2_1" name="answer_8030" value="32318">
                        <label for="answer_2_1">Una canción.</label>
                    </div>
                    <div class="opcion">
                        <input type="radio" id="answer_2_2" name="answer_8030" value="32319">
                        <label for="answer_2_2">Una imagen.</label>
                    </div>
                    <div class="opcion">
                        <input type="radio" id="answer_2_3" name="answer_8030" value="32320">
                        <label for="answer_2_3">Una ruta o camino.</label>
                    </div>
                </div>
            </div>

            <div class="pregunta">
                <div class="titulopregunta">3. Entre estas 3 asignaturas elegiría:</div>
                <div class="opciones">

                    <div class="opcion">
                        <input type="radio" id="answer_3_1" name="answer_8031" value="32321">
                        <label for="answer_3_1">Plástica.</label>
                    </div>
                    <div class="opcion">
                        <input type="radio" id="answer_3_2" name="answer_8031" value="32322">
                        <label for="answer_3_2">Música.</label>
                    </div>
                    <div class="opcion">
                        <input type="radio" id="answer_3_3" name="answer_8031" value="32323">
                        <label for="answer_3_3">Gimnástica.</label>
                    </div>
                </div>
            </div>

            <div class="pregunta">
                <div class="titulopregunta">4. Prefiero:</div>
                <div class="opciones">

                    <div class="opcion">
                        <input type="radio" id="answer_4_1" name="answer_8032" value="32324">
                        <label for="answer_4_1">Hacer deporte.</label>
                    </div>
                    <div class="opcion">
                        <input type="radio" id="answer_4_2" name="answer_8032" value="32325">
                        <label for="answer_4_2">Ver películas.</label>
                    </div>
                    <div class="opcion">
                        <input type="radio" id="answer_4_3" name="answer_8032" value="32326">
                        <label for="answer_4_3">Escuchar música.</label>
                    </div>
                </div>
            </div>

            <div class="pregunta">
                <div class="titulopregunta">5. Mi técnica para estudiar es:</div>
                <div class="opciones">

                    <div class="opcion">
                        <input type="radio" id="answer_5_1" name="answer_8033" value="32327">
                        <label for="answer_5_1">Hacer esquemas y dibujos.</label>
                    </div>
                    <div class="opcion">
                        <input type="radio" id="answer_5_2" name="answer_8033" value="32328">
                        <label for="answer_5_2">Practicar.</label>
                    </div>
                    <div class="opcion">
                        <input type="radio" id="answer_5_3" name="answer_8033" value="32329">
                        <label for="answer_5_3">Explicar y repetir en voz alta.</label>
                    </div>
                </div>
            </div>

            <div class="pregunta">
                <div class="titulopregunta">6. Entre estos 3 trabajos elegiría:</div>
                <div class="opciones">
                    <div class="opcion">
                        <input type="radio" id="answer_6_1" name="answer_8034" value="32330">
                        <label for="answer_6_1">Locutor de radio.</label>
                    </div>
                    <div class="opcion">
                        <input type="radio" id="answer_6_2" name="answer_8034" value="32331">
                        <label for="answer_6_2">Editor en una revista.</label>
                    </div>
                    <div class="opcion">
                        <input type="radio" id="answer_6_3" name="answer_8034" value="32332">
                        <label for="answer_6_3">Director de un club deportivo.</label>
                    </div>
                </div>
            </div>

            <div class="pregunta">
                <div class="titulopregunta">7. Mi entretenimiento favorito es:</div>
                <div class="opciones">
                    <div class="opcion" >
                        <input type="radio" id="answer_7_1" name="answer_8035" value="32333">
                        <label for="answer_7_1">Actividades.</label>
                    </div>
                    <div class="opcion">
                        <input type="radio" id="answer_7_2" name="answer_8035" value="32334">
                        <label for="answer_7_2">La televisión.</label>
                    </div>
                    <div class="opcion">
                        <input type="radio" id="answer_7_3" name="answer_8035" value="32335">
                        <label for="answer_7_3">La radio o audiolibros.</label>
                    </div>
                </div>
            </div>

            <div class="pregunta">
                <div class="titulopregunta">8. Prefiero acudir a:</div>
                <div class="opciones">
                    <div class="opcion">
                        <input type="radio" id="answer_8_1" name="answer_8036" value="32336">
                        <label for="answer_8_1">Un concierto.</label>
                    </div>
                    <div class="opcion">
                        <input type="radio" id="answer_8_2" name="answer_8036" value="32337">
                        <label for="answer_8_2">Una clase de baile.</label>
                    </div>
                    <div class="opcion">
                        <input type="radio" id="answer_8_3" name="answer_8036" value="32338">
                        <label for="answer_8_3">Una exposición de arte.</label>
                    </div>
                </div>
            </div>

            <div class="pregunta">
                <div class="titulopregunta">9. Prefiero aprender:</div>
                <div class="opciones">

                    <div class="opcion">
                        <input type="radio" id="answer_9_1" name="answer_8037" value="32339">
                        <label for="answer_9_1">Leyendo el libro.</label>
                    </div>
                    <div class="opcion">
                        <input type="radio" id="answer_9_2" name="answer_8037" value="32340">
                        <label for="answer_9_2">Escuchando al profesor.</label>
                    </div>
                    <div class="opcion">
                        <input type="radio" id="answer_9_3" name="answer_8037" value="32341">
                        <label for="answer_9_3">Haciendo actividades prácticas.</label>
                    </div>
                </div>
            </div>

            <div class="pregunta">
                <div class="titulopregunta">10. Me gusta:</div>
                <div class="opciones">
                    <div class="opcion">
                        <input type="radio" id="answer_10_1" name="answer_8038" value="32342">
                        <label for="answer_10_1">Ver paisajes bonitos.</label>
                    </div>
                    <div class="opcion">
                        <input type="radio" id="answer_10_2" name="answer_8038" value="32343">
                        <label for="answer_10_2">Escuchar a los demás.</label>
                    </div>
                    <div class="opcion">
                        <input type="radio" id="answer_10_3" name="answer_8038" value="32344">
                        <label for="answer_10_3">Desmontar y montar aparatos para saber cómo funcionan.</label>
                    </div>
                </div>
            </div>

            <button type="submit" class="boton">Calcular resultado</button>
        </form>
    </main>
</body>
</html>