<?php         
const gravedad = 32;

//__________________________________________________
// Cálculo de distancia
// _________________________________________________
function calcDistancia($tiempo, $veli){
    $dist = $veli*$tiempo+0.5*gravedad * $tiempo ** 2;
    return $dist;
}

//__________________________________________________
// Cálculo de velocidad
// _________________________________________________
function calcVelocidad($tiempo, $veli){
    $velf = $veli+gravedad*$tiempo;
    return $velf;
}

//__________________________________________________
// Crea tabla que contiene tiempo, distancia y velocidad final
// _________________________________________________
function generaTabla(){
    global $tablaTDV;
    $tiempo = 0;
    for ($t = 1; $t <= 10; $t++){
        $tiempo=$tiempo + 1.0; 
        $tablaTDV[$t-1][0] = $tiempo;
        $tablaTDV[$t-1][1] = calcDistancia($tablaTDV[$t-1][0], 0);
        $tablaTDV[$t-1][2] = calcVelocidad($tablaTDV[$t-1][0], 0);
    }

}
$col = 0;
$t = 0;
    echo "
        <!DOCTYPE html>
        <html lang'es'>
        <head>
            <meta charset='UTF-8'>
            <meta name='viewport' content='width=device-width, initial-scale=1.0'>
            <meta name='autor' content='equipo9'>
            <meta name='descripcion' content='ejercicio integrador de php con html y css'>
            <link rel='stylesheet' href='../statics/styles/caidaLibre.css'>
        </head>
        <body>
            <h1>Caida Libre </h1>
            <img src='https://i.pinimg.com/originals/7c/31/99/7c31995e81cfae6f5ed4316c4ba67a73.gif'>
            <table border = '4' id='tablaVelocidad' style='background-color:white'>
                <thead>
                    <tr>
                        <th>Tiempo (seg)</th>
                        <th>Posición final</th>
                        <th>Velocidad final (ft/s)</th>
                    </tr>
                </thead>
                <tbody>";

generaTabla();


while($t < 10)
{
    $r=0;
    if($r=0)
    {
            echo '<tr>';
    }
    for ($col = 0; $col < 3; $col ++)
    {
        
        if($col == 2)
        {
            if ($tablaTDV[$t][2]>250)
            {
                echo '<td style="color:red">Exceso</td>';
            }
            else
            {
                echo '<td>'. $tablaTDV[$t][2] . '</td>';
            }
        } 
        else
            echo '<td>'. $tablaTDV[$t][$col]. '</td>';
        /*--------Para indicar que es una nueva row--------*/
        $r=1;
    }
    if($r=1)
        echo '<tr>';
    $t++;
}

    echo '</tbody>
            </table>
            
        </body>
        </html>
            ';
    
?>
<style>

</style>

