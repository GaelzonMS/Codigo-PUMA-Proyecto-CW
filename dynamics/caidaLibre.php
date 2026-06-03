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
            <title>Caida libre</title>
        </head>
        <body>
            <h1>Caida Libre </h1>
            <table border = '4' >
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
                echo '<td>Exceso</td>';
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
            </table>';
    
/*

                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>   
                
        </body>
        </html>
        ";
}
*/
?>
