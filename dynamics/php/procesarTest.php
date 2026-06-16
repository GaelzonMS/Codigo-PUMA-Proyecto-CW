<?php
session_start();

if (!isset($_SESSION['idUsuario'])) 
{   //  Verificar si hay sesion activa
    header("Location: ../../index.php"); 
    exit();
}
include 'conexion.php';
// Mapeo de IDs 
$tablaRespuestas = 
[
    // --- PREGUNTA 1 ---
    32315 => 'Kinestesico', 
    32316 => 'Visual',       
    32317 => 'Auditivo',     
    // --- PREGUNTA 2 ---
    32318 => 'Auditivo',    
    32319 => 'Visual',       
    32320 => 'Kinestesico', 
    // --- PREGUNTA 3 ---
    32321 => 'Visual',     
    32322 => 'Auditivo',     
    32323 => 'Kinestesico', 
    // --- PREGUNTA 4 ---
    32324 => 'Kinestesico', 
    32325 => 'Visual',       
    32326 => 'Auditivo',     
    // --- PREGUNTA 5 ---
    32327 => 'Visual',       
    32328 => 'Kinestesico', 
    32329 => 'Auditivo',     
    // --- PREGUNTA 6 ---
    32330 => 'Auditivo',     
    32331 => 'Visual',       
    32332 => 'Kinestesico', 
    // --- PREGUNTA 7 ---
    32333 => 'Kinestesico', 
    32334 => 'Visual',       
    32335 => 'Auditivo',     
    // --- PREGUNTA 8 ---
    32336 => 'Auditivo',    
    32337 => 'Kinestesico', 
    32338 => 'Visual',      
    // --- PREGUNTA 9 ---
    32339 => 'Visual',      
    32340 => 'Auditivo',    
    32341 => 'Kinestesico',
    // --- PREGUNTA 10 ---
    32342 => 'Visual',       
    32343 => 'Auditivo',     
    32344 => 'Kinestesico'  
];
// Inicializar contadores
$puntuacion = 
[
    'Visual'       => 0,
    'Auditivo'     => 0,
    'Kinestesico'  => 0
];

// Los nombres de tus inputs = 'answer_8029' - 'answer_8038'
$totalPreguntas = 0;
for ($i = 8029; $i <= 8038; $i++) 
{
    $nombreInput = "answer_" . $i;
    if (isset($_POST[$nombreInput])) 
    {
        $idRespuesta = intval($_POST[$nombreInput]);
        //sumamos un punto a esa categoría
        if (array_key_exists($idRespuesta, $tablaRespuestas)) 
        {
            $categoria = $tablaRespuestas[$idRespuesta];
            $puntuacion[$categoria]++;
            $totalPreguntas++;
        }
    }
}

// Validar que se haya respondido al menos una parte del test para evitar errores
if ($totalPreguntas === 0) {
    echo "<script>alert('Por favor, responde las preguntas del test.'); window.history.back();</script>";
    exit();
}
// arsort ordena el array de mayor a menor manteniendo las llaves
arsort($puntuacion); 
$estiloPredominante = key($puntuacion); // Obtiene la llave del valor mas alto
echo $estiloPredominante;
?>

<!DOCTYPE html>
<html lang="en">    
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../statics/styles/cuestionarioPersonalidad.css">
    <title>Resultado del Test</title>
</head>
<body>
    <main>
        <h1>Tu estilo de aprendizaje predominante es: <?php echo $estiloPredominante; ?></h1>
        <a href="CuestionarioPersonalidad.php" class="boton">Volver al test</a>
    </main>
</body>
</html>
